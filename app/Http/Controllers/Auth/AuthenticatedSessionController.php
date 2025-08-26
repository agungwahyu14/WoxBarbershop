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

        // Default redirect for customers and other roles
        return redirect()->intended(RouteServiceProvider::HOME);
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
