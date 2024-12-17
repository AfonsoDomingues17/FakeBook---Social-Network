@extends('layouts.app')

@section('content')
<div id="register">
<div class="form">
<form method="POST" action="{{ Auth::check() && Auth::user()->isAdmin() ?  route('admin.create') : route('register') }}" enctype="multipart/form-data">
    {{ csrf_field() }}

    <img id="p_picture_review" src="https://i.pinimg.com/736x/0d/64/98/0d64989794b1a4c9d89bff571d3d5842.jpg" alt="preview of profile picture" width="200" height="200">
    <input id="photo_url" type="file" name="photo_url" accept="image/*" onchange="previewProfilePicture(event)">
    
    <label for="name">Name</label>
    <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus>
    @if ($errors->has('name'))
      <span class="error">{{ $errors->first('name') }}</span>
    @endif

    <label for="username">Username</label>
    <input id="username" type="text" name="username" value="{{ old('username') }}" required>
    @if ($errors->has('username'))
      <span class="error">{{ $errors->first('username') }}</span>
    @endif

    <label for="email">E-Mail Address</label>
    <input id="email" type="email" name="email" value="{{ old('email') }}" required>
    @if ($errors->has('email'))
      <span class="error">{{ $errors->first('email') }}</span>
    @endif

    <label for="password">Password</label>
    <input id="password" type="password" name="password" required>
    @if ($errors->has('password'))
      <span class="error">{{ $errors->first('password') }}</span>
    @endif

    <label for="password-confirm">Confirm Password</label>
    <input id="password-confirm" type="password" name="password_confirmation" required>

    <label for="bio">Bio</label>
    <input id="bio" type="text" name="bio" value="{{ old('bio') }}">
    @if ($errors->has('bio'))
      <span class="error">{{ $errors->first('bio') }}</span>
    @endif

    <label for="age">Age</label>
    <input id="age" type="number" name="age" value="{{ old('age') }}" required>
    @if ($errors->has('age'))
      <span class="error">{{ $errors->first('age') }}</span>
    @endif

    <label for="country">Country</label>
    <input id="country-search" type="text" class="form-control" placeholder="Start typing your country..." oninput="filterCountries()" onclick="toggleCountryDropdown()">
    <select id="country" name="country" size="5" required onchange="selectCountry(event)">
        @foreach ($countries as $country)
            <option value="{{ $country->name }}">{{ $country->name }}</option>
        @endforeach
    </select>
    @if ($errors->has('country'))
      <span class="error">{{ $errors->first('country') }}</span>
    @endif

    <label for="gender">Gender</label>
    <select id="gender" name="gender" required>
        <option value="" disabled selected>Select your gender</option>
        <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>Male</option>
        <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>Female</option>
        <option value="Other" {{ old('gender') == 'Other' ? 'selected' : '' }}>Other</option>
    </select>
    @if ($errors->has('gender'))
      <span class="error">{{ $errors->first('gender') }}</span>
    @endif

    <div class="radio-group">
      <label for="is_public">Visibility:</label>
      <div id="radio">
        <label for="public">Public</label>
        <input type="radio" id="public" name="is_public" value="public" required>
      </div>
      <div id="radio">
        <label for="private">Private</label>
        <input type="radio" id="private" name="is_public" value="private" required>
      </div>
    </div>

    @if(Auth::check() && Auth::user()->isAdmin())
        <button type="submit">Create User</button>
    @else
        <button type="submit">Register</button>
    @endif
</form>
</div>
</div>
<script src="{{ asset('js/register.js') }}"></script>
@endsection
