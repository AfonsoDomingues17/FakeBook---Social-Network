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

    </head>
    <body>
        <main>
            <header>
                <div class="navbar">
                    <a href="{{ url('/') }}"><img id="logo" src="{{ Storage::url('LOGO.png') }}" alt="FakeBook Logo" width="50" height="50"></a>
                    <h1>
                        <a href="{{ url('/') }}">FakeBook!</a>
                    </h1>
                    
                    <form class="search-bar" action="/search" method="GET">
                        <input type="text" name="query" placeholder="Search..." required>
                        <input type="hidden" name="type" value="users">
                    </form>
                    @if(Route::currentRouteName() === 'homepage' && Auth::check() && !Auth::user()->isAdmin())
                        <section id="timeline_options">
                            <a href="{{ route('homepage', ['type' => 'public']) }}">
                                <button id="public-posts-btn">Public Posts</button>
                            </a>
                            <a href="{{ route('homepage', ['type' => 'following']) }}" >
                                <button id="following-posts-btn">Following Posts</button>
                            </a>
                        </section>
                    @endif

                    <section id="account-options">
                        @if (Auth::check())
                            <a class="button" href="{{ url('/logout') }}"> <p>Logout</p></a>
                            @if (Auth::user()->isAdmin())
                            <a href="{{ route('admin.page') }}">
                                    <span id="admin_page"><p>Admin Page</p></span>
                                </a> 
                            @else
                            <a class="button" href="{{url('/users/' . Auth::user()->id)}}">
                                <p>{{ Auth::user()->name }}</p>
                            </a>
                            @endif
                        @else
                            <a class="button" href="{{ url('/login') }}"> <p>Login</p></a>
                            <a class="button" href="{{ url('/register') }}"> <p>Register</p></a>
                        @endif
                    </section>
                </div>
            </header>
            <section id="content">
                @yield('content')
            </section>
        </main>
        <footer>
            <p>&copy; FakeBook 2024</p>
        </footer>
    </body>
</html>