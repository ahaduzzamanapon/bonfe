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
                            <option value="Passed"> Competent </option>
                            <option value="Fail"> Non-Competent </option>
                        </select>
                    </div>
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
        const fileExtension = examResultSheet.name.split('.').pop().toLowerCase();

        if (!allowedExtensions.includes(fileExtension)) {
            alert('Invalid file type. Please upload a file with one of the following extensions: ' + allowedExtensions
                .join(', '));
            return false;
        }

        const studentId = localStorage.getItem('student_id_for_exam_result');

        if (!examResult || !studentId) {
            alert('Please select a result and ensure a student ID is set');
            return false;
        }

        const formData = new FormData();
        formData.append('_token', '{{ csrf_token() }}');
        formData.append('examResult', examResult);
        formData.append('studentId', studentId);
        formData.append('examResultSheet', examResultSheet);

        $.ajax({
            url: '{{ route('submit_exam_result') }}',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function() {
                alert('Result submitted successfully');
                $('#exam_result_modal').modal('hide');
                location.reload();
            },
            error: function() {
                alert('Failed to submit exam result');
            }
        });
    }
</script>
{{-- exam result modal --}}





{{-- forwardToAssessmentCenter_modal start --}}
<!-- Modal -->
<div class="modal fade" id="forwardToAssessmentCenter_modal" tabindex="-1" role="dialog"
    aria-labelledby="modelTitleId" aria-hidden="true">
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
                <div id="forwardToAssessmentCenter_modal_body" style="overflow-y: scroll;">
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
        $.ajax({
            url: '{{ route('forwardToAssessmentCenter_modal') }}',
            type: 'GET',
            success: function(data) {
                $('#forwardToAssessmentCenter_modal_body').html(data);
            }
        })
    }

    function forwardToAssessmentCenter_select() {
        var student_ids_forwardToAssessmentCenter = $('.student_ids_forwardToAssessmentCenter');
        var selected_ids = student_ids_forwardToAssessmentCenter.filter(':checked').map(function() {
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
            function() {
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
                success: function(data) {
                    if (data.success) {
                        alert(data.message);
                        $('#forwardToAssessmentCenter_modal').modal('hide');
                        createTable()
                    } else {
                        alert(data.message);
                    }
                },
                error: function() {
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
<div class="modal fade" id="forwardToDistrictAdmin_modal" tabindex="-1" role="dialog"
    aria-labelledby="modelTitleId" aria-hidden="true">
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
                <div id="forwardToDistrictAdmin_modal_body" style="overflow-y: scroll;">
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
            success: function(data) {
                $('#forwardToDistrictAdmin_modal_body').html(data);
            }
        })
    }

    function forwardToDistrictAdmin_select() {
      console.log('forwardToDistrictAdmin_select');
        var student_ids_forwardToDistrictAdmin = $('.student_ids_forwardToDistrictAdmin');
        var selected_ids = student_ids_forwardToDistrictAdmin.filter(':checked').map(function() {
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
            function() {
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
                success: function(data) {
                    if (data.success) {
                        alert(data.message);
                        $('#forwardToDistrictAdmin_modal').modal('hide');
                        createTable()
                    } else {
                        alert(data.message);
                    }
                },
                error: function() {
                    alert('Failed to forward to District Admin');
                }
            });
        } else {
            alert('Please select at least one student to forward.');
        }
    }
</script>


{{-- forwardToDistrictAdmin_modal end --}}


{{-- forwardToChairman_modal start --}}
<!-- Modal -->
<div class="modal fade" id="forwardToChairman_modal" tabindex="-1" role="dialog"
    aria-labelledby="modelTitleId" aria-hidden="true">
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
                <div id="forwardToChairman_modal_body" style="overflow-y: scroll;">
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
            success: function(data) {
                $('#forwardToChairman_modal_body').html(data);
            }
        })
    }

    function forwardToChairman_select() {
        console.log('forwardToChairman_select');
        var student_ids_forwardToChairman = $('.student_ids_forwardToChairman');
        var selected_ids = student_ids_forwardToChairman.filter(':checked').map(function() {
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
            function() {
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
                success: function(data) {
                    if (data.success) {
                        alert(data.message);
                        $('#forwardToChairman_modal').modal('hide');
                        createTable();
                    } else {
                        alert(data.message);
                    }
                },
                error: function() {
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
<div class="modal fade" id="approveStudent_modal" tabindex="-1" role="dialog"
    aria-labelledby="modelTitleId" aria-hidden="true">
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
                <div id="approveStudent_modal_body" style="overflow-y: scroll;">
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
            success: function(data) {
                $('#approveStudent_modal_body').html(data);
            }
        })
    }

    function approveStudent_select() {
        console.log('approveStudent_select');
        var student_ids_approveStudent = $('.student_ids_approveStudent');
        var selected_ids = student_ids_approveStudent.filter(':checked').map(function() {
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
            function() {
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
                success: function(data) {
                    if (data.success) {
                        alert(data.message);
                        $('#approveStudent_modal').modal('hide');
                        createTable();
                    } else {
                        alert(data.message);
                    }
                },
                error: function() {
                    alert('Failed to approve student');
                }
            });
        } else {
            alert('Please select at least one student to approve.');
        }
    }
</script>

{{-- approveStudent_modal end --}}

{{-- generateCertificate_modal start --}}
<!-- Modal -->
<div class="modal fade" id="generateCertificate_modal" tabindex="-1" role="dialog"
    aria-labelledby="modelTitleId" aria-hidden="true">
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
                <a class="btn btn-primary" href="javascript:void(0)" onclick="selectAllStudents()">
                    Select All</a>
                <div id="generateCertificate_modal_body" style="overflow-y: scroll;">
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
            success: function(data) {
                $('#generateCertificate_modal_body').html(data);
            }
        })
    }

    function generateCertificate_select() {
        console.log('generateCertificate_select');
        var student_ids_generateCertificate = $('.student_ids_generateCertificate');
        var selected_ids = student_ids_generateCertificate.filter(':checked').map(function() {
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
            function() {
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
        var selected_ids = student_ids_forwardToAssessmentCenter.filter(':checked').map(function() {
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


