<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'users';
    protected $primaryKey = 'nip';
    public $incrementing = false; // Karena nip bukan auto-increment

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nip',
        'name',
        'email',
        'password',
        'role',
        'unit',
        'tanggal_naik_gaji',
        'gol',
        'jabatan',
        'kode_jabatan',
        'kode_unit',
        'bidang_tugas',
        'periode_unit_bln',
        'lama_tg_mas_th',
        'pendidikan',
        'tanggal_lahir',
        'jenis_kelamin',
        'agama',
        'alamat',
        'no_hp',
        'no_darurat',
        
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'tanggal' => 'date',
    ];

    /**
     * Relasi ke tabel history.
     * Seorang user bisa memiliki banyak history rolling unit.
     */
    public function histories()
    {
        return $this->hasMany(History::class, 'nip', 'nip');
    }
}
