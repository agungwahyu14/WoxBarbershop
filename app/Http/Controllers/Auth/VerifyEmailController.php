<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;

class VerifyEmailController extends Controller
{
    /**
     * Mark the user's email address as verified.
     * 
     * User clicks verification link from email → Email gets verified → Redirect to login
     * No authentication required - works directly from email link.
     */
    public function __invoke(Request $request, $id, $hash): RedirectResponse
    {
        // Find user by ID
        $user = User::findOrFail($id);
        
        // Verify the hash matches the user's email
        if (! hash_equals($hash, sha1($user->getEmailForVerification()))) {
            Log::warning('Email verification failed - invalid hash', [
                'user_id' => $id,
                'email' => $user->email,
                'ip' => $request->ip()
            ]);
            
            return redirect()->route('login')
                ->with('error', __('auth.verification_link_invalid'));
        }
        
        // Check if already verified
        if ($user->hasVerifiedEmail()) {
            Log::info('User attempted to verify already verified email', [
                'user_id' => $user->id,
                'email' => $user->email
            ]);
            
            return redirect()->route('login')
                ->with('info', __('auth.email_already_verified'));
        }

        // Mark email as verified with current timestamp
        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
            
            Log::info('User email verified successfully', [
                'user_id' => $user->id,
                'email' => $user->email,
                'verified_at' => $user->email_verified_at,
                'ip' => $request->ip()
            ]);
        }

        // Redirect to login with success message
        return redirect()->route('login')
            ->with('success', __('auth.email_verified_success'));
    }
}
