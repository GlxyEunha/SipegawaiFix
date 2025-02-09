<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\History;
use Illuminate\Support\Facades\Auth;

class AdminUserController extends Controller
{
    // Menampilkan daftar pegawai yang bisa dirolling
    public function index()
    {
        if (Auth::user()->role !== 'admin_user') {
            return abort(403, 'Unauthorized action.');
        }

        $users = User::all();
        return view('dashboard.admin_user', compact('users'));
    }

}