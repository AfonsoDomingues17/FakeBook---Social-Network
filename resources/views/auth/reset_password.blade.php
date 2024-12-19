@extends('layouts.app')

@section('content')
    <div class="form">
        <form method="POST" action="{{ route('password.update') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            <input type="hidden" name="email" value="{{ $email }}">
            <label for="password">New Password</label>
            <input type="password" id="password" name="password" required>
            @if ($errors->has('password'))
                <span class="error">{{ $errors->first('password') }}</span>
            <label for="password_confirmation">Confirm Password</label>
            <input type="password" id="password_confirmation" name="password_confirmation" required>
            @if ($errors->has('password_confirmation'))
                <span class="error">{{ $errors->first('password_confirmation') }}</span>
            <button type="submit">Reset Password</button>
        </form>
</div>
@endsection