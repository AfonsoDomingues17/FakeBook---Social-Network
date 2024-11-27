<div class="modal fade" id="editPostModal-{{ $post->id }}" tabindex="-1" aria-labelledby="editPostModalLabel-{{ $post->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editPostModalLabel-{{ $post->id }}">Edit Post</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editPostForm-{{ $post->id }}" method="POST" action="{{ route('posts.update', $post->id) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="previous_url" value="{{ Request::fullUrl() }}">
                    <div>
                        <label for="description-{{ $post->id }}">Description:</label>
                        <textarea id="description-{{ $post->id }}" name="description">{{ $post->description }}</textarea>
                        @error('description')
                        <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div id="Type">
                        <label for="is_public-{{ $post->id }}">Public:</label>
                        <input type="hidden" name="is_public" value="0">
                        <input type="checkbox" id="is_public-{{ $post->id }}" name="is_public" value="1" {{ $post->is_public ? 'checked' : '' }}>
                        @error('is_public')
                        <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary">Update</button>
                </form>
            </div>
        </div>
    </div>
</div>
