document.addEventListener('DOMContentLoaded', function () {
    function updateWatchlistActions(action, userId) {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const url = action === 'add' ? `/admin/watchlist/add/${userId}` : `/admin/watchlist/remove/${userId}`;
        const method = 'POST';

        fetch(url, {
            method: method,
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({})
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const watchlistActions = document.getElementById(`watchlist-actions-${userId}`);
                if (action === 'add') {
                    watchlistActions.innerHTML = `
                        <form id="remove-watchlist-form-${userId}" action="/admin/watchlist/remove/${userId}" method="POST">
                            <input type="hidden" name="_token" value="${csrfToken}">
                            <button type="submit">Remove from Watchlist</button>
                        </form>
                    `;
                } else {
                    watchlistActions.innerHTML = `
                        <form id="add-watchlist-form-${userId}" action="/admin/watchlist/add/${userId}" method="POST">
                            <input type="hidden" name="_token" value="${csrfToken}">
                            <button type="submit">Add to Watchlist</button>
                        </form>
                    `;
                }
                attachWatchlistEventListeners(userId); // Re-attach event listeners after updating the DOM
            } else {
                alert(data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }

    function attachWatchlistEventListeners(userId) {
        const addWatchlistForm = document.getElementById(`add-watchlist-form-${userId}`);
        const removeWatchlistForm = document.getElementById(`remove-watchlist-form-${userId}`);

        if (addWatchlistForm) {
            addWatchlistForm.addEventListener('submit', function (e) {
                e.preventDefault();
                updateWatchlistActions('add', userId);
            });
        }

        if (removeWatchlistForm) {
            removeWatchlistForm.addEventListener('submit', function (e) {
                e.preventDefault();
                updateWatchlistActions('remove', userId);
            });
        }
    }

    // Attach event listeners to all watchlist forms on page load
    document.querySelectorAll('[id^="watchlist-actions-"]').forEach(function (element) {
        const userId = element.getAttribute('data-user-id');
        attachWatchlistEventListeners(userId);
    });
});