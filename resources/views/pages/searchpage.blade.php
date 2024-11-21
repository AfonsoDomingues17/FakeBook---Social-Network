@extends('layouts.app')

@section('content')

<section>
    <h1>Welcome to FakeBook</h1>
    @include('partials.search')
    <p>Results of <?=$type?> search for "<?=$query?>"</p>
</section>
<section id="results">

@if ($type === 'users')
    @each('partials.user', $results, 'user')
@elseif ($type === 'posts')
    @each('partials.post', $results, 'post')
@endif

</section>
@endsection