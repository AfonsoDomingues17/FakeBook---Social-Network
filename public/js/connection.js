document.addEventListener('click', (event) => {
    
    if (event.target && event.target.id === 'Follow') {
        const followButton = event.target;
        const userId = followButton.dataset.userId;
        fetch(`/follow/users/${userId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if(data.success){
                
                followButton.innerHTML = 'Following';
                followButton.classList.add('unfollow');
                followButton.setAttribute('id', 'unfollow');
            }
        })
        .catch(error => {
            console.error('Error following user:', error);
        });
    } else if (event.target && event.target.id === 'unfollow') {
        const unfollowButton = event.target;
        const userId = unfollowButton.dataset.userId;
        fetch(`/unfollow/users/${userId}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if(data.success){
                console.log(data);
                unfollowButton.innerHTML = 'Follow';
                unfollowButton.classList.remove('unfollow');
                unfollowButton.setAttribute('id', 'Follow');
            }
        })
        .catch(error => {
            console.error('Error unfollowing user:', error);
        });
    }
});