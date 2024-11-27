<div class="modal fade" id="createPostModal" tabindex="-1" aria-labelledby="createPostModalLabel" aria-hidden="true">
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
                    <div>
                        <label for="typep">Post Type:</label>
                        <select id="typep" name="typep" onchange="toggleMediaUpload()" required>
                            <option value="TEXT">Text</option>
                            <option value="MEDIA">Media</option>
                        </select>
                        @error('typep')
                        <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div id="media-upload" style="display: none;">
                        <label for="media">Upload Media:</label>
                        <input type="file" id="media" name="media[]" multiple onchange="validateFileCount()">
                        @error('media')
                        <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                        <div id="media-preview"></div>
                    </div>
                    <div>
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
<script>
    document.getElementById('media').addEventListener('change', function(event) {
        const mediaContainer = document.getElementById('media-preview');
        mediaContainer.innerHTML = ''; // Clear previous previews

        Array.from(event.target.files).forEach((file, index) => {
            if (file.type.startsWith('image/')) {
                const wrapper = document.createElement('div');
                wrapper.classList.add('image-wrapper');

                const img = document.createElement('img');
                img.src = URL.createObjectURL(file);

                const label = document.createElement('span');
                label.classList.add('image-label');
                label.textContent = `Image ${index + 1}`;

                wrapper.appendChild(img);
                wrapper.appendChild(label);
                mediaContainer.appendChild(wrapper);
            }
        });
    });
</script>