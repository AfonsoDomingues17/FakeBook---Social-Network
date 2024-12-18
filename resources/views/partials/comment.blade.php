<div class="comment" id="comment-{{ $comment->id }}">
    <div id="commentContent">
        <img src="{{ route('userphoto', ['user_id' => $comment->user->id]) }}" alt="profile picture" width="30" height="30" >
        <div id="commentText">
            <a href="{{ route('profile', ['user_id' => $comment->user->id]) }}">{{ $comment->user->name . ' '}} </a>
            <p id="CCcontent">{{ $comment->content }}</p>
        </div>
        <div class="interaction-bar">
            <div class="like-container" data-comment-id="{{ $comment->id }}" >
                <form class="comment-like-form" action="{{ route('comment.like') }}" method="POST">
                    @csrf
                    <input type="hidden" name="id" value="{{ $comment->id }}">
                    <button type="submit" >
                        @if (Auth::check() && $comment->likedByUsers()->where('user_id', Auth::user()->id)->exists())
                            <i class="fa-solid fa-heart"></i>
                        @else
                            <i class="fa-regular fa-heart"></i>
                        @endif
                    </button>
                </form>
                <span class="like-count">{{ $comment->likedByUsers()->count() }}</span>
            </div>
        </div>  
    </div>
    @if (Auth::check() && (Auth::user()->id == $comment->user->id || Auth::user()->isAdmin()))
        <div class="comment-options" >
            <button  id="edit" onclick="toggleEditForm({{ $comment->id }})"><p>Edit</p></button>
            <form action="{{ route('comments.destroy', ['comment_id' => $comment->id]) }}" method="POST" onsubmit="deleteComment(event, {{ $comment->id }});">
                @csrf
                @method('DELETE')
                <button id="delete" type="submit" ><p>Delete</p></button>
            </form>
        </div>
        <div id="edit-form-{{ $comment->id }}" style="display: none;">
            <form class="update" id="edit-comment-form-{{ $comment->id }}" action="{{ route('comments.update', ['comment_id' => $comment->id]) }}" method="POST" style="width: 100%;">
                @csrf
                @method('PUT')
                <input type="hidden" name="comment_id" value="{{ $comment->id }}">
                <textarea style="min-height: fit-content;" name="content"  rows="3" required >{{ $comment->content }}</textarea>
                <button id="update" type="submit" ><p>Update</p></button>
            </form>
        </div>
    @endif
    
</div>

<script src="{{ asset('js/edit-comment.js') }}"></script>