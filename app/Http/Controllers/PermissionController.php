<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Permission;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    public function indexPermissions()
    {
        $users = User::with('permissions')->get();
        return view('permissions.index', compact('users'));
    }

    public function editPermissions($nip)
    {
        $user = User::with('permissions')->where('nip', $nip)->firstOrFail();
        $availablePages = ['dashboard', 'reports', 'settings', 'users', 'profile']; // Halaman yang tersedia
        return view('permissions.edit', compact('user', 'availablePages'));
    }

    public function updatePermissions(Request $request, $nip)
    {
        $request->validate([
            'pages' => 'array|required',
        ]);

        Permission::where('user_nip', $nip)->delete(); // Hapus izin lama

        foreach ($request->pages as $page) {
            Permission::create([
                'user_nip' => $nip,
                'page' => $page,
            ]);
        }

        return redirect()->route('permissions.index')->with('success', 'Permissions updated successfully');
    }
}
