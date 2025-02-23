<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory;

    protected $table = 'permissions'; // Sesuaikan dengan nama tabel di database
    protected $primaryKey = 'nip'; // Sesuaikan jika menggunakan 'nip' sebagai primary key
    public $timestamps = false;
    protected $fillable = ['nip', 'dashboard', 'daftar_pegawai', 'tambah_pengguna', 'rolling', 'riwayat', 'gaji'];

    public function user()
    {
        return $this->belongsTo(User::class, 'nip', 'nip');
    }
}
