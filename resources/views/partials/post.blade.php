<script src="{{ asset('js/post-carousel.js') }}"></script>
<article class="post">
    <p>{{ $post->owner->name }}</p>
    <p>{{ $post->description }}</p>
    <p>Type: {{ $post->typep }}</p>
    <p>Created at: {{ $post->datecreation }}</p>
    <p>Public: {{ $post->is_public ? 'Yes' : 'No' }}</p>
    @if ($post->media->count() > 0)
        <div class="media">
            <img id="media-image-{{ $post->id }}" src="{{ Storage::url($post->media->first()->photo_url) }}" alt="Media">
            @if ($post->media->count() > 1)
            <button id="media-prev-{{ $post->id }}">Previous</button>
            <button id="media-next-{{ $post->id }}">Next</button>
            @endif
        </div>
        <script>
            const mediaUrls{{ $post->id }} = [
                @foreach ($post->media as $media)
                    "{{ Storage::url($media->photo_url) }}",
                @endforeach
            ];
            initMediaCarousel({{ $post->id }}, mediaUrls{{ $post->id }});
        </script>
    @endif
    @if (Auth::check() && Auth::user()->id == $post->owner_id)
        <a href="{{ route('posts.edit', $post) }}">Edit</a>
        <form action="{{ route('posts.destroy', $post) }}" method="POST">
            @csrf
            @method('DELETE')
            <button type="submit">Delete</button>
        </form>
    @endif
</article>

