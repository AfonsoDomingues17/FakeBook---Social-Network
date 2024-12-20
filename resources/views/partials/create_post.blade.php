<div class="modal fade" id="createPostModal" tabindex="-1" aria-labelledby="createPostModalLabel" aria-hidden="true" style="display: none; height: 100vh;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createPostModalLabel">Create A Post!</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div id="CreatePostModalContent">
                <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div id="description">
                        <label for="description">Description:<em style="color: red;">*</em></label>
                        <textarea id="description" name="description" required></textarea>
                        @if($errors->has('description'))
                        <span class="error">{{ $errors->first('description') }} <i class="fa-solid fa-circle-exclamation"></i></span>
                        @endif
                    </div>
                    <div id="media-upload">
                        <label for="media">Upload Media:</label>
                        <input type="file" id="media" name="media[]" accept="image/*" multiple onchange="validateFileCount()">
                        @if($errors->has('media'))
                        <span class="error">{{ $errors->first('media') }} <i class="fa-solid fa-circle-exclamation"></i></span>
                        @endif
                        <div id="media-preview"></div>
                    </div>
                    <div id="postCategory">
                        <label for="category">Category:</label>
                        <select id="category" name="category">
                            <option value="">Select a category if applies</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                        @if($errors->has('category'))
                        <span class="error">{{ $errors->first('category') }} <i class="fa-solid fa-circle-exclamation"></i></span>
                        @endif
                    </div>
                    <div id="Type">
                        <label for="is_public">Public:</label>
                        <input type="hidden" name="is_public" value="0">
                        <input type="checkbox" id="is_public" name="is_public" value="1">
                        @if($errors->has('is_public'))
                        <span class="error">{{ $errors->first('is_public') }} <i class="fa-solid fa-circle-exclamation"></i></span>
                        @endif
                    </div>
                    <p><em style="color: red;">*</em> Fields are required.</p>
                    <div id="modal-footer">
                        <button type="submit" class="btn btn-primary">Create</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('js/mediaPost.js') }}"></script>