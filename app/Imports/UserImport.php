<?php

namespace App\Imports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;


class UserImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new User([
            'name' => $row['name'],
            'nip' => $row['nip'],
            'unit' => $row['unit'],
            'tanggal_naik_gaji' => $this->convertExcelDate($row['tanggal_naik_gaji']),
            'gol' => $row['gol'],
            'jabatan' => $row['jabatan'],
            'kode_jabatan' => $row['kode_jabatan'],
            'kode_unit' => $row['kode_unit'],
            'bidang_tugas' => $row['bidang_tugas'],
            'periode_unit_bln' => $row['periode_unit_bln'],
            'lama_tg_mas_th' => $row['lama_tg_mas_th'],
            'pendidikan' => $row['pendidikan'],
            'tanggal_lahir' => $this->convertExcelDate($row['tanggal_lahir']),
            'jenis_kelamin' => $row['jenis_kelamin'],
            'agama' => $row['agama'],
        ]);
    }


    private function convertExcelDate($date)
    {
        if (empty($date) || $date == 'NULL') {
            return null; // Jika kosong, kembalikan null
        }

        // Jika input sudah dalam format yang dapat diproses sebagai tanggal
        if (strtotime($date)) {
            return Carbon::parse($date)->format('Y-m-d');
        }

        // Jika input adalah angka (format serial Excel)
        if (is_numeric($date) && $date > 0) {
            $unixTimestamp = ($date - 25569) * 86400; // Konversi ke UNIX timestamp
            return Carbon::createFromTimestampUTC($unixTimestamp)->format('Y-m-d');
        }

        return null; // Jika format tidak dikenal, return null
    }
}