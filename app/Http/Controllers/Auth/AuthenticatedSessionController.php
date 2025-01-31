<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();
        $request->session()->regenerate();

        // Cek apakah user memiliki role
        $role = auth()->user()->role ?? null;

        if ($role === 'admin_sdm') {
            return redirect()->route('admin_sdm.dashboard');
        } elseif ($role === 'admin_user') {
            return redirect()->route('admin_user.dashboard');
        } elseif ($role === 'pegawai') {
            return redirect()->route('pegawai.dashboard');
        } elseif ($role === 'pemutus') {
            return redirect()->route('pemutus.dashboard');
        }

        return redirect('/dashboard'); // Jika role tidak ditemukan, arahkan ke dashboard umum
    }


    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
