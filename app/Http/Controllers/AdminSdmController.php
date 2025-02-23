<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\History;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Imports\UserImport;
use Illuminate\Support\Str;
use App\Models\GeneratedAccount;
use App\Models\Permission;
use App\Models\RiwayatTugas;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AdminSdmController extends Controller
{
    public function index(Request $request)
    {
        if (Auth::user()->role !== 'admin_sdm') {
            return abort(403, 'Unauthorized action.');
        }

        $query = User::query();

        // Search (Mencari berdasarkan nama, NIP, atau unit)
        if ($request->has('search') && $request->get('search') != '') {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                ->orWhere('nip', 'like', '%' . $search . '%')
                ->orWhere('unit', 'like', '%' . $search . '%');
            });
        }

        // Filter by Golongan (Menyaring data berdasarkan golongan)
        $gol = $request->input('gol');
        if ($gol && $gol != 'semua') {
            $query->where('gol', $gol);
        }

        // Mengambil hasil pencarian dan filter
        $users = $query->get();
    
        return view('dashboard.admin_sdm', compact('users'));
    }

    public function search_filter_Rolling(Request $request)
    {
        if (Auth::user()->role !== 'admin_sdm') {
            return abort(403, 'Unauthorized action.');
        }

        $query = User::query();

        // Search (Mencari berdasarkan nama, NIP, atau unit)
        if ($request->has('search') && $request->get('search') != '') {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                ->orWhere('nip', 'like', '%' . $search . '%')
                ->orWhere('unit', 'like', '%' . $search . '%');
            });
        }

        // Filter by Golongan (Menyaring data berdasarkan golongan)
        $gol = $request->input('gol');
        if ($gol && $gol != 'semua') {
            $query->where('gol', $gol);
        }

        // Mengambil hasil pencarian dan filter
        $users = $query->get();

        return view('rolling.index', compact('users'));
    }

    public function chart()
    {
        if (Auth::user()->role !== 'admin_sdm') {
            return abort(403, 'Unauthorized action.');
        }

        $data = DB::table('users')
        ->select('gol', DB::raw('count(*) as jumlah'))
        ->groupBy('gol')
        ->orderBy('gol')
        ->get();

        $pendidikan = User::selectRaw('pendidikan, COUNT(*) as jumlah')
        ->groupBy('pendidikan')
        ->orderByRaw("FIELD(pendidikan, 'S3', 'S2', 'S1', 'DIV', 'DIII', 'DI', 'SMA')")
        ->get();

        $usia = DB::table('users')
         ->selectRaw('
             COUNT(*) as count,
             CASE
                 WHEN TIMESTAMPDIFF(YEAR, tanggal_lahir, CURDATE()) < 25 THEN "<25"
                 WHEN TIMESTAMPDIFF(YEAR, tanggal_lahir, CURDATE()) BETWEEN 26 AND 30 THEN "26-30"
                 WHEN TIMESTAMPDIFF(YEAR, tanggal_lahir, CURDATE()) BETWEEN 31 AND 40 THEN "31-40"
                 WHEN TIMESTAMPDIFF(YEAR, tanggal_lahir, CURDATE()) BETWEEN 41 AND 50 THEN "41-50"
                 ELSE ">50"
             END AS age_group
         ')
         ->groupBy('age_group')
         ->get();

         $gender = [
            'Laki-laki' => User::where('jenis_kelamin', 'L')->count(),
            'Perempuan' => User::where('jenis_kelamin', 'P')->count(),
        ];

        $jabatan = User::selectRaw('jabatan, COUNT(*) as jumlah')
        ->groupBy('jabatan')
        ->orderByRaw("FIELD(jabatan, 'Kepala Kantor', 'Pejabat Pengawas', 'PBC Ahli Muda', 'PBC Ahli Pertama', 'PBC Mahir', 'PBC Terampil', 'Pranata Keuangan APBN Terampil', 'Pelaksana Pemeriksa')")
        ->get();

        $agama = User::selectRaw('agama, COUNT(*) as total')
        ->whereIn('agama', ['ISLAM', 'KRISTEN', 'KATHOLIK', 'HINDU', 'BUDHA'])
        ->groupBy('agama')
        ->pluck('total', 'agama');

        return view('chart.index', compact('data', 'pendidikan', 'usia', 'gender', 'jabatan', 'agama'));
    }

    public function rolling()
    {
        if (Auth::user()->role !== 'admin_sdm') {
            return abort(403, 'Unauthorized action.');
        }

        $users = User::all();
        return view('rolling.index', compact('users'));
    }

    // Menampilkan riwayat rolling unit pegawai
    public function history($nip)
    {
        if (Auth::user()->role !== 'admin_sdm') {
            return abort(403, 'Unauthorized action.');
        }

        $history = History::where('nip', $nip)->get();
        return view('rolling.history', compact('history'));
    }

    // Form untuk merolling pegawai ke unit baru
    public function create($nip)
    {
        if (Auth::user()->role !== 'admin_sdm') {
            return abort(403, 'Unauthorized action.');
        }

        $user = User::where('nip', $nip)->first();
        return view('rolling.create', compact('user'));
    }

    // Simpan perubahan rolling unit pegawai
    public function store(Request $request, $nip)
    {
        if (Auth::user()->role !== 'admin_sdm') {
            return abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'unit' => 'required|string|max:255',
        ]);

        // Cek apakah pegawai sudah pernah di unit ini sebelumnya
        $existingHistory = History::where('nip', $nip)->where('unit', $request->unit)->exists();
        if ($existingHistory) {
            return redirect()->back()->with('error', 'Pegawai sudah pernah berada di unit ini.');
        }

        // Simpan ke history
        History::create([
            'nip' => $nip,
            'unit' => $request->unit
        ]);

        // Update unit di tabel users
        User::where('nip', $nip)->update(['unit' => $request->unit]);

        return redirect()->route('rolling.index')->with('success', 'Unit pegawai berhasil diubah.');
    }

    public function store_form(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'nip' => 'required|string|unique:users,nip',
            'jabatan' => 'required|string',
            'role' => 'required|string',
            'unit' => 'required|string',
            'tanggal_naik_gaji' => 'required|date',
            'gol' => 'required|string',
            'kode_jabatan' => 'required|integer',
            'kode_unit' => 'required|integer',
            'bidang_tugas' => 'required|string',
            'periode_unit_bln' => 'required|integer',
            'lama_tg_mas_th' => 'required|integer',
            'pendidikan' => 'required|string',
            'jenis_kelamin' => 'required|string',
            'tanggal_lahir' => 'required|date',
            'agama' => 'required|string',
            'alamat' => 'required|string',
            'no_hp' => 'required|string',
            'no_darurat' => 'required|string',
        ]);

        // Generate email
        $firstName = explode(' ', trim($request->name))[0];
        $randomNumber = rand(100, 999);
        $email = strtolower($firstName) . $randomNumber . "@gmail.com";

        // Generate random password
        $password = Str::random(10);

        // Create user
        $user = User::create([
            'name' => $request->name,
            'nip' => $request->nip,
            'email' => $email,
            'password' => Hash::make($password),
            'jabatan' => $request->jabatan,
            'role' => $request->role,
            'unit' => $request->unit,
            'tanggal_naik_gaji' => $request->tanggal_naik_gaji,
            'gol' => $request->gol,
            'kode_jabatan' => $request->kode_jabatan,
            'kode_unit' => $request->kode_unit,
            'bidang_tugas' => $request->bidang_tugas,
            'periode_unit_bln' => $request->periode_unit_bln,
            'lama_tg_mas_th' => $request->lama_tg_mas_th,
            'pendidikan' => $request->pendidikan,
            'jenis_kelamin' => $request->jenis_kelamin,
            'tanggal_lahir' => $request->tanggal_lahir,
            'agama' => $request->agama,
            'alamat' => $request->alamat,
            'no_hp' => $request->no_hp,
            'no_darurat' => $request->no_darurat,
        ]);

        $generated = GeneratedAccount::create([
            'name' => $request->name,
            'nip' => $request->nip,
            'email' => $email,
            'password' => $password,
        ]);

        return redirect()->route('admin_sdm.index')->with('success', 'Pegawai berhasil ditambahkan!');
    }

    public function edit($nip)
    {
        $user = User::where('nip', $nip)->firstOrFail();
        return view('akun.edit_admin_sdm', compact('user'));
    }

    public function update(Request $request, $nip)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'jabatan' => 'required|string',
            'role' => 'required|string',
            'unit' => 'required|string',
            'tanggal_naik_gaji' => 'required|date',
            'gol' => 'required|string',
            'kode_jabatan' => 'required|integer',
            'kode_unit' => 'required|integer',
            'bidang_tugas' => 'required|string',
            'periode_unit_bln' => 'required|integer',
            'lama_tg_mas_th' => 'required|integer',
            'pendidikan' => 'required|string',
            'jenis_kelamin' => 'required|string',
            'tanggal_lahir' => 'required|date',
            'agama' => 'required|string',
            'alamat' => 'required|string',
            'no_hp' => 'required|string',
            'no_darurat' => 'required|string',
        ]);

        $user = User::findOrFail($nip);
        $user->update([
            'name' => $request->name,
            'jabatan' => $request->jabatan,
            'role' => $request->role,
            'unit' => $request->unit,
            'tanggal_naik_gaji' => $request->tanggal_naik_gaji,
            'gol' => $request->gol,
            'kode_jabatan' => $request->kode_jabatan,
            'kode_unit' => $request->kode_unit,
            'bidang_tugas' => $request->bidang_tugas,
            'periode_unit_bln' => $request->periode_unit_bln,
            'lama_tg_mas_th' => $request->lama_tg_mas_th,
            'pendidikan' => $request->pendidikan,
            'jenis_kelamin' => $request->jenis_kelamin,
            'tanggal_lahir' => $request->tanggal_lahir,
            'agama' => $request->agama,
            'alamat' => $request->alamat,
            'no_hp' => $request->no_hp,
            'no_darurat' => $request->no_darurat,
        ]);

        return redirect()->route('admin_sdm.dashboard')->with('success', 'User berhasil diperbarui');
    }

    public function destroy($nip)
    {
        DB::table('generated_akun')->where('nip', $nip)->delete();
        $user = User::where('nip', $nip)->firstOrFail();
        $user->delete();

        return redirect()->route('admin_sdm.dashboard')->with('success', 'User berhasil dihapus');
    }

    public function view_upload()
    {
        if (Auth::user()->role !== 'admin_sdm') {
            return abort(403, 'Unauthorized action.');
        }

        return view('akun.upload_admin_sdm');
    }

    public function import()
    {
        try {
            // Pastikan file diunggah
            if (!request()->hasFile('file')) {
                return back()->with('error', 'Tidak ada file yang diunggah.');
            }

            // Simpan jumlah user sebelum impor
            $countBefore = User::count();

            // Lakukan proses impor tanpa email dan password
            Excel::import(new UserImport, request()->file('file'));

            // Ambil user setelah impor berdasarkan ID terbaru
            $userAfterImport = User::latest()->take(User::count() - $countBefore)->get();

            // Simpan data yang baru diimpor dalam sesi agar tampil di view
            session(['pegawaiData' => $userAfterImport]);

            return back()->with('success', 'Impor berhasil, silakan lanjutkan dengan generate akun.');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat mengimpor: ' . $e->getMessage());
        }
    }

    public function form_akun()
    {
        if (Auth::user()->role !== 'admin_sdm') {
            return abort(403, 'Unauthorized action.');
        }

        return view('akun.form_admin_sdm');
    }

    public function exportExcel()
    {
        // Ambil data dari tabel `users` dan `rolling_histories` yang memiliki nip yang sama
        $akuns = DB::table('generated_akun')->select('name', 'nip', 'email', 'password')->get();

        // Buat spreadsheet baru
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Header kolom
        $sheet->setCellValue('A1', 'Name');
        $sheet->setCellValue('B1', 'NIP');
        $sheet->setCellValue('C1', 'Email');
        $sheet->setCellValue('D1', 'Password');

        // Isi data
        $row = 2;
        foreach ($akuns as $akun) {
            $sheet->setCellValue('A' . $row, $akun->name);
            $sheet->setCellValue('B' . $row, $akun->nip);
            $sheet->setCellValue('C' . $row, $akun->email);
            $sheet->setCellValue('D' . $row, $akun->password);
            $row++;
        }

        // Simpan dan download file Excel
        $writer = new Xlsx($spreadsheet);
        $fileName = 'daftar_akun.xlsx';

        return new StreamedResponse(function () use ($writer) {
            $writer->save('php://output');
        }, 200, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment;filename="' . $fileName . '"',
            'Cache-Control' => 'max-age=0',
        ]);
    }

    public function daftar_tugas(Request $request)
    {
        if (Auth::user()->role !== 'admin_sdm') {
            return abort(403, 'Unauthorized action.');
        }

        // Mulai query join antara users dan riwayat_tugas
        $query = DB::table('users')
            ->join('riwayat_tugas', 'users.nip', '=', 'riwayat_tugas.nip')
            ->select('users.*', 'riwayat_tugas.nama_tugas', 'riwayat_tugas.tanggal_mulai', 'riwayat_tugas.id_tugas');

        // Logic Pencarian (Search)
        if ($request->filled('search')) { // Pastikan input search ada dan tidak kosong
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('users.name', 'like', '%' . $search . '%') // Cari di users.name
                ->orWhere('users.nip', 'like', '%' . $search . '%') // Cari di users.nip
                ->orWhere('riwayat_tugas.nama_tugas', 'like', '%' . $search . '%'); // Cari di riwayat_tugas.nama_tugas
            });
        }

        // Eksekusi query
        $tugas = $query->get();

        return view('riwayat.index_admin_sdm', compact('tugas'));
    }

    public function tambahTugas(Request $request)
    {
        $request->validate([
            'nama_tugas' => 'required|string|max:255',
            'nip' => 'required|string|unique:riwayat_tugas,nip',
            'tanggal_mulai' => 'required|date',
        ]);

        // Create user
        $tugas = RiwayatTugas::create([
            'nama_tugas' => $request->nama_tugas,
            'nip' => $request->nip,
            'tanggal_mulai' => $request->tanggal_mulai,
        ]);

        return redirect()->route('admin_sdm.daftar_tugas')->with('success', 'Riwayat tugas berhasil ditambahkan!');
    }

    public function form_tugas()
    {
        if (Auth::user()->role !== 'admin_sdm') {
            return abort(403, 'Unauthorized action.');
        }

        return view('riwayat.form_tugas_admin_sdm');
    }

    public function editTugas($id_tugas)
    {
        $tugas = RiwayatTugas::where('id_tugas', $id_tugas)->firstOrFail();
        return view('riwayat.edit_admin_sdm', compact('tugas'));
    }

    public function updateTugas(Request $request, $id_tugas)
    {
        $request->validate([
            'nama_tugas' => 'required|string|max:255',
            'tanggal_mulai' => 'required|date',
        ]);

        $tugas = RiwayatTugas::findOrFail($id_tugas);
        $tugas->update([
            'nama_tugas' => $request->nama_tugas,
            'tanggal_mulai' => $request->tanggal_mulai,
        ]);

        return redirect()->route('admin_sdm.daftar_tugas')->with('success', 'Riwayat tugas berhasil diperbarui');
    }

    public function destroyTugas($id_tugas)
    {
        $tugas = RiwayatTugas::where('id_tugas', $id_tugas)->firstOrFail();
        $tugas->delete();

        return redirect()->route('admin_sdm.daftar_tugas')->with('success', 'Riwayat tugas berhasil dihapus');
    }

    public function atur_menu()
    {
        if (Auth::user()->role !== 'admin_sdm') {
            return abort(403, 'Unauthorized action.');
        }

        $menu = DB::table('users')
        ->join('permissions', 'users.nip', '=', 'permissions.nip')
        ->select('users.*', 'permissions.*')
        ->get();

        return view('menu.index', compact('menu'));
    }

    public function editMenu($nip)
    {
        $menu = DB::table('permissions')
        ->join('users', 'permissions.nip', '=', 'users.nip')
        ->where('permissions.nip', $nip)
        ->select('permissions.*', 'users.name', 'users.email')
        ->first();

        if (!$menu) {
            abort(404);
        }

        return view('menu.edit', compact('menu'));
    }

    public function updateMenu(Request $request, $nip)
    {
        $menu = Permission::where('nip', $nip)->firstOrFail();

        $menu->update([
            'dashboard' => $request->input('dashboard', 0),
            'daftar_pegawai' => $request->input('daftar_pegawai', 0),
            'tambah_pengguna' => $request->input('tambah_pengguna', 0),
            'rolling' => $request->input('rolling', 0),
            'riwayat' => $request->input('riwayat', 0),
            'gaji' => $request->input('gaji', 0),
        ]);

        return redirect()->route('admin_sdm.atur_menu')->with('success', 'Menu berhasil diperbarui!');
    }
}