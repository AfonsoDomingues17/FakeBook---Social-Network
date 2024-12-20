if (window.location.pathname.includes("/advsearch")) {
    let page = 1;
    let loading = false;
    window.noMoreResultsAdv = false;

    const advancedsearchResultsContainer = document.getElementById("search-results-container");

    function loadMoreResults() {
        if (window.noMoreResultsAdv) return;

        if (
            advancedsearchResultsContainer.scrollTop + advancedsearchResultsContainer.clientHeight >=
            advancedsearchResultsContainer.scrollHeight - 100 && !loading
        ) {
            loading = true;
            page++;
            document.getElementById("loading").style.display = "none";

            // Construct the fetch URL with filters
            let fetchUrl = `/advsearch?type=${searchType}&page=${page}`;
            if (userFullname) fetchUrl += `&user_fullname=${userFullname}`;
            if (userUsername) fetchUrl += `&user_username=${userUsername}`;
            if (userCountry) fetchUrl += `&user_country=${userCountry}`;
            
            if (postDescription) fetchUrl += `&post_description=${postDescription}`;
            if (postCategory) fetchUrl += `&post_category=${postCategory}`;
            if (postType) fetchUrl += `&post_type=${postType}`;

            if (groupName) fetchUrl += `&group_name=${groupName}`;
            if (groupDescription) fetchUrl += `&group_description=${groupDescription}`;
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
                        window.noMoreResultsAdv = true;
                        loading = false;
                        document.getElementById("loading").style.display = "none";
                        return;
                    }

                    elements.forEach((element) => {
                        advancedsearchResultsContainer.appendChild(element);
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
            !window.noMoreResultsAdv
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
