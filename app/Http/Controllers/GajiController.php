<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Carbon\Carbon;

class GajiController extends Controller
{
    public function index(Request $request)
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
    
        return view('gaji.index', compact('pegawai', 'jumlahKenaikanGaji'));
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

        return redirect()->route('gaji.index')->with('success', 'Kenaikan gaji telah disetujui.');
    }

}
