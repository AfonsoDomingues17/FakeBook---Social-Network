@extends('layouts.app')

@section('content')

<section class="homepage-layout d-flex justify-content-center align-items-center">
    <section id="search-results" class="d-flex flex-column align-items-center">
        <h2 class="mt-2 mb-3">
            Search results (<?=$type?>) for "{{ $query }}"
        </h2>
        <section id="search-results-container">
            @if($type === 'users')
                @each('partials.user', $users, 'user')
            @elseif($type === 'posts')
                @each('partials.post', $posts, 'post')
            @elseif($type === 'groups')
                @each('partials.group', $groups, 'group')
            @endif
        </section>
    </section>
    <div id="loading" style="display: none;" class="spinner-border" role="status"></div>
</section>

<script>
    var searchUrl = '{{ url("search") }}';
    var searchType = @json($type);
    var searchQuery = @json($query);
</script>
<script type="text/javascript" src={{ url('js/lazyScroll.js') }} defer></script>
<script type="text/javascript" src={{ url('js/searchType.js') }} defer></script>

@endsection