@extends('layouts.app')

@section('content')

<section class="homepage-layout d-flex justify-content-center align-items-center">
    <section id="search-results">
        <h2>
            @if(count($users) === 0 && count($posts) === 0)
                No results found
            @else
                Search results (<?=$type?>)
            @endif
        </h2>
        <section id="search-results-container">
            @if($type === 'users')
                @each('partials.user', $users, 'user')
            @elseif($type === 'posts')
                @each('partials.post', $posts, 'post')
            @endif  
        </section>
        <div id="loading" style="display: none;" class="spinner-border" role="status"></div>
    </section>
</section>

<script>
    var searchUrl = '{{ url("search") }}';
    var searchType = @json($type);
    var searchQuery = @json($query ?? '');

    var userFullname = @json(request()->query('user_fullname'));
    var userUsername = @json(request()->query('user_username'));
    var userCountry = @json(request()->query('user_country'));
    var postDescription = @json(request()->query('post_description'));
    var postCategory = @json(request()->query('post_category'));
    var postType = @json(request()->query('post_type'));
</script>
<script type="text/javascript" src={{ url('js/lazyScroll.js') }} defer></script>
<script type="text/javascript" src={{ url('js/searchType.js') }} defer></script>
<script type="text/javascript" src={{ url('js/searchFilter.js') }} defer></script>
<script type="text/javascript" src={{ url('js/searchOrder.js') }} defer></script>

@endsection