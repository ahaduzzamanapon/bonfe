<!DOCTYPE html>
<html lang="en-US">

<head>
    <title>
        @php
            if (!Auth::check()) {
                // dd("Please login first");
                // redirect(route('welcome'));
            }
            $setting = DB::table('sitesettings')->first();
        @endphp
        @section('title')| {{ !empty($setting) ? $setting->name : 'Gym Master' }} -
        {{ !empty($setting) ? $setting->slogan : 'Gym Master' }} @show
    </title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">


    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" href="{{ asset('img/favicon.ico') }}" />
    <link rel="stylesheet" href="{{ asset('fonts/iconmind.css') }}">

    <!-- global css -->
    <link type="text/css" href="{{ asset('css/app.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('vendors/perfect-scrollbar/css/perfect-scrollbar.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/custom.css') }}">
    {{-- //<script src="https://cdn.tailwindcss.com"></script> --}}

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    <!-- DataTables CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">

    <!-- DataTables Buttons Extension CSS -->
    <link rel="stylesheet" type="text/css"
        href="https://cdn.datatables.net/buttons/2.2.0/css/buttons.dataTables.min.css">



    <!-- DataTables JS -->
    <script type="text/javascript" charset="utf-8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js">
    </script>

    <!-- DataTables Buttons Extension JS -->
    <script type="text/javascript" charset="utf-8"
        src="https://cdn.datatables.net/buttons/2.2.0/js/dataTables.buttons.min.js"></script>

    <!-- JS for pdfmake (PDF export functionality) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.69/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.69/vfs_fonts.min.js"></script>

    <!-- JS for individual button types like Excel, Print, etc. -->
    <script type="text/javascript" charset="utf-8" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js">
    </script>
    <script type="text/javascript" charset="utf-8" src="https://cdn.datatables.net/buttons/2.2.0/js/buttons.flash.min.js">
    </script>
    <script type="text/javascript" charset="utf-8" src="https://cdn.datatables.net/buttons/2.2.0/js/buttons.html5.min.js">
    </script>
    <script type="text/javascript" charset="utf-8" src="https://cdn.datatables.net/buttons/2.2.0/js/buttons.print.min.js">
    </script>






    <!-- jQuery UI CSS -->
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">

    <!-- jQuery UI Library -->
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>

    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.39.0/css/tempusdominus-bootstrap-4.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script
        src="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.39.0/js/tempusdominus-bootstrap-4.min.js">
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.min.css">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.min.js"></script>
    <!-- jQuery Library -->

    <!-- Flatpickr CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

<!-- Flatpickr JS -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>



    <style>
        #demo {
            position: relative;

            overflow: auto;
        }
    </style>
    <style>
        .dataTables_wrapper {
            overflow-x: scroll;
        }

        .chosen-single {
            padding: 6px !important;
            height: fit-content !important;
        }
    </style>
    <!-- end of global css -->

    <!-- vendors  css -->
    @yield('header_styles')
</head>

<body>

    <style>
        /* From Uiverse.io by doniaskima */
        /* HTML: <div class="loaderss"></div> */
        .loaderss {
            --c1: #673b14;
            --c2: #13007D;
            width: 40px;
            height: 80px;
            border-top: 4px solid var(--c1);
            border-bottom: 4px solid var(--c1);
            background: linear-gradient(90deg, var(--c1) 2px, var(--c2) 0 5px, var(--c1) 0) 50%/7px 8px no-repeat;
            display: grid;
            overflow: hidden;
            animation: l5-0 2s infinite linear;
        }

        .loaderss::before,
        .loaderss::after {
            content: "";
            grid-area: 1/1;
            width: 75%;
            height: calc(50% - 4px);
            margin: 0 auto;
            border: 2px solid var(--c1);
            border-top: 0;
            box-sizing: content-box;
            border-radius: 0 0 40% 40%;
            -webkit-mask: linear-gradient(#000 0 0) bottom/4px 2px no-repeat,
                linear-gradient(#000 0 0);
            -webkit-mask-composite: destination-out;
            mask-composite: exclude;
            background: linear-gradient(var(--d, 0deg), var(--c2) 50%, #0000 0) bottom /100% 205%,
                linear-gradient(var(--c2) 0 0) center/0 100%;
            background-repeat: no-repeat;
            animation: inherit;
            animation-name: l5-1;
        }

        .loaderss::after {
            transform-origin: 50% calc(100% + 2px);
            transform: scaleY(-1);
            --s: 3px;
            --d: 180deg;
        }

        @keyframes l5-0 {
            80% {
                transform: rotate(0)
            }

            100% {
                transform: rotate(0.5turn)
            }
        }

        @keyframes l5-1 {

            10%,
            70% {
                background-size: 100% 205%, var(--s, 0) 100%
            }

            70%,
            100% {
                background-position: top, center
            }
        }
    </style>

    <div id="loader_div"
        style="position: fixed;top: 0;left: 0;height: 100vh;width: 100vw;background-color: #ffffffa8;z-index: 99999999999;display: flex;overflow: hidden;align-items: center;justify-content: center;">
        <div class="loaderss" style="margin: auto;"></div>
    </div>




    {{-- <!-- header logo: style can be found in header-->
    <header class="header">
        <nav class="navbar navbar-expand-lg navbar-light navbar-static-top col-md-12"
            style="display: flex;align-content: center;justify-content: center;" role="navigation">
            <div class="col-md-12"
                style="display: flex;box-shadow: 0px 0px 4px 2px #bfbfbf;border-radius: 8px;padding: 11px;background: #ffffff;align-items: center;">
                <a href="javascript:void(0)" class="ml-100 toggle-right d-xl-none d-lg-block">
                    <img src="{{ asset('img/images/toggle.png') }}" alt="logo" />
                </a>
                <a href="{{ url('/') }}" class="ml-100 toggle-right d-xl-block d-lg-none" id="logo_sol">
                    <img style="height: 69px;" src="{{ asset($setting->logo) }}" alt="logo" />
                </a>

                <h3
                    style="display: flex;width: -webkit-fill-available;place-content: center;font-size: x-large;width: -moz-available;">
                    {{ !empty($setting) ? $setting->name : 'Inventory' }} -
                    {{ !empty($setting) ? $setting->slogan : 'Inventory' }} </h3>
                <div class="navbar-right ml-auto">
                    <ul class="navbar-nav nav">
                        <li class="dropdown notifications-menu nav-item dropdown">
                            <a href="javascript:void(0)" class="dropdown-toggle nav-link dropdown-toggle"
                                data-toggle="dropdown" id="navbarDropdown"
                                style="border: 1px solid #a9a9a9;padding: 0 17px;border-radius: 7px;">
                                <i class="im im-icon-Boy fs-16"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-notifications table-striped"
                                aria-labelledby="navbarDropdown">

                                <li class="dropdown-footer">
                                    @if (!empty(Auth::user()) && Auth::user()->member_id != null)
                                        <a class="dropdown-item"
                                            href="{{ route('members.details', ['id' => Auth::user()->member_id]) }}">
                                            Profile
                                        </a>
                                    @endif
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                        onclick="event.preventDefault();
                                    document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                        style="display: none;">
                                        @csrf
                                    </form>
                                </li>
                            </ul>
                        </li>

                    </ul>
                </div>
            </div>
        </nav>
    </header> --}}

    <style>
        .header-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
            transition: all 0.3s ease-in-out;
        }

        /* Title Styling */
        .header-title {
            font-family: 'Poppins', sans-serif;
            font-size: 24px;
            font-weight: bold;
            color: #444;
            text-align: center;
            flex: 1;
        }

        /* Toggle Menu Icon */
        .toggle-menu img {
            width: 30px;
            height: auto;
            cursor: pointer;
            transition: transform 0.3s ease-in-out;
        }

        .toggle-menu img:hover {
            transform: rotate(90deg);
        }

        /* User Icon Dropdown */
        .dropdown-toggle {
            background-color: #f5f5f5;
            border: 1px solid #ddd;
            border-radius: 8px;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        .dropdown-toggle:hover {
            background-color: #e7f7ef;
            transform: scale(1.1);
        }

        .dropdown-menu {
            background: #ffffff;
            border: 1px solid #ddd;
            border-radius: 10px;
            box-shadow: 0px 6px 12px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .dropdown-item {
            padding: 10px 20px;
            color: #444;
            font-size: 14px;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .dropdown-item:hover {
            background: #e7f7ef;
            color: rgb(58 0 255);
        }
        @media (max-width: 767px) {
            .logo {
                display: none!important;
            }
        }

    </style>


    <header class="header">
        <nav class="navbar navbar-expand-lg navbar-light col-md-12" style="background: #eae2fb;" role="navigation">
            <div class="col-md-12 header-container"
            style="display: flex;align-items: center;padding: 4px 20px;background: linear-gradient(135deg, #13007D, #3819e7);box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);border-radius: 10px;">
                <!-- Sidebar Toggle for Mobile -->
                <a href="javascript:void(0)" onclick="toggleSidebar()" class="toggle-menu d-xl-none d-lg-block">
                    <img src="{{ asset('img/images/toggle.png') }}" alt="Toggle Menu" style="height: 30px;">
                </a>

                <!-- Logo -->
                <a href="{{ url('/') }}" class="logo d-flex align-items-center ml-3">
                    <img style="height: 60px;" src="{{ asset($setting->logo) }}" alt="Logo" />
                </a>

                <!-- Title -->
                <h3 class="header-title text-center mx-auto" style="font-size: 24px; font-weight: 600; color: #ffffff;">
                    {{ $setting->name ?? 'Inventory' }} - {{ $setting->slogan ?? 'Inventory' }}
                </h3>

                <!-- User Dropdown -->
                <div class="navbar-right ml-auto">
                    <ul class="navbar-nav nav">
                        <li class="dropdown notifications-menu nav-item">
                            <a href="javascript:void(0)" class="dropdown-toggle nav-link" data-toggle="dropdown"
                            style="border: 1px solid #ddd;padding: 10px 15px;border-radius: 8px;transition: all 0.3s ease;background: linear-gradient(135deg, #bdb2fd, #bdb2fd);">
                                <i class="im im-icon-Boy fs-16"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-notifications"
                                style="min-width: 200px; border-radius: 10px;">
                                <li class="dropdown-item">
                                    @if (!empty(Auth::user()) && Auth::user()->member_id != null)
                                        <a href="{{ route('members.details', ['id' => Auth::user()->member_id]) }}"
                                            class="d-block">
                                            Profile
                                        </a>
                                    @endif
                                    <a href="{{ route('logout') }}" class="d-block"
                                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        Logout
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                        style="display: none;">
                                        @csrf
                                    </form>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>





    <div class="wrapper">
        <aside class="left-aside" style="min-height: 100vh;height: 100vh;">
            <section class="sidebar metismenu sidebar-res">
                @include('layouts/leftmenu')
            </section>
        </aside>
        <aside class="right-aside">
            @yield('content')
        </aside>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>




    <script src="{{ asset('js/app.js') }}" type="text/javascript"></script>
    <!-- end of page level js -->
    <!-- Start of vendor js -->
    @yield('footer_scripts')
    @yield('scripts')

    <script src="{{ asset('vendors/perfect-scrollbar/js/perfect-scrollbar.js') }}"></script>
    <script src="{{ asset('js/custom.js') }}"></script>



    {{-- <script>
        $(document).ready(function() {
            $('input').attr('required', 'required');
        });
    </script> --}}

    <style>
        .top_solver {
            top: -161px !important;
        }
    </style>

    <script>
        $(document).ready(function() {
            var ww = $(window).width();
            checkWidth();
            $(window).resize(function() {
                checkWidth();
            });
        });

        function checkWidth() {
            console.log('checkWidth');

            var ww = $(window).width();
            console.log(ww);

            if (ww < 767) {
                $('.sidebar-res').css('margin-left', '');
                $('.barrr').css('margin-left', '-24px');

            }


            if (ww < 992) {
                console.log('rhb');
                $('.left-aside').addClass('top_solver');
            } else {
                $('.left-aside').removeClass('top_solver');
            }

        }
    </script>
    <script>
        $(document).ready(function() {
            var table = new DataTable('.data_t', {
                dom: 'Bfrtip', // This will place the buttons in the top of the table
                buttons: [{
                    extend: 'collection',
                    text: 'Export',
                    buttons: ['copy', 'excel', 'print']
                }],
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            var ww = $(window).width();
            $(window).resize(function() {
                checkWidth();
            });
        });

        function checkWidth() {
            var ww = $(window).width();
            if (ww < 992) {
                $('#logo_sol').addClass('d-none');
            } else {
                $('#logo_sol').removeClass('d-none');
            }
        }
    </script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.4.2/chosen.jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.4.2/chosen.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/metismenu"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/metismenu/dist/metisMenu.min.css">
    <script>
        $(document).ready(function() {
            $('.chosen-select').chosen();
        });
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
    <script>
        function alert(message) {
            toastr.info(message)
        }

        function error_alert(message) {
            toastr.error(message)
        }

        function success_alert(message) {
            toastr.success(message)
        }
    </script>

    <script>
        $(document).ready(function() {
            $('#loader_div').hide();
        });
        $(document).on('ajaxStart', function() {
            $('#loader_div').show();
        }).on('ajaxStop', function() {
            $('#loader_div').hide();
        });
        $(window).on('beforeunload', function() {
            $('#loader_div').show();
        });
        $(window).on('pageshow', function(event) {
            if (event.originalEvent.persisted) {
                $('#loader_div').hide();
            }
        });

    </script>
    <script>
        function toggleSidebar() {
            var sidebar = document.querySelector('.barrr');
            sidebar.classList.toggle('barrr_res');
        }
    </script>

<script>
    $(function() {
        $('input[type="date"]').each(function() {
            console.log($(this).val());
        });
    });

</script>

<script>
    $(document).ready(function() {
        var dateFields = document.querySelectorAll('input[type="date"]');
        dateFields.forEach(function(dateField) {
            date_fixer(dateField.id);
        });
    });
</script>

<script>
    function date_fixer(id){

        const dateField = document.getElementById(id);
        var dateValue = dateField.value;
        console.log('dateValue', dateValue);

        if(dateValue == ''){
            dateValue = '{{ date('Y-m-d') }}';
        }
        date_ayy = dateValue.split('-');
        daValue = date_ayy[2] + '-' + date_ayy[1] + '-' + date_ayy[0];
        flatpickr(`#${id}`, {
            dateFormat: "d-m-Y",
            allowInput: true,
            defaultDate: daValue
        });
    }
</script>



</body>

</html>
