<!-- FILE: resources/views/partials/edit_post.blade.php -->
<form action="{{ route('posts.update', $post) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div>
        <label for="description">Description:</label>
        <input type="text" id="description" name="description" value="{{ $post->description }}">
    </div>
    @if ($post->typeP == 'MEDIA')
        <div id="media-upload">
            <label for="media">Upload Media:</label>
            <input type="file" id="media" name="media[]" multiple onchange="validateFileCount()">
        </div>
    @endif
    <div>
        <label for="is_public">Public:</label>
        <input type="checkbox" id="is_public" name="is_public" {{ $post->is_public ? 'checked' : '' }}>
    </div>
    <button type="submit">Update</button>
</form>

<script src="{{ asset('js/mediaPost.js') }}"></script>

