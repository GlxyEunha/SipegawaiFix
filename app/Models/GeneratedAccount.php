<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GeneratedAccount extends Model
{
    use HasFactory;

    protected $table = 'generated_akun';
    protected $fillable = ['nip', 'name', 'email', 'password'];

    public $timestamps = false;
}
