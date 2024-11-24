<form action="{{ route('posts.update', $post->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <input type="hidden" name="previous_url" value="{{ url()->previous() }}">
    <div>
        <label for="description">Description:</label>
        <input type="text" id="description" name="description" value="{{ $post->description }}">
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
    <button type="submit">Update</button>
</form>

<script src="{{ asset('js/mediaPost.js') }}"></script>