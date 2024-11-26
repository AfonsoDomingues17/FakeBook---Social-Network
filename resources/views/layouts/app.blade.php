<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Styles -->
        <link href="{{ url('css/app.css') }}" rel="stylesheet">
        <script type="text/javascript">
            // Fix for Firefox autofocus CSS bug
            // See: http://stackoverflow.com/questions/18943276/html-5-autofocus-messes-up-css-loading/18945951#18945951
        </script>
        <script type="text/javascript" src={{ url('js/app.js') }} defer>
        </script>
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
        <script src="{{ asset('js/search.js') }}" defer></script>
        <script type="text/javascript" src="{{ asset('js/lazyScroll.js') }}" defer></script>
        <script type="text/javascript" src="{{ asset('js/searchType.js') }}" defer></script>
        <script type="text/javascript" src="{{ asset('js/feedType.js') }}" defer></script>
    </head>
    <body>
        <main>
            <header>
                <nav class="navbar navbar-expand-lg bg-body-tertiary fs-4">
                    <div class="container-fluid">
                        <a class="navbar-brand fs-4" href="{{ url('/') }}">
                            <img src="{{ Storage::url('LOGO.png') }}" alt="Logo" width="50">
                            FakeBook
                        </a>
                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class="collapse navbar-collapse" id="navbarSupportedContent">
                            <ul class="navbar-nav me-auto mb-2 mb-lg-0 py-3">
                                <li class="nav-item">
                                    <form class="d-flex position-relative" role="search" action="/search" method="GET">
                                        <div style="width: 100%; position: relative;">
                                            <input class="form-control me-2 fs-4" type="search" name="query" id="search" placeholder="Search" aria-label="Search" data-bs-toggle="dropdown" aria-expanded="false" style="width: 98%;">
                                            <ul class="dropdown-menu position-absolute" id="real-time-search" aria-labelledby="search" style="width: 98%;"></ul>
                                        </div>
                                        <input type="hidden" name="type" value="users">
                                        <button class="btn btn-outline-success fs-4" type="submit">Search</button>
                                    </form>
                                </li>
                            </ul>
                            @if(Route::currentRouteName() === 'search')
                                @include('partials.search')
                            @endif
                            @if(Route::currentRouteName() === 'homepage' && Auth::check() && !Auth::user()->isAdmin())
                                <ul class="nav nav-underline">
                                    <li class="nav-item">
                                        <a class="nav-link active" aria-current="page" href="{{ route('homepage', ['type' => 'public']) }}" id="feed-public">Public Posts</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('homepage', ['type' => 'following']) }}" id="feed-following">Following Posts</a>
                                    </li>
                                </ul>
                            @endif
                            <ul class="navbar-nav ms-auto">
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        More
                                    </a>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="#">About us</a></li>
                                        <li><a class="dropdown-item" href="#">Help / FAQ</a></li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li><a class="dropdown-item" href="#">Settings</a></li>
                                    </ul>
                                </li>
                                @if (Auth::check())
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ url('/logout') }}">
                                            Logout
                                        </a>
                                    </li>
                                    @if (Auth::user()->isAdmin())
                                        <li class="nav-item">
                                            <a class="nav-link" href="{{ route('admin.page') }}">
                                                Admin Page
                                            </a>
                                        </li>
                                    @else
                                        <li class="nav-item">
                                            <a class="nav-link" href="{{url('/users/' . Auth::user()->id)}}">
                                                {{ Auth::user()->name }}
                                            </a>
                                        </li>
                                    @endif
                                @else
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ url('/login') }}">
                                            Login
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ url('/register') }}">
                                            Register
                                        </a>
                                    </li>
                                @endif
                                <button type="button" class="btn btn-primary position-relative">
                                    Inbox
                                    <span class="position-absolute top-0 start-100 translate-middle p-2 bg-danger border border-light rounded-circle">
                                        <span class="visually-hidden">New alerts</span>
                                    </span>
                                </button>
                            </ul>
                        </div>
                    </div>
                </nav>
            </header>
            <section id="content">
                @yield('content')
            </section>
        </main>
        <footer>
            <hr class="my-4 mx-auto mb-3" style="width: 90%;">
            <p class="text-center">&copy; FakeBook 2024</p>
        </footer>
    </body>
</html>