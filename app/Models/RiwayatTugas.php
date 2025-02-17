<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiwayatTugas extends Model
{
    use HasFactory;

    protected $table = 'riwayat_tugas';
    protected $primaryKey = 'id_tugas';
    protected $fillable = ['id_tugas', 'nip', 'nama_tugas', 'tanggal_mulai'];

    public $timestamps = false;
}
