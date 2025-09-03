<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        try {
            Log::info('User registration attempt', [
                'email' => $request->email,
                'name' => $request->name,
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);

            $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
                'no_telepon' => ['required', 'string', 'max:15', 'unique:'.User::class], // Tambahkan validasi untuk no_telepon
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
            ]);

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'no_telepon' => $request->no_telepon,
                'password' => Hash::make($request->password),
                'is_active' => true, // Set user aktif secara default saat registrasi
            ]);

            $user->assignRole('pelanggan');

            Log::info('User registered successfully', [
                'user_id' => $user->id,
                'email' => $user->email,
                'name' => $user->name,
                'role' => 'pelanggan',
                'is_active' => true,
                'ip' => $request->ip()
            ]);

            event(new Registered($user));

            Auth::login($user);

            // $request->session()->regenerate();
            return redirect()->route('verification.notice')
                ->with('success', 'Registration successful! Please verify your email address.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::warning('User registration validation failed', [
                'email' => $request->email,
                'errors' => $e->errors(),
                'ip' => $request->ip()
            ]);
            
            throw $e;

        } catch (\Exception $e) {
            Log::error('User registration failed', [
                'email' => $request->email ?? 'unknown',
                'name' => $request->name ?? 'unknown',
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'ip' => $request->ip()
            ]);

            return back()->withInput()->with('error', 'Registration failed. Please try again.');
        }
    }
}
