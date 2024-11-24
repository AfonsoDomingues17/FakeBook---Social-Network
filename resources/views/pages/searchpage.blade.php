@extends('layouts.app')

@section('content')

<section class="homepage-layout">
    <section class="left-sidebar">
        <h1>Welcome to FakeBook</h1>
        @include('partials.search')
        <a class="button" id="search-users">Users</a><br>
        <a class="button" id="search-posts">Posts</a><br>
        <a class="button" id="search-groups">Groups</a>
    </section>
    <section id="search-results">
        <h2>
            Search results ({{$type}}) for "{{ $query }}"
        </h2>
        <section id="search-results-container">
            @if($type === 'users')
                @foreach($users as $userData)
                    @include('partials.user', ['user' => $userData['user'], 'isInWatchlist' => $userData['isInWatchlist']])
                @endforeach
            @elseif($type === 'posts')
                @each('partials.post', $posts, 'post')
            @elseif($type === 'groups')
                @each('partials.group', $groups, 'group')
            @endif
        </section>
    </section>
    <div id="loading" style="display: none;">Loading...</div>
</section>

<script>
    var searchUrl = '{{ url("search") }}';
    var searchType = @json($type);
    var searchQuery = @json($query);
</script>
<script type="text/javascript" src={{ url('js/lazyScroll.js') }} defer></script>
<script type="text/javascript" src={{ url('js/searchType.js') }} defer></script>

@endsection