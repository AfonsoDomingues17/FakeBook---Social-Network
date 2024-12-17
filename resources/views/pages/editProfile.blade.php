@extends('layouts.app')

@section('content')
<section id="editprofile">
    <div class="form">
    <form method="POST" action="{{ route('updateprofile', ['user_id' => $user->id]) }}" enctype="multipart/form-data">
    {{ csrf_field() }}
    {{ method_field('PUT') }}

        <!-- Profile Picture Preview -->
        <img id="p_picture_review" src="{{ route('userphoto', ['user_id' => $user->id]) }}" alt="profile picture" width="200" height="200">
        <input id="photo_url" type="file" name="photo_url" accept="image/*" onchange="previewProfilePicture(event)">
        
        <!-- Name Field -->
        <label for="name">Name</label>
        <input id="name" type="text" name="name" value="{{ old('name', $user->name) }}" required>
        @if ($errors->has('name'))
            <span class="error">{{ $errors->first('name') }}</span>
        @endif

        <!-- Username Field -->
        <label for="username">Username</label>
        <input id="username" type="text" name="username" value="{{ old('username', $user->username) }}" required>
        @if ($errors->has('username'))
            <span class="error">{{ $errors->first('username') }}</span>
        @endif

        <!-- Bio Field -->
        <label for="bio">Bio</label>
        <input id="bio" type="text" name="bio" value="{{ old('bio', $user->bio) }}">
        @if ($errors->has('bio'))
            <span class="error">{{ $errors->first('bio') }}</span>
        @endif

        <!-- Gender Dropdown -->
        <label for="gender">Gender</label>
        <select id="gender" name="gender" required>
            <option value="" disabled selected>Select your gender</option>
            <option value="Male" {{ old('gender', $user->gender) == 'Male' ? 'selected' : '' }}>Male</option>
            <option value="Female" {{ old('gender', $user->gender) == 'Female' ? 'selected' : '' }}>Female</option>
            <option value="Other" {{ old('gender', $user->gender) == 'Other' ? 'selected' : '' }}>Other</option>
        </select>
        @if ($errors->has('gender'))
            <span class="error">{{ $errors->first('gender') }}</span>
        @endif

        <!-- Age Field -->
        <label for="age">Age</label>
        <input id="age" type="number" name="age" value="{{ old('age', $user->age) }}" required>
        @if ($errors->has('age'))
            <span class="error">{{ $errors->first('age') }}</span>
        @endif

        <!-- Country Search Input and Dropdown -->
        <label for="country">Country</label>
        <input id="country-search" type="text" placeholder="Start typing your country..." value="{{ old('country', $user->country) }}" oninput="filterCountries()" onclick="toggleCountryDropdown()">
        <select id="country" name="country" size="5" required onchange="selectCountry(event)">
            @foreach ($countries as $country)
                <option value="{{ $country->name }}" {{ old('country', $user->country) == $country->name ? 'selected' : '' }}>{{ $country->name }}</option>
            @endforeach
        </select>
        @if ($errors->has('country'))
            <span class="error">{{ $errors->first('country') }}</span>
        @endif

        <!-- Visibility Radio Buttons -->
        <div class="radio-group">
            <label for="is_public">Visibility:</label>
            <div id="radio">
                <label for="public">Public</label>
                <input type="radio" id="public" name="is_public" value="public" required {{ old('is_public', $user->is_public ? 'public' : 'private') == 'public' ? 'checked' : '' }}>
            </div>
            <div id="radio">
                <label for="private">Private</label>
                <input type="radio" id="private" name="is_public" value="private" required {{ old('is_public', $user->is_public ? 'public' : 'private') == 'private' ? 'checked' : '' }}>
            </div>
        </div>

        <!-- Submit Button -->
        <button type="submit">Confirm Changes</button>

    </form>
    </div>
</section>
<script src="{{ asset('js/register.js') }}" defer></script>
@endsection
