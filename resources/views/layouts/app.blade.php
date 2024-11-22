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
        <link href="{{ url('css/milligram.min.css') }}" rel="stylesheet">
        <link href="{{ url('css/app.css') }}" rel="stylesheet">
        <script type="text/javascript">
            // Fix for Firefox autofocus CSS bug
            // See: http://stackoverflow.com/questions/18943276/html-5-autofocus-messes-up-css-loading/18945951#18945951
        </script>
        <script type="text/javascript" src={{ url('js/app.js') }} defer>
        </script>
    </head>
    <body>
        <main>
            <header>
                <h1><a href="{{ url('/') }}">FakeBook!</a></h1>
                @if(Route::currentRouteName() === 'home' && Auth::check())
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
                    @if (Auth::check() || Auth::guard('admin')->check())
                        <a class="button" href="{{ url('/logout') }}"> Logout </a>
                        @if (Auth::guard('admin')->check())
                            <a href="">
                                <span id="admin_page">Admin Page</span>
                            </a> 
                        @else
                        <a href="{{url('/users/' . Auth::user()->id)}}">
                            <span>{{ Auth::user()->name }}</span>
                        </a>
                        @endif
                    @else
                        <a class="button" href="{{ url('/login') }}"> Login </a>
                        <a class="button" href="{{ url('/register') }}"> Register </a>
                    @endif
                </section>
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