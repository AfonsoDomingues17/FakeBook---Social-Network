const nextButton = document.getElementById('nextButton');
if(nextButton){
    nextButton.addEventListener('click', () => {
        console.log('click');
        const users = document.getElementById('pageUsers');
        const creation = document.getElementById('CreationPage');
        console.log(users);
        console.log(creation);
        if(users && creation){
            users.style.display = 'none';
            creation.style.display = 'block';
            nextButton.style.display = 'none';
        }
    });
}


const Special = document.getElementById('special');
if(Special){
    document.addEventListener('click', function(event) {
        const target = event.target;
        console.log(target);

        if (target && target.id === 'chat-header' && target.dataset.id) {
            fetch(`/groups/${target.dataset.id}/info`)
                .then(response => response.text())
                .then(html => {
                    if (window.innerWidth < 1500) { // Replace content if screen is too small
                            special.innerHTML = html;
                        } else { // Append content if screen is large enough
                            special.innerHTML += html;
                        }
                })
                .catch(error => console.error('Error fetching chat:', error));
        }
    });
}