<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @php
        if (!Auth::check()) {
            // dd("Please login first");
            // redirect(route('welcome'));
        }
        $setting = DB::table('sitesettings')->first();
    @endphp

    <title>{{ !empty($setting) ? $setting->name : 'Title' }} -
        {{ !empty($setting) ? $setting->slogan : 'Slogan' }}</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" />

    <!-- Library / Plugin Css Build -->
    <link rel="stylesheet" href="{{ asset('assets/css/core/libs.min.css') }}" />

    <!-- Aos Animation Css -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/aos/dist/aos.css') }}" />

    <!-- Hope Ui Design System Css -->
    <link rel="stylesheet" href="{{ asset('assets/css/hope-ui.min.css?v=5.0.0') }}" />

    <!-- Custom Css -->
    <link rel="stylesheet" href="{{ asset('assets/css/custom.min.css?v=5.0.0') }}" />

    <!-- Customizer Css -->
    <link rel="stylesheet" href="{{ asset('assets/css/customizer.min.css?v=5.0.0') }}" />

    <!-- RTL Css -->
    <link rel="stylesheet" href="{{ asset('assets/css/rtl.min.css?v=5.0.0') }}" />
    <link rel="stylesheet" href="{{ asset('fonts/iconmind.css') }}">

    @include('layouts/datatables_css')



    <style>
        .btn:hover {
            color: #ffffff !important;
            background-color: #1f9303 !important;
            border-color: #1f9303 !important;
        }

        .nav-item {
            margin-top: 7px !important;
        }

        * {
            padding: 0;
            margin: 0;
        }

        .border {
            border: var(--bs-border-width) var(--bs-border-style) #4e4e4e !important;
        }

        .btn {
            padding: 2px 7px !important;

        }

        .sidebar.sidebar-default .nav-link:not(.static-item).active,
        .sidebar.sidebar-default .nav-link:not(.static-item)[aria-expanded="true"] {
            background: white;
            border-left: 5px solid #56d53b;
            -webkit-box-shadow: none;
            box-shadow: none;
            color: #000;
            height: 53px;
        }

        .sidebar.navs-rounded-all .sidebar-body .nav-item .nav-link {
            -webkit-border-radius: 0.25rem;
            border-radius: 0.25rem;
            height: 53px;
        }

        .sidebar .sidebar-body {
            padding-right: 0rem;
            overflow: hidden;
        }

        .form-control {
            border: 1px solid #808080;
        }

        .sidebar.sidebar-default .nav-link:not(.static-item).active, .sidebar.sidebar-default .nav-link:not(.static-item)[aria-expanded="true"] {
            background: #b2eda6;
            border-left: 5px solid #56d53b;
            -webkit-box-shadow: none;
            box-shadow: none;
            color: #000000;
            height: 53px;
        }

        .text-dark {
            --bs-text-opacity: 1;
            color: rgb(53 46 46) !important;
        }

        .text-muted {
            --bs-text-opacity: 1;
            color: rgb(40 41 44 / 75%) !important;
        }

        .card-body {
            -webkit-box-flex: 1;
            -webkit-flex: 1 1 auto;
            -ms-flex: 1 1 auto;
            flex: 1 1 auto;
            padding: var(--bs-card-spacer-y) var(--bs-card-spacer-x);
            color: var(--bs-card-color);
            font-size: 12px;
        }

        .page-item.active .page-link {
            z-index: 1;
            color: #fff;
            background-color: #39ca16;
            border-color: #39ca16;
        }
        .sidebar.sidebar-default
    .nav-link:not(.static-item):hover:not(.active):not([aria-expanded="true"]) {
    background: #b2eda6;
    color: #000000;
    -webkit-box-shadow: unset;
    box-shadow: unset;
}
        label {
    display: inline-block;
    font-size: 16px;
    color: #3b3b3b;
    font-weight: 300;
}
    </style>

</head>



<body style="background: #f2fff0;">
    <!-- loader Start -->
    {{-- <div id="loading">
        <div class="loader simple-loader">
            <div class="loader-body"></div>
        </div>
    </div> --}}

    <aside class="sidebar sidebar-default sidebar-white sidebar-base navs-rounded-all">
        <div class="sidebar-header d-flex align-items-center justify-content-start"
            style="background: #b2eda6;height: 58px;border-bottom: 2px solid;">
            <a href="{{ url('/') }}" class="navbar-brand">
                <div class="logo-main">
                    <img src="{{ !empty($setting) ? asset($setting->logo) : 'assets/images/Picture1.jpg' }} "
                        class="img-fluid" alt="logo" style="height: 40px;">
                </div>
                <span class="logo-title">উপ-আনুষ্ঠানিক <br> শিক্ষা বোর্ড, ঢাকা</span>
            </a>
            <div class="sidebar-toggle" data-toggle="sidebar" data-active="true">
                <i class="icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path d="M4.25 12.2744L19.25 12.2744" stroke="currentColor" stroke-width="1.5"
                            stroke-linecap="round" stroke-linejoin="round"></path>
                        <path d="M10.2998 18.2988L4.2498 12.2748L10.2998 6.24976" stroke="currentColor"
                            stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                </i>
            </div>
        </div>
        <div class="sidebar-body pt-0 data-scrollbar">
            <div class="sidebar-list">
                <!-- Sidebar Menu Start -->
                <ul class="navbar-nav iq-main-menu" id="sidebar-menu">
                    @include('layouts/leftmenu')
                </ul>
                <!-- Sidebar Menu End -->
            </div>
        </div>
        <div class="sidebar-footer"
            style="bottom: 0;position: absolute;border: 1px solid #2dff00;width: 100%;padding: 7px;color: black;font-size: 13px;">
            Developed by - <a href="https://mysoftheaven.com" target="_blank">Mysoftheaven (BD) Ltd.</a>
        </div>
    </aside>
    <main class="main-content">
        <div class="position-relative iq-banner">
            <!--Nav Start-->
            <nav class="nav navbar navbar-expand-xl navbar-light iq-navbar" style="background: #b2eda6;padding: 0;">
                <div class="container-fluid navbar-inner">
                    <a href="../dashboard/index.html" class="navbar-brand">
                        <h4 class="logo-title"></h4>
                    </a>
                    <div class="sidebar-toggle" data-toggle="sidebar" data-active="true">
                        <i class="icon">
                            <svg width="20px" class="icon-20" viewBox="0 0 24 24">
                                <path fill="currentColor"
                                    d="M4,11V13H16L10.5,18.5L11.92,19.92L19.84,12L11.92,4.08L10.5,5.5L16,11H4Z" />
                            </svg>
                        </i>
                    </div>
                    <!-- Navbar Toggle Button -->
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                        data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                        aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon">
                            <span class="mt-2 navbar-toggler-bar bar1"></span>
                            <span class="navbar-toggler-bar bar2"></span>
                            <span class="navbar-toggler-bar bar3"></span>
                        </span>
                    </button>

                    <!-- Navbar Content -->
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="mb-2 navbar-nav ms-auto align-items-center navbar-list mb-lg-0">
                            <li class="nav-item dropdown custom-drop">
                                <a class="py-0 nav-link d-flex align-items-center" href="#" id="navbarDropdown"
                                    role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <img src="{{ asset('assets/images/avatars/01.png') }}" alt="User-Profile"
                                        class="theme-color-default-img img-fluid avatar avatar-50 avatar-rounded" />
                                    <div class="caption ms-3 d-none d-md-block">
                                        <h6 class="mb-0 caption-title">
                                            {{ Auth::user()->name ?? 'Guest' }}
                                        </h6>
                                        <p class="mb-0 caption-sub-title">
                                            {{ Auth::user()->role ?? 'User' }}
                                        </p>
                                    </div>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end profile-dropdown"
                                    aria-labelledby="navbarDropdown">
                                    <div class="profile-dropdown-body p-3 bg-white rounded shadow-lg"
                                        style="width: 269px;">
                                        <div class="d-flex align-items-center mb-3">
                                            <img src="{{ asset('assets/images/avatars/01.png') }}" alt="User Profile"
                                                class="img-fluid rounded-circle me-2"
                                                style="width: 50px; height: 50px;" />
                                            <div>
                                                <h6 class="mb-0">Hi, {{ Auth::user()->name }}</h6>
                                                <small class="text-muted">{{ Auth::user()->email }}</small>
                                            </div>
                                        </div>
                                        <hr>
                                        <a href="{{ route('logout') }}" class="btn btn-sm btn-danger w-100"
                                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                            <i class="bi bi-box-arrow-right me-1"></i> Logout
                                        </a>
                                        <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                            style="display: none;">
                                            @csrf
                                        </form>
                                    </div>

                                </div>
                            </li>
                        </ul>
                    </div>

                </div>
            </nav>
            <!-- Nav Header Component End -->
            <!--Nav End-->
            <div style="margin: 8px;height: 100%;width: 98%;">
                @yield('content')
            </div>
        </div>
        <!-- Footer Section Start -->
        {{-- <footer class="footer" style="position: absolute;bottom: 0px;">
            <div class="footer-body">
                <ul class="left-panel list-inline mb-0 p-0">
                    <li class="list-inline-item">
                        <a href="../dashboard/extra/privacy-policy.html">Privacy Policy</a>
                    </li>
                    <li class="list-inline-item">
                        <a href="../dashboard/extra/terms-of-service.html">Terms of Use</a>
                    </li>
                </ul>
                <div class="right-panel">
                    ©
                    <script>
                        document.write(new Date().getFullYear());
                    </script>
                </div>
            </div>
        </footer> --}}
        <!-- Footer Section End -->
    </main>
    <!-- Wrapper End-->

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
        integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
        integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous">
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
        integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous">
    </script>
    <script src="{{ asset('assets/js/core/libs.min.js') }}"></script>

    <!-- External Library Bundle Script -->
    <script src="{{ asset('assets/js/core/external.min.js') }}"></script>

    <!-- Widgetchart Script -->
    <script src="{{ asset('assets/js/charts/widgetcharts.js') }}"></script>

    <!-- mapchart Script -->
    <script src="{{ asset('assets/js/charts/vectore-chart.js') }}"></script>
    <script src="{{ asset('assets/js/charts/dashboard.js') }}"></script>

    <!-- fslightbox Script -->
    <script src="{{ asset('assets/js/plugins/fslightbox.js') }}"></script>

    <!-- Settings Script -->
    <script src="{{ asset('assets/js/plugins/setting.js') }}"></script>

    <!-- Slider-tab Script -->
    <script src="{{ asset('assets/js/plugins/slider-tabs.js') }}"></script>

    <!-- Form Wizard Script -->
    <script src="{{ asset('assets/js/plugins/form-wizard.js') }}"></script>

    <!-- AOS Animation Plugin-->
    <script src="{{ asset('assets/vendor/aos/dist/aos.js') }}"></script>

    <!-- App Script -->
    <script src="{{ asset('assets/js/hope-ui.js') }}" defer></script>

    @include('layouts/datatables_js')

    @yield('footer_scripts')


</body>

</html>
