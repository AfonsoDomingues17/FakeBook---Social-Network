Pusher.logToConsole = true;

const pusherAppKey = 'd0fbf1ca7b9f6c2d83f5';
const pusherCluster = 'eu';

const pusher = new Pusher(pusherAppKey, {
    cluster: pusherCluster,
    encrypted: true
});

const channel = pusher.subscribe('FakeBook');
channel.bind('pusher:subscription_succeeded', function() {
    console.log('Subscription succeeded');
});

channel.bind('notification-postlike', function(data) {
    console.log('New notification:');   
    try {
        console.log(`New notification: ${data.message}`);
    } catch (error) {
        console.error('Error handling notification-postlike event:', error);
        console.log('Received data:', data);
    }
});

channel.bind_global(function(eventName, data) {
    console.log(`Event received: ${eventName}`, data);
});

document.getElementById('like-form').addEventListener('submit', function(event) {
    event.preventDefault(); 

    const form = event.target;
    const formData = new FormData(form);

    fetch(form.action, {
        method: form.method,
        headers: {
            'X-CSRF-TOKEN': form.querySelector('input[name="_token"]').value
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            console.log('Post liked successfully');
            // Optionally update the UI to reflect the like
        } else {
            console.error('Error liking post:', data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
});
