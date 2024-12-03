/*
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
                        <form id="remove-watchlist-form-${userId}" action="/admin/watchlist/remove/${userId}" method="POST" data-user-id="${userId}">
                            <input type="hidden" name="_token" value="${csrfToken}">
                            <button type="submit">Remove from Watchlist</button>
                        </form>
                    `;
                } else {
                    watchlistActions.innerHTML = `
                        <form id="add-watchlist-form-${userId}" action="/admin/watchlist/add/${userId}" method="POST" data-user-id="${userId}">
                            <input type="hidden" name="_token" value="${csrfToken}">
                            <button type="submit">Add to Watchlist</button>
                        </form>
                    `;
                }
                // No need to manually reattach event listeners due to event delegation
            } else {
                alert(data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }

    // Use event delegation for watchlist actions
    document.body.addEventListener('submit', function (e) {
        const form = e.target;

        // Identify the action based on form ID
        if (form.id.startsWith('add-watchlist-form-') || form.id.startsWith('remove-watchlist-form-')) {
            e.preventDefault();
            const userId = form.id.split('-').pop();
            const formUserId = form.getAttribute('data-user-id');
            console.log(`Form submitted: ${form.id}`);
            console.log(`Extracted user ID: ${userId}`);
            console.log(`Form user ID: ${formUserId}`);

            // Ensure the userId matches the form's userId
            if (userId === formUserId) {
                const action = form.id.startsWith('add-watchlist-form-') ? 'add' : 'remove';
                console.log(`Action: ${action}`);
                updateWatchlistActions(action, userId);
            }
        }
    });

    // No need to manually attach listeners to individual forms on page load
});