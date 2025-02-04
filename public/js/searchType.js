document.addEventListener('DOMContentLoaded', function() {
    var searchUrl = window.searchUrl;

    // Get query parameters from URL
    const urlParams = new URLSearchParams(window.location.search);
    const initialQuery = urlParams.get('query') || '';
    const initialType = urlParams.get('type') || 'users';

    // Set the initial query in the input field
    const queryInput = document.querySelector('input[name="query"]');
    if (queryInput) {
        queryInput.value = initialQuery;
    }

    // Perform initial search if query and type are present
    if (initialQuery && initialType) {
        updateSearchResults(initialType, initialQuery);
        changeButton(initialType);
    }

    const searchUsersButton = document.getElementById('search-users');
    const searchPostsButton = document.getElementById('search-posts');
    const searchGroupsButton = document.getElementById('search-groups');

    if (searchUsersButton) {
        searchUsersButton.addEventListener('click', function() {
            var searchQuery = queryInput ? queryInput.value : '';
            updateSearchResults('users', searchQuery);
            changeButton('users');
            updateUrl('users', searchQuery);
            window.searchType = 'users'; // Update global searchType
            window.noMoreResults = false; // Reset noMoreResults
            document.getElementById('filter-dropdown').style.display = 'flex';
            document.getElementById('order-dropdown').style.display = 'none';
            uncheckAllCheckboxes();
        });
    }
    
    if (searchPostsButton) {
        searchPostsButton.addEventListener('click', function() {
            var searchQuery = queryInput ? queryInput.value : '';
            updateSearchResults('posts', searchQuery);
            changeButton('posts');
            updateUrl('posts', searchQuery);
            window.searchType = 'posts'; // Update global searchType
            window.noMoreResults = false; // Reset noMoreResults
            document.getElementById('filter-dropdown').style.display = 'flex';
            document.getElementById('order-dropdown').style.display = 'flex';
        });
    }

    function updateSearchResults(type, query) {
        document.getElementById('loading').style.display = 'block';
        document.getElementById('search-results-container').style.display = 'none';
        fetch(`${searchUrl}?type=${type}&query=${query}`)
            .then(response => response.text())
            .then(data => {
                // Parse the response and update the DOM
                const parser = new DOMParser();
                const doc = parser.parseFromString(data, 'text/html');
    
                // TYPE SWITCH
                let classToSearch; 
                switch (type) {
                    case 'users':
                        classToSearch = 'article.user';
                        break;
                    case 'posts':
                        classToSearch = 'article.post';
                        break;
                    default:
                        classToSearch = '';
                        break;
                }
    
                const elements = doc.querySelectorAll(classToSearch);
                const searchResults = document.getElementById('search-results-container');
                searchResults.innerHTML = ''; // Clear previous results

                document.getElementById('filter-dropdown').innerHTML = doc.querySelector('#filter-dropdown').innerHTML;
    
                elements.forEach(element => {
                    searchResults.appendChild(element);
                });
    
                // Update the heading text
                if(elements.length === 0) {
                    document.querySelector('#search-results h2').innerHTML = `No results found for "${query}"`;
                } else {
                    document.querySelector('#search-results h2').innerHTML = `Search results (${type})`;
                }
    
                // Update the global searchType variable
                window.searchType = type;    

                document.getElementById('loading').style.display = 'none';
                document.getElementById('search-results-container').style.display = 'block';

                // Reattach event listeners for the filter dropdown
                window.reattachFilterEventListeners();
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

    function uncheckAllCheckboxes() {
        const catCheckboxes = document.querySelectorAll('.category-checkbox');
        catCheckboxes.forEach(checkbox => {
            checkbox.checked = false;
        });
        const countryCheckboxes = document.querySelectorAll('.country-checkbox');
        countryCheckboxes.forEach(checkbox => {
            checkbox.checked = false;
        });
    }
});