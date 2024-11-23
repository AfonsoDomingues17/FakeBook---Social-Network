let page = 1;
let loading = false;

window.addEventListener('scroll', debounce(function() {
    if (window.scrollY + window.innerHeight >= document.documentElement.scrollHeight - 100 && !loading) {
        loading = true;
        page++;
        document.getElementById('loading').style.display = 'block';

        fetch(`${searchUrl}?type=${searchType}&query=${searchQuery}&page=${page}`)
        .then(response => response.text())
        .then(data => {

            if (data.trim().length === 0) {
                // No more data to load
                window.removeEventListener('scroll', debounce);
                document.getElementById('loading').style.display = 'none';
                return;
            }

            // Create a temporary DOM element to parse the data
            const tempDiv = document.createElement('div');
            tempDiv.innerHTML = data;

            // INSERTING NEW ELEMENTS
            let classToSearch; 
            switch (searchType) {
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
            console.log(classToSearch);
            const elements = tempDiv.querySelectorAll(classToSearch);

            const searchResults = document.getElementById('search-results');
            elements.forEach(element => {
                searchResults.appendChild(element);
            });

            document.getElementById('loading').style.display = 'none';
            loading = false;
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('loading').style.display = 'none';
            loading = false;
            alert('An error occurred while loading more results.');
        });
    }
}, 200));

function debounce(func, wait) {
    let timeout;
    return function() {
        const context = this, args = arguments;
        clearTimeout(timeout);
        timeout = setTimeout(() => func.apply(context, args), wait);
    };
}