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

        GeneratedAccount::create([
            'name' => $request->name,
            'nip' => $request->nip,
            'email' => $email,
            'password' => $password,
        ]);

        return redirect()->route('admin_user.index')->with('success', 'Pegawai berhasil ditambahkan!');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);

        try {
            $file = $request->file('file');
            $data = Excel::toArray([], $file)[0];

            $row = []; // Inisialisasi array kosong untuk menyimpan data
            foreach ($data as $key => $rowData) {
                if ($key == 0) continue; // Skip header row
                $row[] = [
                    'nip' => $rowData[0],
                    'name' => $rowData[1],
                    'unit' => $rowData[2],
                    'tanggal_naik_gaji' => $rowData[3],
                    'gol' => $rowData[4],
                    'jabatan' => $rowData[5],
                    'kode_jabatan' => $rowData[6],
                    'kode_unit' => $rowData[7],
                    'bidang_tugas' => $rowData[8],
                    'periode_unit_bln' => $rowData[9],
                    'lama_tg_mas_th' => $rowData[10],
                    'pendidikan' => $rowData[11],
                    'tanggal_lahir' => $rowData[12],
                    'jenis_kelamin' => $rowData[13],
                    'agama' => $rowData[14],
                ];

                // Simpan data ke database
                User::create([
                    'nip' => $rowData[0],
                    'name' => $rowData[1],
                    'unit' => $rowData[2],
                    'tanggal_naik_gaji' => $rowData[3],
                    'gol' => $rowData[4],
                    'jabatan' => $rowData[5],
                    'kode_jabatan' => $rowData[6],
                    'kode_unit' => $rowData[7],
                    'bidang_tugas' => $rowData[8],
                    'periode_unit_bln' => $rowData[9],
                    'lama_tg_mas_th' => $rowData[10],
                    'pendidikan' => $rowData[11],
                    'tanggal_lahir' => $rowData[12],
                    'jenis_kelamin' => $rowData[13],
                    'agama' => $rowData[14],
                ]);
            }

            // Kirim data ke view setelah diimpor
            return view('akun.upload', ['pegawaiData' => $row])->with('success', 'Data berhasil diimport.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat mengimport data.');
        }
    }


    public function generateAccounts()
    {
        try {
            $users = User::whereNull('email')->get();

            foreach ($users as $user) {
                $email = Str::slug($user->name, '.') . '@example.com';
                $password = Str::random(10);

                $user->update([
                    'role' => 'pegawai',
                    'email' => $email,
                    'password' => Hash::make($password),
                ]);

                // Simpan data akun yang dibuat ke log (jika diperlukan)
                GeneratedAccount::create([
                    'name' => $user->name,
                    'email' => $email,
                    'password' => $password, // Simpan hanya sementara untuk testing
                ]);
            }

            return redirect()->back()->with('success', 'Akun berhasil dibuat.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat membuat akun.');
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