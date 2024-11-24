@extends('layouts.app')

@section('content')

<section id="profile">

<img src="{{ route('userphoto', ['user_id' => $user->id]) }}" alt="profile picture" width="200" height="200"><br>

        
        <span id="username">{{$user->username}}</span> <br>
        <h2>{{$user->name}}</h2> <br>
        <span id="bio"><p>{{$user->bio}}</p></span><br>
        <span><p>Publicações:{{$n_posts}}</p></span>
        <span><p>Followers:{{$n_followers}}</p></span>
        <span><p>Following:{{$n_following}}</p></span>
        @if(Auth::check())
            @if (Auth::user()->isAdmin() || $user->id == Auth::user()->id)
            <a href="{{route('editprofile',['user_id' => $user->id])}}">Edit Profile</a> <br>
            @endif
            @if (Auth::user()->isAdmin())
            <form action="{{ route('deleteuser', ['user_id' => $user->id]) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this account? This action cannot be undone.');">
                @csrf
                @method('DELETE')
                <button type="submit">Delete account</button>
            </form>
            @endif
            @if (!Auth::user()->isAdmin() && Auth::user()->id != $user->id)
            <button>Follow</button>
            <button>Send Message</button>
            <button>Block</button>
            @endif
        @endif
</section>
@if (Auth::check() && $user->id == Auth::user()->id) <!-- If the user is logged in and is the owner of the profile -->
    <section id="posts">
        @if ($n_posts > 0)
            @foreach ($posts as $post)
                @include('partials.post', ['post' => $post])
            @endforeach
        @else 
            <p>You dont have any post! Post something!</p>
        @endif
        <a href="{{ route('posts.create') }}">Add post</a>
    </section>
    
@else
    @if ($user->is_public || (Auth::check() && Auth::user()->isAdmin()))
        <section id="posts">
            @if ($n_posts > 0)
                @foreach ($posts as $post)
                    @include('partials.post', ['post' => $post])
                @endforeach
            @else 
                <p>This user has no posts!</p>
            @endif
        
        </section>
    @else
        <section id="posts">
            <p>This user profile is private!</p>
            @if (Auth::check())
                <p>Follow to see more of this user</p>
                <button>Follow</button>
            @else
                <p>Login to see more of this user</p>
                <a href="{{url('/login')}}">Login</a>
            @endif
        </section>
    @endif  
@endif
@endsection