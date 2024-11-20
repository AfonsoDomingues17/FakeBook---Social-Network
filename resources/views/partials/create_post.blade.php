<!-- FILE: resources/views/partials/create_post.blade.php -->
<form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div>
        <label for="description">Description:</label>
        <input type="text" id="description" name="description">
    </div>
    <div>
        <label for="typeP">Post Type:</label>
        <select id="typeP" name="typeP" onchange="toggleMediaUpload()">
            <option value="TEXT">Text</option>
            <option value="MEDIA">Media</option>
        </select>
    </div>
    <div id="media-upload" style="display: none;">
        <label for="media">Upload Media:</label>
        <input type="file" id="media" name="media[]" multiple onchange="validateFileCount()">
    </div>
    <div>
        <label for="is_public">Public:</label>
        <input type="checkbox" id="is_public" name="is_public">
    </div>
    <button type="submit">Create</button>
</form>

<script src="{{ asset('js/mediaPost.js') }}"></script>