<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #3498db;
            --secondary-color: #2ecc71;
            --background-color: #f8f9fa;
            --text-color: #333;
        }

        body {
            font-family: 'Roboto', sans-serif;
            background-color: var(--background-color);
            color: var(--text-color);
        }

        .bg-dark {
            background-color: #2c3e50 !important;
        }

        .nav-link {
            color: #fff;
            display: flex;
            align-items: center;
            font-size: 0.95rem;
            transition: all 0.3s ease;
        }

        .nav-link:hover {
            background-color: var(--primary-color);
            color: white;
            transform: translateX(5px);
        }

        .nav-link.active {
            background-color: var(--secondary-color);
        }

        .nav-link i.bi-chevron-right,
        .nav-link i.bi-chevron-down {
            margin-left: auto;
            transition: transform 0.3s ease;
        }

        .sidebar-submenu {
            padding-left: 1rem;
        }

        .sidebar-submenu .nav-link {
            padding-left: 1rem;
            font-size: 0.9rem;
        }

        .main-menu-item {
            margin-bottom: 10px;
        }

        .dropdown-toggle::after {
            margin-left: 10px;
        }

        #userDropdown {
            position: relative;
        }

        #userDropdown:hover::after {
            content: attr(title);
            font-size: 0.8em;
            opacity: 0.8;
            position: absolute;
            bottom: -20px;
            left: 0;
            background-color: #2c3e50;
            padding: 5px;
            border-radius: 3px;
            white-space: nowrap;
            z-index: 1000;
        }

        #userDropdown:hover {
            background-color: var(--primary-color);
        }

        .sticky-sidebar {
            position: sticky;
            top: 0;
            height: 100vh;
            overflow-y: auto;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .fs-5 {
            font-size: 1.25rem !important;
            font-weight: 500;
        }

        .collapse {
            transition: height 0.3s ease;
        }

        .col.py-3 {
            padding: 2rem;
        }

        @media (max-width: 768px) {
            .sticky-sidebar {
                position: static;
                height: auto;
            }

            .col.py-3 {
                padding: 1rem;
            }
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="row flex-nowrap">
            <!-- Sidebar -->
            <div class="col-auto col-md-3 col-xl-2 px-sm-2 px-0 bg-dark sticky-sidebar">
                <div class="d-flex flex-column align-items-center align-items-sm-start px-3 pt-2 text-white min-vh-100">
                    <a href="/"
                        class="d-flex align-items-center pb-3 mb-md-0 me-md-auto text-white text-decoration-none">
                        <span class="fs-5 d-none d-sm-inline">IT Support Dashboard</span>
                    </a>
                    @auth
                        <ul class="nav nav-pills flex-column mb-sm-auto mb-0 align-items-center align-items-sm-start"
                            id="menu">
                            @if (auth()->user()->role === 'admin')
                                <li class="nav-item main-menu-item">
                                    <a href="{{ route('hotel.chart') }}" class="nav-link align-middle px-0">
                                        <i class="bi bi-house"></i> <span class="ms-1 d-none d-sm-inline">Home</span>
                                    </a>
                                </li>
                            @endif
                            <li class="nav-item main-menu-item">
                                <a href="{{ route('reportIssue') }}" class="nav-link px-0 align-middle">
                                    <i class="bi bi-clipboard"></i> <span class="ms-1 d-none d-sm-inline">Report
                                        Issue</span>
                                </a>
                            </li>
                            @if (auth()->user()->role === 'admin')
                                <li class="nav-item main-menu-item">
                                    <a href="{{ route('filter.form') }}" class="nav-link px-0 align-middle">
                                        <i class="bi bi-file-earmark-pdf"></i><span class="ms-1 d-none d-sm-inline">Filter
                                            Report PDF</span>
                                    </a>
                                </li>
                                <li class="main-menu-item">
                                    <a href="#submenu2" data-bs-toggle="collapse" class="nav-link px-0 align-middle">
                                        <i class="bi bi-calendar-check"></i>
                                        <span class="ms-1 d-none d-sm-inline">Daily Checklist</span>
                                        <i class="bi bi-chevron-right ms-auto px-2"></i>
                                    </a>
                                    <ul class="collapse nav flex-column ms-1" id="submenu2" data-bs-parent="#menu">
                                        <li class="w-100">
                                            <a href="/daily/search" class="nav-link px-0">
                                                <span class="d-none d-sm-inline px-3">Search Daily</span>
                                            </a>
                                        </li>
                                        <li class="w-100">
                                            <a href="/daily/hotels/tlcmn" class="nav-link px-0">
                                                <span class="d-none d-sm-inline px-3">TLCMN</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="/daily/hotels/ehcm" class="nav-link px-0">
                                                <span class="d-none d-sm-inline px-3">EHCM</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="/daily/hotels/uncm" class="nav-link px-0">
                                                <span class="d-none d-sm-inline px-3">UNCM</span>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="main-menu-item">
                                    <a href="#submenu3" data-bs-toggle="collapse" class="nav-link px-0 align-middle">
                                        <i class="bi bi-gear"></i>
                                        <span class="ms-1 d-none d-sm-inline">Structure Settings</span>
                                        <i class="bi bi-chevron-right ms-auto px-2"></i>
                                    </a>
                                    <ul class="collapse nav flex-column ms-1" id="submenu3" data-bs-parent="#menu">
                                        <li class="w-100">
                                            <a href="{{ route('structure.buildings') }}" class="nav-link px-0">
                                                <span class="d-none d-sm-inline px-3">Buildings</span>
                                            </a>
                                        </li>
                                        <li class="w-100">
                                            <a href="{{ route('structure.categories') }}" class="nav-link px-0">
                                                <span class="d-none d-sm-inline px-3">Categories Issues</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ route('structure.departments') }}" class="nav-link px-0">
                                                <span class="d-none d-sm-inline px-3">Departments</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ route('users.index') }}" class="nav-link px-0"><span
                                                    class="d-none d-sm-inline px-3">Users</span></a>
                                        </li>
                                    </ul>
                                </li>
                            @endif
                            <li class="nav-item main-menu-item">
                                <a href="#userSubmenu" data-bs-toggle="collapse" class="nav-link px-0 align-middle"
                                    id="userDropdown"
                                    title="{{ Auth::user()->name }}{{ Auth::user()->department ? ', ' . Auth::user()->department->name : '' }}">
                                    <i class="bi bi-person"></i>
                                    <span class="ms-1 d-none d-sm-inline">
                                        {{ Auth::user()->name }}
                                    </span>
                                    <i class="bi bi-chevron-right ms-auto"></i>
                                </a>
                                <ul class="collapse nav flex-column ms-1" id="userSubmenu" data-bs-parent="#menu">
                                    <li class="w-100">
                                        <a href="{{ route('logout') }}" class="nav-link px-0"
                                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                            <span class="d-none d-sm-inline px-3">Sign out</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    @endauth
                </div>
            </div>

            <!-- Main content area -->
            <div class="col py-3">
                @yield('content')
            </div>
        </div>
    </div>

    <!-- Logout form -->
    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
        @csrf
    </form>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const dropdownToggles = document.querySelectorAll('[data-bs-toggle="collapse"]');

            dropdownToggles.forEach(toggle => {
                const collapseId = toggle.getAttribute('href');
                const collapse = document.querySelector(collapseId);

                collapse.addEventListener('show.bs.collapse', function() {
                    const icon = toggle.querySelector('.bi-chevron-right');
                    if (icon) {
                        icon.style.transform = 'rotate(90deg)';
                    }

                    dropdownToggles.forEach(otherToggle => {
                        if (otherToggle !== toggle) {
                            const targetId = otherToggle.getAttribute('href');
                            const targetCollapse = document.querySelector(targetId);
                            const bsCollapse = bootstrap.Collapse.getInstance(
                                targetCollapse);
                            if (bsCollapse && bsCollapse._isShown()) {
                                bsCollapse.hide();
                            }
                        }
                    });
                });

                collapse.addEventListener('hide.bs.collapse', function() {
                    const icon = toggle.querySelector('.bi-chevron-right');
                    if (icon) {
                        icon.style.transform = 'rotate(0deg)';
                    }
                });
            });

            // Logout functionality
            const logoutForm = document.getElementById('logout-form');
            const logoutLink = document.querySelector('a[href="{{ route('logout') }}"]');

            if (logoutLink) {
                logoutLink.addEventListener('click', function(e) {
                    e.preventDefault();
                    logoutForm.submit();
                });
            }
        });
    </script>
</body>

</html>
