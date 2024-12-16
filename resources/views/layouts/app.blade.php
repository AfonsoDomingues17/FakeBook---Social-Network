<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
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
        <script src="{{asset('js/group.js')}}" defer></script>
    </head>
    <body class="@yield('body-class')">
    <header>
                <div class="navbar">
                    <a href="{{ url('/') }}"><img id="logo" src="{{ Storage::url('public/LOGO.png') }}" alt="FakeBook Logo" width="50" height="50"></a>
                    <h1>
                        <a href="{{ url('/') }}">FakeBook!</a>
                    </h1>
                        @if((Auth::check() && !Auth::user()->isBanned()) || !Auth::check())
                        <form  action="{{route('search')}}" method="GET">
                            <div style="position: relative;">
                                <input id="search" type="text" name="query" placeholder="search for users">
                                <input type="hidden" name="type" value="users">
                                <ul  id="real-time-search"></ul> <!-- Add this element to display search results -->
                            </div>
                        </form>
                        @endif
                        @if(Route::currentRouteName() === 'homepage' && Auth::check() && !Auth::user()->isAdmin() && !Auth::user()->isBanned())
                        
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
                            @include('partials.search')
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
                            <div id="notification-container">
                                <i id="noti" class="fa-solid fa-bell"></i>
                                @if (Auth::user()->unreadNotifications()->count() > 0)
                                    <span id="number_noti">{{ Auth::user()->unreadNotifications()->count() }}</span>
                                @else
                                    <span id="number_noti" style="display: none;">0</span>
                                @endif
                                <div id="notification-dropdown">
                                    <ul>
                                        @foreach (Auth::user()->notifications as $notification)
                                            <li id="notification" data-notification-id="{{$notification->id}}">
                                                <a href="{{route('profile', ['user_id' => $notification->sender->id])}}">
                                                <img src="{{route('userphoto', ['user_id' => $notification->sender->id])}}" alt="profile picture" width="50" height="50">
                                                <p>{{'@'. $notification->sender->username . ' '}} <span id="noti_content">{{$notification->content}}</span></p>
                                                </a>
                                                <button  style="display:none" id="Follow" data-user-id="{{$notification->sender->id}}">Follow</button>

                                                @if ($notification->typen === 'FOLLOW_REQUEST')
                                                    <div id="notification-actions">
                                                        <button id="accept" data-user-id="{{ $notification->sender->id }}">Accept</button>
                                                        <button id="reject" data-user-id="{{ $notification->sender->id }}">Eliminate</button>
                                                    </div>
                                                @elseif (!Auth::user()->isFollowing($notification->sender->id))
                                                    <button id="Follow" data-user-id="{{$notification->sender->id}}">Follow Back</button>
                                                @endif
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
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
                            @include('partials.create_group')
                    </section>
                </div>
        </header>
        <main>
        <section id="sidebar">
            <div class= "navigators">
                <a class="auth" href="{{ url('/') }}"><i class="fa-solid fa-house"></i><p>Home</p></a>
                @if(Auth::check() && !Auth::user()->isAdmin() && !Auth::user()->isBanned())
                    <a class="auth" href="#" data-bs-toggle="modal" data-bs-target="#groupCreationModal"><i class="fa-solid fa-user-group"></i><p>Create Group</p></a> 
                    @if(Auth::user()->typeu === 'INFLUENCER')
                        <a  class="auth" href="{{ route('influencer.page', Auth::user()->id) }}"> <i class="fa-solid fa-chart-line"></i> <p>View Statistics </p> </a>
                    @endif
                    <a class="auth" href="#" data-bs-toggle="modal" data-bs-target="#createPostModal"><i class="fa-solid fa-plus"></i><p>Create Post</p></a>
                @endif
                @if(Auth::check())
                    @if(!Auth::user()->isAdmin() && !Auth::user()->isBanned())
                        <a class="auth" href="{{ route('direct_chats.index') }}"><i class="fa-regular fa-paper-plane"></i><p>Conversations</p></a>
                    @endif
                    <a class="auth" href="{{ Auth::user()->isAdmin() ? route('admin.page') : route('profile', ['user_id' => Auth::user()->id]) }}">
                        <img src="{{ route('userphoto', ['user_id' => Auth::user()->id]) }}" alt="" width="50" height="50">
                        <p>{{ Auth::user()->name }}</p>
                    </a>
                    <a id="buttonLog" class="button" href="{{ route('logout') }}"
                                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <p>Logout</p>
                    </a>
                    <a class="auth" href="{{ route('reports') }}"><i class="fa-solid fa-flag"></i><p>Reports</p></a>
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
            <section id="content" >
                @yield('content')
            </section>
            
        </main>
        <footer>
            <p>&copy; FakeBook 2024</p>
        </footer>
        @include('partials.create_post')
        @include('partials.create_group')
        @include('partials.group_participants')
    </body>
</html>