window.initMediaCarousel = function (postId, mediaUrls) {
    if (!window.mediaIndex) {
        window.mediaIndex = {};
    }

    if (!window.mediaIndex[postId]) {
        window.mediaIndex[postId] = 0;
    }

    const showImage = (index) => {
        const imageElement = document.getElementById(`media-image-${postId}`);
        if (imageElement && mediaUrls[index]) {
            imageElement.src = mediaUrls[index];
        }
    };

    const prevImage = () => {
        let currentIndex = window.mediaIndex[postId];
        currentIndex = currentIndex > 0 ? currentIndex - 1 : mediaUrls.length - 1;
        window.mediaIndex[postId] = currentIndex;
        showImage(currentIndex);
    };

    const nextImage = () => {
        let currentIndex = window.mediaIndex[postId];
        currentIndex = currentIndex < mediaUrls.length - 1 ? currentIndex + 1 : 0;
        window.mediaIndex[postId] = currentIndex;
        showImage(currentIndex);
    };

    // Attach event listeners dynamically for buttons
    const prevButton = document.querySelector(`#media-prev-${postId}`);
    const nextButton = document.querySelector(`#media-next-${postId}`);
    if (prevButton) prevButton.addEventListener("click", prevImage);
    if (nextButton) nextButton.addEventListener("click", nextImage);

    // Initialize the carousel
    showImage(window.mediaIndex[postId]);
};
