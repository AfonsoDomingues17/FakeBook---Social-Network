if (window.location.pathname.includes("/search")) {
    let page = 1;
    let loading = false;
    window.noMoreResults = false;
  
    function loadMoreResults() {
      if (window.noMoreResults) return;
  
      if (
        window.scrollY + window.innerHeight >=
          document.documentElement.scrollHeight - 100 &&
        !loading
      ) {
        loading = true;
        page++;
        document.getElementById("loading").style.display = "block";
  
        fetch(`/search?query=${searchQuery}&type=${searchType}&page=${page}`)
          .then((response) => response.text())
          .then((data) => {
            // Create a temporary DOM element to parse the data
            const tempDiv = document.createElement("div");
            tempDiv.innerHTML = data;
  
            // INSERTING NEW ELEMENTS
            let classToSearch;
            switch (searchType) {
              case "users":
                classToSearch = ".user";
                break;
              case "posts":
                classToSearch = ".post";
                break;
              case "groups":
                classToSearch = ".group";
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
  
            const searchResults = document.getElementById(
              "search-results-container"
            );
            elements.forEach((element) => {
              searchResults.appendChild(element);
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
  
    window.addEventListener("scroll", debounce(loadMoreResults, 200));
  
    // Add an event listener for the wheel event to detect when the user attempts to scroll
    window.addEventListener("wheel", function () {
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