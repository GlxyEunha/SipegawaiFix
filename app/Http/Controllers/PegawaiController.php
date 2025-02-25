<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\History;
use App\Models\Permission;
use App\Imports\UserImport;
use Illuminate\Support\Str;
use App\Models\RiwayatTugas;
use Illuminate\Http\Request;
use App\Models\RollingHistory;
use App\Models\GeneratedAccount;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PegawaiController extends Controller
{
    // Menampilkan daftar pegawai yang bisa dirolling
    public function index()
    {
        if (Auth::user()->role !== 'pegawai') {
            return abort(403, 'Unauthorized action.');
        }

        $users = User::all();
        return view('dashboard.pegawai', compact('users'));
    }

    // Chart
    public function chart()
    {
        if (Auth::user()->role !== 'pegawai') {
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

    public function view_upload()
    {
        if (Auth::user()->role !== 'pegawai') {
            return abort(403, 'Unauthorized action.');
        }

        return view('akun.upload');
    }

    # Form Tambah Pegawai
    public function form_akun()
    {
        if (Auth::user()->role !== 'pegawai') {
            return abort(403, 'Unauthorized action.');
        }

        return view('akun.form');
    }

    //Create Pegawai
    public function store(Request $request)
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
        ]);

        $generated = GeneratedAccount::create([
            'name' => $request->name,
            'nip' => $request->nip,
            'email' => $email,
            'password' => $password,
        ]);

        return redirect()->route('pegawai.index')->with('success', 'Pegawai berhasil ditambahkan!');
    }

    // Edit Pegawai
    public function edit($nip)
    {
        $user = User::where('nip', $nip)->firstOrFail();
        return view('akun.edit', compact('user'));
    }

    // Update Pegawai
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
        ]);

        return redirect()->route('pegawai.dashboard')->with('success', 'User berhasil diperbarui');
    }


    // Delete Pegawai
    public function destroy($nip)
    {
        DB::table('generated_akun')->where('nip', $nip)->delete();
        $user = User::where('nip', $nip)->firstOrFail();
        $user->delete();

        return redirect()->route('pegawai.dashboard')->with('success', 'User berhasil dihapus');
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

    public function generateAccounts()
    {
        try {
            $allUser = User::whereNull('email')->orWhereNull('password')->get();

            foreach ($allUser as $user) {
                // Generate email
                $firstName = explode(' ', trim($user->name))[0];
                $randomNumber = rand(100, 999);
                $email = strtolower($firstName) . $randomNumber . "@gmail.com";

                // Generate random password
                $password = Str::random(10);

                // Simpan user ke database
                $user->update([
                    'email' => $email,
                    'password' => Hash::make($password)
                ]);

                GeneratedAccount::create([
                    'name' => $user->name,
                    'nip' => $user->nip,
                    'email' => $email,
                    'password' => $password, 
                ]);

                Permission::create([
                    'nip' => $user->nip
                ]);
            }

            session()->forget('pegawaiData');


            return redirect()->route('pegawai.upload')->with('success', 'Akun berhasil dibuat untuk semua pengguna.');
        } catch (\Exception $e) {
            return redirect()->route('pegawai.upload')->with('error', 'Terjadi kesalahan saat membuat akun: ' . $e->getMessage());
        }
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

        return redirect()->route('pegawai.tugas')->with('success', 'Riwayat tugas berhasil ditambahkan!');
    }

    public function form_tugas()
    {
        if (Auth::user()->role !== 'pegawai') {
            return abort(403, 'Unauthorized action.');
        }

        return view('riwayat.form_tugas');
    }

    public function index_tugas()
    {
        if (Auth::user()->role !== 'pegawai') {
            return abort(403, 'Unauthorized action.');
        }

        $tugas = DB::table('users')
        ->join('riwayat_tugas', 'users.nip', '=', 'riwayat_tugas.nip')
        ->select('users.*', 'riwayat_tugas.nama_tugas', 'riwayat_tugas.tanggal_mulai', 'riwayat_tugas.id_tugas')
        ->get();

        return view('riwayat.index', compact('tugas'));
    }

    public function editTugas($id_tugas)
    {
        $tugas = RiwayatTugas::where('id_tugas', $id_tugas)->firstOrFail();
        return view('riwayat.edit', compact('tugas'));
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

        return redirect()->route('pegawai.tugas')->with('success', 'Riwayat tugas berhasil diperbarui');
    }

    public function destroyTugas($id_tugas)
    {
        $tugas = RiwayatTugas::where('id_tugas', $id_tugas)->firstOrFail();
        $tugas->delete();

        return redirect()->route('pegawai.tugas')->with('success', 'Riwayat tugas berhasil dihapus');
    }

    public function indexRolling()
    {
        $users = User::all();
        return view('rolling.index', compact('users'));
    }

    public function storeRolling(Request $request)
    {
        $request->validate([
            'nip' => 'required|exists:users,nip',
            'new_unit' => 'required'
        ]);

        $user = User::where('nip', $request->nip)->first();

        RollingHistory::create([
            'nip' => $request->nip,
            'old_unit' => $user->unit,
            'new_unit' => $request->new_unit,
        ]);

        return redirect()->route('rolling.index')->with('success', 'Data rolling berhasil disimpan.');
    }

    public function hasilRolling(Request $request)
    {
        // Query awal: mengambil data rolling history yang belum diterima dan join dengan tabel users
        $query = RollingHistory::where('rolling_histories.is_accepted', false)
            ->join('users', 'rolling_histories.nip', '=', 'users.nip')
            ->select('rolling_histories.*', 'users.name', 'users.unit', 'users.gol', 'users.nip'); // Pilih kolom yang relevan

        // Search (Mencari berdasarkan nama, NIP, atau unit)
        if ($request->filled('search')) { // Gunakan filled() untuk memastikan nilai tidak kosong
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('users.name', 'like', '%' . $search . '%')
                ->orWhere('users.nip', 'like', '%' . $search . '%')
                ->orWhere('users.unit', 'like', '%' . $search . '%');
            });
        }

        // Filter by Golongan (Menyaring data berdasarkan golongan)
        if ($request->filled('gol') && $request->get('gol') != 'semua') {
            $query->where('users.gol', $request->get('gol'));
        }

        // Mengambil hasil pencarian dan filter
        $rollings = $query->get();

        return view('rolling.hasil', compact('rollings'));
    }


    public function acceptRolling($nip)
    {
        $rolling = RollingHistory::where('nip', $nip)->where('is_accepted', false)->first();

        if ($rolling) {
            User::where('nip', $nip)->update(['unit' => $rolling->new_unit]);
            $rolling->update(['is_accepted' => true]);

            return redirect()->route('rolling.hasil')->with('success', 'Rolling diterima dan unit diperbarui.');
        }

        return redirect()->route('rolling.hasil')->with('error', 'Data tidak ditemukan.');
    }

    public function exportExcelRolling()
    {
        // Ambil data dari tabel `users` dan `rolling_histories` yang memiliki nip yang sama
        $rollings = RollingHistory::where('is_accepted', false)
            ->join('users', 'users.nip', '=', 'rolling_histories.nip')
            ->select('users.name', 'users.nip', 'rolling_histories.old_unit', 'rolling_histories.new_unit')
            ->get();

        // Buat spreadsheet baru
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Header kolom
        $sheet->setCellValue('A1', 'Name');
        $sheet->setCellValue('B1', 'NIP');
        $sheet->setCellValue('C1', 'Unit Sekarang');
        $sheet->setCellValue('D1', 'Unit Baru');

        // Isi data
        $row = 2;
        foreach ($rollings as $rolling) {
            $sheet->setCellValue('A' . $row, $rolling->name);
            $sheet->setCellValue('B' . $row, $rolling->nip);
            $sheet->setCellValue('C' . $row, $rolling->old_unit);
            $sheet->setCellValue('D' . $row, $rolling->new_unit);
            $row++;
        }

        // Simpan dan download file Excel
        $writer = new Xlsx($spreadsheet);
        $fileName = 'rolling_history.xlsx';

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
        if (Auth::user()->role !== 'pegawai') {
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

        return view('riwayat.index', compact('tugas'));
    }

    public function createTugas(Request $request)
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

        return redirect()->route('pegawai.tugas')->with('success', 'Riwayat tugas berhasil ditambahkan!');
    }

    public function formtugas()
    {
        if (Auth::user()->role !== 'pegawai') {
            return abort(403, 'Unauthorized action.');
        }

        return view('riwayat.form_tugas');
    }

    public function edit_Tugas($id_tugas)
    {
        $tugas = RiwayatTugas::where('id_tugas', $id_tugas)->firstOrFail();
        return view('riwayat.edit', compact('tugas'));
    }

    public function update_Tugas(Request $request, $id_tugas)
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

    public function destroy_Tugas($id_tugas)
    {
        $tugas = RiwayatTugas::where('id_tugas', $id_tugas)->firstOrFail();
        $tugas->delete();

        return redirect()->route('pegawai.tugas')->with('success', 'Riwayat tugas berhasil dihapus');
    }

    public function index_gaji(Request $request)
    {
        $today = Carbon::now();
        $threshold = $today->copy()->subMonths(23); // 1 tahun 11 bulan
        $limit = $today->copy()->subMonths(24); // 2 tahun
    
        // Query dasar untuk mendapatkan pegawai yang memenuhi syarat
        $query = User::where(function ($q) use ($threshold, $limit) {
            $q->where('tanggal_naik_gaji', '<=', $threshold)
              ->where('tanggal_naik_gaji', '>', $limit);
        });
    
        // Logic Pencarian (Search)
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%') // Cari di nama
                  ->orWhere('nip', 'like', '%' . $search . '%') // Cari di NIP
                  ->orWhere('unit', 'like', '%' . $search . '%') // Cari di unit
                  ->orWhere('jabatan', 'like', '%' . $search . '%'); // Cari di jabatan
            });
        }
    
        // Logic Filter Golongan
        if ($request->filled('gol')) {
            $gol = $request->get('gol');
            if ($gol !== 'semua') {
                $query->where('gol', $gol);
            }
        }
    
        // Ambil hasil pencarian & filter
        $pegawai = $query->get();
    
        // Hitung jumlah pegawai yang memenuhi syarat
        $jumlahKenaikanGaji = $pegawai->count();
    
        return view('gaji.pegawai_index', compact('pegawai', 'jumlahKenaikanGaji'));
    }    

    public static function getJumlahKenaikanGaji()
    {
        $today = Carbon::now();
        $threshold = $today->copy()->subMonths(23);
        $limit = $today->copy()->subMonths(24);

        // Hitung jumlah pegawai yang memenuhi syarat kenaikan gaji
        return User::where('tanggal_naik_gaji', '<=', $threshold)
                   ->where('tanggal_naik_gaji', '>', $limit)
                   ->count();
    }

    public function setujui($nip)
    {
        $pegawai = User::where('nip', $nip)->first();
        if ($pegawai) {
            $pegawai->tanggal_naik_gaji = Carbon::parse($pegawai->tanggal_naik_gaji)->addYears(2);
            $pegawai->save();
        }

        return redirect()->route('pegawai.gaji.index')->with('success', 'Kenaikan gaji telah disetujui.');
    }
}
