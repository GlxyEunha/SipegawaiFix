<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RollingHistory extends Model
{
    use HasFactory;

    protected $fillable = ['nip', 'old_unit', 'new_unit', 'is_accepted'];

    public function user()
    {
        return $this->belongsTo(User::class, 'nip', 'nip');
    }
}
