document.addEventListener('DOMContentLoaded', function() {
    Pusher.logToConsole = true;

    var pusher = new Pusher(pusherAppKey, {
        cluster: pusherCluster,
        encrypted: true
    });

    var channel = pusher.subscribe('direct-chat-' + directChatId);
    channel.bind('new-message', function(data) {
        var message = data.message;
        var messageElement = '<div class="message"><strong>' + message.author.name + ':</strong><p>' + message.content + '</p>';
        if (message.image_url) {
            messageElement += '<img src="/storage/' + message.image_url + '" alt="Image">';
        }
        messageElement += '</div>';
        document.getElementById('messages').innerHTML += messageElement;
    });
});