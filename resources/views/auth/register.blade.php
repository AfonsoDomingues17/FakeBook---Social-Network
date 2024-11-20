@extends('layouts.app')

@section('content')
<form method="POST" action="{{ route('register') }}">
    {{ csrf_field() }}

  
    <img id ="p_picture_review" src="https://i.pinimg.com/736x/0d/64/98/0d64989794b1a4c9d89bff571d3d5842.jpg" alt="preview of profile picture" >
  

    <label for="name">Name</label>
    <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus>
    @if ($errors->has('name'))
      <span class="error">
          {{ $errors->first('name') }}
      </span>
    @endif
    
    <label for="username">Username</label>
    <input id="username" type="text" name="username" value="{{ old('username') }}" required>
    @if ($errors->has('username'))
      <span class="error">
          {{ $errors->first('username') }}
      </span>
    @endif

    <label for="email">E-Mail Address</label>
    <input id="email" type="email" name="email" value="{{ old('email') }}" required>
    @if ($errors->has('email'))
      <span class="error">
          {{ $errors->first('email') }}
      </span>
    @endif

    <label for="password">Password</label>
    <input id="password" type="password" name="password" required>
    @if ($errors->has('password'))
      <span class="error">
          {{ $errors->first('password') }}
      </span>
    @endif

    <label for="password-confirm">Confirm Password</label>
    <input id="password-confirm" type="password" name="password_confirmation" required>

    <label for="bio">Bio</label>
    <input id="bio" type="text" name="bio" required>
    @if ($errors->has('bio'))
    <span class="error">
        {{ $errors->first('bio') }}
    </span>
    @endif

    <label for="age">Age</label>
    <input id="age" type="number" name="age" value="{{ old('age') }}" required>
    @if ($errors->has('age'))
      <span class="error">
          {{ $errors->first('age') }}
      </span>
    @endif

    <div class="radio-group">
      <label for="is_public">Visibility</label>
      <label for="public">Public</label>
      <input type="radio" id="public" name="is_public" value="public" required>
      <label for="private">Private</label>
      <input type="radio" id="private" name="is_public" value="private" required>
    </div>

    <button type="submit">
      Register
    </button>
    <a class="button button-outline" href="{{ route('login') }}">Login</a>
</form>

@endsection