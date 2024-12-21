@extends('layouts.app')

@section('content')
<section id="editprofile">
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    <div class="form">
    
    <form method="POST" action="{{ route('updateprofile', ['user_id' => $user->id]) }}" enctype="multipart/form-data">
    {{ csrf_field() }}
    {{ method_field('PUT') }}

        <!-- Profile Picture Preview -->
        <img id="p_picture_review" src="{{ route('userphoto', ['user_id' => $user->id]) }}" alt="profile picture" width="200" height="200">
        <input id="photo_url" type="file" name="photo_url" accept="image/*" onchange="previewProfilePicture(event)">
        @if ($errors->has('photo_url'))
            <span class="error">{{ $errors->first('photo_url') }} <i class="fa-solid fa-circle-exclamation"></i></span>
        @endif
        
        <!-- Name Field -->
        <label for="name">Name<em style="color: red;">*</em></label>
        <input id="name" type="text" name="name" value="{{ old('name', $user->name) }}" required>
        @if ($errors->has('name'))
            <span class="error">{{ $errors->first('name') }} <i class="fa-solid fa-circle-exclamation"></i></span>
        @endif

        <!-- Username Field -->
        <label for="username">Username<em style="color: red;">*</em></label>
        <input id="username" type="text" name="username" value="{{ old('username', $user->username) }}" required>
        @if ($errors->has('username'))
            <span class="error">{{ $errors->first('username') }} <i class="fa-solid fa-circle-exclamation"></i></span>
        @endif

        <!-- Bio Field -->
        <label for="bio">Bio</label>
        <input id="bio" type="text" name="bio" value="{{ old('bio', $user->bio) }}">
        @if ($errors->has('bio'))
            <span class="error">{{ $errors->first('bio') }} <i class="fa-solid fa-circle-exclamation"></i></span>
        @endif

        <!-- Gender Dropdown -->
        <label for="gender">Gender<em style="color: red;">*</em></label>
        <select id="gender" name="gender" required>
            <option value="" disabled selected>Select your gender</option>
            <option value="Male" {{ old('gender', $user->gender) == 'Male' ? 'selected' : '' }}>Male</option>
            <option value="Female" {{ old('gender', $user->gender) == 'Female' ? 'selected' : '' }}>Female</option>
            <option value="Other" {{ old('gender', $user->gender) == 'Other' ? 'selected' : '' }}>Other</option>
        </select>
        @if ($errors->has('gender'))
            <span class="error">{{ $errors->first('gender') }} <i class="fa-solid fa-circle-exclamation"></i></span>
        @endif

        <!-- Age Field -->
        <label for="age">Age<em style="color: red;">*</em></label>
        <input id="age" type="number" name="age" value="{{ old('age', $user->age) }}" required>
        @if ($errors->has('age'))
            <span class="error">{{ $errors->first('age') }} <i class="fa-solid fa-circle-exclamation"></i></span>
        @endif

        <!-- Country Search Input and Dropdown -->
        <label for="country-search">Country</label>
        <input id="country-search" type="text" placeholder="Start typing your country..." value="{{ old('country', $user->country->name ?? '') }}" oninput="filterCountries()" onclick="toggleCountryDropdown()">
        <input type="hidden" id="country-id" name="country_id" value="{{ old('country_id', $user->country_id ?? '') }}">
        <select id="country" size="5"  onchange="selectCountry(event)">
            @foreach ($countries as $country)
                <option value="{{ $country->id }}" {{ old('country_id', $user->country_id ?? '') == $country->id ? 'selected' : '' }}>{{ $country->name }}</option>
            @endforeach
        </select>
        @if ($errors->has('country_id'))
            <span class="error">{{ $errors->first('country_id') }} <i class="fa-solid fa-circle-exclamation"></i></span>
        @endif

        <!-- Visibility Radio Buttons -->
        <div class="radio-group">
            <label for="public">Visibility:<em style="color: red;">*</em></label>
            <div id="radio">
                <label for="public">Public</label>
                <input type="radio" id="public" name="is_public" value="public" required {{ old('is_public', $user->is_public ? 'public' : 'private') == 'public' ? 'checked' : '' }}>
            </div>
            <div id="radio">
                <label for="private">Private</label>
                <input type="radio" id="private" name="is_public" value="private" required {{ old('is_public', $user->is_public ? 'public' : 'private') == 'private' ? 'checked' : '' }}>
            </div>
        </div>
        @if ($errors->has('is_public'))
            <span class="error">{{ $errors->first('is_public') }} <i class="fa-solid fa-circle-exclamation"></i></span>
        @endif
        <p><em style="color: red;">*</em> Fields are required.</p>
        <!-- Submit Button -->
        <button type="submit">Confirm Changes</button>

    </form>
    </div>
</section>
<script src="{{ asset('js/register.js') }}" defer></script>
@endsection
