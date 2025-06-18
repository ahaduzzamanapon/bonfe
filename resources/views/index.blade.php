@extends('layouts.default')
{{-- Page title --}}
@section('title')
    Dashboard @parent
@stop
{{-- page level styles --}}
@section('header_styles')
    <link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">
@stop
@section('content')
    <section class="content-header">
        <div class="col-md-12">
            <div class="row">
                @php
                    $programs = \App\Models\Program::latest()->get();
                    $occupations = \App\Models\Occupation::latest()->get();
                @endphp
                <h3 class="col-md-6 pull-left">
                    Dashboard
                </h3>
                <div class="col-md-6 pull-right">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <select id="dashboard_program" name="dashboard_program" class="form-control" onchange="fetchDashboardData()">
                                    @foreach ($programs as $program)
                                        <option value="{{ $program->id }}">{{ $program->program_title }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <select id="dashboard_occupation" name="dashboard_occupation" class="form-control" onchange="fetchDashboardData()">
                                    <option value="">Select Occupation</option>
                                    @foreach ($occupations as $occupation)
                                        <option value="{{ $occupation->id }}">{{ $occupation->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <br>

    </section>
    <style>
        .custom-card {
            display: flex;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            overflow: hidden;
            margin-bottom: 15px;
            transition: transform 0.3s ease;
            background-color: #fff;
            min-height: 89px;
        }

        .spinner-border {
            --bs-spinner-width: 1rem;
            --bs-spinner-height: 1rem;
            --bs-spinner-vertical-align: -0.125em;
            --bs-spinner-border-width: 0.2em;
            --bs-spinner-animation-speed: 0.50s;
            --bs-spinner-animation-name: spinner-border;
            border: var(--bs-spinner-border-width) solid currentcolor;
            border-right-color: rgba(0, 0, 0, 0);
        }

        .custom-card:hover {
            transform: translateY(-4px);
        }

        .card-icon {
            flex: 0 0 100px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 45px;
        }

        .card-content {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            /* <- vertical centering */
            padding: 0 15px;
        }

        .card-content h3 {
            margin: 0;
            font-size: 22px;
            color: #333;
            font-weight: bold;
        }

        .card-content p {
            margin: 4px 0 0;
            font-size: 15px;
            color: #666;
            font-weight: bold;
        }

        .teal {
            background-color: teal;
        }

        .green {
            background-color: #28a745;
        }

        .blue {
            background-color: #007bff;
        }

        .red {
            background-color: #dc3545;
        }

        .aqua {
            background-color: #17a2b8;
        }

        .fuchsia {
            background-color: #e83e8c;
        }

        .orange {
            background-color: #fd7e14;
        }

        .yellow {
            background-color: #ffc107;
        }

        .dashboard_cards_header {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
        }

        .dashboard_card {
            width: 30% !important;
            margin: 0px 15px !important;
        }

        .tiles-title {
            font-size: 18px !important;
        }

        .heading {
            margin-top: 8px !important;
            font-size: 18px !important;
        }

        .report-table td {
            font-size: 15px !important;
        }

        @media (max-width: 768px) {
            .dashboard_card {
                width: 100% !important;
            }
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <section class="content">
        <div class="dashboard_cards_header">
            <div class="dashboard_card">
                <a class="indexLink" href="{{ route('students.index') }}" style="text-decoration: none!important;">
                    <div class="custom-card">
                        <div class="card-icon teal">
                            <i class="icon im im-icon-User"></i>
                        </div>
                        <div class="card-content">
                            <h3 id="total_students">
                                <div class="spinner-border" role="status"><span class="sr-only">Loading...</span></div>
                            </h3>
                            <p>Total Lerner</p>
                        </div>
                    </div>
                </a>
            </div>
            <div class="dashboard_card">
                <a class="indexLink" href="{{ route('students.index') }}" style="text-decoration: none!important;">
                    <div class="custom-card">
                        <div class="card-icon blue">
                            <i class="icon im im-icon-Map-Marker"></i>
                        </div>
                        <div class="card-content">
                            <h3 id="total_passed_students">
                                <div class="spinner-border" role="status"><span class="sr-only">Loading...</span></div>
                            </h3>
                            <p>Total <span id='com_pass'></span> </p>
                        </div>
                    </div>
                </a>
            </div>
            <div class="dashboard_card">
                <a class="indexLink" href="{{ route('students.index') }}" style="text-decoration: none!important;">
                    <div class="custom-card ">
                        <div class="card-icon green">
                            <i class="icon im im-icon-Map"></i>
                        </div>
                        <div class="card-content">
                            <h3 id="total_failed_students">
                                <div class="spinner-border" role="status"><span class="sr-only">Loading...</span></div>
                            </h3>
                            <p>Total <span id='com_fail'></span>  </p>
                        </div>
                    </div>
                </a>
            </div>
            <div class="dashboard_card">
                <a class="indexLink" href="{{ route('students.students_waiting_for_chairman_approval') }}"
                    style="text-decoration: none!important;">
                    <div class="custom-card ">
                        <div class="card-icon fuchsia">
                            <i class="icon im im-icon-Map"></i>
                        </div>
                        <div class="card-content">
                            <h3 id="waiting_for_chairman">
                                <div class="spinner-border" role="status"><span class="sr-only">Loading...</span></div>
                            </h3>
                            <p>Waiting for Chairmen Approval</p>
                        </div>
                    </div>
                </a>
            </div>
            <div class="dashboard_card">
                <a href="{{ route('students.students_waiting_for_district_approval') }}"
                    style="text-decoration: none!important;" class="indexLink">
                    <div class="custom-card ">
                        <div class="card-icon aqua">
                            <i class="icon im im-icon-Map"></i>
                        </div>
                        <div class="card-content">
                            <h3 id="waiting_for_district">
                                <div class="spinner-border" role="status"><span class="sr-only">Loading...</span></div>
                            </h3>
                            <p>Waiting for District Approval</p>
                        </div>
                    </div>
                </a>
            </div>


            <div class="dashboard_card">
                <a href="{{ route('students.index') }}" class="indexLink" style="text-decoration: none!important;">
                    <div class="custom-card ">
                        <div class="card-icon orange">
                            <i class="icon im im-icon-Map"></i>
                        </div>
                        <div class="card-content">
                            <h3 id="generated_certificate">
                                <div class="spinner-border" role="status"><span class="sr-only">Loading...</span></div>
                            </h3>
                            <p>Generated Certificate</p>
                        </div>
                    </div>
                </a>
            </div>



            <div class="col-md-12" style="padding: 8px 28px 1px 86px;">
                <div class="row" style="gap: 50px;">
                    <div class="col-md-5"
                        style="box-shadow: 0px 0px 7px 1px #bababa;background: #ffffff;border-radius: 7px;">
                        <div style="width: 100%; max-width: 270px; margin: 30px auto;">
                            <canvas id="studentPieChart"></canvas>
                        </div>
                    </div>
                    <div class="col-md-5"
                        style="box-shadow: 0px 0px 7px 1px #bababa;background: #ffffff;border-radius: 7px;">
                        <div style="width: 100%; max-width: 320px; margin: 30px auto;">
                            <canvas id="studentapprovalPieChart"></canvas>
                        </div>
                    </div>

                </div>
            </div>


        </div>
    </section>
@section('footer_scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        function fetchDashboardData() {
            $('#total_students').html('<div class="spinner-border" role="status"><span class="sr-only">Loading...</span></div>');
            $('#total_passed_students').html('<div class="spinner-border" role="status"><span class="sr-only">Loading...</span></div>');
            $('#total_failed_students').html('<div class="spinner-border" role="status"><span class="sr-only">Loading...</span></div>');
            $('#waiting_for_chairman').html('<div class="spinner-border" role="status"><span class="sr-only">Loading...</span></div>');
            $('#waiting_for_district').html('<div class="spinner-border" role="status"><span class="sr-only">Loading...</span></div>');
            $('#generated_certificate').html('<div class="spinner-border" role="status"><span class="sr-only">Loading...</span></div>');
        
            $.ajax({
                url: "{{ route('dashboard.data') }}",
                method: "GET",
                data: {
                    program_id: $('#dashboard_program').val(),
                    occupation_id: $('#dashboard_occupation').val()
                },
                success: function(data) {
                    program_type =data.program_type
                    if (program_type == 'General') {
                        $('#com_pass').html('Promising');
                        $('#com_fail').html('Optainane');

                        $('.indexLink').attr('href', "{{ route('general_students.index') }}");
                    } else {
                        $('#com_pass').html('Competent');
                        $('#com_fail').html('Not Competent yet');

                        $('.indexLink').attr('href', "{{ route('students.index') }}");
                    }
                    $('#total_students').html(data.total_students);
                    $('#total_passed_students').html(data.total_passed_students);
                    $('#total_failed_students').html(data.total_failed_students);
                    $('#waiting_for_chairman').html(data.waiting_for_chairman);
                    $('#waiting_for_district').html(data.waiting_for_district);
                    $('#generated_certificate').html(data.generated_certificate);
                    drawPieCharts(data);
                }
            });
        }

        function drawPieCharts(data) {
            new Chart(document.getElementById('studentPieChart'), {
                type: 'pie',
                data: {
                    labels: ['Promising', 'Optainane '],
                    datasets: [{
                        data: [data.total_passed_students, data.total_failed_students],
                        backgroundColor: ['#28a745', '#dc3545']
                    }]
                }
            });

            new Chart(document.getElementById('studentapprovalPieChart'), {
                type: 'pie',
                data: {
                    labels: ['Waiting for Chairman', 'Waiting for District', 'Generated Certificate'],
                    datasets: [{
                        data: [data.waiting_for_chairman, data.waiting_for_district, data
                            .generated_certificate
                        ],
                        backgroundColor: ['#ffc107', '#17a2b8', '#6c757d']
                    }]
                }
            });
        }

        $(document).ready(fetchDashboardData);
    </script>
@endsection


@stop
