<div class="modal fade" id="{{ $modalId }}" tabindex="-1" aria-labelledby="editPostModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editPostModalLabel">Edit Post</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                @if(auth()->user()->isAdmin())
                    <p>Post Author: {{ $post->owner->username }}</p>
                @endif
                <form id="editPostForm" method="POST" action="{{ route('posts.update', $post->id) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="previous_url" value="{{ url()->current() }}">
                    <div>
                        <label for="description">Description:</label>
                        <textarea id="description" name="description"> {{ $post->description }}</textarea>
                        @error('description')
                        <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div>
                        <label for="is_public">Public:</label>
                        <input type="hidden" name="is_public" value="0">
                        <input type="checkbox" id="is_public" name="is_public" value="1" {{ $post->is_public ? 'checked' : '' }}>
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

<script>
    // Adjust the size of the description textarea to fit the content as the user types
    document.addEventListener('input', event => {
        if (event.target.tagName === 'TEXTAREA') {
            event.target.style.height = 'auto';
            event.target.style.height = `${event.target.scrollHeight}px`;
        }
    });
</script>