function toggleMediaUpload() {
    var postType = document.getElementById('typep').value;
    var mediaUpload = document.getElementById('media-upload');
    if (postType === 'MEDIA') {
        mediaUpload.style.display = 'block';
    } else {
        mediaUpload.style.display = 'none';
    }
}

function validateFileCount() {
    const mediaInput = document.getElementById('media');
    const maxFiles = 5;

    if (mediaInput.files.length > maxFiles) {
        alert('You can only upload a maximum of 5 files.');

        // Create a DataTransfer object to retain only the first `maxFiles` files
        const dataTransfer = new DataTransfer();
        for (let i = 0; i < maxFiles; i++) {
            dataTransfer.items.add(mediaInput.files[i]);
        }

        // Update the input files with the truncated list
        mediaInput.files = dataTransfer.files;
    }
}
