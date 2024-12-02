<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class CheckResetToken
{
    public function handle($request, Closure $next)
    {
        $userId = $request->route('user_id');
        $token = $request->route('token');

        $user = User::findOrFail($userId);
        // If the user doesn't exist, redirect to the not found page
        if (!$user) {
            return redirect()->route('reset.not.found');
        }

        // Check if the token exists in the database
        $reset = DB::table('password_reset_tokens')->where('email', $user->email)->first();

        // If the token doesn't exist or doesn't match the one in the database, redirect to the not found page
        if (!$reset || !Hash::check($token, $reset->token)) {
            return redirect()->route('reset.not.found');
        }

        return $next($request);
    }
}