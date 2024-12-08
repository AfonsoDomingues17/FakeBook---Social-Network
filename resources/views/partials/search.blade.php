<section id="search_options">
    <button id="search-users">Users</button>
    <button id="search-posts">Posts</button>
    <button id="search-groups">Groups</button>
    @if ($type === 'posts')
    <div class="dropdown" id="filter-dropdown" style="display: block">
    @else
    <div class="dropdown" id="filter-dropdown" style="display: none">
    @endif
        <button class="btn dropdown-toggle" type="button" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
            Filter by
        </button>
        <form class="dropdown-menu p-4" id="filter-posts">
            <h5 class="mx-auto">Category</h5>
            <input type="hidden" name="selected_categories" id="selected_categories" value="">
            <div class="form-check form-switch">
                <input class="form-check-input category-checkbox" type="checkbox" role="switch" id="category-1" value="1">
                <label class="form-check-label" for="category-1">Outdoors</label>
            </div>
            <div class="form-check form-switch">
                <input class="form-check-input category-checkbox" type="checkbox" role="switch" id="category-2" value="2">
                <label class="form-check-label" for="category-2">Travel</label>
            </div>
            <div class="form-check form-switch">
                <input class="form-check-input category-checkbox" type="checkbox" role="switch" id="category-3" value="3">
                <label class="form-check-label" for="category-3">Literature</label>
            </div>
            <div class="form-check form-switch">
                <input class="form-check-input category-checkbox" type="checkbox" role="switch" id="category-4" value="4">
                <label class="form-check-label" for="category-4">Cooking</label>
            </div>
            <div class="form-check form-switch">
                <input class="form-check-input category-checkbox" type="checkbox" role="switch" id="category-5" value="5">
                <label class="form-check-label" for="category-5">Technology</label>
            </div>
            <div class="form-check form-switch">
                <input class="form-check-input category-checkbox" type="checkbox" role="switch" id="category-6" value="6">
                <label class="form-check-label" for="category-6">Fitness</label>
            </div>
            <div class="form-check form-switch">
                <input class="form-check-input category-checkbox" type="checkbox" role="switch" id="category-7" value="7">
                <label class="form-check-label" for="category-7">Food</label>
            </div>
            <div class="form-check form-switch">
                <input class="form-check-input category-checkbox" type="checkbox" role="switch" id="category-8" value="8">
                <label class="form-check-label" for="category-8">Music</label>
            </div>
            <div class="form-check form-switch">
                <input class="form-check-input category-checkbox" type="checkbox" role="switch" id="category-9" value="9">
                <label class="form-check-label" for="category-9">Film</label>
            </div>
            <div class="form-check form-switch">
                <input class="form-check-input category-checkbox" type="checkbox" role="switch" id="category-10" value="10">
                <label class="form-check-label" for="category-10">Art</label>
            </div>
            <button type="submit" id="filter-category" class="btn btn-primary">Apply filter</button>
        </form>
    </div>
    @if ($type === 'posts')
    <div class="dropdown" id="order-dropdown" style="display: block">
    @else
    <div class="dropdown" id="order-dropdown" style="display: none">
    @endif
        <button class="btn dropdown-toggle" type="button" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
            Order by
        </button>
        <form class="dropdown-menu p-4" id="order-posts">
            <div class="form-check">
                <input class="form-check-input" type="radio" id="order-1" checked>
                <label class="form-check-label" for="order-1">Most relevant</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" id="order-2">
                <label class="form-check-label" for="order-2">Latest</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" id="order-3">
                <label class="form-check-label" for="order-3">Oldest</label>
            </div>
            <button type="submit" id="feed-order" class="btn btn-primary">Apply order</button>
        </form>
    </div>
</section>