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