<!doctype html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <title><?php echo e(config('app.name', 'Laravel')); ?></title>

    <!-- Scripts -->
    <script difer src="<?php echo e(asset('js/app.js')); ?>" defer></script>
    <script difer src="<?php echo e(asset('js/jquery.nicescroll.js')); ?>" defer></script>
    <script difer src="<?php echo e(asset('js/jquery.dcjqaccordion.2.7.js')); ?>" defer></script>
    <script difer src="<?php echo e(asset('js/jquery.scrollTo.min.js')); ?>" defer></script>
    <script difer src="<?php echo e(asset('js/scripts.js')); ?>" defer></script>
    <script difer src="<?php echo e(asset('js/bootstrap.min.js')); ?>" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Ruda:400,700,900" rel="stylesheet">

    <!-- Styles -->
    <link href="<?php echo e(asset('css/app.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(asset('css/bootstrap.min.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(asset('css/font-awesome.min.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(asset('css/style.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(asset('css/style-responsive.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(asset('css/custom.css')); ?>" rel="stylesheet">
</head>
<body>
    <div id="app">
        <!-- <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="<?php echo e(url('/')); ?>">
                    <?php echo e(config('app.name', 'Laravel')); ?>

                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="<?php echo e(__('Toggle navigation')); ?>">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar --><!--
                    <ul class="navbar-nav mr-auto">

                    </ul>

                    <!-- Right Side Of Navbar --><!--
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links --><!--
                        <?php if(auth()->guard()->guest()): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="<?php echo e(route('login')); ?>"><?php echo e(__('Login')); ?></a>
                            </li>
                            <?php if(Route::has('register')): ?>
                                <li class="nav-item">
                                    <a class="nav-link" href="<?php echo e(route('register')); ?>"><?php echo e(__('Register')); ?></a>
                                </li>
                            <?php endif; ?>
                        <?php else: ?>
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    <?php echo e(Auth::user()->name); ?> <span class="caret"></span>
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="<?php echo e(route('logout')); ?>"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        <?php echo e(__('Logout')); ?>

                                    </a>

                                    <form id="logout-form" action="<?php echo e(route('logout')); ?>" method="POST" style="display: none;">
                                        <?php echo csrf_field(); ?>
                                    </form>
                                </div>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </nav> -->
        <header class="header black-bg">
            <?php if(auth()->guard()->check()): ?>
                <div class="sidebar-toggle-box">
                    <div class="fa fa-bars tooltips" data-placement="right" data-original-title="Toggle Navigation"></div>
                </div>
            <?php endif; ?>
            <!--logo start-->
            <a href="<?php echo e(route("dashboard")); ?>" class="logo"><b><?php echo e(config('app.name', 'Brad Davidson')); ?></b></a>
            <!--logo end-->
            <!-- <div class="nav notify-row" id="top_menu">
                <!--  notification start --><!--
                <ul class="nav top-menu">
                    <!-- settings start --><!--
                    <li class="dropdown">
                        <a data-toggle="dropdown" class="dropdown-toggle" href="index.html#">
                        <i class="fa fa-tasks"></i>
                        <span class="badge bg-theme">4</span>
                        </a>
                        <ul class="dropdown-menu extended tasks-bar">
                            <div class="notify-arrow notify-arrow-green"></div>
                            <li>
                                <p class="green">You have 4 pending tasks</p>
                            </li>
                            <li>
                                <a href="index.html#">
                                    <div class="task-info">
                                        <div class="desc">Dashio Admin Panel</div>
                                        <div class="percent">40%</div>
                                    </div>
                                    <div class="progress progress-striped">
                                        <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 40%">
                                            <span class="sr-only">40% Complete (success)</span>
                                        </div>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <a href="index.html#">
                                    <div class="task-info">
                                        <div class="desc">Database Update</div>
                                        <div class="percent">60%</div>
                                    </div>
                                    <div class="progress progress-striped">
                                        <div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 60%">
                                            <span class="sr-only">60% Complete (warning)</span>
                                        </div>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <a href="index.html#">
                                    <div class="task-info">
                                        <div class="desc">Product Development</div>
                                        <div class="percent">80%</div>
                                    </div>
                                    <div class="progress progress-striped">
                                        <div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100" style="width: 80%">
                                            <span class="sr-only">80% Complete</span>
                                        </div>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <a href="index.html#">
                                    <div class="task-info">
                                        <div class="desc">Payments Sent</div>
                                        <div class="percent">70%</div>
                                    </div>
                                    <div class="progress progress-striped">
                                        <div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100" style="width: 70%">
                                            <span class="sr-only">70% Complete (Important)</span>
                                        </div>
                                    </div>
                                </a>
                            </li>
                            <li class="external">
                                <a href="#">See All Tasks</a>
                            </li>
                        </ul>
                    </li>
                    <!-- settings end --><!--
                    <!-- inbox dropdown start--><!--
                    <li id="header_inbox_bar" class="dropdown">
                        <a data-toggle="dropdown" class="dropdown-toggle" href="index.html#">
                        <i class="fa fa-envelope-o"></i>
                        <span class="badge bg-theme">5</span>
                        </a>
                        <ul class="dropdown-menu extended inbox">
                            <div class="notify-arrow notify-arrow-green"></div>
                            <li>
                                <p class="green">You have 5 new messages</p>
                            </li>
                            <li>
                                <a href="index.html#">
                                <span class="photo"><img alt="avatar" src="img/ui-zac.jpg"></span>
                                <span class="subject">
                                <span class="from">Zac Snider</span>
                                <span class="time">Just now</span>
                                </span>
                                <span class="message">
                                Hi mate, how is everything?
                                </span>
                                </a>
                            </li>
                            <li>
                                <a href="index.html#">
                                <span class="photo"><img alt="avatar" src="img/ui-divya.jpg"></span>
                                <span class="subject">
                                <span class="from">Divya Manian</span>
                                <span class="time">40 mins.</span>
                                </span>
                                <span class="message">
                                Hi, I need your help with this.
                                </span>
                                </a>
                            </li>
                            <li>
                                <a href="index.html#">
                                <span class="photo"><img alt="avatar" src="img/ui-danro.jpg"></span>
                                <span class="subject">
                                <span class="from">Dan Rogers</span>
                                <span class="time">2 hrs.</span>
                                </span>
                                <span class="message">
                                Love your new Dashboard.
                                </span>
                                </a>
                            </li>
                            <li>
                                <a href="index.html#">
                                <span class="photo"><img alt="avatar" src="img/ui-sherman.jpg"></span>
                                <span class="subject">
                                <span class="from">Dj Sherman</span>
                                <span class="time">4 hrs.</span>
                                </span>
                                <span class="message">
                                Please, answer asap.
                                </span>
                                </a>
                            </li>
                            <li>
                                <a href="index.html#">See all messages</a>
                            </li>
                        </ul>
                    </li>
                    <!-- inbox dropdown end --><!--
                    <!-- notification dropdown start--><!--
                    <li id="header_notification_bar" class="dropdown">
                        <a data-toggle="dropdown" class="dropdown-toggle" href="index.html#">
                        <i class="fa fa-bell-o"></i>
                        <span class="badge bg-warning">7</span>
                        </a>
                        <ul class="dropdown-menu extended notification">
                            <div class="notify-arrow notify-arrow-yellow"></div>
                            <li>
                                <p class="yellow">You have 7 new notifications</p>
                            </li>
                            <li>
                                <a href="index.html#">
                                <span class="label label-danger"><i class="fa fa-bolt"></i></span>
                                Server Overloaded.
                                <span class="small italic">4 mins.</span>
                                </a>
                            </li>
                            <li>
                                <a href="index.html#">
                                <span class="label label-warning"><i class="fa fa-bell"></i></span>
                                Memory #2 Not Responding.
                                <span class="small italic">30 mins.</span>
                                </a>
                            </li>
                            <li>
                                <a href="index.html#">
                                <span class="label label-danger"><i class="fa fa-bolt"></i></span>
                                Disk Space Reached 85%.
                                <span class="small italic">2 hrs.</span>
                                </a>
                            </li>
                            <li>
                                <a href="index.html#">
                                <span class="label label-success"><i class="fa fa-plus"></i></span>
                                New User Registered.
                                <span class="small italic">3 hrs.</span>
                                </a>
                            </li>
                            <li>
                                <a href="index.html#">See all notifications</a>
                            </li>
                        </ul>
                    </li>
                    <!-- notification dropdown end --><!--
                </ul>
                <!--  notification end --><!--
            </div> -->
            <?php if(auth()->guard()->check()): ?>
                <div class="top-menu">
                    <ul class="nav pull-right top-menu">
                        <li>
                            <a class="logout" href="<?php echo e(route('logout')); ?>"
                               onclick="event.preventDefault();
                                             document.getElementById('logout-form').submit();">
                                     <?php echo e(__('Logout')); ?>

                             </a>

                            <form id="logout-form" action="<?php echo e(route('logout')); ?>" method="POST" style="display: none;">
                                <?php echo csrf_field(); ?>
                            </form>
                         </li>
                    </ul>
                </div>
            <?php endif; ?>
        </header>

        <?php if(auth()->guard()->check()): ?>
            <aside>
                <div id="sidebar" class="nav-collapse ">
                    <!-- sidebar menu start-->
                    <ul class="sidebar-menu" id="nav-accordion">
                        <p class="centered"><a href="profile.html"><img src="<?php echo e(asset('img/friends/fr-05.jpg')); ?>" class="img-circle" width="80"></a></p>
                        <h5 class="centered"><?php echo e(Auth::user()->name); ?></h5>
                        <li class="mt">
                            <a class="<?php echo e((request()->is('/') ? 'active' : '')); ?>" href="<?php echo e(route('dashboard')); ?>">
                                <i class="fa fa-dashboard"></i>
                                <span><?php echo e(__('Dashboard')); ?></span>
                            </a>
                        </li>
                        <li class="sub-menu">
                            <a class="<?php echo e((request()->is('items*') ? 'active' : '')); ?>" href="<?php echo e(route('items.index')); ?>">
                                <i class="fa fa-object-group"></i>
                                <span><?php echo e(__('Items')); ?></span>
                            </a>
                        </li>
                        <li class="sub-menu">
                            <a class="<?php echo e((request()->is('folders*') ? 'active' : '')); ?>" href="javascript:;">
                                <i class="fa fa-users"></i>
                                <span><?php echo e(__('Folders')); ?></span>
                            </a>
                        </li>
                        <li class="sub-menu">
                            <a class="<?php echo e((request()->is('stock/levels*') ? 'active' : '')); ?>" href="javascript:;">
                                <i class="fa fa-database"></i>
                                <span><?php echo e(__('Stock Levels')); ?></span>
                            </a>
                        </li>
                        <li class="sub-menu">
                            <a class="<?php echo e((request()->is('stock/values*') ? 'active' : '')); ?>" href="javascript:;">
                                <i class="fa fa-money"></i>
                                <span><?php echo e(__('Stock Values')); ?></span>
                            </a>
                        </li>
                    </ul>
                    <!-- sidebar menu end-->
                </div>
            </aside>
        <?php endif; ?>

        <main class="py-4">
            <section <?php if(auth()->guard()->check()): ?> id="main-content" <?php endif; ?>>
                <?php echo $__env->yieldContent('content'); ?>
            </section>
        </main>
    </div>
</body>
</html>
<?php /**PATH E:\Jaydeep Mor\Work\Jessica\BRAD DAVIDSON\brad_davidson\resources\views/layouts/app.blade.php ENDPATH**/ ?>