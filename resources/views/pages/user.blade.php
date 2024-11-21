@extends('layouts.app')

@section('content')

<section id="profile">

<img src="{{ $user->photo_url ? Storage::url($user->photo_url) : Storage::url('profile_pictures/default-profile.png') }}" alt="profile picture" width="200" height="200"><br>

        
        <span id="username">{{$user->username}}</span> <br>
        @if (Auth::check() && $user->id == Auth::user()->id)
        <a href="{{route('editprofile',['user_id' => $user->id])}}">Edit Profile</a> <br>
        @endif
        <h2>{{$user->name}}</h2> <br>
        <span id="bio"><p>{{$user->bio}}</p></span><br>
        <span><p>Publicações:{{$n_posts}}</p></span>
        <span><p>Followers:{{$n_followers}}</p></span>
        <span><p>Following:{{$n_following}}</p></span>
        @if (Auth::check() && $user->id != Auth::user()->id)
        <button>Follow</button>
        <button>Send Message</button>
        <button>Block</button>
        @endif
        
</section>
@if (Auth::check() && $user->id == Auth::user()->id)
    <section id="posts">
        @if ($n_posts > 0)
            <p>Post to be printed here later</p>
        
        @else 
            <p>You dont have any post! Post something!</p>
            <a href="">Add post</a>
           
        @endif
    </section>
    
@else
    @if ($user->is_public)
        <section id="posts">
            @if ($n_posts > 0)
                <p>Post to be printed here later</p>
            
            @else 
                <p>This user dont have any post!</p>
                
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