<div class="modal fade" id="editPostModal-{{ $post->id }}" tabindex="-1" aria-labelledby="editPostModalLabel-{{ $post->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editPostModalLabel-{{ $post->id }}">Edit Post</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div id="editPostModalContent">
                <form id="editPostForm-{{ $post->id }}" method="POST" action="{{ route('posts.update', $post->id) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="previous_url" value="{{ Request::fullUrl() }}">
                    <div id="description">
                        <label for="description-{{ $post->id }}">Description:</label>
                        <textarea id="description-{{ $post->id }}" name="description">{{ $post->description }}</textarea>
                        @if($errors->has('description'))
                        <span class="error">{{ $errors->first('description') }}</span>
                        @endif

                    </div>
                    <div id="Type">
                        <label for="is_public-{{ $post->id }}">Public:</label>
                        <input type="hidden" name="is_public" value="0">
                        <input type="checkbox" id="is_public-{{ $post->id }}" name="is_public" value="1" {{ $post->is_public ? 'checked' : '' }}>
                        @if($errors->has('is_public'))
                        <span class="error">{{ $errors->first('is_public') }}</span>
                        @endif
                    </div>
                    
                    <div id="modal-footer">
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
