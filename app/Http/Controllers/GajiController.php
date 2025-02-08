<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Carbon\Carbon;

class GajiController extends Controller
{
    public function index()
    {
        $today = Carbon::now();
        $threshold = $today->copy()->subMonths(23); // 1 tahun 11 bulan
        $limit = $today->copy()->subMonths(24); // 2 tahun

        // Ambil pegawai yang memenuhi syarat (>= 1 tahun 11 bulan dan < 2 tahun)
        $pegawai = User::where(function ($query) use ($threshold, $limit) {
                            $query->where('tanggal_naik_gaji', '<=', $threshold)
                                  ->where('tanggal_naik_gaji', '>', $limit);
                        })
                        ->get();

        $jumlahKenaikanGaji = $pegawai->count(); // Hitung jumlah pegawai

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
