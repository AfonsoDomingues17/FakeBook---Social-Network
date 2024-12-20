document.addEventListener('DOMContentLoaded', function() {
    reattachFilterEventListeners();
});

function reattachFilterEventListeners() {
    const countryCheckboxes = document.querySelectorAll('.country-checkbox');
    const selectedCountriesInput = document.getElementById('selected_countries');
    const filterCountry = document.getElementById('filter-country');

    const catCheckboxes = document.querySelectorAll('.category-checkbox');
    const selectedCategoriesInput = document.getElementById('selected_categories');
    const filterCategory = document.getElementById('filter-category');

    countryCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const selectedCountries = Array.from(countryCheckboxes)
                .filter(checkbox => checkbox.checked)
                .map(checkbox => checkbox.value)
                .join(',');

            selectedCountriesInput.value = selectedCountries;
        });
    });

    if (filterCountry) {
        filterCountry.addEventListener('click', function(event) {
            event.preventDefault(); // Prevent the form from submitting
            const selectedCountries = selectedCountriesInput.value;
            const searchQuery = document.querySelector('input[name="query"]').value;
            updateSearchResults('users', searchQuery, selectedCountries, undefined);
        });
    }

    catCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const selectedCategories = Array.from(catCheckboxes)
                .filter(checkbox => checkbox.checked)
                .map(checkbox => checkbox.value)
                .join(',');

            selectedCategoriesInput.value = selectedCategories;
        });
    });

    if (filterCategory) {
        filterCategory.addEventListener('click', function(event) {
            event.preventDefault(); // Prevent the form from submitting
            const selectedCategories = selectedCategoriesInput.value;
            const searchQuery = document.querySelector('input[name="query"]').value;
            updateSearchResults('posts', searchQuery, undefined, selectedCategories);
        });
    }
}

function updateSearchResults(type, query, countries, categories) {
    document.getElementById('loading').style.display = 'block';

    var url = `${searchUrl}?type=${type}&query=${query}`;

    if (countries) url += `&countries=${countries}`;
    else if (categories) url += `&categories=${categories}`;
    
    fetch(url)
        .then(response => response.text())
        .then(data => {
            const parser = new DOMParser();
            const doc = parser.parseFromString(data, 'text/html');

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

// Make the function globally accessible
window.reattachFilterEventListeners = reattachFilterEventListeners;