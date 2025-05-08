@extends('layouts.default')

{{-- Page title --}}
@section('title')
    Students @parent
@stop

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div aria-label="breadcrumb" class="card-breadcrumb">
            <h5><a href="{{ url('/') }}" style="text-decoration: none; color: black;">Dashboard</a> > Students </h5>
        </div>
        <div class="separator-breadcrumb border-top"></div>
    </section>

    <!-- Main content -->
    <div class="content">
        <div class="clearfix"></div>
        @include('flash::message')
        <div class="clearfix"></div>
        <div class="card" width="88vw;">
            <section class="card-header">
                <h5 class="card-title d-inline">Students</h5>
                <span class="float-right">
                    @if (can('assessment_centers_controller'))
                        <a class="btn btn-primary pull-right" onclick="forwardToDistrictAdmin_modal()">Forward to District Admin</a>
                    @endif
                    @if (can('district_admin'))
                        <a class="btn btn-primary pull-right" onclick="forwardToAssessmentController_modal()">Forward to Assessment Controller</a>
                        <a class="btn btn-primary pull-right" onclick="forwardToChairman_modal()">Approve / Forward to Chairman</a>
                    @endif
                    @if (can('chairman'))
                        <a class="btn btn-primary pull-right" onclick="approveStudent_modal()">Approve</a>
                    @endif
                    <a class="btn btn-primary pull-right" href="{{ route('students.create') }}">Add New</a>
                </span>
            </section>
            <div class="card-body table-responsive">
                <div class="row">
                    <div class="col-sm-12 col-md-7">
                        <div style="margin-bottom: 13px;">
                            <strong>Filter By:</strong>
                        </div>
                        <div class="form-group">
                            <div class="btn-group btn-group-toggle d-flex flex-wrap" data-toggle="buttons">
                                <label class="btn btn-outline-primary {{ Request::is('students') ? 'active' : '' }}">
                                    <input onchange="createTable()" class="form-check-input" type="radio"
                                        name="status_filter" id="all" value="all" autocomplete="off"
                                        {{ Request::is('students') ? 'checked' : '' }}> All Students
                                </label>

                                @if (can('assessment_centers_controller'))
                                    <label
                                        class="btn btn-outline-primary {{ Request::is('students_waiting_for_assessment_center_approval') ? 'active' : '' }}">
                                        <input onchange="createTable()" class="form-check-input" type="radio"
                                            name="status_filter" id="waiting_for_assessment_center_approval"
                                            value="waiting_for_assessment_center_approval" autocomplete="off"
                                            {{ Request::is('students_waiting_for_assessment_center_approval') ? 'checked' : '' }}>
                                        Waiting for District Approval
                                    </label>
                                @endif

                                @if (can('district_admin'))
                                    <label
                                        class="btn btn-outline-primary {{ Request::is('students_waiting_for_district_approval') ? 'active' : '' }}">
                                        <input onchange="createTable()" class="form-check-input" type="radio"
                                            name="status_filter" id="waiting_for_district_approval"
                                            value="waiting_for_district_approval" autocomplete="off"
                                            {{ Request::is('students_waiting_for_district_approval') ? 'checked' : '' }}>
                                        Waiting for District Approval
                                    </label>
                                @endif

                                @if (can('chairman'))
                                    <label
                                        class="btn btn-outline-primary {{ Request::is('students_waiting_for_chairman_approval') ? 'active' : '' }}">
                                        <input onchange="createTable()" class="form-check-input" type="radio"
                                            name="status_filter" id="waiting_for_chairman_approval"
                                            value="waiting_for_chairman_approval" autocomplete="off"
                                            {{ Request::is('students_waiting_for_chairman_approval') ? 'checked' : '' }}>
                                        Waiting for Chairman Approval
                                    </label>
                                @endif
                            </div>
                        </div>

                    </div>
                    @php
                        $programs = \App\Models\Program::latest()->get();
                        $occupations = \App\Models\Occupation::latest()->get();
                    @endphp

                    <div class="col-sm-12 col-md-5">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="search">Select Program:</label>
                                    <select id="filter_program" class="form-control">
                                        <option value="">All</option>
                                        @foreach ($programs as $key => $program)
                                            <option @if ($key == 0) selected @endif
                                                value="{{ $program->id }}">{{ $program->program_title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="search">Select Occupation:</label>
                                    <select id="filter_occupation" class="form-control">
                                        <option value="">All</option>
                                        @foreach ($occupations as $occupation)
                                            <option value="{{ $occupation->id }}">{{ $occupation->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <table class="table table-striped table-hover" id="students-table">
                    <thead>
                        <tr>
                            <th>SL</th>
                            <th>Candidate Details</th>
                            <th>Status</th>
                            <th>Result</th>
                            <th>District App.</th>
                            <th>Chairman App.</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="students-table-body">
                    </tbody>
                </table>

            @section('footer_scripts')
                <script>
                    function loader_on() {
                        const tableBody = $('#students-table-body');
                        tableBody.html(
                            '<tr><td colspan="7" class="text-center"><div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div></td></tr>'
                            );
                    }

                    function loader_off() {
                        const tableBody = $('#students-table-body');
                        tableBody.html('');
                    }
                </script>
                <script>
                    function createTable() {
                        const tableBody = $('#students-table-body');
                        const statusFilter = $('input[name="status_filter"]:checked').val();
                        const programId = $('#filter_program').val();
                        const occupationId = $('#filter_occupation').val();
                        loader_on()
                        $.ajax({
                            url: "{{ route('students.get_table') }}",
                            type: "GET",
                            data: {
                                status_filter: statusFilter,
                                program_id: programId,
                                occupation_id: occupationId
                            },
                            success: function(data) {
                                loader_off()
                                $('#students-table').DataTable().destroy();
                                tableBody.html(data.html);
                                $('#students-table').DataTable({
                                    order: [
                                        [0, 'asc']
                                    ],
                                    pageLength: 10,
                                    columnDefs: [{
                                        targets: [0],
                                        orderable: false
                                    }]
                                });
                            },
                            error: function() {
                                alert('Error fetching student data.');
                            }
                        });
                    }
                    $(document).ready(function() {
                        $('#filter_program, #filter_occupation').change(function() {
                            createTable();
                        });
                        createTable();
                    });
                </script>
                <script>
                    function give_exam_result(id) {
                        $('#exam_result_modal').modal('show');
                        localStorage.setItem('student_id_for_exam_result', id);
                    }
                </script>

            

        @endsection
    </div>
</div>
</div>
@endsection
