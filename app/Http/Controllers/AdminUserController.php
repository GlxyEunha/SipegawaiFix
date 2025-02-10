<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\History;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\GeneratedAccount;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class AdminUserController extends Controller
{
    // Menampilkan daftar pegawai yang bisa dirolling
    public function index()
    {
        if (Auth::user()->role !== 'admin_user') {
            return abort(403, 'Unauthorized action.');
        }

        $users = User::all();
        return view('dashboard.admin_user', compact('users'));
    }

    public function view_upload()
    {
        if (Auth::user()->role !== 'admin_user') {
            return abort(403, 'Unauthorized action.');
        }

        return view('akun.upload');
    }

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
            'jabatan' => $request->role,
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

        return redirect()->route('admin_user.index')->with('success', 'Pegawai berhasil ditambahkan!');
    }

    public function edit($nip)
    {
        $user = User::where('nip', $nip)->firstOrFail();
        return view('akun.edit', compact('user'));
    }

    public function update(Request $request, $nip)
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

        $user = User::where('nip', $nip)->firstOrFail();
        $user->update([
            'name' => $request->name,
            'nip' => $request->nip,
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

        return redirect()->view('dashboard.admin_user')->with('success', 'User berhasil diperbarui');
    }

    public function destroy($nip)
    {
        $user = User::where('nip', $nip)->firstOrFail();
        $user->delete();

        return redirect()->route('admin_user.dashboard')->with('success', 'User berhasil dihapus');
    }

    public function import(Request $request)
    {
 
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);

        
        try {
            // Ambil file dari request
            $file = $request->file('file');

            // Baca data dari file Excel
            $rows = Excel::toArray([], $file)[0];

            foreach ($rows as $index => $row) {
                if ($index === 0) continue; // Lewati baris header

                // Pastikan jumlah kolom cukup
                if (count($row) < 15) {
                    throw new \Exception("Format file tidak sesuai, jumlah kolom kurang.");
                }

                // Fungsi untuk mengonversi tanggal
                function parseDate($date)
                {
                    if (empty(trim($date))) {
                        return null;
                    }

                    // Jika formatnya angka (Excel serial date)
                    if (is_numeric($date)) {
                        return Carbon::instance(Date::excelToDateTimeObject($date))->format('Y-m-d');
                    }

                    // Jika formatnya string (DD/MM/YYYY)
                    if (preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $date)) {
                        return Carbon::createFromFormat('d/m/Y', $date)->format('Y-m-d');
                    }

                    return null;
                }

                // Konversi format tanggal
                $tanggalNaikGaji = parseDate($row[8]);
                $tanggalLahir = parseDate($row[12]);

                $rowData = [
                    'name' => trim($row[0]),
                    'nip' => trim($row[1]),
                    'gol' => trim($row[2]),
                    'jabatan' => trim($row[3]),
                    'unit' => trim($row[4]),
                    'kode_jabatan' => trim($row[5]),
                    'kode_unit' => trim($row[6]),
                    'bidang_tugas' => trim($row[7]),
                    'tanggal_naik_gaji' => $tanggalNaikGaji,
                    'periode_unit_bln' => trim($row[9]),
                    'lama_tg_mas_th' => trim($row[10]),
                    'pendidikan' => trim($row[11]),
                    'tanggal_lahir' => $tanggalLahir,
                    'jenis_kelamin' => trim($row[13]),
                    'agama' => trim($row[14]),
                    'role' => null,
                    'email' => null, // Email belum dibuat
                    'password' => null, // Password belum dibuat
                ];

                // Validasi Data Wajib
                if (empty($rowData['nip']) || empty($rowData['name']) || empty($rowData['jabatan'])) {
                    continue;
                }

                // Cek apakah NIP sudah ada di database
                if (User::where('nip', $rowData['nip'])->exists()) {
                    continue;
                }

                // Simpan ke database User tanpa email dan password
                User::create($rowData);
            }

            return redirect()->back()->with('success', 'Data berhasil diimport.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat mengimport data: ' . $e->getMessage());
        }
    }


    public function generateAccounts()
    {
        try {
            $users = User::whereNull('email')->get();

            foreach ($users as $user) {
                // Buat email berdasarkan nama pengguna
                $firstName = strtolower(str_replace(' ', '', explode(' ', $user->name)[0]));
                do {
                    $email = $firstName . rand(100, 999) . "@example.com";
                } while (User::where('email', $email)->exists());

                // Generate password acak
                $password = Str::random(10);

                // Perbarui data user dengan email dan password
                $user->update([
                    'email' => $email,
                    'password' => Hash::make($password),
                ]);

                // Simpan ke model GeneratedAccount
                GeneratedAccount::create([
                    'name' => $user->name,
                    'nip' => $user->nip,
                    'email' => $email,
                    'password' => $password, // Simpan hanya sementara untuk testing
                ]);
            }

            return redirect()->back()->with('success', 'Akun berhasil dibuat.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat membuat akun: ' . $e->getMessage());
        }
    }

    public function form_akun()
    {
        if (Auth::user()->role !== 'admin_user') {
            return abort(403, 'Unauthorized action.');
        }

        return view('akun.form');
    }

}