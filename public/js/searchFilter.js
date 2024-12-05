document.addEventListener('DOMContentLoaded', function() {
    const checkboxes = document.querySelectorAll('.category-checkbox');
    const selectedCategoriesInput = document.getElementById('selected_categories');
    const filterButton = document.getElementById('filter-category');

    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const selectedCategories = Array.from(checkboxes)
                .filter(checkbox => checkbox.checked)
                .map(checkbox => checkbox.value)
                .join(',');

            selectedCategoriesInput.value = selectedCategories;
        });
    });

    if (filterButton) {
        filterButton.addEventListener('click', function(event) {
            event.preventDefault(); // Prevent the form from submitting
            const selectedCategories = selectedCategoriesInput.value;
            const searchQuery = document.querySelector('input[name="query"]').value;
            updateSearchResults('posts', searchQuery, selectedCategories);
        });
    }

    function updateSearchResults(type, query, categories) {
        document.getElementById('loading').style.display = 'block';
        fetch(`${searchUrl}?type=${type}&query=${query}&categories=${categories}`)
            .then(response => response.text())
            .then(data => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(data, 'text/html');
    
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
    
                const elements = doc.querySelectorAll(classToSearch);
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
});