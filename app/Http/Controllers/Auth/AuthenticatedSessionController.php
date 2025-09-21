<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
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
        try {
            Log::channel('user_activity')->info('User login attempt', [
                'email' => $request->email,
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);

            $request->authenticate();

            // Check if user is active
            $user = Auth::user();
            if (!$user->is_active) {
                Auth::logout();
                
                Log::channel('user_activity')->warning('Login attempt by inactive user', [
                    'user_id' => $user->id,
                    'email' => $user->email,
                    'ip' => $request->ip()
                ]);

               return redirect()->route('login')->with('error', 'Akun Anda dinonaktifkan, hubungi admin.');

            }

            // Update last login timestamp
            $user->updateLastLogin();
            
            Log::channel('user_activity')->info('User login successful', [
                'user_id' => $user->id,
                'email' => $user->email,
                'last_login_at' => $user->last_login_at,
                'ip' => $request->ip()
            ]);

            $request->session()->regenerate();

            // Default redirect for customers and other roles
            return redirect()->intended(RouteServiceProvider::HOME)
                ->with('success', 'Selamat datang kembali, ' . $user->name . '!');

        } catch (\Exception $e) {
            Log::error('Login error occurred', [
                'error' => $e->getMessage(),
                'email' => $request->email ?? 'unknown',
                'ip' => $request->ip(),
                'trace' => $e->getTraceAsString()
            ]);

              return redirect()->route('login')->with('error', 'Email or Password wrong.');
        }
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        try {
            // Log the logout attempt
            \Illuminate\Support\Facades\Log::info('User logout attempt', [
                'user_id' => auth()->id(),
                'user_name' => auth()->user()->name ?? 'Unknown',
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            Auth::guard('web')->logout();

            $request->session()->invalidate();

            $request->session()->regenerateToken();

            // Log successful logout
            \Illuminate\Support\Facades\Log::info('User logout successful');

            return redirect('/')->with('success', 'You have been logged out successfully.');
            
        } catch (\Exception $e) {
            // Log the error
            \Illuminate\Support\Facades\Log::error('Logout error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_id' => auth()->id() ?? 'Unknown',
            ]);

            // Still try to logout even if there's an error
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect('/')->with('warning', 'Logout completed with some issues.');
        }
    }
}
