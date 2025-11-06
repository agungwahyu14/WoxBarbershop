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
            ], [
                'email.unique' => __('auth.email_already_exists'),
                'no_telepon.unique' => __('auth.phone_already_exists'),
                'name.required' => __('auth.name_required'),
                'email.required' => __('auth.email_required'),
                'email.email' => __('auth.email_invalid'),
                'no_telepon.required' => __('auth.phone_required'),
                'password.required' => __('auth.password_required'),
                'password.confirmed' => __('auth.password_confirmation_mismatch'),
            ]);

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'no_telepon' => $request->no_telepon,
                'password' => Hash::make($request->password),
                'is_active' => true, // Set user aktif secara default saat registrasi
                'email_verified_at' => now(), // Auto verify email
            ]);

            $user->assignRole('pelanggan');

            Log::info('User registered successfully', [
                'user_id' => $user->id,
                'email' => $user->email,
                'name' => $user->name,
                'role' => 'pelanggan',
                'is_active' => true,
                'email_verified_at' => $user->email_verified_at,
                'auto_verified' => true,
                'ip' => $request->ip()
            ]);

            // Skip email verification event since we auto-verify
            // event(new Registered($user));

            Auth::login($user);

            return redirect()->route('dashboard')
                ->with('success', __('auth.registration_successful_welcome'));

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::warning('Registration validation failed', [
                'email' => $request->email,
                'errors' => $e->errors(),
                'ip' => $request->ip()
            ]);

            // Ambil error message yang paling relevan
            $errorMessage = '';
            $errors = $e->errors();
            
            if (isset($errors['email']) && count($errors['email']) > 0) {
                $errorMessage = $errors['email'][0]; // Ambil error email pertama
            } elseif (isset($errors['no_telepon']) && count($errors['no_telepon']) > 0) {
                $errorMessage = $errors['no_telepon'][0]; // Ambil error phone pertama
            } else {
                // Ambil error pertama dari field manapun
                $firstFieldErrors = array_values($errors)[0];
                $errorMessage = $firstFieldErrors[0];
            }

            return back()->withInput()->with('error', $errorMessage);

        } catch (\Exception $e) {
            Log::error('Registration failed with exception', [
                'email' => $request->email,
                'error' => $e->getMessage(),
                'ip' => $request->ip(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()->withInput()->with('error', __('auth.registration_failed'));
        }
    }
}
