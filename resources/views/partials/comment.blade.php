<div class="comment" id="comment-{{ $comment->id }}">
    <div id="commentContent">
        <img src="{{ route('userphoto', ['user_id' => $comment->user->id]) }}" alt="profile picture" width="30" height="30" >
        <div id="commentText">
            <a href="{{ $comment->user->name === 'Anonymous' ? route('reset.not.found') : route('profile', ['user_id' => $comment->user->id]) }}">{{ $comment->user->name . ' '}} </a>            <p id="CCcontent">{{ $comment->content }}</p>
        </div>
        <div class="interaction-bar">
            <div class="like-container" data-comment-id="{{ $comment->id }}" >
                @if (!Auth::check() || Auth::user()->isAdmin())
                    <button id="likeComment" type="button" class="like-button" onclick="window.location.href='{{ route('login') }}'">
                        <i class="fa-regular fa-heart" aria-label="Liked Comment" role="button" tabindex="0"></i>
                    </button>
                @else
                    <form class="comment-like-form" action="{{ route('comment.like') }}" method="POST">
                        @csrf
                        <input type="hidden" name="id" value="{{ $comment->id }}">
                        <button type="submit" aria-label="{{ Auth::check() && $comment->likedByUsers()->where('user_id', Auth::user()->id)->exists() ? 'Unlike this comment' : 'Like this comment' }}" class="like-button">
                            @if (Auth::check() && $comment->likedByUsers()->where('user_id', Auth::user()->id)->exists())
                                <i class="fa-solid fa-heart" aria-hidden="true"></i>
                            @else
                                <i class="fa-regular fa-heart" aria-hidden="true"></i>
                            @endif
                        </button>
                    </form>
                @endif
                <span class="like-count">{{ $comment->likedByUsers()->count() }}</span>
            </div>
        </div>  
    </div>
    @if (Auth::check() && (Auth::user()->id == $comment->user->id || Auth::user()->isAdmin() || Auth::user()->id == $post->owner_id))
        <div class="comment-options" style="margin-top: 0.5em;">
            <button  id="edit" onclick="toggleEditForm({{ $comment->id }})"><p>Edit</p></button>
            <form action="{{ route('comments.destroy', ['comment_id' => $comment->id]) }}" method="POST" onsubmit="deleteComment(event, {{ $comment->id }});">
                @csrf
                @method('DELETE')
                <input type="hidden" name="post_id" value="{{ $comment->post_id }}">
                <button id="delete" type="submit" ><p>Delete</p></button>
            </form>
        </div>
        <div id="edit-form-{{ $comment->id }}" style="display: none;">
            <form class="update" id="edit-comment-form-{{ $comment->id }}" action="{{ route('comments.update', ['comment_id' => $comment->id]) }}" method="POST" style="width: 100%;">
                @csrf
                @method('PUT')
                <input type="hidden" name="comment_id" value="{{ $comment->id }}">
                <textarea style="min-height: fit-content;" name="content"  rows="3" required >{{ $comment->content }}</textarea>
                @if($errors->has('content'))
                    <span class="error">{{ $errors->first('content') }} <i class="fa-solid fa-circle-exclamation"></i></span>
                @endif
                <button id="update" type="submit" ><p>Update</p></button>
            </form>
        </div>
    @endif
    
</div>

<script src="{{ asset('js/edit-comment.js') }}"></script>