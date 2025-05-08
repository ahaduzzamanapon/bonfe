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
    <!-- Flatpickr CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

    <!-- Flatpickr JS -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />


    @include('layouts/datatables_css')



    <style>
        .btn:hover {
            color: #ffffff !important;
            background-color: #1f9303 !important;
            border-color: #1f9303 !important;
        }
        .btn-check:focus + .btn, .btn:focus {
    color: var(--bs-btn-hover-color);
    background-color: #05534c;
    border-color: var(--bs-btn-hover-border-color);
    outline: 0;
    -webkit-box-shadow: var(--bs-btn-focus-box-shadow);
    box-shadow: var(--bs-btn-focus-box-shadow);
}

        .select2-container .select2-selection--single {
            box-sizing: border-box;
            cursor: pointer;
            display: block;
            height: 38px;
            user-select: none;
            -webkit-user-select: none;
            padding: 4px;
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
            height: 40px;
        }

        .sidebar.navs-rounded-all .sidebar-body .nav-item .nav-link {
            -webkit-border-radius: 0.25rem;
            border-radius: 0.25rem;
            height: 40px;
        }

        .sidebar .sidebar-body {
            padding-right: 0rem;
            overflow: hidden;
        }

        .form-control {
            border: 1px solid #808080;
            padding: 1px 13px;
        }

        .card-breadcrumb {
            padding: 12px;
            cursor: pointer;
        }

        .sidebar.sidebar-default .nav-link:not(.static-item).active,
        .sidebar.sidebar-default .nav-link:not(.static-item)[aria-expanded="true"] {
            background: #c9e6a1;
            border-left: 5px solid #56d53b;
            -webkit-box-shadow: none;
            box-shadow: none;
            color: #000000;
            height: 40px;
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
            color: #000000;
            /* font-size: 13px!important; */
        }
        .btn-outline-primary:not(:disabled):not(.disabled).active, .btn-outline-primary:not(:disabled):not(.disabled):active, .show>.btn-outline-primary.dropdown-toggle {
            color: #fff;
            background-color: #8dc542;
            border-color: #683091;
        }

        .page-item.active .page-link {
            z-index: 1;
            color: #fff;
            background-color: #8dc641;
            border-color: #8dc641;
        }

        .table thead tr th {
            text-transform: Capitalize;
            letter-spacing: 0.2px;
            background-color: var(--bs-body-bg);
            color: #3a3a3a;
        }

        .sidebar.sidebar-default .nav-link:not(.static-item):hover:not(.active):not([aria-expanded="true"]) {
            background: #c9e6a1;
            color: #000000;
            -webkit-box-shadow: unset;
            box-shadow: unset;
        }

        .sidebar-base .nav-item:not(.static-item) {
            padding-left: 1rem;
        }

        label {
            display: inline-block;
            font-size: 16px;
            color: #000000;
            font-weight: 500;
        }

        .card {
            -webkit-box-shadow: 0 10px 30px 0 rgba(17, 38, 146, 0.05);
            box-shadow: 0 0px 4px 3px rgb(0 0 0 / 5%);
            /* margin-bottom: 2rem; */
            border: 1px solid #acacac;
            min-height: 88vh;
            margin: 0;
        }

        .table {
            font-size: 13px;
            overflow: auto;
        }

        .dropdown-item {
            border: 1px solid #828282;
            border-radius: 9px;
            margin: 4px;
        }

        .btn-primary:not(:disabled):not(.disabled).active,
        .btn-primary:not(:disabled):not(.disabled):active,
        .show>.btn-primary.dropdown-toggle {
            color: #fff;
            background-color: #8dc641;
            border-color: #8dc641;
        }

        .badge {
            font-size: 9px;
            padding: 8px;
        }

        .pagination {
            justify-content: flex-end;
        }

        .btn-primary {
            --bs-btn-bg: #0aa699;
            --bs-btn-border-color: #0aa699;
            --bs-btn-hover-bg: var(--bs-primary-hover-bg);
            --bs-btn-hover-border-color: var(--bs-primary-hover-border);
            --bs-btn-active-bg: var(--bs-primary-active-bg);
            --bs-btn-active-border-color: var(--bs-primary-active-border);
            --bs-btn-disabled-bg: #0aa699;
            --bs-btn-disabled-border-color: #0aa699;
        }

        .card .card-header {
            margin-bottom: 0;
            border: 0;
            padding-bottom: 0;
            -webkit-border-radius: var(--bs-border-radius-lg);
            padding: 7px;
            background: #8dc542;
            border-top: 3px solid red;
            margin: 0px;
            border-radius: 6px 6px 0px 0;
            color: white;
        }
    </style>

</head>



<body style="background: #d6e1ea;
;">
    <!-- loader Start -->
    {{-- <div id="loading">
        <div class="loader simple-loader">
            <div class="loader-body"></div>
        </div>
    </div> --}}

    @include('all_modal')

    <aside class="sidebar sidebar-default sidebar-white sidebar-base navs-rounded-all">
        <div class="sidebar-header d-flex align-items-center justify-content-start"
            style="background: #8dc641;height: 58px;border-bottom: 2px solid;">
            <a href="{{ url('/') }}" class="navbar-brand">
                <div class="logo-main">
                    <img src="{{ !empty($setting) ? asset($setting->logo) : 'assets/images/Picture1.jpg' }} "
                        class="img-fluid" alt="logo" style="height: 58px;width: 65px;">
                </div>
                <span class="logo-title">উপানুষ্ঠানিক <br> শিক্ষা বোর্ড, ঢাকা</span>
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
        <div class="sidebar-body pt-0">
            <div class="sidebar-list">
                <!-- Sidebar Menu Start -->
                <ul class="navbar-nav iq-main-menu" id="sidebar-menu" style="height: 82vh;overflow-y: scroll;">
                    @include('layouts/leftmenu')
                </ul>
                <!-- Sidebar Menu End -->
            </div>
        </div>
        <div class="sidebar-footer"
            style="bottom: 0;position: absolute;border: 1px solid #8dc641;width: 100%;padding: 7px;color: black;font-size: 12px;background: #8dc641;font-weight: bold;">
            Developed by - <a href="https://mysoftheaven.com" target="_blank" style="color: white;">Mysoftheaven (BD)
                Ltd.</a>
        </div>
    </aside>
    <main class="main-content">
        <div class="position-relative iq-banner">
            <!--Nav Start-->
            <nav class="nav navbar navbar-expand-xl navbar-light iq-navbar" style="background: #683091;padding: 0;">
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
                            <li class="nav-item dropdown">
                                <a href="#" class="nav-link" id="notification-drop" data-bs-toggle="dropdown">
                                    <svg class="icon-24" width="24" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg" style="color: aliceblue;">
                                        <path
                                            d="M19.7695 11.6453C19.039 10.7923 18.7071 10.0531 18.7071 8.79716V8.37013C18.7071 6.73354 18.3304 5.67907 17.5115 4.62459C16.2493 2.98699 14.1244 2 12.0442 2H11.9558C9.91935 2 7.86106 2.94167 6.577 4.5128C5.71333 5.58842 5.29293 6.68822 5.29293 8.37013V8.79716C5.29293 10.0531 4.98284 10.7923 4.23049 11.6453C3.67691 12.2738 3.5 13.0815 3.5 13.9557C3.5 14.8309 3.78723 15.6598 4.36367 16.3336C5.11602 17.1413 6.17846 17.6569 7.26375 17.7466C8.83505 17.9258 10.4063 17.9933 12.0005 17.9933C13.5937 17.9933 15.165 17.8805 16.7372 17.7466C17.8215 17.6569 18.884 17.1413 19.6363 16.3336C20.2118 15.6598 20.5 14.8309 20.5 13.9557C20.5 13.0815 20.3231 12.2738 19.7695 11.6453Z"
                                            fill="currentColor"></path>
                                        <path opacity="0.4"
                                            d="M14.0088 19.2283C13.5088 19.1215 10.4627 19.1215 9.96275 19.2283C9.53539 19.327 9.07324 19.5566 9.07324 20.0602C9.09809 20.5406 9.37935 20.9646 9.76895 21.2335L9.76795 21.2345C10.2718 21.6273 10.8632 21.877 11.4824 21.9667C11.8123 22.012 12.1482 22.01 12.4901 21.9667C13.1083 21.877 13.6997 21.6273 14.2036 21.2345L14.2026 21.2335C14.5922 20.9646 14.8734 20.5406 14.8983 20.0602C14.8983 19.5566 14.4361 19.327 14.0088 19.2283Z"
                                            fill="currentColor"></path>
                                    </svg>
                                    <span class="bg-danger dots"
                                        style="padding: 2px 7px;border-radius: 50%;font-size: 11px;position: absolute;left: 22px;color: #fff">{{ count(get_notification()) }}</span>
                                </a>
                                <div class="p-0 sub-drop dropdown-menu dropdown-menu-end"
                                    aria-labelledby="notification-drop">
                                    <div class="m-0 shadow-none card">
                                        <div
                                            class="py-3 card-header d-flex justify-content-between bg-primary rounded-top">
                                            <div class="header-title">
                                                <h5 class="mb-0 text-white">All Notifications</h5>
                                            </div>
                                        </div>
                                        <div class="p-0 card-body" style="height: 40vh;overflow-y: scroll;">
                                            @foreach (get_notification() as $key => $notification)
                                                @if (who('chairmen'))
                                                    <a href="{{ url('/students_waiting_for_chairman_approval') }}"
                                                        class="iq-sub-card">
                                                    @else
                                                        <a href="{{ url('/students_waiting_for_district_approval') }}"
                                                            class="iq-sub-card">
                                                @endif
                                                <div class="d-flex align-items-center">
                                                    <img class="p-1 avatar-40 rounded-pill bg-primary-subtle"
                                                        src="{{ $notification->image ? asset($notification->image) : asset('assets/images/avatars/01.png') }}"
                                                        alt="">
                                                    <div class="ms-3 w-100">
                                                        <h6 class="mb-0 text-start iq-text">
                                                            {{ $notification->candidate_name }} Waiting For Approval
                                                        </h6>
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <small
                                                                class="float-end font-size-12">{{ $notification->updated_at->diffForHumans() }}</small>
                                                        </div>
                                                    </div>
                                                </div>
                                                </a>
                                            @endforeach

                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="nav-item dropdown custom-drop">
                                <a class="py-0 nav-link d-flex align-items-center" href="#" id="navbarDropdown"
                                    role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    @if (Auth::user()->image && file_exists(public_path(Auth::user()->image)))
                                        <img src="{{ asset(Auth::user()->image) }}" alt="User-Profile"
                                            class="theme-color-default-img img-fluid avatar avatar-50 avatar-rounded" />
                                    @else
                                        <img src="{{ asset('assets/images/avatars/01.png') }}" alt="User-Profile"
                                            class="theme-color-default-img img-fluid avatar avatar-50 avatar-rounded" />
                                    @endif


                                    <div class="caption ms-3 d-none d-md-block">
                                        <h6 class="mb-0 caption-title" style="color: white;">
                                            {{ Auth::user()->name }} {{ Auth::user()->last_name }}
                                        </h6>
                                        {{-- <p class="mb-0 caption-sub-title" style="color: white;">
                                            {{ Auth::user()->role ?? 'User' }}
                                        </p> --}}
                                    </div>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end profile-dropdown"
                                    aria-labelledby="navbarDropdown">
                                    <div class="profile-dropdown-body p-3 bg-white rounded shadow-lg"
                                        style="width: 269px;">
                                        <div class="d-flex align-items-center mb-3">
                                            @if (Auth::user()->image && file_exists(public_path(Auth::user()->image)))
                                                <img src="{{ asset(Auth::user()->image) }}" alt="User Profile"
                                                class="img-fluid rounded-circle me-2" @else <img
                                                    src="{{ asset('assets/images/avatars/01.png') }}"
                                                    alt="User Profile" class="img-fluid rounded-circle me-2"
                                                    @endif
                                                style="width: 50px; height: 50px;" />
                                                <div>
                                                    <h6 class="mb-0">Hi, {{ Auth::user()->name }}
                                                        {{ Auth::user()->last_name }}
                                                    </h6>
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
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        function alert(message) {
            Swal.fire({
            text: message,
            });
        }
    </script>


    <script>
        $(document).ready(function() {
            $('.select2').select2();
        });
    </script>


    <script>
        $(document).ready(function() {
            var dateFields = document.querySelectorAll('.date');
            dateFields.forEach(function(dateField) {
                date_fixer(dateField.id);
            });
        });
    </script>

    <script>
        function date_fixer(id) {

            const dateField = document.getElementById(id);
            var dateValue = dateField.value;
            console.log('dateValue', dateValue);

            // if(dateValue == ''){
            //     dateValue = '{{ date('Y-m-d') }}';
            // }
            date_ayy = dateValue.split('-');
            daValue = date_ayy[2] + '-' + date_ayy[1] + '-' + date_ayy[0];
            flatpickr(`#${id}`, {
                dateFormat: "d-m-Y",
                allowInput: true,
                defaultDate: daValue
            });
        }
    </script>

    @include('layouts/datatables_js')

    @yield('footer_scripts')


</body>

</html>
