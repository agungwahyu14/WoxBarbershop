<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class PasswordController extends Controller
{
    /**
     * Update the user's password.
     */
    public function update(Request $request): RedirectResponse|JsonResponse
    {
        try {
            $validated = $request->validateWithBag('updatePassword', [
                'current_password' => ['required', 'current_password'],
                'password' => ['required', Password::defaults(), 'confirmed'],
            ]);

            $request->user()->update([
                'password' => Hash::make($validated['password']),
            ]);

            // Return JSON response for AJAX requests
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => __('auth.password_updated')
                ]);
            }

            return back()->with('status', 'password-updated');

        } catch (\Illuminate\Validation\ValidationException $e) {
            // Handle validation errors for AJAX
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => __('auth.password_update_failed'),
                    'errors' => $e->errors()
                ], 422);
            }

            throw $e;

        } catch (\Exception $e) {
            // Handle general errors for AJAX
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => __('auth.password_update_failed'),
                    'errors' => ['general' => __('auth.password_update_failed')]
                ], 500);
            }

            return back()->with('error', 'Password update failed. Please try again.');
        }
    }
}
