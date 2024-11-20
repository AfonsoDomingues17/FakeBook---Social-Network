@extends('layouts.app')

@section('content')

<section id="profile">
   
        <img src="https://salaovirtual.org/wp-content/uploads/2022/01/Brasileiros-carecas-2.jpg" alt=""><br>
        
        <span id="username">{{$user->username}}</span> <br>
        <a href="{{route('editprofile',['user_id' => $user->id])}}">Edit Profile</a> <br>
        <h2>{{$user->name}}</h2> <br>
        <span id="bio"><p>{{$user->bio}}</p></span><br>
        <span><p>Publicações:{{$n_posts}}</p></span>
        <span><p>Followers:{{$n_followers}}</p></span>
        <span><p>Following:{{$n_following}}</p></span>
        @if ($user->id != Auth::user()->id)
        <button>Follow</button>
        <button>Send Message</button>
        <button>Block</button>
        @endif
        
</section>
@if (!$user->is_public)
    <p>This profile is private</p>
    <p>Follow to see all the posts of this user!</p>
    <button>Follow</button>

@else
<section id="posts">
    @if ($n_posts > 0)
        <p>Post to be printed here later</p>
    
    @else 
        @if ($user->id != Auth::user()->id)
        <p>This user has no posts!</p>
        @else
        <p>You dont have any post! Post something!</p>
        <a href="">Add post</a>
        @endif
    @endif
</section>
@endif


@endsection