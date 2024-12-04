@extends('layouts.app')

@section('content')
    <div class="form">
        <form method="POST" action="{{ route('password.email') }}">
            @csrf
            <label for="email">Enter your email:</label>
            <input id="email" type="email" name="email" required autofocus>

            @if ($errors->has('email'))
                <span class="error">{{ $errors->first('email') }}</span>
            @endif

            <button type="submit">Send Reset Link</button>
            @if (session('status'))
                <p class="success">{{ session('status') }}</p>
            @endif
        </form>
    </div>
@endsection
