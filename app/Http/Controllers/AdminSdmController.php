<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\History;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminSdmController extends Controller
{
    public function index(Request $request)
    {
        if (Auth::user()->role !== 'admin_sdm') {
            return abort(403, 'Unauthorized action.');
        }

        $query = User::query();

        // Search
        if ($request->has('search') && $request->get('search') != '') {
            $query->where('name', 'like', '%' . $request->get('search') . '%')
                ->orWhere('nip', 'like', '%' . $request->get('search') . '%')
                ->orWhere('unit', 'like', '%' . $request->get('search') . '%');
        }

        // Filter by Golongan
        $gol = $request->input('gol');
        if ($gol && $gol != 'semua') {
            $query->where('gol', $gol);
        }

        $users = $query->get();
    
            return view('dashboard.admin_sdm', compact('users'));
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

    public function daftar_tugas()
    {
        if (Auth::user()->role !== 'admin_sdm') {
            return abort(403, 'Unauthorized action.');
        }

        $tugas = DB::table('users')
        ->join('riwayat_tugas', 'users.nip', '=', 'riwayat_tugas.nip')
        ->select('users.*', 'riwayat_tugas.nama_tugas', 'riwayat_tugas.tanggal_mulai', 'riwayat_tugas.id_tugas')
        ->get();

        return view('riwayat.daftar_tugas', compact('tugas'));
    }
}