@extends('layouts.app')

@section('content')

<section>
    <h1>Welcome to FakeBook</h1>
    @include('partials.search')
</section>
<section id="posts">
    <h2>Posts from public users:</h2>
    @each('partials.post', $posts, 'post')
</section>
<section id="users">
    <h2>Users that you may know!</h2>
    @each('partials.user', $users, 'user')
</section>
@endsection