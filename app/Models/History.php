<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class History extends Model
{
    use HasFactory;

    protected $table = 'history';
    protected $primaryKey = 'id_history';
    public $timestamps = false;

    protected $fillable = [
        'nip',
        'unit'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'nip', 'nip');
    }
}
