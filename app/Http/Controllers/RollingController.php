<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\RollingHistory;

class RollingController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('rolling.index', compact('users'));
    }

    public function store(Request $request)
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

    public function hasil()
    {
        $rollings = RollingHistory::where('is_accepted', false)
            ->join('users', 'rolling_histories.nip', '=', 'users.nip')
            ->select('rolling_histories.*', 'users.*')  // Pilih kolom yang dibutuhkan
            ->get();

        return view('rolling.hasil', compact('rollings'));
    }

    public function accept($nip)
    {
        $rolling = RollingHistory::where('nip', $nip)->where('is_accepted', false)->first();

        if ($rolling) {
            User::where('nip', $nip)->update(['unit' => $rolling->new_unit]);
            $rolling->update(['is_accepted' => true]);

            return redirect()->route('rolling.hasil')->with('success', 'Rolling diterima dan unit diperbarui.');
        }

        return redirect()->route('rolling.hasil')->with('error', 'Data tidak ditemukan.');
    }

    public function exportExcel()
    {
        return Excel::download(new RollingExport, 'hasil_rolling.xlsx');
    }
}
