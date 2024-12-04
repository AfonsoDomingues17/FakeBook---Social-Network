<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Mail\PasswordResetMail;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;

class PasswordResetController extends Controller
{
    // Show the password reset request form
    public function showRequestForm()
    {
        return view('auth.forgot_password');
    }

    // Logic to send the password reset link
    public function sendResetLinkEmail(Request $request)
    {
        $this->validate($request, ['email' => 'required|email']);

        // Check if a user with that email exists
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'No user found with that email address.']);
        }

        // Check if a token already exists for the user
        $exists = DB::table('password_reset_tokens')->where('email', $request->email)->first();
        // If so, delete existing entry, making previous token invalid
        if($exists) {
            DB::table('password_reset_tokens')->where('email', $request->email)->delete();
        }

        $token = app('auth.password.broker')->createToken($user);

        Mail::to($user->email)->send(new PasswordResetMail($user, $token));

        return back()->with('status', 'Password reset link sent!');
    }

    // Show the password reset form
    public function showResetForm($token)
    {
        // Check if the token exists in the database
        $reset = DB::table('password_reset_tokens')->get()->first(function ($record) use ($token) {
            return Hash::check($token, $record->token);
        });

        // If the token doesn't exist redirect to the not found page
        if (!$reset) {
            return redirect()->route('reset.not.found')->withErrors(['token' => 'Invalid token.']);
        }

        // Check if the token has expired
        $expiration = config('auth.passwords.users.expire');
        $expiredTime = Carbon::parse($reset->created_at)->addMinutes($expiration);

        if (Carbon::now()->greaterThan($expiredTime)) {
            return redirect()->route('reset.not.found')
                ->withErrors(['token' => 'This password reset link has expired.']);
        }

        return view('auth.reset_password', ['token' => $token, 'email' => $reset->email]);
    }

    // Logic to reset the password
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|confirmed|min:8',
            'token' => 'required',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                ])->save();
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('success', 'Password reset successfully!')
            : back()->withErrors(['email' => [__($status)]]);
    }
}

