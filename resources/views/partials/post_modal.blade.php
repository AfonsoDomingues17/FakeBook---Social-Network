<div class="modal fade" id="postModal-{{ $post->id }}" tabindex="-1" aria-labelledby="postModalLabel-{{ $post->id }}" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class="post_author">
                    <img src="{{route('userphoto', ['user_id' => $post->owner_id])}}" alt="profile picture" width="70" height="70">
                    @if (!(request()->routeIs('profile') && request()->route('user_id') == $post->owner_id))
                        <p ><a href="{{ route('profile', ['user_id' => $post->owner_id]) }}" style="color:white;">{{ $post->owner->username}}</a></p>
                    @endif
                    <p id="date" style="color: white; opacity: 0.8; margin-right: 0.5em;">{{ \Carbon\Carbon::parse($post->datecreation)->format('m/d/Y') }}</p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div id="PostContent">
                @if ($post->typep === 'MEDIA')
                    @if ($post->media->isNotEmpty())
                        <img id="media-image-{{ $post->id }}" src="{{ route('media.show', $post->media->first()->id) }}" alt="Media">
                        @if ($post->media->count() > 1)
                            <div class="post-media-controls">
                                <button id="media-prev-{{ $post->id }}">Previous</button>
                                <button id="media-next-{{ $post->id }}">Next</button>
                            </div>
                        @endif
                    @else
                        <img id="media-image-{{ $post->id }}" src="{{ route('media.show', 'default') }}" alt="Default Media">
                    @endif
                @endif
                <div id="CommentBody">
                    <p id="postdescription">{{ $post->description }}</p>
                    @if ($post->media->count() > 0)
                            <script>
                                window['mediaUrls{{ $post->id }}'] = [
                                    @foreach ($post->media as $media)
                                        "{{ route('media.show', $media->id) }}",
                                    @endforeach
                                ];
                            </script>
                    @endif  
                
                    <div id="Comments">
                        <h5>Comments</h5>
                        <div class="comSec" id="comments-section-{{ $post->id }}" >
                            @foreach($post->comments as $comment)
                                @include ('partials.comment', ['comment' => $comment])
                            @endforeach
                        </div>
                        @if(Auth::check() && !Auth::user()->isAdmin()) 
                            <form id="comment-form-{{ $post->id }}" action="{{ route('comments.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="post_id" value="{{ $post->id }}">
                                <textarea name="content" class="form-control" placeholder="Write a comment..." ></textarea>
                                <button type="submit" ><i id="send" class="fa-solid fa-right-to-bracket"></i></button>
                            </form>                       
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('js/post-modal.js') }}"></script>