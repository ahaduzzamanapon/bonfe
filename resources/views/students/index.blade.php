@extends('layouts.default')

{{-- Page title --}}
@section('title')
    learner @parent
@stop

@section('content')

<style>
    .badge-warning {
    color: #212529!important;
    background-color: #ffc107;
}
</style>
    <!-- Content Header (Page header) -->
    {{-- <section class="content-header">
        <div aria-label="breadcrumb" class="card-breadcrumb">
            <h5><a href="{{ url('/') }}" style="text-decoration: none; color: black;">Dashboard</a> > learner </h5>
        </div>
        <div class="separator-breadcrumb border-top"></div>
    </section> --}}

    <!-- Main content -->
    <div class="content">
        <div class="clearfix"></div>
        @include('flash::message')
        <div class="clearfix"></div>
        <div class="card" width="88vw;">
            <section class="card-header">
                <h5 class="card-title d-inline">Learner</h5>
                <span class="float-right">

                    {{-- ── Quick Links Box ── --}}
                    <span class="border rounded px-2 py-1 mr-2" style="display:inline-flex;align-items:center;gap:6px;background:#f8f9fa;">
                        <small class="text-muted font-weight-bold mr-1">Quick Links:</small>

                        @if (can('district_admin') || can('chairman'))
                            <a class="btn btn-warning btn-sm" onclick="setAssessmentStatus_modal()" title="Set Ready/Dropout/Absent">Set Status</a>
                            <a class="btn btn-info btn-sm" onclick="forwardToAssistantRegistrar_modal()" title="Forward Ready students to Registrar">→ Registrar</a>
                        @endif
                        @if (can('assistant_registrar'))
                        <a class="btn btn-success btn-sm" onclick="giveRegistrationNumber_modal()">Give Reg. No.</a>
                        @endif

                        @if (can('assessment_centers_controller'))
                            <a class="btn btn-primary btn-sm" onclick="forwardToDistrictAdmin_modal()">→ District Admin</a>
                        @endif

                        @if (can('district_admin') || can('chairman'))
                            <a class="btn btn-primary btn-sm" onclick="forwardToAssessmentCenter_modal()">→ Assessment Center</a>
                            <a class="btn btn-primary btn-sm" onclick="forwardToAssessmentController_modal()">→ Assessment Controller</a>
                        @endif

                        @if (can('assessment_controller'))
                            <a class="btn btn-primary btn-sm" onclick="forwardToChairman_modal()">→ Chairman</a>
                            <a class="btn btn-danger btn-sm" onclick="backTodistrict_modal()">← Back to District</a>
                        @endif

                        @if (can('chairman'))
                            <a class="btn btn-success btn-sm" onclick="approveStudent_modal()">Approve</a>
                        @endif

                        <a class="btn btn-secondary btn-sm" onclick="generateCertificate_modal()">Certificate</a>
                    </span>

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
                    <div class="col-sm-12 col-md-12">
                        <div style="margin-bottom: 13px;">
                            <strong>Filter By:</strong>
                        </div>
                        <div class="form-group">
                            <div class="btn-group btn-group-toggle d-flex flex-wrap" data-toggle="buttons">
                                <label class="btn btn-outline-primary {{ Request::is('students') ? 'active' : '' }}">
                                    <input onchange="createTable()" class="form-check-input" type="radio"
                                        name="status_filter" id="all" value="all" autocomplete="off"
                                        {{ Request::is('students') ? 'checked' : '' }}> All learner
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
                                    <label
                                        class="btn btn-outline-primary {{ Request::is('students_back_to_district_approval') ? 'active' : '' }}">
                                        <input onchange="createTable()" class="form-check-input" type="radio"
                                            name="status_filter" id="back_to_district_approval"
                                            value="back_to_district_approval" autocomplete="off"
                                            {{ Request::is('students_back_to_district_approval') ? 'checked' : '' }}>
                                        Back to District
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

                                @if (can('assistant_registrar') || can('district_admin') || can('chairman'))
                                    <label class="btn btn-outline-success">
                                        <input onchange="createTable()" class="form-check-input" type="radio"
                                            name="status_filter" id="waiting_for_registration"
                                            value="waiting_for_registration" autocomplete="off">
                                        Waiting for Registration
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
                    $insatitutes = \App\Models\Insatitute::where('district', auth()->user()->district_id)->orderBy('insatitute_name')->get();
                    @endphp
                    <div class="col-sm-12 col-md-12">
                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="search">Program:</label>
                                    <select id="filter_program" class="form-control">
                                        <option value="">All</option>
                                        @foreach ($programs as $key => $program)
                                            <option @if ($key == 0) selected @endif
                                                value="{{ $program->id }}">{{ $program->program_title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="search">Trade(Course):</label>
                                    <select id="filter_occupation" class="form-control">
                                        <option value="">All</option>
                                        @foreach ($occupations as $occupation)
                                            <option value="{{ $occupation->id }}">{{ $occupation->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="filter_institution">Institution:</label>
                                    <select id="filter_institution" class="form-control">
                                        <option value="">All</option>
                                        @foreach ($insatitutes as $inst)
                                            <option value="{{ $inst->id }}">{{ $inst->insatitute_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            @php
                                if (can('district_admin') || can('assessment_centers_controller')) {
                                    $districts = \App\Models\District::where('id', auth()->user()->district_id)
                                        ->pluck('name_en', 'id')
                                        ->toArray();
                                    $upazilas = \App\Models\Upazila::where('dis_id', auth()->user()->district_id)
                                        ->pluck('name_en', 'id')
                                        ->prepend('Select Upazila', '')
                                        ->toArray();
                                } else {
                                    $districts = \App\Models\District::all()->pluck('name_en', 'id')->prepend('Select District', '')->toArray();
                                    $upazilas = \App\Models\Upazila::all()->pluck('name_en', 'id')->prepend('Select Upazila', '')->toArray();
                                }
                            @endphp

                            
                            <!-- district_id Field -->
                            <div class="col-md-2 @if(can('district_admin') || can('assessment_centers_controller')) d-none @endif">
                                <div class="form-group">
                                    {!! Form::label('district_id', 'District', ['class' => 'control-label']) !!}
                                    {!! Form::select('district_id', $districts, null, ['class' => 'form-control select2']) !!}
                                </div>
                            </div>


                            <!-- Upajila Id Field -->
                            <div class="col-md-2 @if(can('district_admin') || can('assessment_centers_controller')) d-none @endif">
                                <div class="form-group">
                                    {!! Form::label('upajila_id', 'Upazila/City', ['class' => 'control-label']) !!}
                                    {!! Form::select('upajila_id', $upazilas, null, ['class' => 'form-control select2']) !!}
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    {!! Form::label('search_term', 'Search', ['class' => 'control-label']) !!}
                                    {!! Form::text('search_term', null, ['class' => 'form-control']) !!}
                                </div>
                            </div>
                            <div class="col-md-1">
                                {!! Form::label('search_term', '.', ['class' => 'control-label text-white']) !!}
                               <a class="btn btn-primary" href="#" onclick="createTable()">Search</a>
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
                        const district_id = $('#district_id').val();
                        const upajila_id = $('#upajila_id').val();
                        const search_term = $('#search_term').val();
                        const institution_id = $('#filter_institution').val();
                        const programType = '{{ Request::is('general_students') ? "General" : "Technical" }}';
                        console.log('loadStudents');

                        $.ajax({
                            url: "{{ route('students.get_table') }}",
                            method: "GET",
                            data: {
                                offset: offset,
                                limit: limit,
                                status_filter: statusFilter,
                                program_id: programId,
                                occupation_id: occupationId,
                                program_type: programType,
                                district_id: district_id,
                                upajila_id: upajila_id,
                                search_term: search_term,
                                institution_id: institution_id

                            },
                            success: function(data) {
                                
                                if (data.students.length === 0) {
                                    loading = false;
                                    allLoaded = true;
                                    return;
                                }

                                $.each(data.students, function(index, student) {
                                    var can_give_exam_result = {{ can('give_exam_result') ? 'true' : 'false' }};
                                    var can_chairman = {{ can('chairman') ? 'true' : 'false' }};
                                    const row = `<tr>
                                        <td>${offset + index + 1}</td>
                                        <td>
                                            <div style="line-height: 1px;">
                                                <p style="font-weight: bold;color: #000">${student.candidate_name_bn}</p>
                                                <div style="line-height: 2px;">
                                                    <p style="font-size: 10px;"><strong>Trade(Course):</strong> ${student.occupation}</p>
                                                    <p style="font-size: 10px;"><strong>Institution:</strong> ${student.insatitute_name ?? ''}</p>
                                                    <p style="font-size: 10px;"><strong>Regis. No:</strong> ${student.registration_number}</p>
                                                    <p style="font-size: 10px;"><strong>Candidate. No:</strong> ${student.candidate_id}</p>
                                                    <p style="font-size: 10px;"><strong>District:</strong> ${student.district}</p>
                                                    <p style="font-size: 10px;"><strong>Certificate No:</strong> ${student.certificate_number}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge badge-${student.status === 'Pending' ? 'warning' : 'success'}">${student.status === 'Waiting for District Admin Approval' && student.controller_back_comments!=null? 'Back to District Admin' : student.status}</span>
                                            <br>
                                            ${student.status === 'Waiting for District Admin Approval' && student.controller_back_comments != null ? `<p style="width: 160px;white-space: break-spaces;">Comment: ${student.controller_back_comments}</p>` : ''}

                                        </td>





                                        <td>
    <!-- Status Badge -->
    <span class="badge badge-${
        student.exam_status === 'Fail' ? 'danger' : 
        student.exam_status === 'Absent' ? 'secondary' :
        (student.exam_status === 'Pending' || student.exam_status === 'Ready for Assessment') ? 'warning' : 
        'success'
    }">
        ${
            student.exam_status === 'Absent' ? 'Absent' :
            programType === 'General'
                ? student.exam_status === 'Fail' ? 'Obtained' 
                : student.exam_status === 'Ready for Assessment' ? 'Ready for Assessment'
                : student.exam_status === 'Pending' ? 'Pending' : 'Promising'
                
                : student.exam_status === 'Fail' ? 'Not Competent yet' 
                : student.exam_status === 'Ready for Assessment' ? 'Ready for Assessment'
                : student.exam_status === 'Pending' ? 'Pending' : 'Competent'
        }
    </span>
    
    <br><br>
    
    <!-- Action Button -->
    <a style="background: #ffc107; padding: 4px; color: black; border-radius: 4px; cursor: pointer;" 
       onclick="viewResult(${student.id})">
       View result sheet
    </a>
</td>
                                        <td><span class="badge badge-${student.districts_admin_status === 'Pending' ? 'warning' : 'success'}">${student.districts_admin_status}</span></td>
                                        <td><span class="badge badge-${student.chairmen_status === 'Pending' ? 'warning' : 'success'}">${student.chairmen_status}</span></td>
                                        <td>
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-outline-primary btn-xs dropdown-toggle" data-toggle="dropdown" data-boundary="viewport" aria-haspopup="true" aria-expanded="false">
                                                    <i class="im im-icon-List2" data-placement="top" title="Actions">Action</i>
                                                </button>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item" href="/students/${student.id}"><i class="im im-icon-Eye"></i> View</a>
                                                    ${student.status !== 'Chairman Approved' || student.program_id === 1 ? `
                                                        <a class="dropdown-item" href="/students/${student.id}/edit"><i class="im im-icon-Pen"></i> Edit</a>` : ''
                                                    }
                                                    ${student.exam_status === 'Ready for Assessment' && can_give_exam_result ? `
                                                        <a class="dropdown-item" onclick="give_exam_result(${student.id})" href="javascript:void(0);"><i class="im im-icon-Pencil-Ruler"></i> Give Exam Result</a>` : ''
                                                    }
                                                   
                                                    ${student.status == 'Chairman Approved' && student.certificate_number==null ? `
                                                        <a class="dropdown-item" onclick="give_certificate_number(${student.id})" href="javascript:void(0);"><i class="im im-icon-People-onCloud"></i> Give Certificate Number</a>` : ''
                                                    }
                                                   
                                                    ${student.exam_status === 'Pending' ? `
                                                        <form method="POST" action="/students/${student.id}" onsubmit="return confirm('Are you sure?');">
                                                        @csrf
                                                            <input type="hidden" name="_method" value="DELETE" />
                                                            <button class="dropdown-item" type="submit"><i class="im im-icon-Remove"></i> Delete</button>
                                                        </form>` : ''
                                                    }
                                                    ${student.status === 'Chairman Approved' ? `
                                                        <a class="dropdown-item" target="_blank" href="/students/${student.id}/generate-certificate"><i class="im im-icon-People-onCloud"></i> Generate Certificate</a>` : ''
                                                    }
                                                    ${student.registration_number ? `
                                                        <a class="dropdown-item" target="_blank" href="/students/${student.id}/registration-card"><i class="im im-icon-ID-Card"></i> Registration Card</a>` : ''
                                                    }

                                                    ${student.exam_status === 'Fail' ? `
                                                        <div class="dropdown-divider"></div>
                                                        <a class="dropdown-item text-warning" href="/reassessments?search=${student.registration_number}"><i class="im im-icon-Student-Hat"></i> Re-Assessment</a>` : ''
                                                    }

                                                    ${student.status === 'Chairman Approved' && student.certificate_number ? `
                                                        <div class="dropdown-divider"></div>
                                                        <a class="dropdown-item text-info" href="/certificate-corrections/create/${student.id}"><i class="im im-icon-Diploma-1"></i> Apply Cert. Correction</a>
                                                        <a class="dropdown-item text-secondary" href="/certificate-corrections/versions/${student.id}"><i class="im im-icon-File"></i> Cert. History</a>` : ''
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

                        // // Optional: reload when filters change
                        // $('#filter_program, #filter_occupation, input[name="status_filter"]').change(function() {
                        //     offset = 0;
                        //     allLoaded = false;
                        //     $('#students-table-body').empty();
                        //     loadStudents();
                        // });
                    });

                     $('#district_id').change(function() {
                    var districtId = $(this).val();
                    $.ajax({
                        url: "{{ route('get_upazilas') }}",
                        type: "GET",
                        data: {
                            district_id: districtId
                        },
                        success: function(data) {
                            $('#upajila_id').empty();
                            $('#upajila_id').append('<option value="">Select Upazila</option>');
                            $.each(data, function(index, upajila) {
                                $('#upajila_id').append('<option value="' + upajila.id + '">' + upajila.name + '</option>');
                            });
                        }
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
                        $('#filter_program, #filter_occupation, #district_id, #upajila_id, #search_term, #filter_institution').change(function() {
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
                <script>
                    function give_candidate_id(id) {
                        $('#give_candidate_id_modal').modal('show');
                        localStorage.setItem('give_candidate_id', id);
                    }
                    function give_certificate_number(id) {
                        $('#give_certificate_number_modal').modal('show');
                        localStorage.setItem('give_certificate_number', id);
                    }
                </script>

            

        @endsection
    </div>
</div>
</div>
@endsection
