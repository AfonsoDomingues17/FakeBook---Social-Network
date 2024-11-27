document.addEventListener('DOMContentLoaded', function() {
    var searchUrl = window.searchUrl;

    // Get query parameters from URL
    const urlParams = new URLSearchParams(window.location.search);
    const initialQuery = urlParams.get('query') || '';
    const initialType = urlParams.get('type') || 'users';

    // Set the initial query in the input field
    document.querySelector('input[name="query"]').value = initialQuery;

    // Perform initial search if query and type are present
    if (initialQuery && initialType) {
        updateSearchResults(initialType, initialQuery);
        changeButton(initialType);
    }

    document.getElementById('search-users').addEventListener('click', function() {
        var searchQuery = document.querySelector('input[name="query"]').value;
        updateSearchResults('users', searchQuery);
        changeButton('users');
        updateUrl('users', searchQuery);
    });

    document.getElementById('search-posts').addEventListener('click', function() {
        var searchQuery = document.querySelector('input[name="query"]').value;
        updateSearchResults('posts', searchQuery);
        changeButton('posts');
        updateUrl('posts', searchQuery);
    });

    

    function updateSearchResults(type, query) {
        document.getElementById('loading').style.display = 'block';
        fetch(`${searchUrl}?type=${type}&query=${query}`)
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

                document.querySelector('#search-results h2').innerHTML = `Search results (${type}) for "${query}"`;

                document.getElementById('loading').style.display = 'none';
            })
            .catch(error => {
                console.error('Error fetching search results:', error);
                document.getElementById('loading').style.display = 'none';
            });
    }

    function changeButton(type) {
        const allButtons = document.querySelectorAll('[id^="search-"]'); // Select all buttons with id starting with "search-"
        allButtons.forEach(button => {
            if (button.id === `search-${type}`) {
                // Add the "active" class to the selected button
                button.classList.add('active');
            } else {
                // Remove the "active" class from other buttons
                button.classList.remove('active');
            }
        });
    }

    function updateUrl(type, query) {
        const newUrl = `${window.location.pathname}?type=${type}&query=${query}`;
        history.pushState({ path: newUrl }, '', newUrl);
    }
});