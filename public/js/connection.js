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
            else{
                document.querySelectorAll(`button[data-user-id="${userId}"]`).forEach(button => {
                    button.innerHTML = 'Pending';
                    button.classList.add('pending');
                    button.setAttribute('id', 'pending');
                })
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
    else if(event.target && event.target.id === 'pending'){
        const pendingButton = event.target;
        const userId = pendingButton.dataset.userId;
        fetch(`/follow/request/delete/${userId}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if(data.success){
                document.querySelectorAll(`button[data-user-id="${userId}"]`).forEach(button => {
                button.innerHTML = 'Follow';
                button.classList.remove('pending');
                button.setAttribute('id', 'Follow');
                })
            }
        })
        .catch(error => {
            console.error('Error unfollowing user:', error);
        });
    }
});

document.addEventListener('click', function(event) {

    if (event.target && event.target.id === 'accept') {

        const userId = event.target.dataset.userId;
        fetch(`/follow/accept/users/${userId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if(data.success){
                const notibuttons = document.getElementById('notification-actions');
                const notiContent = document.getElementById('noti_content');
                const followButton = document.getElementById('Follow');
                notiContent.innerHTML = 'started following you!';
                notibuttons.innerHTML = '';
                if(!data.isFollowing) followButton.style.display = 'inline-block';
                
            }
        })
        .catch(error => {
            console.error('Error following user:', error);
        });
    }
    if(event.target && event.target.id === 'reject'){
        const notification = event.target.closest('#notification');
        const notificationID = notification.dataset.notificationId;
        fetch(`/follow/decline/notifications/${notificationID}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if(data.success){
                notification.style.display = 'none';
            }
        })
        .catch(error => {
            console.error('Error following user:', error);
        });
    }

});