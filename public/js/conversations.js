const conversationLinks = document.querySelectorAll('.conversation-link');
    const chatContainer = document.getElementById('chat');

    if (conversationLinks && chatContainer) {
        conversationLinks.forEach(link => {
            link.addEventListener('click', function(event) {
                event.preventDefault();
                const conversationId = this.dataset.id;
                const conversationType = this.dataset.type;
                const url = conversationType === 'group' ? `/groups/${conversationId}` : `/direct-chats/${conversationId}`;

                // Remove background color from all info sections
                conversationLinks.forEach(link => link.querySelector('section#info').style.backgroundColor = '');

                fetch(url)
                    .then(response => response.text())
                    .then(html => {
                        chatContainer.innerHTML = html;
                        // Set background color of the clicked info section to white
                        this.querySelector('section#info').style.backgroundColor = '#ffffff';
                    })
                    .catch(error => console.error('Error fetching chat:', error));
            });
        });
    }