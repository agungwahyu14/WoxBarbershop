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
            Log::info('User login attempt', [
                'email' => $request->email,
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);

            $request->authenticate();

            // Check if user is active
            $user = Auth::user();
            if (!$user->is_active) {
                Auth::logout();
                
                Log::warning('Login attempt with inactive account', [
                    'email' => $request->email,
                    'user_id' => $user->id,
                    'ip' => $request->ip()
                ]);

                return redirect()->route('login')->with('error', __('auth.account_deactivated'));
            }

            // Update last login timestamp
            $user->updateLastLogin();

            $request->session()->regenerate();

            // Clear any admin-related intended URL for regular users
            if ($user->hasRole('pelanggan')) {
                $request->session()->forget('url.intended');
                return redirect()->route('dashboard')
                    ->with('auth_success', __('auth.welcome_back') . ', ' . $user->name . '!');
            }

            // For admin/pegawai, allow intended redirect or default to dashboard
            Log::info('User login successful', [
                'user_id' => $user->id,
                'email' => $user->email,
                'role' => $user->roles->pluck('name')->implode(','),
                'ip' => $request->ip()
            ]);

            return redirect()->intended(RouteServiceProvider::HOME)
                ->with('auth_success', __('auth.welcome_back') . ', ' . $user->name . '!');

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::warning('Login validation failed', [
                'email' => $request->email,
                'errors' => $e->errors(),
                'ip' => $request->ip()
            ]);

            // Ambil error message yang paling relevan
            $errorMessage = '';
            $errors = $e->errors();
            
            if (isset($errors['email']) && count($errors['email']) > 0) {
                $errorMessage = $errors['email'][0]; // Ambil error email pertama
            } elseif (isset($errors['password']) && count($errors['password']) > 0) {
                $errorMessage = $errors['password'][0]; // Ambil error password pertama
            } else {
                // Ambil error pertama dari field manapun
                $firstFieldErrors = array_values($errors)[0];
                $errorMessage = $firstFieldErrors[0];
            }

            return redirect()->route('login')->withInput($request->only('email'))->with('error', $errorMessage);

        } catch (\Illuminate\Auth\AuthenticationException $e) {
            Log::warning('Authentication failed', [
                'email' => $request->email ?? 'unknown',
                'ip' => $request->ip(),
                'error' => $e->getMessage()
            ]);

            return redirect()->route('login')
                ->withInput($request->only('email'))
                ->with('error', __('auth.login_credentials_invalid'));

        } catch (\Exception $e) {
            Log::error('Login error occurred', [
                'error' => $e->getMessage(),
                'email' => $request->email ?? 'unknown',
                'ip' => $request->ip(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->route('login')
                ->withInput($request->only('email'))
                ->with('error', __('auth.login_failed'));
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

            return redirect('/')->with('success', __('auth.logout_success'));
            
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

            return redirect('/')->with('warning', __('auth.logout_warning'));
        }
    }
}
