<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>@yield('title', 'Cinema Management System')</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
</head>
<body class="sb-nav-fixed">
    <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
        <a class="navbar-brand ps-3" href="{{ route('admin.dashboard') }}">
            <i class="fas fa-film me-2"></i>Cinema Management
        </a>
        <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!">
            <i class="fas fa-bars"></i>
        </button>
        <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown">
                    <i class="fas fa-user fa-fw"></i>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="#!">Settings</a></li>
                    <li><a class="dropdown-item" href="#!">Reports</a></li>
                    <li><hr class="dropdown-divider" /></li>
                    <li><a class="dropdown-item" href="#!">Logout</a></li>
                </ul>
            </li>
        </ul>
    </nav>
    
    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                <div class="sb-sidenav-menu">
                    <div class="nav">
                        <div class="sb-sidenav-menu-heading">Core</div>
                        <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                            <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                            Dashboard
                        </a>
                        
                        <div class="sb-sidenav-menu-heading">Cinema Management</div>
                        <a class="nav-link {{ request()->routeIs('movies.*') ? 'active' : '' }}" href="{{ route('movies.index') }}">
                            <div class="sb-nav-link-icon"><i class="fas fa-film"></i></div>
                            Movies
                        </a>
                        <a class="nav-link {{ request()->routeIs('theaters.*') ? 'active' : '' }}" href="{{ route('theaters.index') }}">
                            <div class="sb-nav-link-icon"><i class="fas fa-couch"></i></div>
                            Theaters
                        </a>
                        <a class="nav-link {{ request()->routeIs('showtimes.*') ? 'active' : '' }}" href="{{ route('showtimes.index') }}">
                            <div class="sb-nav-link-icon"><i class="fas fa-clock"></i></div>
                            Showtimes
                        </a>
                        
                        <div class="sb-sidenav-menu-heading">Quick Actions</div>
                        <a class="nav-link" href="{{ route('movies.create') }}">
                            <div class="sb-nav-link-icon"><i class="fas fa-plus"></i></div>
                            Add Movie
                        </a>
                        <a class="nav-link" href="{{ route('theaters.create') }}">
                            <div class="sb-nav-link-icon"><i class="fas fa-plus"></i></div>
                            Add Theater
                        </a>
                    </div>
                </div>
                <div class="sb-sidenav-footer">
                    <div class="small">Logged in as:</div>
                    Cinema Manager
                </div>
            </nav>
        </div>
        
        <div id="layoutSidenav_content">
            <main>
                @yield('content')
            </main>
            
            <footer class="py-4 bg-light mt-auto">
                <div class="container-fluid px-4">
                    <div class="d-flex align-items-center justify-content-between small">
                        <div class="text-muted">Copyright &copy; Cinema Management System 2024</div>
                        <div>
                            <a href="#">Privacy Policy</a>
                            &middot;
                            <a href="#">Terms &amp; Conditions</a>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/scripts.js') }}"></script>
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js"></script>
</body>
</html>