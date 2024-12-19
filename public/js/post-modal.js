// Used to load the edit-comment.js script to be executed when creating a comment
function loadScript(callback) {
    const script = document.createElement('script');
    script.type = 'text/javascript';
    script.src = '/js/edit-comment.js'; // Hardcoded URL
    script.onload = callback;
    document.head.appendChild(script);
}

document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.modal').forEach(modal => {
        const postId = modal.getAttribute('id').split('-')[1];
        const mediaUrlsModal = window['mediaUrls' + postId];
        let currentIndex = 0;

        const updateMedia = () => {
            const mediaImage = modal.querySelector(`#media-image-${postId}`);
            if (mediaImage) {
                mediaImage.src = mediaUrlsModal[currentIndex];
            }
        };

        const prevButton = modal.querySelector(`#media-prev-${postId}`);
        const nextButton = modal.querySelector(`#media-next-${postId}`);

        if (prevButton && nextButton) {
            prevButton.addEventListener('click', function() {
                currentIndex = (currentIndex > 0) ? currentIndex - 1 : mediaUrlsModal.length - 1;
                updateMedia();
            });

            nextButton.addEventListener('click', function() {
                currentIndex = (currentIndex < mediaUrlsModal.length - 1) ? currentIndex + 1 : 0;
                updateMedia();
            });

            updateMedia();
        }
    });
});

document.querySelectorAll('form[id^="comment-form-"]').forEach((form) => {
    // Prevent adding multiple listeners
    if (form.dataset.listenerAdded) return;
    form.dataset.listenerAdded = true;

    form.addEventListener('submit', async (event) => {
        event.preventDefault();

        const formData = new FormData(form);
        const csrfToken = formData.get('_token');
        const postId = formData.get('post_id');
        const url = form.action;

        try {
            const response = await fetch(url, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                },
            });

            if (response.ok) {
                const data = await response.json();
                const commentsSection = document.getElementById(`comments-section-${postId}`);
                commentsSection.insertAdjacentHTML('beforeend', data.comment); // Append the new comment
                loadScript(form.reset());
                const commentCountSpan = document.querySelector(`.comment-container[data-post-id="${postId}"] .comment-count`);
                if (commentCountSpan) {
                    commentCountSpan.textContent = data.commentCount;
                }
            }
        } catch (error) {
            console.error('Error during fetch request:', error);
        }
    });
});