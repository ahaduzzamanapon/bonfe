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
        $setting = DB::table(table: 'sitesettings')->first();
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
        /* ─── Google Font ─────────────────────────────────────────── */
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

        /* ─── Base ───────────────────────────────────────────────── */
        * { padding: 0; margin: 0; box-sizing: border-box; }
        body { background: #dde6ee; font-size: 13px; font-family: 'Inter', sans-serif; }

        /* ─── Scrollbar ──────────────────────────────────────────── */
        ::-webkit-scrollbar { width: 4px; height: 5px; cursor: pointer; }
        ::-webkit-scrollbar-thumb { background: #8dc641; border-radius: 1px; }
        ::-webkit-scrollbar-track { background: #f0f0f0; }

        /* ─── Number inputs ──────────────────────────────────────── */
        input[type="number"]::-webkit-outer-spin-button,
        input[type="number"]::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }
        input[type="number"] { -moz-appearance: textfield; appearance: textfield; }
        input[type="number"]::-ms-clear,
        input[type="number"]::-ms-reveal { display: none; }

        /* ─── Scrollbar ──────────────────────────────────────────── */
        ::-webkit-scrollbar { width: 4px; height: 4px; cursor: pointer; }
        ::-webkit-scrollbar-thumb { background: #8dc641; border-radius: 1px; }
        ::-webkit-scrollbar-track { background: #f0f0f0; }

        /* ─── Number inputs ──────────────────────────────────────── */
        input[type="number"]::-webkit-outer-spin-button,
        input[type="number"]::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }
        input[type="number"] { -moz-appearance: textfield; appearance: textfield; }

        /* ════════════════════════════════════════════════════════════
           SIDEBAR
        ════════════════════════════════════════════════════════════ */
        /* Header */
        .sidebar-header {
            background: linear-gradient(135deg, #7bb835 0%, #5a9e20 100%) !important;
            height: 56px !important;
            border-bottom: 2px solid rgba(0,0,0,.15) !important;
            padding: 0 12px !important;
        }
        .sidebar-header .navbar-brand { gap: 8px; }
        .sidebar-header .logo-title {
            font-size: 11.5px; font-weight: 700; color: #fff;
            line-height: 1.3; text-shadow: 0 1px 2px rgba(0,0,0,.3);
        }

        /* Nav items */
        .sidebar.sidebar-default .nav-link:not(.static-item).active,
        .sidebar.sidebar-default .nav-link:not(.static-item)[aria-expanded="true"] {
            background: rgba(141,198,65,.22);
            border-left: 3px solid #8dc641;
            box-shadow: none;
            color: #1a5e02;
            font-weight: 600;
        }
        .sidebar.sidebar-default .nav-link:not(.static-item):hover:not(.active):not([aria-expanded="true"]) {
            background: rgba(141,198,65,.12);
            color: #2a6e08;
            box-shadow: none;
        }
        .sidebar.navs-rounded-all .sidebar-body .nav-item .nav-link {
            border-radius: 4px;
            height: 33px;
            font-size: 12.5px;
            padding: 0 0.65rem;
            transition: background .15s ease, color .15s ease;
        }
        .sidebar .sidebar-body { padding-right: 0; overflow: hidden; }
        .sidebar-base .nav-item:not(.static-item) { padding-left: 0.5rem; padding-right: 0.3rem; }
        .nav-item { margin-top: 2px !important; }
        #sidebar-menu {
            height: calc(100vh - 96px);
            overflow-y: auto;
            padding-bottom: 40px;
        }
        /* Submenu indent */
        .sidebar .sub-nav .nav-link { padding-left: 2rem !important; font-size: 12px; }

        /* Footer */
        .sidebar-footer {
            position: absolute; bottom: 0; width: 100%;
            background: linear-gradient(135deg, #7bb835 0%, #5a9e20 100%);
            padding: 5px 10px;
            font-size: 11px; font-weight: 600; color: #fff;
            border-top: 1px solid rgba(255,255,255,.25);
            text-align: center;
        }
        .sidebar-footer a { color: #fff; text-decoration: underline; }

        /* ════════════════════════════════════════════════════════════
           NAVBAR (top bar)
        ════════════════════════════════════════════════════════════ */
        .iq-navbar {
            background: linear-gradient(90deg, #5a2478 0%, #7c3ca8 100%) !important;
            padding: 0 !important;
            min-height: 48px !important;
            box-shadow: 0 2px 8px rgba(0,0,0,.25);
        }
        .iq-navbar .nav-link { padding: 4px 8px !important; }

        /* ════════════════════════════════════════════════════════════
           BUTTONS
        ════════════════════════════════════════════════════════════ */
        .btn { padding: 3px 10px !important; font-size: 12.5px; border-radius: 4px; transition: all .15s ease; }
        .btn-sm { padding: 2px 8px !important; font-size: 12px !important; }
        .btn-primary {
            background-color: #0aa699 !important; border-color: #0aa699 !important; color: #fff !important;
        }
        .btn-primary:hover { background-color: #088f83 !important; border-color: #088f83 !important; }
        .btn-danger  { background-color: #dc3545 !important; border-color: #dc3545 !important; color:#fff !important; }
        .btn-danger:hover  { background-color: #b82333 !important; border-color: #b82333 !important; }
        .btn-success { background-color: #28a745 !important; border-color: #28a745 !important; color:#fff !important; }
        .btn-warning { background-color: #ffc107 !important; border-color: #ffc107 !important; color:#222 !important; }
        .btn-secondary { background-color: #6c757d !important; border-color: #6c757d !important; color:#fff !important; }
        .btn-light { background-color: #f8f9fa !important; border-color: #dee2e6 !important; color:#333 !important; }
        .btn:focus { outline:0; box-shadow: 0 0 0 .2rem rgba(10,166,153,.35); }
        .btn-primary:not(:disabled):not(.disabled).active,
        .btn-primary:not(:disabled):not(.disabled):active { background-color: #8dc641 !important; border-color: #8dc641 !important; }

        /* ════════════════════════════════════════════════════════════
           INPUTS & FORMS
        ════════════════════════════════════════════════════════════ */
        label { display:inline-block; font-size:12.5px; color:#111; font-weight:500; margin-bottom:2px; }
        .form-control {
            border: 1px solid #9a9a9a; padding: 3px 10px;
            font-size: 12.5px; height: calc(1.5em + .6rem + 2px);
            border-radius: 4px; transition: border-color .15s ease, box-shadow .15s ease;
        }
        .form-control:focus { border-color: #8dc641; box-shadow: 0 0 0 .18rem rgba(141,198,65,.25); }
        .form-group { margin-bottom: 10px; }
        .select2-container .select2-selection--single {
            height: 32px; border: 1px solid #9a9a9a; border-radius: 4px;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered { line-height: 30px; font-size: 12.5px; }
        .select2-container--default .select2-selection--single .select2-selection__arrow { height: 30px; }

        /* ════════════════════════════════════════════════════════════
           CARD
        ════════════════════════════════════════════════════════════ */
        .card {
            box-shadow: 0 1px 6px rgba(0,0,0,.08);
            border: 1px solid #c4c4c4;
            border-radius: 6px;
            min-height: auto; /* ← removed 88vh that caused dashboard overflow */
            margin: 0;
            background: #fff;
        }
        /* pages with a single main card (index/edit/create) should fill height */
        .content > .card { min-height: calc(100vh - 80px); }
        .card .card-header {
            border: 0;
            padding: 7px 12px;
            background: linear-gradient(90deg, #7bb835 0%, #5a9e20 100%);
            border-bottom: 3px solid #e44;
            margin: 0;
            border-radius: 6px 6px 0 0;
            color: #fff;
        }
        .card .card-header h4,
        .card .card-header h5,
        .card .card-header h6 { font-size: 13.5px; margin: 0; line-height: 1.4; font-weight: 600; }
        .card-body { flex: 1 1 auto; padding: 10px 14px; color: #111; }
        .card-footer { padding: 6px 12px; background: #f9f9f9; border-top: 1px solid #ddd; }

        /* ════════════════════════════════════════════════════════════
           TABLE
        ════════════════════════════════════════════════════════════ */
        .table { font-size: 12.5px; }
        .table thead tr th {
            text-transform: capitalize; letter-spacing: .2px;
            background: #f3f6f9; color: #333; padding: 6px 8px;
            border-bottom: 2px solid #ddd; font-weight: 600;
        }
        .table td, .table th { padding: 5px 8px; vertical-align: middle; }
        .table-hover tbody tr:hover { background-color: rgba(141,198,65,.08); }

        /* ════════════════════════════════════════════════════════════
           MISC
        ════════════════════════════════════════════════════════════ */
        .badge { font-size: 10px; padding: 3px 8px; border-radius: 4px; font-weight: 600; }
        .bg-success { background-color: #28a745 !important; }
        .bg-danger  { background-color: #dc3545 !important; }
        .bg-warning { background-color: #ffc107 !important; }
        .bg-info    { background-color: #17a2b8 !important; }
        .pagination { justify-content: flex-end; }
        .page-item.active .page-link { background-color: #8dc641; border-color: #8dc641; color: #fff; }
        .page-link { color: #5a9e20; font-size: 12.5px; padding: 3px 8px; }
        .text-muted { color: #666 !important; }
        .dropdown-item { font-size: 12.5px; padding: 5px 12px; }
        .dropdown-menu { border: 1px solid #ddd; box-shadow: 0 4px 16px rgba(0,0,0,.12); border-radius: 6px; }
        .content-wrap { padding: 8px; }

        /* ════════════════════════════════════════════════════════════
           SIDEBAR SECTION LABELS
        ════════════════════════════════════════════════════════════ */
        .nav-section-label {
            font-size: 10px; font-weight: 700; letter-spacing: 1px;
            text-transform: uppercase; color: #8dc641;
            padding: 10px 14px 3px;
            display: block;
        }
        .nav-divider {
            border: none; border-top: 1px solid rgba(141,198,65,.2);
            margin: 4px 10px;
        }
        /* Sub-nav chevron rotate */
        .nav-link[aria-expanded="true"] .right-icon { transform: rotate(90deg); }
        .nav-link .right-icon { transition: transform .2s ease; }

        /* ════════════════════════════════════════════════════════════
           NAVBAR ENHANCEMENTS
        ════════════════════════════════════════════════════════════ */
        .navbar-page-title {
            color: rgba(255,255,255,.9);
            font-size: 13px;
            font-weight: 600;
            letter-spacing: .3px;
            line-height: 1;
        }
        .navbar-page-subtitle {
            font-size: 10.5px;
            color: rgba(255,255,255,.6);
            margin-top: 1px;
        }
        @keyframes pulse-dot {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.25); }
        }
        .notif-dot { animation: pulse-dot 1.8s ease-in-out infinite; display: inline-block; }

        /* ════════════════════════════════════════════════════════════
           PAGE TRANSITION
        ════════════════════════════════════════════════════════════ */
        #page-loader {
            position: fixed; top: 0; left: 0; width: 100%; height: 3px;
            background: linear-gradient(90deg, #8dc641, #0aa699, #683091);
            z-index: 99999;
            transform: scaleX(0); transform-origin: left;
            transition: transform .4s ease;
        }
        #page-loader.loading { transform: scaleX(1); }

        /* ════════════════════════════════════════════════════════════
           FLASH MESSAGE
        ════════════════════════════════════════════════════════════ */
        #message {
            position: fixed; top: 56px; right: 12px; z-index: 9999;
            min-width: 260px; max-width: 380px;
            border-radius: 6px; padding: 10px 14px;
            font-size: 13px; font-weight: 500;
            box-shadow: 0 4px 16px rgba(0,0,0,.2);
            animation: slideInRight .35s ease;
        }
        @keyframes slideInRight {
            from { transform: translateX(120%); opacity:0; }
            to   { transform: translateX(0);    opacity:1; }
        }
    </style>

</head>



<body>
    <!-- loader Start -->
    {{-- <div id="loading">
        <div class="loader simple-loader">
            <div class="loader-body"></div>
        </div>
    </div> --}}

    @include('all_modal')

    <aside class="sidebar sidebar-default sidebar-white sidebar-base navs-rounded-all">
        <div class="sidebar-header d-flex align-items-center justify-content-start">
            <a href="{{ url('/') }}" class="navbar-brand d-flex align-items-center" style="gap:8px;">
                <img src="{{ asset('assets/images/logo.png') }}" alt="logo" style="height:44px; flex-shrink:0;">
                <span class="logo-title">উপানুষ্ঠানিক শিক্ষা<br>বোর্ড, বাংলাদেশ</span>
            </a>
            <div class="sidebar-toggle ms-auto" data-toggle="sidebar" data-active="true" style="cursor:pointer; color:#fff; padding:4px;">
                <i class="icon">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M4.25 12.2744L19.25 12.2744" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                        <path d="M10.2998 18.2988L4.2498 12.2748L10.2998 6.24976" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                </i>
            </div>
        </div>
        <div class="sidebar-body pt-0">
            <div class="sidebar-list">
                <ul class="navbar-nav iq-main-menu" id="sidebar-menu">
                    @include('layouts/leftmenu')
                </ul>
            </div>
        </div>
        <div class="sidebar-footer">
            Developed by &mdash; <a href="https://mysoftheaven.com" target="_blank">Mysoftheaven (BD) Ltd.</a>
        </div>
    </aside>
    <main class="main-content">
        <div class="position-relative iq-banner">
            <!--Nav Start-->
            <nav class="nav navbar navbar-expand-xl navbar-light iq-navbar">
                <div class="container-fluid navbar-inner">
                    <a href="../dashboard/index.html" class="navbar-brand">
                        <h4 class="logo-title"></h4>
                    </a>
                    <div class="sidebar-toggle" data-toggle="sidebar" data-active="true" style="cursor:pointer; color:#fff;">
                        <svg width="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path fill="currentColor" d="M4,11V13H16L10.5,18.5L11.92,19.92L19.84,12L11.92,4.08L10.5,5.5L16,11H4Z"/>
                        </svg>
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
                                                    {{-- <img class="p-1 avatar-40 rounded-pill bg-primary-subtle"
                                                        src="{{ $notification->image ? asset($notification->image) : asset('assets/images/avatars/01.png') }}"
                                                        alt=""> --}}
                                                    <div class="ms-3 w-100">
                                                        <h6 class="mb-0 text-start iq-text">
                                                            {{ $notification->candidate_name }} Waiting For Approval
                                                        </h6>
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <small
                                                                class="float-end font-size-12">{{ \Carbon\Carbon::parse($notification->updated_at)->diffForHumans() }}</small>
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
                                        <img src="{{ asset(Auth::user()->image) }}" alt="Profile"
                                            class="img-fluid avatar avatar-40 avatar-rounded" style="border-radius:50%;" />
                                    @else
                                        <img src="{{ asset('assets/images/avatars/01.png') }}" alt="Profile"
                                            class="img-fluid avatar avatar-40 avatar-rounded" style="border-radius:50%;" />
                                    @endif
                                    <div class="caption ms-2 d-none d-md-block">
                                        <h6 class="mb-0" style="color:white; font-size:12.5px;">
                                            {{ Auth::user()->name }} {{ Auth::user()->last_name }}
                                        </h6>
                                        <span style="font-size:10px; background:white; color:#333; padding:1px 6px; border-radius:5px; font-weight:500;">
                                            {{ get_designation() }}
                                        </span>
                                    </div>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <div class="p-3 bg-white rounded shadow-lg" style="width:250px;">
                                        <div class="d-flex align-items-center mb-2">
                                            @if (Auth::user()->image && file_exists(public_path(Auth::user()->image)))
                                                <img src="{{ asset(Auth::user()->image) }}" alt="Profile"
                                                    class="img-fluid rounded-circle me-2" style="width:42px;height:42px;object-fit:cover;" />
                                            @else
                                                <img src="{{ asset('assets/images/avatars/01.png') }}" alt="Profile"
                                                    class="img-fluid rounded-circle me-2" style="width:42px;height:42px;" />
                                            @endif
                                            <div>
                                                <h6 class="mb-0" style="font-size:13px;">{{ Auth::user()->name }} {{ Auth::user()->last_name }}</h6>
                                                <small class="text-muted" style="font-size:11px;">{{ Auth::user()->email }}</small>
                                            </div>
                                        </div>
                                        <hr class="my-2">
                                        <a href="{{ route('logout') }}" class="btn btn-sm btn-danger w-100"
                                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                            Logout
                                        </a>
                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">
                                            @csrf
                                        </form>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
            <div class="content-wrap">
                @yield('content')
            </div>
        </div>
    </main>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
        integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
        integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
        integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <script src="{{ asset('assets/js/core/libs.min.js') }}"></script>
    <script src="{{ asset('assets/js/core/external.min.js') }}"></script>
    <script src="{{ asset('assets/js/charts/widgetcharts.js') }}"></script>
    <script src="{{ asset('assets/js/charts/vectore-chart.js') }}"></script>
    <script src="{{ asset('assets/js/charts/dashboard.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/fslightbox.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/setting.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/slider-tabs.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/form-wizard.js') }}"></script>
    <script src="{{ asset('assets/vendor/aos/dist/aos.js') }}"></script>
    <script src="{{ asset('assets/js/hope-ui.js') }}" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        function alert(message) {
            Swal.fire({ text: message });
        }

        $(document).ready(function () {
            $('.select2').select2();
            // Init date fields
            document.querySelectorAll('.date').forEach(function (dateField) {
                date_fixer(dateField.id);
            });
        });

        function date_fixer(id) {
            const dateField = document.getElementById(id);
            if (!dateField) return;
            var dateValue = dateField.value;
            if (dateValue && dateValue.match(/^\d{4}-\d{2}-\d{2}/)) {
                let parts = dateValue.split('-');
                let timePart = parts[2].split(' ');
                dateValue = timePart[0] + '-' + parts[1] + '-' + parts[0];
                dateField.value = dateValue;
            }
            flatpickr('#' + id, { dateFormat: 'd-m-Y', allowInput: true });
        }
    </script>

    @yield('footer_scripts')
    @stack('footer_scripts')
    @yield('scripts')
    @include('layouts/datatables_js')
</body>

</html>
