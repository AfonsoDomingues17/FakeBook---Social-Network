<script src="{{ asset('js/post-carousel.js') }}"></script>
<article class="post">
    <div class="post_author">
        <img src="{{route('userphoto', ['user_id' => $post->owner_id])}}" alt="user profile picture" width="70" height="70">
        @if (!(request()->routeIs('profile') && request()->route('user_id') == $post->owner_id))
            <p><a href="{{ route('profile', ['user_id' => $post->owner_id]) }}">{{ $post->owner->username}}</a></p>
        @endif
        <p id="date">{{ \Carbon\Carbon::parse($post->datecreation)->format('m/d/Y') }}</p>
    </div>
    @if ($post->typep === 'TEXT')
        <p id="postContent">{{ $post->description }}</p>

    @else
        <p id="postdescription">{{ $post->description }}</p>
        <div class="media">
        @if ($post->typep === 'MEDIA')
                @if ($post->media->isNotEmpty())
                    <img id="media-image-{{ $post->id }}" src="{{ route('media.show', $post->media->first()->id) }}" alt="Post Media">
                    @if ($post->media->count() > 1)
                        <div class="post-media-controls">
                            <button id="media-prev-{{ $post->id }}">Previous</button>
                            <button id="media-next-{{ $post->id }}">Next</button>
                        </div>
                    @endif
                @else
                    <img id="media-image-{{ $post->id }}" src="{{ route('media.show', 'default') }}" alt="PostDefault Media">
                @endif
            @endif
        </div>
    @endif
    <div class="interaction-bar">
    @if(Auth::check() && !Auth::user()->isAdmin())
        <div class="like-container" data-post-id="{{ $post->id }}" style="display: flex; flex-direction: row; gap:10px">
            <form class="like-form" action="{{ route('post.like') }}" method="POST">
                @csrf
                <input type="hidden" name="id" value="{{ $post->id }}">
                <button type="submit" class="like-button">
                    @if (Auth::check() && $post->likedByUsers()->where('user_id', Auth::user()->id)->exists())
                        <i class="fa-solid fa-heart" aria-label="Like Post" role="button" tabindex="0"></i>
                    @else
                        <i class="fa-regular fa-heart" aria-label="Liked Post" role="button" tabindex="0"></i>
                    @endif
                </button>
            </form>
            <span class="like-count">{{ $post->getNumberOfLikes() }}</span>
        </div>
        <p><i class="fa-regular fa-comment" aria-label="Like Comment" role="button" tabindex="0"></i> 33</p>
        <button id="reportPost" type="button" class="report-button" data-bs-toggle="modal" data-bs-target="#reportPostModal-{{ $post->id }}">
            <i class="fa-regular fa-flag" aria-label="report comment" role="button" tabindex="0"></i>
        </button>
        @include('partials.report_modal', ['type' => 'post', 'id' => $post->id]) 
    @endif
    </div>
    <div class="action_buttons">
        @if(Auth::check())
            @if (Auth::user()->isAdmin() || Auth::user()->id == $post->owner_id)
                    <button type="button" id="editPostBtn" class="btn btn-primary edit-post-btn" data-bs-toggle="modal" data-bs-target="#editPostModal-{{ $post->id }}" data-post-id="{{ $post->id }}" data-current-url="{{ Request::fullUrl() }}">
                        Edit Post
                    </button>
                    <form action="{{ route('posts.destroy', $post->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <input type="hidden" name="previous_url" value="{{ url()->current() }}">
                        <button type="submit" id="deletePostBtn" class="btn btn-danger delete-post-btn">Delete</button>
                    </form>
            @endif
        @endif
    </div>
    @if ($post->media->count() > 0)
    <script>
        const mediaUrls{{ $post->id }} = [
            @foreach ($post->media as $media)
                "{{ route('media.show', $media->id) }}",
            @endforeach
        ];
        initMediaCarousel({{ $post->id }}, mediaUrls{{ $post->id }});
    </script>
    @endif

    <button id="view-post-btn" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#postModal-{{ $post->id }}" style="display: none">View Post</button>
    @include('partials.edit_post', ['post' => $post, 'modalId' => 'editPostModal-' . $post->id])
    @include('partials.post_modal', ['post' => $post])
</article>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const avoid = [
            '.like-button',
            '.post_author',
            '.edit-post-btn',
            '.delete-post-btn',
            '.post-media-controls',
            '.modal',
            '.report-button',
        ];

        document.body.addEventListener('click', function(event) {
            const post = event.target.closest('.post');
            if (post) {
                const isExcluded = avoid.some(selector => event.target.closest(selector));
                if (!isExcluded) {
                    const viewPostBtn = post.querySelector('#view-post-btn');
                    if (viewPostBtn) {
                        viewPostBtn.click();
                    }
                }
            }
        });

        avoid.forEach(selector => {
            document.querySelectorAll(selector).forEach(element => {
                element.addEventListener('click', function(event) {
                    event.stopPropagation();
                });
            });
        });

        document.querySelectorAll('.post_author').forEach(authorDiv => {
            authorDiv.addEventListener('click', function() {
                const link = authorDiv.querySelector('a');
                if (link) {
                    window.location.href = link.href;
                }
            });
        });
    });
</script>