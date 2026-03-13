<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
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

        $user = Auth::user();

        if (!$user) {
            Auth::logout();
            return redirect()->route('login');
        }

        if ($user->isAdmin()) {
            return redirect()->route('dashboard');
        }

        if ($user->isOperator()) {
            return redirect()->route('sigint.frequency.index');
        }

        return redirect()->route('dashboard');
    }


    /*
    |--------------------------------------------------------------------------
    | VERIFY OTP
    |--------------------------------------------------------------------------
    */

    public function verifyOtp(Request $request): RedirectResponse
    {
        $request->validate([
            'otp' => ['required', 'digits:6']
        ]);

        if (!session('otp_hash') || now()->gt(session('otp_expires_at'))) {
            session()->flush();
            return redirect()->route('login')
                ->withErrors(['otp' => 'OTP expired. Please login again.']);
        }

        if (session('otp_attempts') >= 3) {
            session()->flush();
            return redirect()->route('login')
                ->withErrors(['otp' => 'Too many failed attempts.']);
        }

        if (!Hash::check($request->otp, session('otp_hash'))) {
            session(['otp_attempts' => session('otp_attempts') + 1]);
            return back()->withErrors(['otp' => 'Invalid OTP.']);
        }

        /*
        |--------------------------------------------------------------------------
        | OTP SUCCESS — COMPLETE LOGIN
        |--------------------------------------------------------------------------
        */

        $userId = session('otp_user_id');

        Auth::loginUsingId($userId);

        session()->forget([
            'otp_hash',
            'otp_expires_at',
            'otp_attempts',
            'otp_user_id',
            'otp_role'
        ]);

        $user = Auth::user();

        // ROLE-BASED REDIRECT
        if ($user->isAdmin()) {
            return redirect()->route('dashboard');
        }

        if ($user->isOperator()) {
            return redirect()->route('sigint.frequency.index');
        }

        return redirect()->route('dashboard');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
