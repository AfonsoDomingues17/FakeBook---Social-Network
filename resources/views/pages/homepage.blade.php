@extends('layouts.app')

@section('content')
<section class="homepage-layout">
    <section class="left-sidebar">
        <h1>Welcome to FakeBook</h1>
        @include('partials.search')
    </section>
    <section id="posts">
        <h2>
            @if($type === 'public')
                Public Posts
            @else
                Following Posts
            @endif
        </h2>
        <section id="posts-container">
            @if($type === 'following' && Auth::check())
                <h3>Login to see posts from accounts you follow</h3>
                <a href="{{ route('login') }}">
                    <button id="follow-redirect-login">Login</button>
                </a>
            @else
                @each('partials.post', $posts, 'post')
            @endif
        </section>
    </section>
    <section class="suggested-users">
        <h2>Users that you may know!</h2>
        @each('partials.user', $users, 'user')
    </section>
</section>

@endsection