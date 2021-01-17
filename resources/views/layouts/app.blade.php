<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script difer src="{{ asset('js/app.js') }}" defer></script>
    <script difer src="{{ asset('js/jquery.nicescroll.js') }}" defer></script>
    <script difer src="{{ asset('js/jquery.dcjqaccordion.2.7.js') }}" defer></script>
    <script difer src="{{ asset('js/jquery.scrollTo.min.js') }}" defer></script>
    <!-- <script difer src="{{ asset('js/bootstrap.min.js') }}" defer></script> -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" defer></script>
    <script difer src="{{ asset('js/bootstrap-datetimepicker.min.js') }}" defer></script>
    <script type="text/javascript" src="https://seal.godaddy.com/getSeal?sealID=RPID2xBpzvHJDn2eUdkP7XNumDKdZtZm8hKwsPaInmywr8ABw62Qw92cxee8" defer></script>
    <script type="text/javascript" src="{{ asset('js/emojionearea.min.js') }}" defer></script>
    <script type="text/javascript" src="{{ asset('js/croppie.js') }}" defer></script>
    <script difer src="{{ asset('js/scripts.js') }}" defer></script>

    @stack('scripts')
    <!-- <script difer src="{{ asset('js/lightslider.js') }}" defer></script> -->
    <!-- <script src="{{ asset('js/jquery.ui.widget.js') }}" defer></script> -->
    <!-- The Templates plugin is included to render the upload/download listings -->
    <!-- <script src="http://blueimp.github.io/JavaScript-Templates/js/tmpl.min.js" defer></script> -->
    <!-- The Load Image plugin is included for the preview images and image resizing functionality -->
    <!-- <script src="http://blueimp.github.io/JavaScript-Load-Image/js/load-image.min.js" defer></script> -->
    <!-- The Canvas to Blob plugin is included for image resizing functionality -->
    <!-- <script src="http://blueimp.github.io/JavaScript-Canvas-to-Blob/js/canvas-to-blob.min.js" defer></script> -->
    <!-- blueimp Gallery script -->
    <!-- <script src="http://blueimp.github.io/Gallery/js/jquery.blueimp-gallery.min.js" defer></script>
    <script src="{{ asset('js/jquery.iframe-transport.js') }}" defer></script>
    <script src="{{ asset('js/jquery.fileupload.js') }}" defer></script>
    <script src="{{ asset('js/jquery.fileupload-process.js') }}" defer></script>
    <script src="{{ asset('js/jquery.fileupload-image.js') }}" defer></script>
    <script src="{{ asset('js/jquery.fileupload-validate.js') }}" defer></script>
    <script src="{{ asset('js/jquery.fileupload-ui.js') }}" defer></script>
    <script src="{{ asset('js/jquery.fileupload.main.js') }}" defer></script> -->

    <!-- Fonts -->
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Ruda:400,700,900" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <!-- <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet"> -->
    @auth
        <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" />
    @else
        <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet" />
    @endauth

    <link href="{{ asset('css/font-awesome.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('css/style-responsive.css') }}" rel="stylesheet">
    <link href="{{ asset('css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/to-do.css') }}" rel="stylesheet">
    <!-- <link href="{{ asset('css/lightslider.css') }}" rel="stylesheet"> -->
    @yield('styles')
    <link rel="stylesheet" href="{{ asset('css/emojionearea.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/croppie.min.css') }}">
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
    @auth
        @if (auth()->user()->isProUnlimitedClients())
            <style type="text/css">
                ul.sidebar-menu li a.active, ul.sidebar-menu li a:hover, ul.sidebar-menu li a:focus {
                    background: #1A9AD7;
                }

                .chat-room-head {
                    background: #1A9AD7;
                }

                .tab button.active {
                    background-color: #1A9AD7;
                    color: #ffffff;
                }
                .tab button:hover {
                    background-color: #1a9ad7e0;
                    color: #ffffff;
                }

                .saveNotes.btn.btn-primary {
                    background: #1A9AD7;
                }

                .room-box h5 a {
                    color: #1A9AD7;
                }
            </style>
        @endif
    @endauth
    <!-- blueimp Gallery styles -->
    <!-- <link rel="stylesheet" href="http://blueimp.github.io/Gallery/css/blueimp-gallery.min.css">
    <link rel="stylesheet" href="{{ asset('css/jquery.fileupload.css') }}">
    <link rel="stylesheet" href="{{ asset('css/jquery.fileupload-ui.css') }}">
    <noscript>
        <link rel="stylesheet" href="{{ asset('css/jquery.fileupload-noscript.css') }}">
    </noscript>
    <noscript>
        <link rel="stylesheet" href="{{ asset('css/jquery.fileupload-ui-noscript.css') }}">
    </noscript> -->
</head>
<body>
    <div id="app">
        <!-- <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar --><!--
                    <ul class="navbar-nav mr-auto">

                    </ul>

                    <!-- Right Side Of Navbar --><!--
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links --><!--
                        @guest
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                            </li>
                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav> -->
        @if(Auth::guard('admin')->check() || Auth::guard('client')->check())
            <header class="header black-bg">
                @if(Auth::guard('admin')->check() || Auth::guard('client')->check())
                    <div class="sidebar-toggle-box">
                        <div class="fa fa-bars tooltips" data-placement="right" data-original-title="Toggle Navigation"></div>
                    </div>
                @endif
                <!--logo start-->
                <a href="{{ route("dashboard") }}" class="logo"><b>{{ config('app.name', 'Brad Davidson') }}</b></a>
                <!--logo end-->

                @if(Auth::guard('admin')->check() || Auth::guard('client')->check())
                    <div class="top-menu">
                        <ul class="nav pull-right top-menu">
                            <li>
                                <a class="logout" href="{{ route('logout') }}"
                                   onclick="event.preventDefault();
                                                 document.getElementById('logout-form').submit();">
                                         {{ __('Logout') }}
                                 </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    @csrf
                                </form>
                             </li>
                        </ul>
                        <notifications :notifications-data="{{ (new App\Notification)->getNotifications() }}" :user-id="{{ auth()->user()->id }}"></notifications>
                    </div>
                @endif
            </header>
        @endif

        @if(Auth::guard('admin')->check() || Auth::guard('client')->check())
            <aside>
                <div id="sidebar" class="nav-collapse ">
                    <!-- sidebar menu start-->
                    <ul class="sidebar-menu" id="nav-accordion">
                        <p class="centered profile-pic">
                            <a href="{{ route('clients.myprofile') }}">
                                @if(!empty(auth()->user()->profile_photo_icon))
                                    <img src="{{ auth()->user()->profile_photo_icon }}" class="img-circle" width="80" style="border: unset;">
                                @else
                                    <img src="{{ asset('img/friends/fr-05.jpg') }}" class="img-circle" width="80" style="border: unset;">
                                @endif
                            </a>
                        </p>
                        <h5 class="centered">
                            @if(Auth::guard('admin')->check())
                                {{ Auth::guard('admin')->user()->fullname }}
                            @elseif(Auth::guard('client')->check())
                                {{ Auth::guard('client')->user()->fullname }}
                            @endif
                        </h5>
                        <li class="mt">
                            <a class="{{ (request()->is('/') ? 'active' : '') }}" href="{{ route('dashboard') }}">
                                <!-- <i class="fa fa-dashboard"></i> -->
                                <svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24"><path d="M0 0h24v24H0z" fill="none"/><path id="menu-svg" d="M3 13h8V3H3v10zm0 8h8v-6H3v6zm10 0h8V11h-8v10zm0-18v6h8V3h-8z"/></svg>
                                <span>{{ __('Dashboard') }}</span>
                            </a>
                        </li>
                        @can('tags_access')
                            <li class="sub-menu">
                                <a class="{{ (request()->is('tags*') ? 'active' : '') }}" href="{{ route('tags.index') }}">
                                    <!-- <i class="fa fa-object-group"></i> -->
                                    <svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24"><path d="M0 0h24v24H0z" fill="none"/><path id="menu-svg" d="M21.41 11.58l-9-9C12.05 2.22 11.55 2 11 2H4c-1.1 0-2 .9-2 2v7c0 .55.22 1.05.59 1.42l9 9c.36.36.86.58 1.41.58.55 0 1.05-.22 1.41-.59l7-7c.37-.36.59-.86.59-1.41 0-.55-.23-1.06-.59-1.42zM5.5 7C4.67 7 4 6.33 4 5.5S4.67 4 5.5 4 7 4.67 7 5.5 6.33 7 5.5 7z"/></svg>
                                    <span>{{ __('Tags') }}</span>
                                </a>
                            </li>
                        @endcan
                        @can('inventories_access')
                            <li class="sub-menu">
                                <a class="{{ (request()->is('inventory*') ? 'active' : '') }}" href="{{ route('inventory.index') }}">
                                    <!-- <i class="fa fa-object-group"></i> -->
                                    <svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24"><path d="M0 0h24v24H0z" fill="none"/><path id="menu-svg" d="M12 2l-5.5 9h11z"/><circle id="menu-svg" cx="17.5" cy="17.5" r="4.5"/><path id="menu-svg" d="M3 13.5h8v8H3z"/></svg>
                                    <span>{{ __('Inventory') }}</span>
                                </a>
                            </li>
                        @endcan
                        @can('clients_access')
                            <li class="sub-menu">
                                <a class="{{ (request()->is('clients*') ? 'active' : '') }}" href="{{ route('clients.index') }}">
                                    <!-- <i class="fa fa-users"></i> -->
                                    <svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24"><path d="M0 0h24v24H0z" fill="none"/><path id="menu-svg" d="M12 2c-4.97 0-9 4.03-9 9 0 4.17 2.84 7.67 6.69 8.69L12 22l2.31-2.31C18.16 18.67 21 15.17 21 11c0-4.97-4.03-9-9-9zm0 2c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3zm0 14.3c-2.5 0-4.71-1.28-6-3.22.03-1.99 4-3.08 6-3.08 1.99 0 5.97 1.09 6 3.08-1.29 1.94-3.5 3.22-6 3.22z"/></svg>
                                    <span>{{ __('Clients') }}</span>
                                </a>
                            </li>
                        @endcan
                        @can('chat_access')
                            <li class="sub-menu">
                                <a class="{{ (request()->is('chat*') ? 'active' : '') }}" href="{{ route('chat.index') }}">
                                    <!-- <i class="fa fa-users"></i> -->
                                    <svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24"><path d="M0 0h24v24H0z" fill="none"/><path id="menu-svg" d="M20 2H4c-1.1 0-1.99.9-1.99 2L2 22l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zM6 9h12v2H6V9zm8 5H6v-2h8v2zm4-6H6V6h12v2z"/></svg>
                                    <span>{{ __('Chat') }}</span>
                                </a>
                            </li>
                        @endcan
                        @can('calendar_access')
                            <li class="sub-menu">
                                <a class="{{ (request()->is('calendar*') ? 'active' : '') }}" href="{{ route('calendar.index') }}">
                                    <!-- <i class="fa fa-users"></i> -->
                                    <svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24"><path d="M0 0h24v24H0z" fill="none"/><path id="menu-svg" d="M20 3h-1V1h-2v2H7V1H5v2H4c-1.1 0-2 .9-2 2v16c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 18H4V8h16v13z"/></svg>
                                    <span>{{ __('Calendar') }}</span>
                                </a>
                            </li>
                        @endcan
                        @can('note_access')
                            <li class="sub-menu">
                                <a class="{{ (request()->is('notes*') ? 'active' : '') }}" href="{{ route('notes.index') }}">
                                    <!-- <i class="fa fa-users"></i> -->
                                    <svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24"><path d="M0 0h24v24H0V0z" fill="none"/><path id="menu-svg" d="M3 18h12v-2H3v2zM3 6v2h18V6H3zm0 7h18v-2H3v2z"/></svg>
                                    <span>{{ __('Notes') }}</span>
                                </a>
                            </li>
                        @endcan
                        @can('coaching_access')
                            <li class="sub-menu">
                                <a class="{{ (request()->is('coaching*') ? 'active' : '') }}" href="{{ route('coaching.index') }}">
                                    <!-- <i class="fa fa-users"></i> -->
                                    <svg xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 24 24" height="24" viewBox="0 0 24 24" width="24"><g><rect id="menu-svg" fill="none" height="24" width="24"/><path d="M15.5,13.5c0,2-2.5,3.5-2.5,5h-2c0-1.5-2.5-3-2.5-5c0-1.93,1.57-3.5,3.5-3.5h0C13.93,10,15.5,11.57,15.5,13.5z M13,19.5h-2 V21h2V19.5z M19,13c0,1.68-0.59,3.21-1.58,4.42l1.42,1.42C20.18,17.27,21,15.23,21,13c0-2.74-1.23-5.19-3.16-6.84l-1.42,1.42 C17.99,8.86,19,10.82,19,13z M16,5l-4-4v3c0,0,0,0,0,0c-4.97,0-9,4.03-9,9c0,2.23,0.82,4.27,2.16,5.84l1.42-1.42 C5.59,16.21,5,14.68,5,13c0-3.86,3.14-7,7-7c0,0,0,0,0,0v3L16,5z"/></g></svg>
                                    <span>{{ __('Coaching') }}</span>
                                </a>
                            </li>
                        @endcan
                        @if (!auth()->user()->isSuperAdmin())
                            @can('coaching_show_to_clients')
                                <li class="sub-menu">
                                    <a class="{{ (request()->is('coaching/client*') ? 'active' : '') }}" href="{{ route('coaching.client.index') }}">
                                        <!-- <i class="fa fa-users"></i> -->
                                        <svg xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 24 24" height="24" viewBox="0 0 24 24" width="24"><g><rect id="menu-svg" fill="none" height="24" width="24"/><path d="M15.5,13.5c0,2-2.5,3.5-2.5,5h-2c0-1.5-2.5-3-2.5-5c0-1.93,1.57-3.5,3.5-3.5h0C13.93,10,15.5,11.57,15.5,13.5z M13,19.5h-2 V21h2V19.5z M19,13c0,1.68-0.59,3.21-1.58,4.42l1.42,1.42C20.18,17.27,21,15.23,21,13c0-2.74-1.23-5.19-3.16-6.84l-1.42,1.42 C17.99,8.86,19,10.82,19,13z M16,5l-4-4v3c0,0,0,0,0,0c-4.97,0-9,4.03-9,9c0,2.23,0.82,4.27,2.16,5.84l1.42-1.42 C5.59,16.21,5,14.68,5,13c0-3.86,3.14-7,7-7c0,0,0,0,0,0v3L16,5z"/></g></svg>
                                        <span>{{ __('Coaching') }}</span>
                                    </a>
                                </li>
                            @endcan
                        @endif
                        @can('supplements_access')
                            <li class="sub-menu">
                                <a class="{{ (request()->is('supplements*') ? 'active' : '') }}" href="{{ route('supplements.index') }}">
                                    <!-- <i class="fa fa-users"></i> -->
                                    <svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24"><path d="M0 0h24v24H0z" fill="none"/><path id="menu-svg" d="M4 6H2v14c0 1.1.9 2 2 2h14v-2H4V6zm16-4H8c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm-1 9h-4v4h-2v-4H9V9h4V5h2v4h4v2z"/></svg>
                                    <span>{{ __('Supplements') }}</span>
                                </a>
                            </li>
                        @endcan
                        @can('stock_levels_access')
                            <li class="sub-menu">
                                <a class="{{ (request()->is('stock_levels*') ? 'active' : '') }}" href="{{ route('stock_levels.index') }}">
                                    <!-- <i class="fa fa-database"></i> -->
                                    <svg xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 24 24" height="24" viewBox="0 0 24 24" width="24"><rect id="menu-svg" fill="none" height="24" width="24"/><g><path d="M7.5,21H2V9h5.5V21z M14.75,3h-5.5v18h5.5V3z M22,11h-5.5v10H22V11z"/></g></svg>
                                    <span>{{ __('Stock Levels') }}</span>
                                </a>
                            </li>
                        @endcan
                        @can('stock_values_access')
                            <li class="sub-menu">
                                <a class="{{ (request()->is('stock_values*') ? 'active' : '') }}" href="{{ route('stock_values.index') }}">
                                    <!-- <i class="fa fa-money"></i> -->
                                    <svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24"><path d="M0 0h24v24H0z" fill="none"/><path id="menu-svg" d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1.41 16.09V20h-2.67v-1.93c-1.71-.36-3.16-1.46-3.27-3.4h1.96c.1 1.05.82 1.87 2.65 1.87 1.96 0 2.4-.98 2.4-1.59 0-.83-.44-1.61-2.67-2.14-2.48-.6-4.18-1.62-4.18-3.67 0-1.72 1.39-2.84 3.11-3.21V4h2.67v1.95c1.86.45 2.79 1.86 2.85 3.39H14.3c-.05-1.11-.64-1.87-2.22-1.87-1.5 0-2.4.68-2.4 1.64 0 .84.65 1.39 2.67 1.91s4.18 1.39 4.18 3.91c-.01 1.83-1.38 2.83-3.12 3.16z"/></svg>
                                    <span>{{ __('Stock Values') }}</span>
                                </a>
                            </li>
                        @endcan
                        @can('logs_access')
                            <li class="sub-menu">
                                <a class="{{ (request()->is('logs*') ? 'active' : '') }}" href="{{ route('logs.index') }}">
                                    <!-- <i class="fa fa-history"></i> -->
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="black" width="24px" height="24px"><path d="M0 0h24v24H0z" fill="none"/><path id="menu-svg" d="M13 3c-4.97 0-9 4.03-9 9H1l3.89 3.89.07.14L9 12H6c0-3.87 3.13-7 7-7s7 3.13 7 7-3.13 7-7 7c-1.93 0-3.68-.79-4.94-2.06l-1.42 1.42C8.27 19.99 10.51 21 13 21c4.97 0 9-4.03 9-9s-4.03-9-9-9zm-1 5v5l4.28 2.54.72-1.21-3.5-2.08V8H12z"/></svg>
                                    <span>{{ __('Logs') }}</span>
                                </a>
                            </li>
                        @endcan
                        @can('resource_access')
                            <li class="sub-menu">
                                <a class="{{ (request()->is('resources*') ? 'active' : '') }}" href="{{ route('resources.index') }}">
                                    <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                                         viewBox="0 0 32 32" style="enable-background:new 0 0 24 24;" width="24px" height="24px" xml:space="preserve">
                                    <title></title>
                                    <g id="folder">
                                        <polygon class="st0" points="25,12 25,7 3,7 3,25 25,25 29,12" id="menu-svg" />
                                        <polygon class="st0" points="25,25 3,25 7,12 29,12" id="menu-svg" />
                                    </g>
                                    </svg>
                                    <span>{{ __('Resources') }}</span>
                                </a>
                            </li>
                        @endcan
                        @can('training_content_access')
                            <li class="sub-menu">
                                <a class="{{ (request()->is('trainingContents*') ? 'active' : '') }}" href="{{ route('trainingContents.index') }}">
                                    <svg id="Layer_1" style="enable-background:new 0 0 256 256;" version="1.1" viewBox="0 0 256 256" width="24px" height="24px" xml:space="preserve" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><g><path id="menu-svg" d="M52.8,81.7c15,0,27.3-12.2,27.3-27.3S67.9,27.1,52.8,27.1S25.6,39.4,25.6,54.4S37.8,81.7,52.8,81.7z M52.8,37.5   c9.3,0,17,7.6,17,17s-7.6,17-17,17s-17-7.6-17-17S43.5,37.5,52.8,37.5z"/><path id="menu-svg" d="M85.1,113.3c-2.8-0.2-5.3,2-5.5,4.8L75.4,178c0,0.2-0.1,0.3-0.1,0.5l-3.8,58.3c-0.1,2.2-2,4-4.2,4h-29   c-2.2,0-4.1-1.7-4.2-4l-3.8-58.3c-0.1-2.1-1.6-4-3.6-4.6c-1.1-0.3-4.3-1.3-4.8-1.8c-0.4-0.6-0.6-3.8-0.7-5.4l-3.5-61.8l0-0.1   c6.5-1.9,12.9-4,19.4-6.1c2.9,3.4,5.8,6.7,8.8,10.1l3.1,3.5c1,1.1,2.4,1.8,3.9,1.8h0c1.5,0,2.9-0.6,3.9-1.8l3.6-4.1   c2.9-3.3,5.6-6.4,8.3-9.6l44.8,10.1c0.7,0.2,1.5,0.2,2.2,0l40.4-8.3c2.8-0.6,4.6-3.3,4-6.1c-0.6-2.8-3.3-4.6-6.1-4l-39.3,8.1   l-44.2-10c-0.2-0.1-0.5-0.1-0.7-0.1c-1.3-0.1-5.7-0.2-8.4,3c-2.8,3.3-5.6,6.5-8.5,9.8c-2.9-3.3-5.7-6.5-8.5-9.8   c-2.6-3-5.9-4-9.5-2.8C28,90.8,21.1,93,14.2,95.1c-4.4,1.4-6.8,4.7-6.8,9.2c0,0.3,0,1.1,0,1.2l3.4,61l0.1,1   c0.6,8.9,1.9,12.3,9.4,15.2l3.6,54.9c0.5,7.6,6.9,13.6,14.5,13.6h29c7.6,0,14-6,14.5-13.6l3.8-57.8c0-0.2,0-0.3,0.1-0.5l4.2-60.4   C90,116,87.9,113.5,85.1,113.3z"/><path id="menu-svg" d="M243.5,4.8H52.8c-2.8,0-5.2,2.3-5.2,5.2s2.3,5.2,5.2,5.2h185.5v134H101.5c-2.8,0-5.2,2.3-5.2,5.2c0,2.8,2.3,5.2,5.2,5.2   h142c2.8,0,5.2-2.3,5.2-5.2V10C248.7,7.2,246.4,4.8,243.5,4.8z"/></g></svg>
                                    <span>{{ __('Training Contents') }}</span>
                                </a>
                            </li>
                        @endcan
                        @can('roles_access')
                            <li>
                                <a href="{{ route('roles.index') }}" class="{{ (request()->is('roles*') ? 'mm-active' : '') }}">
                                    <!-- <i class="fa fa-tasks"></i>  -->
                                    <svg xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 24 24" viewBox="0 0 24 24" fill="black" width="24px" height="24px"><rect id="menu-svg" fill="none" height="24" width="24"/><path d="M22,5.18L10.59,16.6l-4.24-4.24l1.41-1.41l2.83,2.83l10-10L22,5.18z M12,20c-4.41,0-8-3.59-8-8s3.59-8,8-8 c1.57,0,3.04,0.46,4.28,1.25l1.45-1.45C16.1,2.67,14.13,2,12,2C6.48,2,2,6.48,2,12s4.48,10,10,10c1.73,0,3.36-0.44,4.78-1.22 l-1.5-1.5C14.28,19.74,13.17,20,12,20z M19,15h-3v2h3v3h2v-3h3v-2h-3v-3h-2V15z"/></svg>
                                    {{__('Roles')}}
                                </a>
                            </li>
                        @endcan
                        @can('permissions_access')
                            <li>
                                <a href="{{ route('permissions.index') }}" class="{{ (request()->is('permissions*') ? 'mm-active' : '') }}">
                                    <!-- <i class="fa fa-lock"></i> -->
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="black" width="24px" height="24px"><path d="M0 0h24v24H0z" fill="none"/><path id="menu-svg" d="M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zm-6 9c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2zm3.1-9H8.9V6c0-1.71 1.39-3.1 3.1-3.1 1.71 0 3.1 1.39 3.1 3.1v2z"/></svg>
                                    {{__('Permissions')}}
                                </a>
                            </li>
                        @endcan
                        @can('trash_access')
                            <li class="sub-menu">
                                <a class="{{ (request()->is('trash*') ? 'active' : '') }}" href="{{ route('trash.index') }}">
                                    <!-- <i class="fa fa-trash"></i> -->
                                    <svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24"><path d="M0 0h24v24H0z" fill="none"/><path id="menu-svg" d="M19 4h-3.5l-1-1h-5l-1 1H5v2h14zM6 7v12c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6zm8 7v4h-4v-4H8l4-4 4 4h-2z"/></svg>
                                    <span>{{ __('Trash') }}</span>
                                </a>
                            </li>
                        @endcan
                    </ul>
                    <!-- sidebar menu end-->
                </div>
            </aside>
        @endif

        <main class="py-4">
            <section @if(Auth::guard('admin')->check() || Auth::guard('client')->check()) id="main-content" @endif>
                @yield('content')
            </section>
        </main>
    </div>

    @auth
        @can('support_access')
            <div class="support-model d-none">
                <form action="{{ route('support.store') }}" method="POST">
                    @csrf

                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="border-head h3">
                                        {{ __('Support Forum') }}
                                        <br />
                                        <span class="h6">{{ __('Tell us your issue in brief please.') }}</span>
                                    </div>
                                </div>
                            </div>

                            <br />
                            <div class="row">
                                <div class="col-md-{{ !empty(auth()->user()->surname) ? '6' : '12' }}">
                                    <label>{{ __('Name') }}</label>
                                    <input type="text" disabled="" value="{{ auth()->user()->name }}" class="form-control" />
                                </div>

                                @if (!empty(auth()->user()->surname))
                                    <div class="col-md-6">
                                        <label>{{ __('Surname') }}</label>
                                        <input type="text" disabled="" value="{{ auth()->user()->surname }}" class="form-control" />
                                    </div>
                                @endif
                            </div>

                            <br />
                            <div class="row">
                                <div class="col-md-12">
                                    <label>{{ __('Email') }}</label>
                                    <input type="email" disabled="" value="{{ auth()->user()->email }}" class="form-control" />
                                </div>
                            </div>

                            <br />
                            <div class="row">
                                <div class="col-md-12">
                                    <label>{{ __('Your Query') }}</label>
                                    <textarea class="form-control" name="query" placeholder="{{ __('Describe here your queries.') }}" autofocus="true"></textarea>
                                </div>
                            </div>

                            <br />
                            <div class="row">
                                <div class="col-md-12">
                                    <input type="hidden" value="{{ auth()->user()->fullName }}" name="name" />
                                    <input type="hidden" value="{{ auth()->user()->email }}" name="email" />
                                    <button class="btn btn-primary"><i class="fa fa-save"></i></button>
                                    <button type="button" class="btn btn-secondary btn-default bootbox-cancel close-model" style="float: none;"><i class="fa fa-close"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        @endcan
    @endauth

    <footer>
        <div class="footer-godaddy text-right">
            @auth
                @can('support_access')
                    <!-- <button class="btn btn-default support" title="{{ __('Support') }}" data-html="support-model"><i class="fa fa-question-circle"></i> {{ __('Support') }}</button> -->
                @endcan
            @endauth
            <span id="siteseal"></span>
        </div>
    </footer>
</body>
        <script type="text/javascript">
            @stack('custom_scripts')
        </script>
</html>
