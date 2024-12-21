document.addEventListener('DOMContentLoaded', function() {

    // Handle delete user form submission
    document.querySelectorAll('.delete-user-form').forEach(form => {
        form.addEventListener('submit', function(event) {
            event.preventDefault();
            if (confirm('Are you sure you want to delete this user?')) {
                fetch(this.action, {
                    method: 'POST',
                    body: new FormData(this),
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                }).then(response => {
                    if (response.ok) {
                        location.reload();
                    } else {
                        alert('Failed to delete user.');
                    }
                });
            }
        });
    });

    // Handle delete post form submission
    document.querySelectorAll('.delete-post-form').forEach(form => {
        form.addEventListener('submit', function(event) {
            event.preventDefault();
            if (confirm('Are you sure you want to delete this post?')) {
                fetch(this.action, {
                    method: 'POST',
                    body: new FormData(this),
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                }).then(response => {
                    if (response.ok) {
                        location.reload();
                    } else {
                        alert('Failed to delete post.');
                    }
                });
            }
        });
    });

    // Handle solve reports form submission
    document.querySelectorAll('.solve-reports-form').forEach(form => {
        form.addEventListener('submit', function(event) {
            event.preventDefault();
            if (confirm('Are you sure you want to solve these reports? Doing so will delete the reports and maintain the reported content.')) {
                fetch(this.action, {
                    method: 'POST',
                    body: new FormData(this),
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                }).then(response => {
                    if (response.ok) {
                        location.reload();
                    } else {
                        alert('Failed to solve reports.');
                    }
                });
            }
        });
    });
});