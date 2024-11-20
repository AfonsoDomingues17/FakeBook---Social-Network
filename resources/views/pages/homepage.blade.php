@extends('layouts.app')

@section('content')

<section>
    <h1>Welcome to FakeBook</h1>
    <p>Posts to be added here later</p>
</section>
<section id="users">

<h2>Users that you may know!</h2>
@each('partials.user', $users, 'user')

</section>
@endsection