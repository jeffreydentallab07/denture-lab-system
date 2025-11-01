<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Clinic;
use Carbon\Carbon;

class ForgotPasswordController extends Controller
{
    /**
     * Show forgot password form
     */
    public function showForgotForm()
    {
        return view('auth.forgot-password');
    }

    /**
     * Send password reset link
     */
    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $email = $request->email;

        // Check if email exists in users table
        $user = User::where('email', $email)->first();
        $userType = 'user';

        // If not found in users, check clinics table
        if (!$user) {
            $user = Clinic::where('email', $email)->first();
            $userType = 'clinic';
        }

        // If email doesn't exist in either table
        if (!$user) {
            return back()->withErrors(['email' => 'We could not find an account with that email address.']);
        }

        // Generate token
        $token = Str::random(64);

        // Delete old tokens for this email
        DB::table('password_reset_tokens')->where('email', $email)->delete();

        // Insert new token
        DB::table('password_reset_tokens')->insert([
            'email' => $email,
            'token' => $token,
            'user_type' => $userType,
            'created_at' => Carbon::now()
        ]);

        // Send email
        try {
            Mail::send('emails.password-reset', ['token' => $token, 'email' => $email], function ($message) use ($email) {
                $message->to($email);
                $message->subject('Reset Password - Jeffrey Dental Laboratory');
            });

            return back()->with('success', 'We have sent you a password reset link to your email!');
        } catch (\Exception $e) {
            return back()->withErrors(['email' => 'Failed to send email. Please try again later.']);
        }
    }

    /**
     * Show reset password form
     */
    public function showResetForm($token)
    {
        return view('auth.reset-password', ['token' => $token]);
    }

    /**
     * Reset password
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:8|confirmed',
            'token' => 'required'
        ]);

        // Check if token exists and is valid (within 60 minutes)
        $passwordReset = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->where('token', $request->token)
            ->first();

        if (!$passwordReset) {
            return back()->withErrors(['email' => 'Invalid password reset token.']);
        }

        // Check if token is expired (60 minutes)
        if (Carbon::parse($passwordReset->created_at)->addMinutes(60)->isPast()) {
            DB::table('password_reset_tokens')->where('email', $request->email)->delete();
            return back()->withErrors(['email' => 'Password reset token has expired. Please request a new one.']);
        }

        // Update password based on user type
        if ($passwordReset->user_type === 'user') {
            $user = User::where('email', $request->email)->first();
            if ($user) {
                $user->password = bcrypt($request->password);
                $user->save();
            }
        } else {
            $clinic = Clinic::where('email', $request->email)->first();
            if ($clinic) {
                $clinic->password = bcrypt($request->password);
                $clinic->save();
            }
        }

        // Delete the token
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return redirect()->route('login')->with('success', 'Your password has been reset successfully! You can now login with your new password.');
    }
}
