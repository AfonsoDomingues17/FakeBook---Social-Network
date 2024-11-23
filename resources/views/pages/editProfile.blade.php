@extends('layouts.app')

@section('content')

<section id="editprofile">
    <form method="POST" action="{{ route('updateprofile', ['user_id' => $user->id]) }}" enctype="multipart/form-data">
    {{ csrf_field() }}
    {{ method_field('PUT') }}

        <img id="p_picture_review" src="{{route('userphoto', ['user_id' => $user->id])}}" alt="profile picture" width="200" height="200">
        <label for="photo_url">Profile Picture</label>
        <input id="photo_url" type="file" name="photo_url" accept="image/*" onchange="previewProfilePicture(event)">

        <label for="username">username</label>
        <input id="username" type="text" name="username" value="{{ old('username', $user->username)}}" required>
        @if ($errors->has('username'))
        <span class="error">
            {{ $errors->first('username') }}
        </span>
        @endif

        <label for="name">Name</label>
        <input id="name" type="text" name="name" value="{{ old('name', $user->name)}}" required>
        @if ($errors->has('name'))
        <span class="error">
            {{ $errors->first('name') }}
        </span>
        @endif

        <label for="bio">Bio</label>
        <input id="bio" type="text" name="bio" value="{{ old('bio', $user->bio)}}" required>
        @if ($errors->has('bio'))
        <span class="error">
            {{ $errors->first('bio') }}
        </span>
        @endif

        <div class="radio-group">
        <label for="is_public">Visibility</label>
        
        @if ($user->is_public == 'public')
        <label for="public">Public</label>
        <input type="radio" id="public" name="is_public" value="public" required checked>
        <label for="private">Private</label>
        <input type="radio" id="private" name="is_public" value="private" required>
        @else
        <label for="public">Public</label>
        <input type="radio" id="public" name="is_public" value="public" required>
        <label for="private">Private</label>
        <input type="radio" id="private" name="is_public" value="private" required checked>
        @endif
        </div>

        <label for="age">Age</label>
        <input id="age" type="number" name="age" value="{{ old('age', $user->age)}}" required>
        @if ($errors->has('age'))
        <span class="error">
            {{ $errors->first('age') }}
        </span>
        @endif

        <button type="submit">
        Confirm Changes
        </button>

    </form>
    
    
    
</section>
<script>
function previewProfilePicture(event) {
    var reader = new FileReader();
    reader.onload = function(){
        var output = document.getElementById('p_picture_review');
        output.src = reader.result;
    };
    reader.readAsDataURL(event.target.files[0]);
}
</script>

@endsection