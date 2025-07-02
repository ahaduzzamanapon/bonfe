@extends('layouts.default')

{{-- Page title --}}
@section('title')
    Lerner @parent
@stop

@section('content')

<style>
    .badge-warning {
    color: #212529!important;
    background-color: #ffc107;
}
</style>
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div aria-label="breadcrumb" class="card-breadcrumb">
            <h5><a href="{{ url('/') }}" style="text-decoration: none; color: black;">Dashboard</a> > Lerner </h5>
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
                <h5 class="card-title d-inline">Lerner</h5>
                <span class="float-right">
                    @if (can('assessment_centers_controller'))
                        <a class="btn btn-primary pull-right" onclick="forwardToDistrictAdmin_modal()">Forward to District Admin</a>
                    @endif
                    @if (can('district_admin'))
                        <a class="btn btn-primary pull-right" onclick="forwardToAssessmentCenter_modal()">Forward to Assessment Center</a>
                        <a class="btn btn-primary pull-right" onclick="forwardToAssessmentController_modal()">Forward to Assessment Controller</a>
                    @endif

                    @if (can('assessment_controller'))
                        <a class="btn btn-primary pull-right" onclick="forwardToChairman_modal()">Approve / Forward to Chairman</a>
                        <a class="btn btn-primary pull-right" onclick="backTodistrict_modal()">Back to District</a>
                    @endif
                    @if (can('chairman'))
                        <a class="btn btn-primary pull-right" onclick="approveStudent_modal()">Approve</a>
                    @endif
                    <a class="btn btn-primary pull-right" onclick="generateCertificate_modal()">Generate Certificate</a>
                    @if (can('student_add'))
                        @if( Request::is('general_students*'))
                            <a class="btn btn-primary pull-right" href="{{ route('general_students.create') }}">Add New</a>
                        @else
                            <a class="btn btn-primary pull-right" href="{{ route('students.create') }}">Add New</a>
                        @endif
                    @endif
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
                                        {{ Request::is('students') ? 'checked' : '' }}> All Lerner
                                </label>

                                @if (can('assessment_centers_controller'))
                                    <label
                                        class="btn btn-outline-primary {{ Request::is('students_waiting_for_assessment_center_approval') ? 'active' : '' }}">
                                        <input onchange="createTable()" class="form-check-input" type="radio"
                                            name="status_filter" id="waiting_for_assessment_center_approval"
                                            value="waiting_for_assessment_center_approval" autocomplete="off"
                                            {{ Request::is('students_waiting_for_assessment_center_approval') ? 'checked' : '' }}>
                                        Waiting for the exam results from the Assessment Center
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
                    if( Request::is('general_students*')){
                        $programs = \App\Models\Program::where('program_type', 'General')->latest()->get();
                    }else{
                        $programs = \App\Models\Program::where('program_type', 'Technical')->latest()->get();
                    }
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
                        tableBody.append(
                            '<tr id="loader_trr"><td colspan="7" class="text-center"><div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div></td></tr>'
                            );
                    }

                    function loader_off() {
                        $('#loader_trr').remove();
                    }
                </script>
                <script>
                    let offset = 0;
                    const limit = 50;
                    let loading = false;
                    let allLoaded = false;

                    function loadStudents() {
                        if (loading || allLoaded) return;
                        loading = true;

                        const statusFilter = $('input[name="status_filter"]:checked').val();
                        const programId = $('#filter_program').val();
                        const occupationId = $('#filter_occupation').val();
                        const programType = '{{ Request::is('general_students') ? "General" : "Technical" }}';


                        $.ajax({
                            url: "{{ route('students.get_table') }}",
                            method: "GET",
                            data: {
                                offset: offset,
                                limit: limit,
                                status_filter: statusFilter,
                                program_id: programId,
                                occupation_id: occupationId,
                                program_type: programType
                            },
                            success: function(data) {
                                if (data.students.length === 0) {
                                    allLoaded = true;
                                    return;
                                }

                                $.each(data.students, function(index, student) {
                                    var can_give_exam_result = {{ can('give_exam_result') ? 'true' : 'false' }};
                                    const row = `<tr>
                                        <td>${offset + index + 1}</td>
                                        <td>
                                            <div style="line-height: 1px;">
                                                <p style="font-weight: bold;color: #000">${student.candidate_name_bn}</p>
                                                <div style="line-height: 2px;">
                                                    <p style="font-size: 10px;"><strong>Occupation:</strong> ${student.occupation}</p>
                                                    <p style="font-size: 10px;"><strong>Regis. No:</strong> ${student.registration_number}</p>
                                                    <p style="font-size: 10px;"><strong>District:</strong> ${student.district}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td><span class="badge badge-${student.status === 'Pending' ? 'warning' : 'success'}">${student.status}</span></td>
                                        <td><span class="badge badge-${student.exam_status === 'Fail' ? 'danger' : student.exam_status === 'Pending' ? 'warning' : 'success'}">${
                                            programType === 'General'
                                                ? student.exam_status === 'Fail' ? 'Optainane ' : student.exam_status === 'Pending' ? 'Pending' : 'Promising'
                                                : student.exam_status === 'Fail' ? 'Not Competent yet ' : student.exam_status === 'Pending' ? 'Pending' : 'Competent'
                                        }</span></td>
                                        <td><span class="badge badge-${student.districts_admin_status === 'Pending' ? 'warning' : 'success'}">${student.districts_admin_status}</span></td>
                                        <td><span class="badge badge-${student.chairmen_status === 'Pending' ? 'warning' : 'success'}">${student.chairmen_status}</span></td>
                                        <td>
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-outline-primary btn-xs dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="im im-icon-List2" data-placement="top" title="Actions">Action</i>
                                                </button>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item" href="/students/${student.id}"><i class="im im-icon-Eye"></i> View</a>
                                                    ${student.status !== 'Chairman Approved' ? `
                                                        <a class="dropdown-item" href="/students/${student.id}/edit"><i class="im im-icon-Pen"></i> Edit</a>` : ''
                                                    }
                                                    ${student.status === 'Waiting for the exam results from the Assessment Center' && can_give_exam_result ? `
                                                        <a class="dropdown-item" onclick="give_exam_result(${student.id})" href="javascript:void(0);"><i class="im im-icon-Pencil-Ruler"></i> Give Exam Result</a>` : ''
                                                    }
                                                    ${student.status === 'Waiting for Chairman Approval' && can_chairman ? `
                                                        <a class="dropdown-item" href="/students/${student.id}/chairman-approve"><i class="im im-icon-Approved-Window"></i> Approve</a>` : ''
                                                    }
                                                    ${student.exam_status === 'Pending' ? `
                                                        <form method="POST" action="/students/${student.id}" onsubmit="return confirm('Are you sure?');">
                                                            <input type="hidden" name="_method" value="DELETE" />
                                                            <button class="dropdown-item" type="submit"><i class="im im-icon-Remove"></i> Delete</button>
                                                        </form>` : ''
                                                    }
                                                    ${student.status === 'Chairman Approved' && student.exam_status !== 'Fail' ? `
                                                        <a class="dropdown-item" target="_blank" href="/students/${student.id}/generate-certificate"><i class="im im-icon-People-onCloud"></i> Generate Certificate</a>` : ''
                                                    }
                                                </div>
                                            </div>
                                        </td>

                                    </tr>`;
                                    $('#students-table-body').append(row);
                                });
                                loader_off()

                                offset += limit;
                                loading = false;
                            },
                            error: function() {
                               
                                loading = false;
                            }
                        });
                    }

                    function setupScrollLazyLoading() {
                        $(window).scroll(function() {
                            if ($(window).scrollTop() + $(window).height() >= $(document).height() - 1500) {
                                loadStudents();
                            }
                        });
                    }

                    // Initialize
                    $(document).ready(function() {
                        offset = 0;
                        $('#students-table-body').empty();
                        loadStudents();
                        setupScrollLazyLoading();

                        // Optional: reload when filters change
                        $('#filter_program, #filter_occupation, input[name="status_filter"]').change(function() {
                            offset = 0;
                            allLoaded = false;
                            $('#students-table-body').empty();
                            loadStudents();
                        });
                    });


                    function createTable() {
                                                loader_on();

                        offset = 0;
                        allLoaded = false;
                        $('#students-table-body').empty();
                        loadStudents();
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
                        $.ajax({
                            url: "{{ route('get_competences_by_occupation') }}",
                            type: "GET",
                            data: {
                                id: id
                            },
                            success: function(data) {                                
                                $('#competence_pass_div').empty().html(data);                                
                            },
                            error: function() {
                            }
                        });
                    }
                </script>

            

        @endsection
    </div>
</div>
</div>
@endsection
