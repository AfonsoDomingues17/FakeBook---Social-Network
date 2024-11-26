document.addEventListener('DOMContentLoaded', function() {

    document.getElementById('feed-public').addEventListener('click', function() {
        changeButton('public');
        updateFeedResults('public');
    });

    document.getElementById('feed-following').addEventListener('click', function() {
        changeButton('following');
        updateFeedResults('following');
    });

    function updateFeedResults(type) {
        fetch(`/?type=${type}`)
            .then(response => response.text())
            .then(data => {
                // Create a temporary DOM element to parse the data
                const tempDiv = document.createElement('div');
                tempDiv.innerHTML = data;

                // TYPE SWITCH
                const classToSearch = '.post'; 

                const elements = tempDiv.querySelectorAll(classToSearch);
                const feedResults = document.getElementById('feed-posts-container');
                feedResults.innerHTML = ''; // Clear previous results

                elements.forEach(element => {
                    feedResults.appendChild(element);
                });
                
                switch (type) {
                    case 'public':
                        document.querySelector('#feed-posts h2').innerHTML = 'Public Feed';
                        break;
                    case 'following':
                        document.querySelector('#feed-posts h2').innerHTML = 'Following Feed';
                        break;
                    default:
                        document.querySelector('#feed-posts h2').innerHTML = 'Feed';
                        break;
                }

                document.querySelector('#feed-results h2').innerHTML = `Search results (${type}) for "${searchQuery}"`;

                document.getElementById('loading').style.display = 'none';
            })
            .catch(error => {
                console.error('Error fetching search results:', error);
                document.getElementById('loading').style.display = 'none';
            });
    }

    function changeButton(type) {
        const allButtons = document.querySelectorAll('[id^="feed-"]'); // Seleciona todos os botões com id começando por "search-"
        allButtons.forEach(button => {
            if (button.id === `feed-${type}`) {
                // Adiciona a classe "active" ao botão selecionado
                button.classList.add('active');
            } else {
                // Remove a classe "active" dos outros botões
                button.classList.remove('active');
            }
        });
    }    
});