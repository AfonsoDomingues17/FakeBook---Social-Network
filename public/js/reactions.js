document.addEventListener('DOMContentLoaded', function () {
    document.body.addEventListener('submit', function (event) {
        if (event.target.matches('.like-form')) {
            event.preventDefault();

            const form = event.target;
            const container = form.closest('.like-container');
            const postId = container.dataset.postId;
            const formData = new FormData(form);

            fetch(form.action, {
                method: form.method,
                headers: {
                    'X-CSRF-TOKEN': form.querySelector('input[name="_token"]').value,
                },
                body: formData,
            })
                .then((response) => response.json())
                .then((data) => {
                    if (data.success) {
                        // Update both the post and the modal
                        const allContainers = document.querySelectorAll(`.like-container[data-post-id="${postId}"]`);
                        allContainers.forEach((likeContainer) => {
                            updateLikeContainer(likeContainer, data.liked, data.likeCount);
                        });
                    } else {
                        console.error('Error liking post:', data.message);
                    }
                })
                .catch((error) => {
                    console.error('Error:', error);
                });
        }
        if (event.target.matches('.comment-like-form')) {
            event.preventDefault();

            const form = event.target;
            const container = form.closest('.like-container');
            const formData = new FormData(form);

            fetch(form.action, {
                method: form.method,
                headers: {
                    'X-CSRF-TOKEN': form.querySelector('input[name="_token"]').value,
                },
                body: formData,
            })
                .then((response) => response.json())
                .then((data) => {
                    if (data.success) {
                        updateLikeContainer(container, data.liked, data.likeCount);
                    } else {
                        console.error('Error liking comment:', data.message);
                    }
                })
                .catch((error) => {
                    console.error('Error:', error);
                });
        }
    });
    function updateLikeContainer(container, liked, likeCount) {
        const likeButton = container.querySelector('.like-button');
        const likeIcon = likeButton.querySelector('i');
        const likeCountElement = container.querySelector('.like-count');

        // Update the icon
        if (liked) {
            likeIcon.classList.remove('fa-regular', 'fa-heart');
            likeIcon.classList.add('fa-solid', 'fa-heart');
        } else {
            likeIcon.classList.remove('fa-solid', 'fa-heart');
            likeIcon.classList.add('fa-regular', 'fa-heart');
        }

        // Update the like count
        likeCountElement.textContent = likeCount;
    }
});