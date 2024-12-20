<!DOCTYPE html>
<html lang="en-US">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>
        <meta name="user-id" content="{{ Auth::id() }}">

        <!-- Styles -->
        <link href="{{ url('css/app.css') }}" rel="stylesheet">
        <script >
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
        <script src="{{asset('js/group.js')}}" defer></script>
        <script src="{{asset('js/reactions.js')}}" defer></script>
        <script src="{{ asset('js/groupLazyScroll.js') }}"></script>
    </head>
    <body class="@yield('body-class')">
    <a href="#content" class="skip-link">Skip to content</a>
    <header>
                <div class="navbar">
                    <a href="{{ url('/') }}"><img id="logo" src="{{ Storage::url('public/LOGO.png') }}" alt="FakeBook Logo" width="50" height="50"></a>
                    <h2>
                        <a href="{{ url('/') }}" aria-label="Go to Home Page">FakeBook!</a>
                    </h2>
                        @if((Auth::check() && !Auth::user()->isBanned()) || !Auth::check())
                        <section id="search-options">
                            <form  action="{{route('search')}}" method="GET">
                                <div>
                                <input id="search" type="text" name="query" placeholder="search here..." value="" aria-label="Search for users">
                                <input type="hidden" name="type" value="users">
                                <ul  id="real-time-search"></ul> <!-- Add this element to display search results -->
                                </div>
                            </form>
                            <button id="advancedSearch" data-bs-toggle="modal" data-bs-target="#advancedSearchModal" aria-label="Advanced search options">
                                <i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i><p>Advanced</p>
                            </button>
                            @include('partials.search_modal')
                        </section>
                        @endif
                        @if(Route::currentRouteName() === 'homepage' && Auth::check() && !Auth::user()->isAdmin() && !Auth::user()->isBanned())
                        
                            <section id="timeline_options">
                                <a href="{{ route('homepage', ['type' => 'public']) }}" aria-label="View public posts">
                                    <button class="timeline" id="public">Public</button>
                                </a>
                                <a href="{{ route('homepage', ['type' => 'following']) }}" aria-label="View posts from followed users">
                                    <button class="timeline" id="following">Following</button>
                                </a>
                            </section>
                        @endif

                        @if(Route::currentRouteName() === 'search')
                            @include('partials.search')
                        @endif

                    <section id="account-options">
                        @if (Auth::check())
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                            <div id="notification-container">
                                <i id="noti" class="fa-solid fa-bell"></i>
                                @if (Auth::user()->unreadNotifications()->count() > 0)
                                    <span id="number_noti" aria-live="assertive">{{ Auth::user()->unreadNotifications()->count() }}</span>
                                @else
                                    <span id="number_noti" style="display: none;" aria-live="assertive">0</span>
                                @endif
                                <div id="notification-dropdown">
                                    <ul>
                                        @foreach (Auth::user()->notifications as $notification)
                                            <li id="notification" data-notification-id="{{$notification->id}}">
                                                <a href="{{route('profile', ['user_id' => $notification->sender->id])}}">
                                                <img src="{{route('userphoto', ['user_id' => $notification->sender->id])}}" alt="profile picture" width="50" height="50">
                                                <p><span id="sender">{{'@'. $notification->sender->username . ' '}}</span> <span id="noti_content">{{$notification->content}}</span></p>
                                                </a>
                                                <button  style="display:none" id="Follow" data-user-id="{{$notification->sender->id}}">Follow</button>

                                                @if ($notification->typen === 'FOLLOW_REQUEST')
                                                <div id="notification-actions">
                                                        <button id="accept" data-user-id="{{ $notification->sender->id }}"><p>Accept</p></button>
                                                        <button id="reject" data-user-id="{{ $notification->sender->id }}"><p>Eliminate</p></button>
                                                </div>
                                                @elseif (!Auth::user()->isFollowing($notification->sender->id))
                                                    <button id="Follow" data-user-id="{{$notification->sender->id}}">Follow Back</button>
                                                @endif
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                            <a class="button" href="{{ route('logout') }}" aria-label="Log out" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        Logout
                            </a>
                        
                                               
                        @else
                            <a class="button" href="{{ url('/login') }}" aria-label="Login to your account"> <p>Login</p></a>
                            <a class="button" href="{{ url('/register') }}" aria-label="Create a new account"> <p>Register</p></a>
                            
                        @endif
                        <i id="dropdown-toggle" class="fa-solid fa-caret-down"></i>
                            <div class="dropdown"> 
                                @if (Auth::check() && Auth::user()->isAdmin())
                                    <a href="{{ route('admin.page') }}" aria-label="Admin page">Admin Page</a>
                                @endif
                                @if(Auth::check())
                                <a class="button" href="{{ route('logout') }}" aria-label="Log out"
                                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        Logout
                                </a>
                                @else
                                <a href="{{ url('/login') }}" aria-label="Login to your account"> <p>Login</p></a>
                                <a href="{{ url('/register') }}" aria-label="Create a new account"> <p>Register</p></a>
                                @endif
                            </div>
                            @include('partials.create_post', ['categories' => $categories])
                            @include('partials.create_group')
                    </section>
                </div>
        </header>
        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif
        <main>
        <section id="sidebar">
            <div class= "navigators">
                <a class="auth" href="{{ url('/') }}" aria-label="Go to Home Page"><i class="fa-solid fa-house" aria-hidden="true"></i><p>Home</p></a>
                @if(Auth::check() && !Auth::user()->isAdmin() && !Auth::user()->isBanned())
                    <a class="auth" href="#" data-bs-toggle="modal" data-bs-target="#groupCreationModal"aria-label="Create a new group"><i class="fa-solid fa-user-group" aria-hidden="true"></i><p>Create Group</p></a> 
                    @if(Auth::user()->typeu === 'INFLUENCER')
                        <a  class="auth" href="{{ route('influencer.page', Auth::user()->id) }}" aria-label="View influencer statistics"> <i class="fa-solid fa-chart-line" aria-hidden="true"></i> <p>View Statistics </p> </a>
                    @endif
                    <a class="auth" href="#" data-bs-toggle="modal" data-bs-target="#createPostModal" aria-label="Create a new post"><i class="fa-solid fa-plus" aria-hidden="true"></i><p>Create Post</p></a>
                @endif
                @if(Auth::check())
                    @if(!Auth::user()->isAdmin() && !Auth::user()->isBanned())
                        <a class="auth" href="{{ route('direct_chats.index') }}" aria-label="Go to Conversations"><i class="fa-regular fa-paper-plane" aria-hidden="true"></i><p>Conversations</p></a>
                    @endif
                    <a class="auth" href="{{ Auth::user()->isAdmin() ? route('admin.page') : route('profile', ['user_id' => Auth::user()->id]) }}" aria-label="Go to Profile or Admin Page">
                        <img src="{{ route('userphoto', ['user_id' => Auth::user()->id]) }}" alt="User profile picture" width="50" height="50">
                        @if(Auth::user()->isAdmin())
                            <p>Admin Page</p>
                        @else
                        <p>Profile</p>
                        @endif
                    </a>
                    @if(Auth::user()->isAdmin())
                        <a class="auth" href="{{url('/register')}}" aria-label="Create New User"><i class="fa-solid fa-user-plus" aria-hidden="true"></i><p>Create User</p></a>
                    @endif
                @endif
                <a class="auth" href="{{ route('reports') }}" aria-label="Go to Reports"><i class="fa-solid fa-flag" aria-hidden="true"></i><p>Reports</p></a>
                @if(Auth::check() )
                    <div class="dropdown-container">
                        <a href="#" id="toggleDropdown" class="auth" aria-expanded="false" aria-controls="DropdownMore" aria-label="More options"><i class="fa-solid fa-bars" aria-hidden="true"></i><p>More</p></a>
                        <div id="DropdownMore" style="display: none;" role="menu">
                            <a class="auth" href="{{ route('help') }}" role="menuitem" aria-label="Go to Help/Contacts"><i class="fa-solid fa-info-circle" aria-hidden="true"></i><p>Help/Contacts</p></a>
                            <a class="auth" href="{{ route('about') }}" role="menuitem" aria-label="Go to About Us"><i class="fa-solid fa-question-circle" aria-hidden="true"></i><p>About Us</p></a>
                            <a class="auth" href="{{ route('settings') }}" role="menuitem" aria-label="Go to Settings"><i class="fa-solid fa-cog" aria-hidden="true"></i><p>Settings</p></a>
                            <a class="button" href="{{ route('logout') }}" aria-label="Log out" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" >Logout</a>
                        </div>
                    </div>
                @else
                    <a class="auth" href="{{ route('help') }}" role="menuitem" aria-label="Go to Help/Contacts"><i class="fa-solid fa-info-circle" aria-hidden="true"></i><p>Help/Contacts</p></a>
                    <a class="auth" href="{{ route('about') }}" role="menuitem" aria-label="Go to About Us"><i class="fa-solid fa-question-circle" aria-hidden="true"></i><p>About Us</p></a>
                    <a class="auth" href="{{ route('settings') }}" role="menuitem" aria-label="Go to Settings"><i class="fa-solid fa-cog" aria-hidden="true"></i><p>Settings</p></a>
                    <div class="dropdown-container">
                        <a href="#" id="toggleDropdown2" class="auth" aria-expanded="false" aria-controls="DropdownMore" aria-label="More options" ><i class="fa-solid fa-bars" aria-hidden="true"></i><p>More</p></a>
                        <div id="DropdownMore2" style="display: none;" role="menu">
                        <a class="button" href="{{ url('/login') }}" aria-label="Login">Login</a>
                        <a class="button" href="{{ url('/register') }}" aria-label="Register">Register</a>
                        </div>
                    </div>
                @endif
                @if (Auth::check())
                <a id="buttonLog" class="button" href="{{ route('logout') }}" aria-label="Logout"
                                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <p>Logout</p>
                </a>
                @else
                    <a id="buttonLogin" class="button" href="{{ url('/login') }}" aria-label="Login"> <p>Login</p></a>
                    <a id="buttonRegister" class="button" href="{{ url('/register') }}" aria-label="Register"> <p>Register</p></a>
                @endif
            </div>

            </section>
            <section id="content" >
                @yield('content')
            </section>
            
        </main>
        <footer>
            <p>&copy; FakeBook 2024</p>
        </footer>
    </body>
</html>