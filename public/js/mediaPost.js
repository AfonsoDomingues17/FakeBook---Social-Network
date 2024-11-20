function toggleMediaUpload() {
    var postType = document.getElementById('typeP').value;
    var mediaUpload = document.getElementById('media-upload');
    if (postType === 'MEDIA') {
        mediaUpload.style.display = 'block';
    } else {
        mediaUpload.style.display = 'none';
    }
}

function validateFileCount() {
    var mediaInput = document.getElementById('media');
    if (mediaInput.files.length > 5) {
        alert('You can only upload a maximum of 5 files.');
        mediaInput.value = ''; // Clear the input
    }
}