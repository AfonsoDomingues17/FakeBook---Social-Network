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
        <script src="https://js.pusher.com/7.2/pusher.min.js" defer></script>
        <script type="text/javascript" src={{ url('js/app.js') }} defer>
        </script>
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
        <script src="{{ asset('js/search.js') }}" defer></script>
        <script src="{{asset('js/searchType.js')}}" defer></script>
        <script src="{{asset('js/connection.js')}}" defer></script>
        <script src="{{asset('js/notification.js')}}" defer></script>
        <script src="{{asset('js/watchlist.js')}}" defer></script>
    </head>
    <body>
    <header>
                <div class="navbar">
                    <a href="{{ url('/') }}"><img id="logo" src="{{ Storage::url('LOGO.png') }}" alt="FakeBook Logo" width="50" height="50"></a>
                    <h1>
                        <a href="{{ url('/') }}">FakeBook!</a>
                    </h1>
                    <form  action="{{route('search')}}" method="GET">
                        <div style="position: relative;">
                            <input id="search" type="text" name="query" placeholder="search for users">
                            <input type="hidden" name="type" value="users">
                            <ul  id="real-time-search"></ul> <!-- Add this element to display search results -->
                        </div>
                    </form>
                    
            
                    
                    @if(Route::currentRouteName() === 'homepage' && Auth::check() && !Auth::user()->isAdmin())
                    
                        <section id="timeline_options">
                            <a href="{{ route('homepage', ['type' => 'public']) }}">
                                <button class="timeline" id="public">Public Posts</button>
                            </a>
                            <a href="{{ route('homepage', ['type' => 'following']) }}" >
                                <button class="timeline" id="following">Following Posts</button>
                            </a>
                        </section>
                    @endif

                    @if(Route::currentRouteName() === 'search')
                        <section id="timeline_options">
                            <button id="search-users">Users</button>
                            <button id="search-posts">Posts</button>
                        </section>
                    @endif

                    <section id="account-options">
                        @if (Auth::check())
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                            <a class="button" href="{{ route('logout') }}"
                                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        Logout
                            </a>
                            @if (Auth::user()->isAdmin())
                            <a href="{{ route('admin.page') }}">
                                    <span id="admin_page"><p>Admin Page</p></span>
                            </a> 
                            @else
                            <a class="auth2" href="{{route('profile',['user_id'=> Auth::user()->id])}}">
                                <p>{{Auth::user()->name}}</p>
                                <img src="{{ route('userphoto', ['user_id' => Auth::user()->id]) }}" alt="" width="50" height="50">
                            </a>
                            
                            
                            @endif
                
                                               
                        @else
                            <a class="button" href="{{ url('/login') }}"> <p>Login</p></a>
                            <a class="button" href="{{ url('/register') }}"> <p>Register</p></a>
                            
                        @endif
                        <i id="dropdown-toggle" class="fa-solid fa-caret-down"></i>
                            <div class="dropdown"> 
                                @if (Auth::check() && Auth::user()->isAdmin())
                                    <a href="{{ route('admin.page') }}">Admin Page</a>
                                @endif
                                @if(Auth::check())
                                <a class="button" href="{{ route('logout') }}"
                                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        Logout
                                </a>
                                @else
                                <a href="{{ url('/login') }}"> <p>Login</p></a>
                                <a href="{{ url('/register') }}"> <p>Register</p></a>
                                @endif
                            </div>
                            @include('partials.create_post')
                    </section>
                </div>
            </header>
        <main>
        <section id="sidebar">
            <div class= "navigators">
                <a class="auth" href="{{ url('/') }}"><i class="fa-solid fa-house"></i><p>Home</p></a>
                <a class="auth" href=""><i class="fa-solid fa-user-group"></i><p>Groups</p></a>
                @if(Auth::check() && !Auth::user()->isAdmin())
                <a class="auth" href="#" data-bs-toggle="modal" data-bs-target="#createPostModal"><i class="fa-solid fa-plus"></i><p>Create Post</p></a>
                @endif
                @if(Auth::check())
                <a class="auth" href="{{ route('direct_chats.index') }}"><i class="fa-regular fa-paper-plane"></i><p>Messages</p></a>
                <a class="auth" href="{{ Auth::user()->isAdmin() ? route('admin.page') : route('profile', ['user_id' => Auth::user()->id]) }}">
                    <img src="{{ route('userphoto', ['user_id' => Auth::user()->id]) }}" alt="" width="50" height="50">
                    <p>{{ Auth::user()->name }}</p>
                </a>
                <a id="buttonLog" class="button" href="{{ route('logout') }}"
                                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <p>Logout</p>
                </a>
                    @if(Auth::user()->isAdmin())
                        <a class="auth" href="{{url('/register')}}"><i class="fa-solid fa-user-plus"></i><p>Create User</p></a>
                    @endif
                @else
                <a id="buttonLogin" class="button" href="{{ url('/login') }}"> <p>Login</p></a>
                <a id="buttonRegister" class="button" href="{{ url('/register') }}"> <p>Register</p></a>
                @endif
                <a class="auth" href="{{ route('help') }}"><i class="fa-solid fa-info-circle"></i><p>Help/Contacts</p></a>
                <a class="auth" href="{{ route('about') }}"><i class="fa-solid fa-question-circle"></i><p>About Us</p></a>
                <a class="auth" href="{{ route('settings') }}"><i class="fa-solid fa-cog"></i><p>Settings</p></a>
            </div>

            </section>
            <section id="content">
                @yield('content')
            </section>
        </main>
        <footer>
            <p>&copy; FakeBook 2024</p>
        </footer>
        @include('partials.create_post')
    </body>
</html>