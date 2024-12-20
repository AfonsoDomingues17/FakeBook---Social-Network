const pusherAppKey = 'd0fbf1ca7b9f6c2d83f5';
const pusherCluster = 'eu';

const pusher = new Pusher(pusherAppKey, {
    cluster: pusherCluster,
    encrypted: true
});

const channel = pusher.subscribe('FakeBook');

channel.bind('notification-followrequest-deleted', function(data) {
    const notification = document.querySelector(`li[data-notification-id="${data.notification_id}"]`);
    const number_noti = document.getElementById('number_noti');
    if(number_noti){
        number_noti.style.display = 'none';
        number_noti.innerText = parseInt(number_noti.innerText) - 1;
    }

    if (notification) {
        notification.remove();
    }
}
);

channel.bind('notification-followrequest', function(data) {
    const authenticatedUserId = document.querySelector('meta[name="user-id"]').getAttribute('content');
    if ((String(data.user.id) !== String(authenticatedUserId)) && (String(authenticatedUserId) === String(data.notification.user_id_dest))) {
        showNotificationPopup(data);
        addNotificationToDropdown(data);
        incrementNotificationCount();

    }
});

function showNotificationPopup(data) {
    const notificationPopup = document.createElement('div');
    notificationPopup.className = 'notification-popup';
    notificationPopup.innerText = `@${data.user.username}${data.message}`;

    document.body.appendChild(notificationPopup);

    setTimeout(() => {
        notificationPopup.classList.add('fade-out');
        setTimeout(() => {
            notificationPopup.remove();
        }, 3000); 
    }, 3000); 
}

function addNotificationToDropdown(data) {
    const notificationDropdown = document.getElementById('notification-dropdown');
    const notificationList = notificationDropdown.querySelector('ul');

    const listItem = document.createElement('li');
    listItem.id = 'notification';
    listItem.dataset.notificationId = data.notification.id;

    const link = document.createElement('a');
    link.href = `/users/${data.user.id}`;
    link.innerHTML = `
        <img src="/users/${data.user.id}/photo" alt="profile picture" width="50" height="50">
        <p>@${data.user.username} <span id="noti_content">${data.message}</span></p>
    `;

    listItem.appendChild(link);

    if (data.notification.typen === 'FOLLOW_REQUEST') {
        const actionsDiv = document.createElement('div');
        actionsDiv.id = 'notification-actions';
        actionsDiv.innerHTML = `
            <button id="accept" data-user-id="${data.user.id}">Accept</button>
            <button id="reject" data-user-id="${data.user.id}">Eliminate</button>
        `;
        listItem.appendChild(actionsDiv);
    }
   
    const followBackButton = document.createElement('button');
    followBackButton.id = 'Follow';
    followBackButton.style.display = 'none';
    followBackButton.dataset.userId = data.user.id;
    followBackButton.innerText = 'Follow Back';
    listItem.appendChild(followBackButton);
    

    notificationList.appendChild(listItem);
}

function incrementNotificationCount() {
    const notificationCountElement = document.getElementById('number_noti');
    let count = parseInt(notificationCountElement.innerText);
    count += 1;
    notificationCountElement.innerText = count;
    notificationCountElement.style.display = 'inline-block';
}

const notificationContainer = document.getElementById('notification-container');
const notificationDropdown = document.getElementById('notification-dropdown');
const notificationNumber = document.getElementById('number_noti');

if (notificationContainer && notificationDropdown) {
    notificationContainer.addEventListener('click', function() {
        console.log('Notification container clicked');
        notificationDropdown.style.display = 'inline-block';
        if(notificationNumber) notificationNumber.style.display = 'none';
    });

    document.addEventListener('click', function(event) {
        if (!notificationDropdown.contains(event.target) && !notificationContainer.contains(event.target)) {
            notificationDropdown.style.display = 'none';
        }
    });
}
