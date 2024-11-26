document.addEventListener('DOMContentLoaded', function() {
    var searchUrl = window.searchUrl;
    var searchQuery = window.searchQuery;

    document.getElementById('search-users').addEventListener('click', function() {
        updateSearchResults('users');
        changeButton('users');
    });

    document.getElementById('search-posts').addEventListener('click', function() {
        updateSearchResults('posts');
        changeButton('posts');
    });

    document.getElementById('search-groups').addEventListener('click', function() {
        updateSearchResults('groups');
        changeButton('groups');
    });

    function updateSearchResults(type) {
        document.getElementById('loading').style.display = 'block';
        fetch(`${searchUrl}?type=${type}&query=${searchQuery}`)
            .then(response => response.text())
            .then(data => {
                // Create a temporary DOM element to parse the data
                const tempDiv = document.createElement('div');
                tempDiv.innerHTML = data;

                // TYPE SWITCH
                let classToSearch; 
                switch (type) {
                    case 'users':
                        classToSearch = '.user';
                        break;
                    case 'posts':
                        classToSearch = '.post';
                        break;
                    case 'groups':
                        classToSearch = '.group';
                        break;
                    default:
                        classToSearch = '';
                        break;
                }

                const elements = tempDiv.querySelectorAll(classToSearch);
                const searchResults = document.getElementById('search-results-container');
                searchResults.innerHTML = ''; // Clear previous results

                elements.forEach(element => {
                    searchResults.appendChild(element);
                });

                document.querySelector('#search-results h2').innerHTML = `Search results (${type}) for "${searchQuery}"`;

                document.getElementById('loading').style.display = 'none';
            })
            .catch(error => {
                console.error('Error fetching search results:', error);
                document.getElementById('loading').style.display = 'none';
            });
    }

    function changeButton(type) {
        const allButtons = document.querySelectorAll('[id^="search-"]'); // Seleciona todos os botões com id começando por "search-"
        allButtons.forEach(button => {
            if (button.id === `search-${type}`) {
                // Adiciona a classe "active" ao botão selecionado
                button.classList.add('active');
            } else {
                // Remove a classe "active" dos outros botões
                button.classList.remove('active');
            }
        });
    }    
});