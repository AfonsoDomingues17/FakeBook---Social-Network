@extends('layouts.app')

@section('content')

<section>
    <h1>Welcome to FakeBook</h1>
    @if (Auth::check())
        <h2>Posts from people you follow</h2>
    @else
        <h2>Public Posts</h2>
    @endif
    @foreach ($posts as $post)
        @include('partials.post', ['post' => $post])
    @endforeach
</section>

<section id="users">
    <h2>Users that you may know!</h2>
    @each('partials.user', $users, 'user')
</section>

@endsection