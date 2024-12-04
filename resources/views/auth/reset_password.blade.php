@extends('layouts.app')

@section('content')
    <div class="form">
        <form method="POST" action="{{ route('password.update') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            <input type="hidden" name="email" value="{{ $email }}">
            <label for="password">New Password</label>
            <input type="password" id="password" name="password" required>
            <label for="password_confirmation">Confirm Password</label>
            <input type="password" id="password_confirmation" name="password_confirmation" required>
            <button type="submit">Reset Password</button>
            @if ($errors->any())
                @foreach ($errors->all() as $error)
                    {{ $error }}
                @endforeach
            @endif
        </form>
    <div/>
@endsection