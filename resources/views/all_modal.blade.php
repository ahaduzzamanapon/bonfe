{{-- exam result modal --}}
<!-- Modal -->
<div class="modal fade" id="exam_result_modal" tabindex="-1" role="dialog" aria-labelledby="exam_result_modalTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Exam Result</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                    onclick="$('#exam_result_modal').modal('hide')">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="form-group">
                        <label for="ExamResult_field">Exam Result</label>
                        <select class="form-control" id="ExamResult_field">
                            @if(Request::is('general_students*'))
                                <option value="Passed"> Promising </option>
                                <option value="Fail"> Optainane </option>
                            @else
                                <option value="Passed"> Competent </option>
                                <option value="Fail"> Not Yet Competent </option>
                                <option value="Absent"> Absent </option>
                            @endif
                        </select>
                    </div>
                    <div style="padding: 10px;">
                        <label for="ExamResult_field">Competence</label>
                        <div id="competence_pass_div" style="border: 1px solid;padding: 10px;">

                        </div>
                    </div>
                    <script>
                        document.getElementById('ExamResult_field').addEventListener('change', function () {
                            var competenceDiv = document.getElementById('competence_pass_div').parentElement;
                            if (this.value === 'Absent') {
                                competenceDiv.style.display = 'none';
                                document.getElementsByName('competence_ids[]').forEach(input => {
                                    input.checked = false;
                                });
                            } else {
                                competenceDiv.style.display = '';
                                if (this.value === 'Passed') {
                                    document.getElementsByName('competence_ids[]').forEach(input => {
                                        input.checked = true;
                                    });
                                } else {
                                    document.getElementsByName('competence_ids[]').forEach(input => {
                                        input.checked = false;
                                    });
                                }
                            }
                        });
                    </script>
                    <div class="form-group">
                        <label for="ExamResult_field">Exam Result Sheet</label>
                        <input type="file" class="form-control" id="ExamResultSheet_field"
                            accept=".pdf, .doc, .docx, .xls, .xlsx, .ppt, .pptx" name="ExamResultSheet_field" required>
                        <small class="form-text text-muted">Upload a file with the exam result. Accepted formats: .pdf,
                            .doc, .docx, .xls, .xlsx, .ppt, .pptx</small>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="$('#exam_result_modal').modal('hide')"
                    data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="submit_exam_result()">Save changes</button>
            </div>
        </div>
    </div>
</div>
<script>
    function submit_exam_result() {
        const examResult = $('#ExamResult_field').val();
        const examResultSheet = $('#ExamResultSheet_field')[0].files[0];
        const allowedExtensions = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx'];

        if (examResultSheet) {
            const fileExtension = examResultSheet.name.split('.').pop().toLowerCase();
            if (!allowedExtensions.includes(fileExtension)) {
                alert('Invalid file type. Please upload a file with one of the following extensions: ' + allowedExtensions.join(', '));
                return false;
            }
        }

        const studentId = localStorage.getItem('student_id_for_exam_result');

        if (!examResult || !studentId) {
            alert('Please select a result and ensure a student ID is set');
            return false;
        }

        var checkedCompetences = [];
        $('input[name="competence_ids[]"]:checked').each(function () {
            checkedCompetences.push($(this).val());
        });

        const formData = new FormData();
        formData.append('_token', '{{ csrf_token() }}');
        formData.append('examResult', examResult);
        formData.append('studentId', studentId);
        if (examResultSheet) {
            formData.append('examResultSheet', examResultSheet);
        }
        formData.append('checkedCompetences', checkedCompetences);

        $.ajax({
            url: '{{ route('submit_exam_result') }}',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function () {
                alert('Result submitted successfully');
                $('#exam_result_modal').modal('hide');
                createTable();
            },
            error: function () {
                alert('Failed to submit exam result');
            }
        });
    }
</script>
{{-- exam result modal --}}

{{--give_candidate_id_modal --}}
<!-- Modal -->
<div class="modal fade" id="give_candidate_id_modal" tabindex="-1" role="dialog"
    aria-labelledby="give_candidate_id_modalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Give Registration Number</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                    onclick="$('#give_candidate_id_modal').modal('hide')">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <label for="candidate_id_field">Registration number</label>
                        <input type="text" class="form-control" id="candidate_id_field">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="$('#give_candidate_id_modal').modal('hide')"
                    data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="give_candidate_id_submit()">Save changes</button>
            </div>
        </div>
    </div>
</div>
<script>
    function give_candidate_id_submit() {
        candidate_id_field = $('#candidate_id_field').val();
        $('#candidate_id_field').val('');
        const studentId = localStorage.getItem('give_candidate_id');

        if (!studentId && candidate_id_field === '') {
            alert('Please select a result and ensure a student ID is set');
            return false;
        }



        const formData = new FormData();
        formData.append('_token', '{{ csrf_token() }}');
        formData.append('candidate_id_field', candidate_id_field);
        formData.append('studentId', studentId);

        $.ajax({
            url: '{{ route('give_candidate_id_submit') }}',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function () {
                alert('Result submitted successfully');
                $('#give_candidate_id_modal').modal('hide');
                createTable();
            },
            error: function () {
                alert('Failed to submit exam result');
            }
        });
    }
</script>
{{-- give_candidate_id_modal --}}

{{--give_certificate_number_modal --}}
<!-- Modal -->
<div class="modal fade" id="give_certificate_number_modal" tabindex="-1" role="dialog"
    aria-labelledby="give_certificate_number_modalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Give Certificate Number</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                    onclick="$('#give_certificate_number_modal').modal('hide')">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <label for="certificate_number">Certificate number</label>
                        <input type="text" class="form-control" id="certificate_number">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="$('#give_certificate_number_modal').modal('hide')"
                    data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="give_certificate_number_submit()">Save changes</button>
            </div>
        </div>
    </div>
</div>
<script>
    function give_certificate_number_submit() {
        certificate_number = $('#certificate_number').val();
        const studentId = localStorage.getItem('give_certificate_number');

        if (!studentId && certificate_number === '') {
            alert('Please select a result and ensure a student ID is set');
            return false;
        }



        const formData = new FormData();
        formData.append('_token', '{{ csrf_token() }}');
        formData.append('certificate_number', certificate_number);
        formData.append('studentId', studentId);

        $.ajax({
            url: '{{ route('give_certificate_number_submit') }}',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function () {
                alert('Result submitted successfully');
                $('#give_certificate_number_modal').modal('hide');
                createTable();
            },
            error: function () {
                alert('Failed to submit exam result');
            }
        });
    }
</script>
{{-- give_candidate_id_modal --}}





{{-- forwardToAssessmentCenter_modal start --}}
<!-- Modal -->
<div class="modal fade" id="forwardToAssessmentCenter_modal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId"
    aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Forward to Assessment Center</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                    onclick="$('#forwardToAssessmentCenter_modal').modal('hide')">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <a class="btn btn-primary" href="javascript:void(0)" onclick="selectAllStudents()">
                    Select All</a>
                <div id="forwardToAssessmentCenter_modal_body" style="overflow-y: scroll;height: 50vh;">
                </div>
                <div class="col-md-12 d-none assessment_details">
                    <div class="row">
                        @php
                            $AssessmentCenter = \App\Models\AssessmentCenter::all()
                                ->pluck('center_name', 'id')
                                ->prepend('Select Center', '')
                                ->toArray();
                        @endphp
                        <!-- Assessment Center Field -->
                        <div class="col-md-4">
                            <div class="form-group">
                                {!! Form::label('assessment_center', 'Assessment Center', ['class' => 'control-label']) !!}
                                {!! Form::select('assessment_center_id', $AssessmentCenter, null, [
    'class' => 'form-control',
    'id' => 'assessment_center_id',
]) !!}
                            </div>
                        </div>
                        <!-- Assessment Date Field -->
                        <div class="col-md-4">
                            <div class="form-group">
                                {!! Form::label('assessment_date', 'Assessment Date', ['class' => 'control-label']) !!}
                                {!! Form::text('assessment_date_field', null, [
    'class' => 'form-control date',
    'id' => 'assessment_date_field',
    'autocomplete' => 'off',
]) !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal"
                    onclick="$('#forwardToAssessmentCenter_modal').modal('hide')">Close</button>
                <button type="button" class="btn btn-primary" id="forwardToAssessmentCenter_modal_button"
                    onclick="forwardToAssessmentCenter_submit()">Forward
                    to Assessment Center</button>
            </div>
        </div>
    </div>
</div>
<script>
    function forwardToAssessmentCenter_modal_body_loader_on() {
        const forwardToAssessmentCenter_modal_body = $('#forwardToAssessmentCenter_modal_body');
        forwardToAssessmentCenter_modal_body.html(
            '<div class="text-center"><div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div></div>'
        );
    }

    function forwardToAssessmentCenter_modal() {
        $('#forwardToAssessmentCenter_modal').modal('show');
        $('#forwardToAssessmentCenter_modal_button').prop('disabled', true);
        forwardToAssessmentCenter_modal_body_loader_on()
        filter_occupation = $('#filter_occupation').val();
        filter_program = $('#filter_program').val();
        district_id = $('#district_id').val();
        upajila_id = $('#upajila_id').val();
        search_term = $('#search_term').val();


        $.ajax({
            url: '{{ route('forwardToAssessmentCenter_modal') }}',
            type: 'GET',
            data: {
                filter_occupation: filter_occupation,
                filter_program: filter_program,
                district_id: district_id,
                upajila_id: upajila_id,
                search_term: search_term,
            },
            success: function (data) {
                $('#forwardToAssessmentCenter_modal_body').html(data);
            }
        })
    }

    function forwardToAssessmentCenter_select() {
        var student_ids_forwardToAssessmentCenter = $('.student_ids_forwardToAssessmentCenter');
        var selected_ids = student_ids_forwardToAssessmentCenter.filter(':checked').map(function () {
            return this.value;
        }).get();
        if (selected_ids.length > 0) {
            $('.assessment_details').hide().removeClass('d-none').fadeIn();
            $('#forwardToAssessmentCenter_modal_button').prop('disabled', false);
        } else {
            $('.assessment_details').hide().addClass('d-none').fadeOut();
            $('#forwardToAssessmentCenter_modal_button').prop('disabled', true);
        }
    }


    function forwardToAssessmentCenter_submit() {

        var student_ids_forwardToAssessmentCenter = $(
            '.student_ids_forwardToAssessmentCenter');
        var selected_ids = student_ids_forwardToAssessmentCenter.filter(':checked').map(
            function () {
                return this.value;
            }).get();
        if (selected_ids.length > 0) {
            var assessment_center_id = $('#assessment_center_id').val();
            var assessment_date = $('#assessment_date_field').val();
            console.log(assessment_center_id, assessment_date)
            if (!assessment_center_id || !assessment_date) {
                alert('Please select an assessment center and date.');
                return false;
            }
            $.ajax({
                url: '{{ route('forwardToAssessmentCenter_send') }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    student_ids_forwardToAssessmentCenter: selected_ids,
                    assessment_center_id: assessment_center_id,
                    assessment_date: assessment_date
                },
                success: function (data) {
                    if (data.success) {
                        alert(data.message);
                        $('#forwardToAssessmentCenter_modal').modal('hide');
                        createTable()
                    } else {
                        alert(data.message);
                    }
                },
                error: function () {
                    alert('Failed to forward to Assessment Center');
                }
            });
        } else {
            alert('Please select at least one student to forward.');
        }
    }
</script>
{{-- forwardToAssessmentCenter_modal end --}}


{{-- forwardToDistrictAdmin_modal start --}}
<!-- Modal -->
<div class="modal fade" id="forwardToDistrictAdmin_modal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId"
    aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Forward to District Admin</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                    onclick="$('#forwardToDistrictAdmin_modal').modal('hide')">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <a class="btn btn-primary" href="javascript:void(0)" onclick="selectAllStudents()">
                    Select All</a>
                <div id="forwardToDistrictAdmin_modal_body" style="overflow-y: scroll;height: 50vh;">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal"
                    onclick="$('#forwardToDistrictAdmin_modal').modal('hide')">Close</button>
                <button type="button" class="btn btn-primary" id="forwardToDistrictAdmin_modal_button"
                    onclick="forwardToDistrictAdmin_submit()">Forward
                    to District Admin</button>
            </div>
        </div>
    </div>
</div>

<script>
    function forwardToDistrictAdmin_modal_body_loader_on() {
        const forwardToDistrictAdmin_modal_body = $('#forwardToDistrictAdmin_modal_body');
        forwardToDistrictAdmin_modal_body.html(
            '<div class="text-center"><div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div></div>'
        );
    }
    function forwardToDistrictAdmin_modal() {
        console.log('forwardToDistrictAdmin_modal');

        $('#forwardToDistrictAdmin_modal').modal('show');
        $('#forwardToDistrictAdmin_modal_button').prop('disabled', true);
        forwardToDistrictAdmin_modal_body_loader_on()
        $.ajax({
            url: '{{ route('forwardToDistrictAdmin_modal') }}',
            type: 'GET',
            success: function (data) {
                $('#forwardToDistrictAdmin_modal_body').html(data);
            }
        })
    }

    function forwardToDistrictAdmin_select() {
        console.log('forwardToDistrictAdmin_select');
        var student_ids_forwardToDistrictAdmin = $('.student_ids_forwardToDistrictAdmin');
        var selected_ids = student_ids_forwardToDistrictAdmin.filter(':checked').map(function () {
            return this.value;
        }).get();
        if (selected_ids.length > 0) {
            $('#forwardToDistrictAdmin_modal_button').prop('disabled', false);
        } else {
            $('#forwardToDistrictAdmin_modal_button').prop('disabled', true);
        }
    }


    function forwardToDistrictAdmin_submit() {

        var student_ids_forwardToDistrictAdmin = $(
            '.student_ids_forwardToDistrictAdmin');
        var selected_ids = student_ids_forwardToDistrictAdmin.filter(':checked').map(
            function () {
                return this.value;
            }).get();
        if (selected_ids.length > 0) {

            $.ajax({
                url: '{{ route('forwardToDistrictAdmin_send') }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    student_ids_forwardToDistrictAdmin: selected_ids,
                },
                success: function (data) {
                    if (data.success) {
                        alert(data.message);
                        $('#forwardToDistrictAdmin_modal').modal('hide');
                        createTable()
                    } else {
                        alert(data.message);
                    }
                },
                error: function () {
                    alert('Failed to forward to District Admin');
                }
            });
        } else {
            alert('Please select at least one student to forward.');
        }
    }
</script>


{{-- forwardToDistrictAdmin_modal end --}}


{{-- forwardToAssessmentController_modal start --}}
<!-- Modal -->
<div class="modal fade" id="forwardToAssessmentController_modal" tabindex="-1" role="dialog"
    aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Forward to Assessment Controller</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                    onclick="$('#forwardToAssessmentController_modal').modal('hide')">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <a class="btn btn-primary" href="javascript:void(0)" onclick="selectAllStudents()">
                    Select All</a>
                <div id="forwardToAssessmentController_modal_body" style="overflow-y: scroll;height: 50vh;">
                </div>
                <div>
                    <input type="checkbox" name="diss_accept" id="diss_accept"
                        onchange="forwardToAssessmentController_select()">
                    <label for="">I have verified the result and confirm its accuracy.</label>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal"
                    onclick="$('#forwardToAssessmentController_modal').modal('hide')">Close</button>
                <button type="button" class="btn btn-primary" id="forwardToAssessmentController_modal_button"
                    onclick="forwardToAssessmentController_submit()">Forward
                    to Assessment Controller</button>
            </div>
        </div>
    </div>
</div>

<script>
    function forwardToAssessmentController_modal_body_loader_on() {
        const forwardToAssessmentController_modal_body = $('#forwardToAssessmentController_modal_body');
        forwardToAssessmentController_modal_body.html(
            '<div class="text-center"><div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div></div>'
        );
    }
    function forwardToAssessmentController_modal() {
        console.log('forwardToAssessmentController_modal');

        $('#forwardToAssessmentController_modal').modal('show');
        $('#forwardToAssessmentController_modal_button').prop('disabled', true);
        forwardToAssessmentController_modal_body_loader_on();
        
        var filter_program = $('#filter_program').val();

        $.ajax({
            url: '{{ route('forwardToAssessmentController_modal') }}',
            type: 'GET',
            data: {
                filter_program: filter_program
            },
            success: function (data) {
                $('#forwardToAssessmentController_modal_body').html(data);
            }
        })
    }

    function forwardToAssessmentController_select() {
        console.log('forwardToAssessmentController_select');
        var student_ids_forwardToAssessmentController = $('.student_ids_forwardToAssessmentController');
        var diss_accept = $('#diss_accept');


        var selected_ids = student_ids_forwardToAssessmentController.filter(':checked').map(function () {
            return this.value;
        }).get();
        if (selected_ids.length > 0 && diss_accept.prop('checked')) {
            $('#forwardToAssessmentController_modal_button').prop('disabled', false);
        } else {
            $('#forwardToAssessmentController_modal_button').prop('disabled', true);
        }
    }

    function forwardToAssessmentController_submit() {
        var student_ids_forwardToAssessmentController = $('.student_ids_forwardToAssessmentController');
        var selected_ids = student_ids_forwardToAssessmentController.filter(':checked').map(
            function () {
                return this.value;
            }).get();
        if (selected_ids.length > 0) {
            $.ajax({
                url: '{{ route('forwardToAssessmentController_send') }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    student_ids_forwardToAssessmentController: selected_ids,
                },
                success: function (data) {
                    if (data.success) {
                        alert(data.message);
                        $('#forwardToAssessmentController_modal').modal('hide');
                        createTable();
                    } else {
                        alert(data.message);
                    }
                },
                error: function () {
                    alert('Failed to forward to Assessment Center');
                }
            });
        } else {
            alert('Please select at least one student to forward.');
        }
    }



</script>

{{-- forwardToAssessmentController_modal end --}}


{{-- forwardToChairman_modal start --}}
<!-- Modal -->
<div class="modal fade" id="forwardToChairman_modal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId"
    aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Forward to Chairman</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                    onclick="$('#forwardToChairman_modal').modal('hide')">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <a class="btn btn-primary" href="javascript:void(0)" onclick="selectAllStudents()">
                    Select All</a>
                <div id="forwardToChairman_modal_body" style="overflow-y: scroll;height: 50vh;">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal"
                    onclick="$('#forwardToChairman_modal').modal('hide')">Close</button>
                <button type="button" class="btn btn-primary" id="forwardToChairman_modal_button"
                    onclick="forwardToChairman_submit()">Forward
                    to Chairman</button>
            </div>
        </div>
    </div>
</div>

<script>
    function forwardToChairman_modal_body_loader_on() {
        const forwardToChairman_modal_body = $('#forwardToChairman_modal_body');
        forwardToChairman_modal_body.html(
            '<div class="text-center"><div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div></div>'
        );
    }
    function forwardToChairman_modal() {
        console.log('forwardToChairman_modal');

        $('#forwardToChairman_modal').modal('show');
        $('#forwardToChairman_modal_button').prop('disabled', true);
        forwardToChairman_modal_body_loader_on();
        $.ajax({
            url: '{{ route('forwardToChairman_modal') }}',
            type: 'GET',
             data: {
                filter_program: filter_program
            },
            success: function (data) {
                $('#forwardToChairman_modal_body').html(data);
            }
        })
    }

    function forwardToChairman_select() {
        console.log('forwardToChairman_select');
        var student_ids_forwardToChairman = $('.student_ids_forwardToChairman');
        var selected_ids = student_ids_forwardToChairman.filter(':checked').map(function () {
            return this.value;
        }).get();
        if (selected_ids.length > 0) {
            $('#forwardToChairman_modal_button').prop('disabled', false);
        } else {
            $('#forwardToChairman_modal_button').prop('disabled', true);
        }
    }

    function forwardToChairman_submit() {
        var student_ids_forwardToChairman = $('.student_ids_forwardToChairman');
        var selected_ids = student_ids_forwardToChairman.filter(':checked').map(
            function () {
                return this.value;
            }).get();
        if (selected_ids.length > 0) {
            $.ajax({
                url: '{{ route('forwardToChairman_send') }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    student_ids_forwardToChairman: selected_ids,
                },
                success: function (data) {
                    if (data.success) {
                        alert(data.message);
                        $('#forwardToChairman_modal').modal('hide');
                        createTable();
                    } else {
                        alert(data.message);
                    }
                },
                error: function () {
                    alert('Failed to forward to Chairman');
                }
            });
        } else {
            alert('Please select at least one student to forward.');
        }
    }
</script>

{{-- forwardToChairman_modal end --}}


{{-- approveStudent_modal start --}}
<!-- Modal -->
<div class="modal fade" id="approveStudent_modal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId"
    aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Approve Student</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                    onclick="$('#approveStudent_modal').modal('hide')">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <a class="btn btn-primary" href="javascript:void(0)" onclick="selectAllStudents()">
                    Select All</a>
                <div id="approveStudent_modal_body" style="overflow-y: scroll;height: 50vh;">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal"
                    onclick="$('#approveStudent_modal').modal('hide')">Close</button>
                <button type="button" class="btn btn-primary" id="approveStudent_modal_button"
                    onclick="approveStudent_submit()">Approve
                    Student</button>
            </div>
        </div>
    </div>
</div>

<script>
    function approveStudent_modal_body_loader_on() {
        const approveStudent_modal_body = $('#approveStudent_modal_body');
        approveStudent_modal_body.html(
            '<div class="text-center"><div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div></div>'
        );
    }
    function approveStudent_modal() {
        console.log('approveStudent_modal');

        $('#approveStudent_modal').modal('show');
        $('#approveStudent_modal_button').prop('disabled', true);
        approveStudent_modal_body_loader_on();
        $.ajax({
            url: '{{ route('approveStudent_modal') }}',
            type: 'GET',
            success: function (data) {
                $('#approveStudent_modal_body').html(data);
            }
        })
    }

    function approveStudent_select() {
        console.log('approveStudent_select');
        var student_ids_approveStudent = $('.student_ids_approveStudent');
        var selected_ids = student_ids_approveStudent.filter(':checked').map(function () {
            return this.value;
        }).get();
        if (selected_ids.length > 0) {
            $('#approveStudent_modal_button').prop('disabled', false);
        } else {
            $('#approveStudent_modal_button').prop('disabled', true);
        }
    }
    function approveStudent_submit() {
        var student_ids_approveStudent = $('.student_ids_approveStudent');
        var selected_ids = student_ids_approveStudent.filter(':checked').map(
            function () {
                return this.value;
            }).get();
        if (selected_ids.length > 0) {
            $.ajax({
                url: '{{ route('approveStudent_send') }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    student_ids_approveStudent: selected_ids,
                },
                success: function (data) {
                    if (data.success) {
                        alert(data.message);
                        $('#approveStudent_modal').modal('hide');
                        createTable();
                    } else {
                        alert(data.message);
                    }
                },
                error: function () {
                    alert('Operation successfull');
                    $('#approveStudent_modal').modal('hide');
                    createTable();
                }
            });
        } else {
            alert('Please select at least one student to approve.');
        }
    }
</script>

{{-- approveStudent_modal end --}}

{{-- backToDistrict_modal start --}}
<!-- Modal -->
<div class="modal fade" id="backToDistrict_modal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId"
    aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Send Back to District</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                    onclick="$('#backToDistrict_modal').modal('hide')">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <a class="btn btn-primary" href="javascript:void(0)" onclick="selectAllStudents()">
                    Select All</a>
                <div id="backToDistrict_modal_body" style="overflow-y: scroll;height: 50vh;">
                </div>
              
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal"
                    onclick="$('#backToDistrict_modal').modal('hide')">Close</button>
                <button type="button" class="btn btn-primary" id="backToDistrict_modal_button"
                    onclick="backToDistrict_submit()">Send Back
                    to District</button>
            </div>
        </div>
    </div>
</div>

<script>
    function backToDistrict_modal_body_loader_on() {
        const backToDistrict_modal_body = $('#backToDistrict_modal_body');
        backToDistrict_modal_body.html(
            '<div class="text-center"><div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div></div>'
        );
    }
    function backTodistrict_modal() {
        console.log('backToDistrict_modal');

        $('#backToDistrict_modal').modal('show');
        $('#backToDistrict_modal_button').prop('disabled', true);
        backToDistrict_modal_body_loader_on();
        $.ajax({
            url: '{{ route('backToDistrict_modal') }}',
            type: 'GET',
            success: function (data) {
                $('#backToDistrict_modal_body').html(data);
            }
        })
    }

    function backTodistrict_modal_select() {
        console.log('backToDistrict_select');
        var student_ids_backToDistrict = $('.backTodistrict_modal_select');
        var selected_ids = student_ids_backToDistrict.filter(':checked').map(function () {
            return this.value;
        }).get();
        if (selected_ids.length > 0) {
            $('#backToDistrict_modal_button').prop('disabled', false);
        } else {
            $('#backToDistrict_modal_button').prop('disabled', true);
        }
    }

    function backToDistrict_submit() {
        var student_ids_backToDistrict = $('.backTodistrict_modal_select');

        var backToDistrict_comments = [];
        $('.backToDistrict_comments').each(function(){
            backToDistrict_comments.push($(this).val());
        });
       
        



        var selected_ids = student_ids_backToDistrict.filter(':checked').map(
            function () {
                return this.value;
            }).get();
        if (selected_ids.length > 0) {
            $.ajax({
                url: '{{ route('backToDistrict_send') }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    student_ids_backToDistrict: selected_ids,
                    comments: backToDistrict_comments,
                },
                success: function (data) {
                    if (data.success) {
                        alert(data.message);
                        $('#backToDistrict_modal').modal('hide');
                        createTable();
                    } else {
                        alert(data.message);
                    }
                },
                error: function () {
                    alert('Failed to send back to district');
                }
            });
        } else {
            alert('Please select at least one student to send back to district.');
        }
    }
</script>

{{-- backToDistrict_modal end --}}







{{-- generateCertificate_modal start --}}
<!-- Modal -->
<div class="modal fade" id="generateCertificate_modal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId"
    aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Generate Certificate</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                    onclick="$('#generateCertificate_modal').modal('hide')">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-2">
                        <a class="btn btn-primary " href="javascript:void(0)" onclick="selectAllStudents()">Select
                            All</a>
                    </div>
                    <div class="col-md-4">
                        <select name="certificate_type" id="certificate_type" class="form-control"
                            onchange="generateCertificate_modal()">
                            @if(Request::is('general_students*'))
                                <option value="Passed"> Promising </option>
                                <option value="Fail"> Optainane </option>
                            @else
                                <option value="Passed"> Competent </option>
                                <option value="Fail"> Not Yet Competent </option>
                            @endif
                        </select>
                    </div>

                </div>
                <div id="generateCertificate_modal_body" style="overflow-y: scroll;height: 50vh;">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal"
                    onclick="$('#generateCertificate_modal').modal('hide')">Close</button>
                <button type="button" class="btn btn-primary" id="generateCertificate_modal_button"
                    onclick="generateCertificate_submit()">Generate
                    Certificate</button>
            </div>
        </div>
    </div>
</div>

<script>
    function generateCertificate_modal_body_loader_on() {
        const generateCertificate_modal_body = $('#generateCertificate_modal_body');
        generateCertificate_modal_body.html(
            '<div class="text-center"><div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div></div>'
        );
    }
    function generateCertificate_modal() {
        console.log('generateCertificate_modal');

        $('#generateCertificate_modal').modal('show');
        $('#generateCertificate_modal_button').prop('disabled', true);
        generateCertificate_modal_body_loader_on();
        $.ajax({
            url: '{{ route('generateCertificate_modal') }}',
            type: 'GET',
            data: {
                _token: '{{ csrf_token() }}',
                'filter_program': $('#filter_program').val(),
                'filter_occupation': $('#filter_occupation').val(),
                'certificate_type': $('#certificate_type').val(),
            },
            success: function (data) {
                $('#generateCertificate_modal_body').html(data);
            }
        })
    }

    function generateCertificate_select() {
        console.log('generateCertificate_select');
        var student_ids_generateCertificate = $('.student_ids_generateCertificate');
        var selected_ids = student_ids_generateCertificate.filter(':checked').map(function () {
            return this.value;
        }).get();
        if (selected_ids.length > 0) {
            $('#generateCertificate_modal_button').prop('disabled', false);
        } else {
            $('#generateCertificate_modal_button').prop('disabled', true);
        }
    }

    function generateCertificate_submit() {
        var student_ids_generateCertificate = $('.student_ids_generateCertificate');
        var selected_ids = student_ids_generateCertificate.filter(':checked').map(
            function () {
                return this.value;
            }).get();
        if (selected_ids.length > 0) {
            window.open('{{ route('generateCertificate_send') }}?' + $.param({
                student_ids_generateCertificate: selected_ids,
                _token: '{{ csrf_token() }}',
            }), '_blank');
        } else {
            alert('Please select at least one student to generate a certificate.');
        }
        $('#generateCertificate_modal').modal('hide');

    }
</script>
{{-- generateCertificate_modal end --}}




<script>
    function selectAllStudents() {
        var student_ids_forwardToAssessmentCenter = $('input[name="student_ids[]"]');
        var selected_ids = student_ids_forwardToAssessmentCenter.filter(':checked').map(function () {
            return this.value;
        }).get();
        if (selected_ids.length > 0) {
            student_ids_forwardToAssessmentCenter.prop('checked', false);
        } else {
            student_ids_forwardToAssessmentCenter.trigger('click');
            student_ids_forwardToAssessmentCenter.prop('checked', true);
        }
    }
</script>





{{-- viewResult --}}
<!-- Modal -->
<div class="modal fade" id="viewResult_modal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Result sheet</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                    onclick="$('#viewResult_modal').modal('hide')">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                
                <div id="result_sheet_body" style="overflow-y: scroll;height: 71vh;">
                    
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal"
                    onclick="$('#viewResult_modal').modal('hide')">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
    function viewResult($student_id) {
        $('#viewResult_modal').modal('show');
        
        $.ajax({
            url: '{{ route('viewResult') }}',
            type: 'GET',
            data: {
                _token: '{{ csrf_token() }}',
                'student_id': $student_id,
            },
            success: function (data) {
                if(data.html == 'No File Found'){
                    alert('No File Found');
                    $('#viewResult_modal').modal('hide');
                    return;
                }
                $('#result_sheet_body').html('');
                var html ='<iframe src="' + data.html + '" width="100%" height="100%" frameborder="0" si></iframe>';
                $('#result_sheet_body').html(html);
            }
        })
    }
</script>
{{-- generateCertificate_modal end --}}




<script>
    function selectAllStudents() {
        var student_ids_forwardToAssessmentCenter = $('input[name="student_ids[]"]');
        var selected_ids = student_ids_forwardToAssessmentCenter.filter(':checked').map(function () {
            return this.value;
        }).get();
        if (selected_ids.length > 0) {
            student_ids_forwardToAssessmentCenter.prop('checked', false);
        } else {
            student_ids_forwardToAssessmentCenter.trigger('click');
            student_ids_forwardToAssessmentCenter.prop('checked', true);
        }
    }
</script>

{{-- ═══════════════════════════════════════════════════════════════════════ --}}
{{-- setAssessmentStatus_modal (District Admin) --}}
<div class="modal fade" id="setAssessmentStatus_modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header ">
                <h5 class="modal-title"><i class="fa fa-tasks"></i> Set Assessment Status</h5>
                <button type="button" class="close" onclick="$('#setAssessmentStatus_modal').modal('hide')">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p class="text-muted">Select students and set their status: <strong>Ready for Assessment</strong>, <strong>Dropout</strong>, or <strong>Absent</strong>. Only <span class="badge badge-success">Ready for Assessment</span> students can be forwarded to the Registrar.</p>
                <a class="btn btn-secondary btn-sm mb-2" href="javascript:void(0)" onclick="setAssessmentStatus_selectAll()">Select All</a>
                <div id="setAssessmentStatus_modal_body" style="overflow-y:scroll;height:50vh;"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="$('#setAssessmentStatus_modal').modal('hide')">Close</button>
                <button type="button" class="btn btn-warning" id="setAssessmentStatus_modal_button" onclick="setAssessmentStatus_submit()" disabled>Save Status</button>
            </div>
        </div>
    </div>
</div>
<script>
    function setAssessmentStatus_modal() {
        $('#setAssessmentStatus_modal').modal('show');
        $('#setAssessmentStatus_modal_body').html('<div class="text-center"><div class="spinner-border text-warning" role="status"><span class="sr-only">Loading...</span></div></div>');
        $.ajax({
            url: '{{ route('setAssessmentStatus_modal') }}',
            type: 'GET',
            success: function (data) {
                $('#setAssessmentStatus_modal_body').html(data);
            }
        });
    }
    function setAssessmentStatus_selectAll() {
        $('.student_ids_setAssessmentStatus').prop('checked', true);
        setAssessmentStatus_select();
    }
    function setAssessmentStatus_select() {
        var checked = $('.student_ids_setAssessmentStatus:checked').length;
        $('#setAssessmentStatus_modal_button').prop('disabled', checked === 0);
    }
    function setAssessmentStatus_submit() {
        var updates = [];
        $('.student_ids_setAssessmentStatus:checked').each(function () {
            var id = $(this).val();
            var status = $('.assessment_status_select[data-id="' + id + '"]').val();
            updates.push({ id: id, status: status });
        });
        if (updates.length === 0) {
            alert('Please select at least one student.');
            return;
        }
        $.ajax({
            url: '{{ route('setAssessmentStatus_send') }}',
            type: 'POST',
            contentType: 'application/json',
            data: JSON.stringify({ _token: '{{ csrf_token() }}', updates: updates }),
            success: function (data) {
                if (data.success) {
                    alert(data.message);
                    $('#setAssessmentStatus_modal').modal('hide');
                    createTable();
                } else {
                    alert(data.message);
                }
            },
            error: function () { alert('Failed to update status.'); }
        });
    }
</script>
{{-- end setAssessmentStatus_modal --}}

{{-- ═══════════════════════════════════════════════════════════════════════ --}}
{{-- forwardToAssistantRegistrar_modal (District Admin) --}}
<div class="modal fade" id="forwardToAssistantRegistrar_modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title"><i class="fa fa-paper-plane"></i> Forward to Assistant Registrar</h5>
                <button type="button" class="close text-white" onclick="$('#forwardToAssistantRegistrar_modal').modal('hide')">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p class="text-muted">Only students with status <span class="badge badge-success">Ready for Assessment</span> and no registration number are shown.</p>
                <a class="btn btn-secondary btn-sm mb-2" href="javascript:void(0)" onclick="forwardToAssistantRegistrar_selectAll()">Select All</a>
                <div id="forwardToAssistantRegistrar_modal_body" style="overflow-y:scroll;height:50vh;"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="$('#forwardToAssistantRegistrar_modal').modal('hide')">Close</button>
                <button type="button" class="btn btn-info" id="forwardToAssistantRegistrar_modal_button" onclick="forwardToAssistantRegistrar_submit()" disabled>Forward to Registrar</button>
            </div>
        </div>
    </div>
</div>
<script>
    function forwardToAssistantRegistrar_modal() {
        $('#forwardToAssistantRegistrar_modal').modal('show');
        $('#forwardToAssistantRegistrar_modal_button').prop('disabled', true);
        $('#forwardToAssistantRegistrar_modal_body').html('<div class="text-center"><div class="spinner-border text-info" role="status"><span class="sr-only">Loading...</span></div></div>');
        $.ajax({
            url: '{{ route('forwardToAssistantRegistrar_modal') }}',
            type: 'GET',
            success: function (data) {
                $('#forwardToAssistantRegistrar_modal_body').html(data);
            }
        });
    }
    function forwardToAssistantRegistrar_selectAll() {
        $('.student_ids_forwardToAssistantRegistrar').prop('checked', true);
        forwardToAssistantRegistrar_select();
    }
    function forwardToAssistantRegistrar_select() {
        var checked = $('.student_ids_forwardToAssistantRegistrar:checked').length;
        $('#forwardToAssistantRegistrar_modal_button').prop('disabled', checked === 0);
    }
    function forwardToAssistantRegistrar_submit() {
        var selected_ids = $('.student_ids_forwardToAssistantRegistrar:checked').map(function(){ return this.value; }).get();
        if (selected_ids.length === 0) { alert('Please select at least one student.'); return; }
        $.ajax({
            url: '{{ route('forwardToAssistantRegistrar_send') }}',
            type: 'POST',
            data: { _token: '{{ csrf_token() }}', student_ids_forwardToAssistantRegistrar: selected_ids },
            success: function (data) {
                if (data.success) {
                    alert(data.message);
                    $('#forwardToAssistantRegistrar_modal').modal('hide');
                    createTable();
                } else { alert(data.message); }
            },
            error: function () { alert('Failed to forward to Registrar.'); }
        });
    }
</script>
{{-- end forwardToAssistantRegistrar_modal --}}

{{-- ═══════════════════════════════════════════════════════════════════════ --}}
{{-- giveRegistrationNumber_modal (Assistant Registrar) --}}
<div class="modal fade" id="giveRegistrationNumber_modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title"><i class="fa fa-id-card"></i> Give Registration Number</h5>
                <button type="button" class="close text-white" onclick="$('#giveRegistrationNumber_modal').modal('hide')">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p class="text-muted">Review the auto-generated registration numbers. Select students and click <strong>Approve & Save</strong> to assign them and return to District Admin.</p>
                <a class="btn btn-secondary btn-sm mb-2" href="javascript:void(0)" onclick="giveRegistrationNumber_selectAll()">Select All</a>
                <div id="giveRegistrationNumber_modal_body" style="overflow-y:scroll;height:55vh;"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="$('#giveRegistrationNumber_modal').modal('hide')">Close</button>
                <button type="button" class="btn btn-success" id="giveRegistrationNumber_modal_button" onclick="giveRegistrationNumber_submit()" disabled>
                    <i class="fa fa-check"></i> Approve &amp; Save
                </button>
            </div>
        </div>
    </div>
</div>
<script>
    function giveRegistrationNumber_modal() {
        $('#giveRegistrationNumber_modal').modal('show');
        $('#giveRegistrationNumber_modal_button').prop('disabled', true);
        $('#giveRegistrationNumber_modal_body').html('<div class="text-center"><div class="spinner-border text-success" role="status"><span class="sr-only">Loading...</span></div></div>');
        $.ajax({
            url: '{{ route('giveRegistrationNumber_modal') }}',
            type: 'GET',
            success: function (data) {
                $('#giveRegistrationNumber_modal_body').html(data);
            }
        });
    }
    function giveRegistrationNumber_selectAll() {
        $('.student_ids_giveRegistrationNumber').prop('checked', true);
        giveRegistrationNumber_select();
    }
    function giveRegistrationNumber_select() {
        var checked = $('.student_ids_giveRegistrationNumber:checked').length;
        $('#giveRegistrationNumber_modal_button').prop('disabled', checked === 0);
    }
    function giveRegistrationNumber_submit() {
        var selected_ids = $('.student_ids_giveRegistrationNumber:checked').map(function(){ return this.value; }).get();
        if (selected_ids.length === 0) { alert('Please select at least one student.'); return; }
        if (!confirm('Approve and assign registration numbers to ' + selected_ids.length + ' student(s)?')) return;

        // Collect custom (editable) reg numbers keyed by student ID
        var custom_reg_numbers = {};
        selected_ids.forEach(function(id) {
            var val = $('.reg_no_input[data-student-id="' + id + '"]').val();
            if (val) custom_reg_numbers[id] = val.trim();
        });

        $('#giveRegistrationNumber_modal_button').prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> Saving...');
        $.ajax({
            url: '{{ route('giveRegistrationNumber_approve') }}',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                student_ids_giveRegistrationNumber: selected_ids,
                custom_reg_numbers: custom_reg_numbers
            },
            success: function (data) {
                if (data.success) {
                    alert(data.message);
                    $('#giveRegistrationNumber_modal').modal('hide');
                    createTable();
                } else { alert(data.message); }
                $('#giveRegistrationNumber_modal_button').prop('disabled', false).html('<i class="fa fa-check"></i> Approve &amp; Save');
            },
            error: function () {
                alert('Failed to approve registration numbers.');
                $('#giveRegistrationNumber_modal_button').prop('disabled', false).html('<i class="fa fa-check"></i> Approve &amp; Save');
            }
        });
    }
</script>
{{-- end giveRegistrationNumber_modal --}}