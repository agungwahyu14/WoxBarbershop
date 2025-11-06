<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $user = $request->user();

        if ($user->hasRole(['admin', 'pegawai'])) {
            return view('admin.profile.edit', [
                'user' => $user,
            ]);
        } elseif ($user->hasRole(['pelanggan', 'customer'])) {
            // Check if user can redeem loyalty points
            $canRedeem = $user->loyalty && $user->loyalty->canRedeemFreeService();
            
            return view('profile.edit', [
                'user' => $user,
                'canRedeem' => $canRedeem,
            ]);
        }

        abort(403, 'Unauthorized action.');
    }

    /**
     * Update user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse|JsonResponse
    {
        try {
            $user = $request->user();
            $data = $request->validated();
            $oldData = $user->only(['name', 'email', 'no_telepon', 'profile_photo']);

            Log::info('Profile update attempt', [
                'user_id' => $user->id,
                'email' => $user->email,
                'ip' => $request->ip(),
                'updated_fields' => array_keys($data)
            ]);

            // Handle profile photo upload
            if ($request->hasFile('profile_photo')) {
                try {
                    // Delete old profile photo if exists
                    if ($user->profile_photo && Storage::disk('public')->exists($user->profile_photo)) {
                        Storage::disk('public')->delete($user->profile_photo);
                        
                        Log::info('Old profile photo deleted', [
                            'user_id' => $user->id,
                            'old_photo' => $user->profile_photo
                        ]);
                    }

                    // Store new profile photo
                    $path = $request->file('profile_photo')->store('profile_photos', 'public');
                    $data['profile_photo'] = $path;
                    
                    Log::info('New profile photo uploaded', [
                        'user_id' => $user->id,
                        'new_photo_path' => $path,
                        'file_size' => $request->file('profile_photo')->getSize(),
                        'mime_type' => $request->file('profile_photo')->getMimeType()
                    ]);

                } catch (\Exception $e) {
                    Log::error('Profile photo upload failed', [
                        'user_id' => $user->id,
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString()
                    ]);

                    if ($request->expectsJson()) {
                        return response()->json([
                            'success' => false,
                            'message' => __('auth.update_failed'),
                            'errors' => ['profile_photo' => __('auth.update_failed')]
                        ], 422);
                    }

                    return Redirect::route('profile.edit')
                        ->with('error', 'Failed to upload profile photo. Please try again.');
                }
            }

            $user->fill($data);

            if ($user->isDirty('email')) {
                $user->email_verified_at = null;
                Log::info('Email changed - verification reset', [
                    'user_id' => $user->id,
                    'old_email' => $oldData['email'],
                    'new_email' => $data['email']
                ]);
            }

            $user->save();

            Log::info('Profile updated successfully', [
                'user_id' => $user->id,
                'old_data' => $oldData,
                'new_data' => $user->only(['name', 'email', 'no_telepon', 'profile_photo']),
                'ip' => $request->ip()
            ]);

            // Return JSON response for AJAX requests
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => __('auth.profile_updated'),
                    'user' => [
                        'name' => $user->name,
                        'email' => $user->email,
                        'no_telepon' => $user->no_telepon,
                        'profile_photo' => $user->profile_photo ? asset('storage/' . $user->profile_photo) : null
                    ]
                ]);
            }

            return Redirect::route('profile.edit')
                ->with('success', 'Profile updated successfully!');

        } catch (\Exception $e) {
            Log::error('Profile update failed', [
                'user_id' => $request->user()->id ?? 'unknown',
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'ip' => $request->ip()
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => __('auth.update_failed'),
                    'errors' => ['general' => __('auth.update_failed')]
                ], 500);
            }

            return Redirect::route('profile.edit')
                ->with('error', 'Failed to update profile. Please try again.');
        }
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
