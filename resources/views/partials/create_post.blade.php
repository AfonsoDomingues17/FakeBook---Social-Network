<div class="modal fade" id="createPostModal" tabindex="-1" aria-labelledby="createPostModalLabel" aria-hidden="true" style="display: none">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createPostModalLabel">Create A Post!</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div>
                        <label for="description">Description:</label>
                        <textarea id="description" name="description" required></textarea>
                        @error('description')
                        <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div id="media-upload">
                        <label for="media">Upload Media:</label>
                        <input type="file" id="media" name="media[]" accept="image/*" multiple onchange="validateFileCount()">
                        @error('media')
                        <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                        <div id="media-preview"></div>
                    </div>
                    <div>
                        <label for="category">Category:</label>
                        <select id="category" name="category">
                            <option value="">Select a category if applies</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                        @error('category')
                        <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div id="Type">
                        <label for="is_public">Public:</label>
                        <input type="hidden" name="is_public" value="0">
                        <input type="checkbox" id="is_public" name="is_public" value="1">
                        @error('is_public')
                        <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary">Create</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('js/mediaPost.js') }}"></script>