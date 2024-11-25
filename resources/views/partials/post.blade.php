<script src="{{ asset('js/post-carousel.js') }}"></script>
<article class="post">
    @if (!(request()->routeIs('profile') && request()->route('user_id') == $post->owner_id))
        <p><a href="{{ route('profile', ['user_id' => $post->owner_id]) }}">{{ $post->owner->username }}</a></p>
    @endif
    <p>{{ $post->description }}</p>
    <p>Type: {{ $post->typep }}</p>
    <p>Created at: {{ $post->datecreation }}</p>
    <p>Public: {{ $post->is_public ? 'Yes' : 'No' }}</p>
    <div class="media">
    @if ($post->typep === 'MEDIA')
            <img id="media-image-{{ $post->id }}" 
                 src="{{ $post->media->isNotEmpty() ? route('media.show', ['media_id' => $post->media->first()->id]) : Storage::url('DEFAULT-POST.jpg') }}" 
                 alt="Media">
            @if ($post->media->count() > 1)
                <button id="media-prev-{{ $post->id }}">Previous</button>
                <button id="media-next-{{ $post->id }}">Next</button>
            @endif
        @endif
    </div>
@if ($post->media->count() > 0)
<script>
    const mediaUrls{{ $post->id }} = [
        @foreach ($post->media as $media)
            "{{ route('media.show', ['media_id' => $media->id]) }}",
        @endforeach
    ];
    initMediaCarousel({{ $post->id }}, mediaUrls{{ $post->id }});
</script>
@endif

    @if(Auth::check())
        @if (Auth::user()->isAdmin() || Auth::user()->id == $post->owner_id)
            <a href="{{ route('posts.edit', $post->id) }}">Edit</a>
            <form action="{{ route('posts.destroy', $post->id) }}" method="POST">
                @csrf
                @method('DELETE')
                <input type="hidden" name="previous_url" value="{{ url()->previous() }}">
                <button type="submit">Delete</button>
            </form>
        @endif
    @endif
</article>