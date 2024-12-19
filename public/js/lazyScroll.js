if (window.location.pathname.includes("/search")) {
    let page = 1;
    let loading = false;
    window.noMoreResults = false;

    const searchResultsContainer = document.getElementById("search-results-container");

    function loadMoreResults() {
        if (window.noMoreResults || loading) return;

        if (
            searchResultsContainer.scrollTop + searchResultsContainer.clientHeight >=
                searchResultsContainer.scrollHeight - 100 &&
            !loading
        ) {
            console.log("Loading more results...");
            loading = true;
            page++;
            document.getElementById("loading").style.display = "block";

            // Retrieve selected filters
            const selectedCountries = document.getElementById('selected_countries')?.value || '';
            const selectedCategories = document.getElementById('selected_categories')?.value || '';

            // Construct the fetch URL with filters
            let fetchUrl = `/search?query=${searchQuery}&type=${searchType}&page=${page}`;
            if (selectedCountries) fetchUrl += `&countries=${selectedCountries}`;
            if (selectedCategories) fetchUrl += `&categories=${selectedCategories}`;

            fetch(fetchUrl)
                .then((response) => response.text())
                .then((data) => {

                    // Create a temporary DOM element to parse the data
                    const tempDiv = document.createElement("div");
                    tempDiv.innerHTML = data;

                    // INSERTING NEW ELEMENTS
                    let classToSearch;
                    switch (searchType) {
                        case "users":
                            classToSearch = "article.user";
                            break;
                        case "posts":
                            classToSearch = "article.post";
                            break;
                        case "groups":
                            classToSearch = "article.group";
                            break;
                        default:
                            classToSearch = "";
                            break;
                    }

                    const elements = tempDiv.querySelectorAll(classToSearch);

                    if (elements.length === 0) {
                        // No more data to load
                        window.noMoreResults = true;
                        loading = false;
                        document.getElementById("loading").style.display = "none";
                        return;
                    }

                    elements.forEach((element) => {
                        searchResultsContainer.appendChild(element);
                    });

                    document.getElementById("loading").style.display = "none";
                    loading = false;

                    // Trigger scroll event to check if more items need to be loaded
                    window.dispatchEvent(new Event("scroll"));
                })
                .catch((error) => {
                    console.error("Error:", error);
                    document.getElementById("loading").style.display = "none";
                    loading = false;
                    alert("An error occurred while loading more results.");
                });
        }
    }

    document.getElementById("search-results").addEventListener("scroll", debounce(loadMoreResults, 200));

    // Add an event listener for the wheel event to detect when the user attempts to scroll
    document.getElementById("search-results").addEventListener("wheel", function () {
        if (
            document.documentElement.scrollHeight <= window.innerHeight &&
            !window.noMoreResults
        ) {
            loadMoreResults();
        }
    });

    function debounce(func, wait) {
        let timeout;
        return function () {
            const context = this,
                args = arguments;
            clearTimeout(timeout);
            timeout = setTimeout(() => func.apply(context, args), wait);
        };
    }
}