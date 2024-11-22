@extends('layouts.app')

@section('content')

<section class="homepage-layout">
    <section class="left-sidebar">
        <h1>Welcome to FakeBook</h1>
        @include('partials.search')
    </section>
    <section id="search-results">
        <h2>
            Search results for "{{ $query }}"
        </h2>
        @if($type === 'users')
            @each('partials.user', $users, 'user')
        @elseif($type === 'posts')
            @each('partials.post', $posts, 'post')
        @endif
    </section>
</section>

@endsection