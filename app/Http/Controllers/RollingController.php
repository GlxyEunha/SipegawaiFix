<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\RollingHistory;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;

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

    public function hasil(Request $request)
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
}
