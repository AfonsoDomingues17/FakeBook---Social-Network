<div class="modal fade" id="postModal-{{ $post->id }}" tabindex="-1" aria-labelledby="postModalLabel-{{ $post->id }}" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <div class="post_author">
                    <img src="{{route('userphoto', ['user_id' => $post->owner_id])}}" alt="profile picture" width="70" height="70">
                    @if (!(request()->routeIs('profile') && request()->route('user_id') == $post->owner_id))
                        <p><a href="{{ route('profile', ['user_id' => $post->owner_id]) }}">{{ $post->owner->username}}</a></p>
                    @endif
                    <p id="date">{{ \Carbon\Carbon::parse($post->datecreation)->format('m/d/Y') }}</p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-9">
                        <article class="post1">
                            @if ($post->typep === 'TEXT')
                                <p id="postContent">{{ $post->description }}</p>

                            @else
                                <p id="postdescription">{{ $post->description }}</p>
                                <div class="media" style="align-items: center">
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
                                </div>
                            @endif
                            <div class="interaction-bar">
                                <div class="like-container" data-post-id="{{ $post->id }}" style="display: flex; flex-direction: row; gap:10px">
                                    <form class="like-form" action="{{ route('post.like') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ $post->id }}">
                                        <button type="submit" class="like-button">
                                            @if (Auth::check() && $post->likedByUsers()->where('user_id', Auth::user()->id)->exists())
                                                <i class="fa-solid fa-heart"></i>
                                            @else
                                                <i class="fa-regular fa-heart"></i>
                                            @endif
                                        </button>
                                    </form>
                                    <span class="like-count">{{ $post->getNumberOfLikes() }}</span>
                                </div>
                                <p><i class="fa-regular fa-comment"></i> 33</p>
                                <p><i class="fa-solid fa-share"></i> 10</p>
                                <button id="reportPost" type="button" class="report-button" data-bs-toggle="modal" data-bs-target="#reportPostModal-{{ $post->id }}">
                                    <i class="fa-regular fa-flag"></i>
                                </button>
                                @include('partials.report_modal', ['type' => 'post', 'id' => $post->id])
                            </div>
                            @if ($post->media->count() > 0)
                                    <script>
                                        window['mediaUrls{{ $post->id }}'] = [
                                            @foreach ($post->media as $media)
                                                "{{ route('media.show', $media->id) }}",
                                            @endforeach
                                        ];
                                    </script>
                            @endif
                        </article>
                    </div>
                    <div class="col-md-3" style="display: flex; flex-direction: column">
                        <h5 style="align-self: start">Comments</h5>
                        <div id="comments-section-{{ $post->id }}" style="max-height: 55vh; overflow-y: auto">
                            @foreach($post->comments as $comment)
                                @include ('partials.comment', ['comment' => $comment])
                            @endforeach
                        </div>
                        @if(Auth::check())
                            <div class="comment-form" style="margin-top: auto">
                                <hr>
                                <form id="comment-form-{{ $post->id }}" action="{{ route('comments.store') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="post_id" value="{{ $post->id }}">
                                    <textarea name="content" class="form-control" placeholder="Write a comment..." style="width: 100%"></textarea>
                                    <button type="submit" class="btn btn-primary mt-2">Submit</button>
                                </form>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('js/post-modal.js') }}"></script>