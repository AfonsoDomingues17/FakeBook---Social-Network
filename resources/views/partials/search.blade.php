<section id="search_options">
    <button id="search-users">Users</button>
    <button id="search-posts">Posts</button>
    <button id="search-groups">Groups</button>
    @if ($type !== 'groups')
    <div class="dropdown" id="filter-dropdown" style="display: block">
    @else
    <div class="dropdown" id="filter-dropdown" style="display: none">
    @endif
        <button class="btn dropdown-toggle" type="button" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
            Filter by
        </button>
        @if ($type === 'users')
        <form class="dropdown-menu p-4" id="filter-users">
            <h5 class="mx-auto">Country</h5>
            <input type="hidden" name="selected_countries" id="selected_countries" value="">
            <div class="max-vh-50">
                @foreach ($countries as $country)
                <div class="form-check form-switch">
                    <input class="form-check-input country-checkbox" type="checkbox" role="switch" id="country-{{ $country->id }}" value="{{ $country->id }}">
                    <label class="form-check-label" for="country-{{ $country->id }}">{{ $country->name }}</label>
                </div>
                @endforeach
            </div>
            <button type="submit" id="filter-country" class="btn btn-primary">Apply filter</button>
        </form>
        @elseif ($type === 'posts')
        <form class="dropdown-menu p-4" id="filter-posts">
            <h5 class="mx-auto">Category</h5>
            <input type="hidden" name="selected_categories" id="selected_categories" value="">
            @foreach ($categories as $category)
            <div class="form-check form-switch">
                <input class="form-check-input category-checkbox" type="checkbox" role="switch" id="category-{{ $category->id }}" value="{{ $category->id }}">
                <label class="form-check-label" for="category-{{ $category->id }}">{{ $category->name }}</label>
                
            </div>
            @endforeach
            <button type="submit" id="filter-category" class="btn btn-primary">Apply filter</button>
        </form>
        @endif
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
                @if($errors->has('order-1'))
                    <span class="error">{{ $errors->first('order-1') }}</span>
                @endif
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" id="order-2">
                <label class="form-check-label" for="order-2">Latest</label>
                @if($errors->has('order-2'))
                    <span class="error">{{ $errors->first('order-2') }}</span>
                @endif
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" id="order-3">
                <label class="form-check-label" for="order-3">Oldest</label>
                @if($errors->has('order-3'))
                    <span class="error">{{ $errors->first('order-3') }}</span>
                @endif
            </div>
            <button type="submit" id="feed-order" class="btn btn-primary">Apply order</button>
        </form>
    </div>
</section>