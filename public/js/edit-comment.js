document.querySelectorAll('form[id^="edit-comment-form-"]').forEach((form) => {
    // Prevent adding multiple listeners
    if (form.dataset.listenerAdded) return;
    form.dataset.listenerAdded = true;

    form.addEventListener('submit', async (event) => {
        event.preventDefault();

        const formData = new FormData(form);
        const csrfToken = formData.get('_token');
        const commentId = formData.get('comment_id');
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
                const updatedContent = await response.text();

                // Update the comment's content
                const commentContainer = document.getElementById(`comment-${commentId}`);
                if (commentContainer) {
                    const commentTextElement = commentContainer.querySelector('p');
                    if (commentTextElement) {
                        commentTextElement.textContent = updatedContent;
                    }
                }

                // Replace the textarea with the updated content
                form.querySelector('textarea').value = updatedContent;
                // Hide the edit form
                toggleEditForm(commentId);
            } else {
                console.error(`Failed to update comment: ${response.status} ${response.statusText}`);
            }
        } catch (error) {
            console.error('Error during fetch request:', error);
        }
    });
});

function toggleEditForm(commentId) {
    const editForm = document.getElementById('edit-form-' + commentId);
    editForm.style.display = (editForm.style.display === 'none' || editForm.style.display === '') ? 'block' : 'none';
}

document.querySelectorAll('.comment').forEach(comment => {
    comment.addEventListener('mouseenter', function() {
        const commentOptions = this.querySelector('.comment-options');
        if (commentOptions) {
            commentOptions.style.display = 'flex';
        }
    });

    comment.addEventListener('mouseleave', function() {
        const commentOptions = this.querySelector('.comment-options');
        if (commentOptions) {
            commentOptions.style.display = 'none';
        }
    });
});

async function deleteComment(event, commentId) {
    event.preventDefault();
    const form = event.target;

    if (!confirm('Are you sure you want to delete this comment?')) {
        return;
    }

    try {
        const response = await fetch(form.action, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': form.querySelector('[name="_token"]').value,
            },
        });
        if(response.ok) {
            const data = await response.json();
            document.getElementById(`comment-${commentId}`).remove();
            const postId = form.querySelector('[name="post_id"]').value;
            const commentCountSpan = document.querySelector(`.comment-container[data-post-id="${postId}"] .comment-count`);
            if (commentCountSpan) {
                commentCountSpan.textContent = data.commentCount;
            }
        } else {
            console.error('Failed to delete comment:', response.status, response.statusText);
        }
    } catch (error) {
        console.error('Error during fetch request:', error);
    }
}