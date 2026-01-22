@extends('layouts.default')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1>Verify & Edit Import Data</h1>
                </div>
            </div>
        </div>
    </section>

    <style>
        .table input.form-control, .table select.form-control {
            width: auto;
            min-width: 150px;
        }
        .table select.form-control option {
            width: auto;
        }
    </style>
    <div class="content px-3">

        @include('adminlte-templates::common.errors')

        <div class="card">
            {!! Form::open(['route' => 'students.import_store']) !!}
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label>Bulk Update District:</label>
                        <div class="input-group">
                            <select id="bulk_district_select" class="form-control">
                                <option value="">Select District</option>
                                @foreach($districts as $id => $name)
                                    <option value="{{ $id }}">{{ $name }}</option>
                                @endforeach
                            </select>
                            <div class="input-group-append">
                                <button type="button" id="btn_apply_district" class="btn btn-primary">Apply to All</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" style="min-width: 1500px;">
                        <thead>
                            <tr>
                                <th>SL</th>
                                <th>Name</th>
                                <th>Name BN</th>
                                <th>Institute</th>
                                <th>Trade</th>
                                <th>District</th>
                                <th>Upazila</th>
                                <th>Program ID</th>
                                <th>Father</th>
                                <th>Mother</th>
                                <th>NID</th>
                                <th>BRN</th>
                                <th>Reg No</th>
                                <th>Mobile</th>
                                <th>Email</th>
                                <th>DOB</th>
                                <th>Gender</th>
                                <th>Address</th>
                                <th>Qual.</th>
                                <th>Assessment Date</th>
                                <th>Type</th>
                                <th>Result</th>
                                <th>Remarks</th>
                                <th>Candidate ID Preview</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($students as $index => $student)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        <input type="text" name="students[{{ $index }}][candidate_name]"
                                            value="{{ $student['candidate_name'] }}" class="form-control" required>
                                    </td>
                                    <td>
                                        <input type="text" name="students[{{ $index }}][candidate_name_bn]"
                                            value="{{ $student['candidate_name_bn'] }}" class="form-control">
                                    </td>
                                    <td>
                                        <select name="students[{{ $index }}][institutionName]" class="form-control" required>
                                            @foreach($institutes as $id => $name)
                                                <option value="{{ $id }}" {{ $student['institutionName'] == $id ? 'selected' : '' }}>
                                                    {{ $name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <select name="students[{{ $index }}][occupation_id]" class="form-control" required>
                                            @foreach($occupations as $id => $title)
                                                <option value="{{ $id }}" {{ $student['occupation_id'] == $id ? 'selected' : '' }}>
                                                    {{ $title }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <select name="students[{{ $index }}][district_id]" class="form-control district-select" required>
                                            @foreach($districts as $id => $name)
                                                <option value="{{ $id }}" {{ $student['district_id'] == $id ? 'selected' : '' }}>
                                                    {{ $name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <select name="students[{{ $index }}][upajila_id]" class="form-control">
                                            <option value="">Select Upazila</option>
                                            @foreach($upazilas as $id => $name)
                                                <option value="{{ $id }}">{{ $name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input type="number" name="students[{{ $index }}][program_id]"
                                            value="{{ $student['program_id'] }}" class="form-control" required
                                            style="min-width: 70px;">
                                    </td>
                                    <td>
                                        <input type="text" name="students[{{ $index }}][father_name]"
                                            value="{{ $student['father_name'] }}" class="form-control">
                                    </td>
                                    <td>
                                        <input type="text" name="students[{{ $index }}][mother_name]"
                                            value="{{ $student['mother_name'] }}" class="form-control">
                                    </td>
                                    <td>
                                        <input type="text" name="students[{{ $index }}][nid]" value="{{ $student['nid'] }}"
                                            class="form-control">
                                    </td>
                                    <td>
                                        <input type="text" name="students[{{ $index }}][brn]"
                                            value="{{ $student['brn'] ?? '' }}" class="form-control">
                                    </td>
                                    <td>
                                        <input type="text" name="students[{{ $index }}][registration_number]"
                                            value="{{ $student['registration_number'] ?? '' }}" class="form-control">
                                    </td>
                                    <td>
                                        <input type="text" name="students[{{ $index }}][mobile_number]"
                                            value="{{ $student['mobile_number'] }}" class="form-control">
                                    </td>
                                    <td>
                                        <input type="email" name="students[{{ $index }}][email]"
                                            value="{{ $student['email'] ?? '' }}" class="form-control">
                                    </td>
                                    <td>
                                        <input type="date" name="students[{{ $index }}][date_of_birth]"
                                            value="{{ $student['date_of_birth'] }}" class="form-control">
                                    </td>
                                    <td>
                                        <select name="students[{{ $index }}][gender]" class="form-control">
                                            <option value="">Select</option>
                                            <option value="Male" {{ isset($student['gender']) && $student['gender'] == 'Male' ? 'selected' : '' }}>Male</option>
                                            <option value="Female" {{ isset($student['gender']) && $student['gender'] == 'Female' ? 'selected' : '' }}>Female</option>
                                            <option value="Other" {{ isset($student['gender']) && $student['gender'] == 'Other' ? 'selected' : '' }}>Other</option>
                                        </select>
                                    </td>
                                    <td>
                                        <input type="text" name="students[{{ $index }}][address]"
                                            value="{{ $student['address'] ?? '' }}" class="form-control">
                                    </td>
                                    <td>
                                        <input type="text" name="students[{{ $index }}][educational_qualification]"
                                            value="{{ $student['educational_qualification'] ?? '' }}" class="form-control">
                                    </td>
                                        <!-- Pass extracted text fields -->
                                        <input type="hidden" name="students[{{ $index }}][assessment_center]" value="{{ $student['assessment_center'] ?? '' }}">
                                        <input type="hidden" name="students[{{ $index }}][assessment_venue]" value="{{ $student['assessment_venue'] ?? '' }}">
                                        <input type="hidden" name="students[{{ $index }}][exam_status]" value="{{ $student['exam_status'] ?? '' }}">
                                        <input type="hidden" name="students[{{ $index }}][institute_type]" value="{{ $student['institute_type'] ?? '' }}">
                                    <td>
                                        <input type="date" name="students[{{ $index }}][assessment_date]"
                                            value="{{ $student['assessment_date'] ?? '' }}" class="form-control">
                                    </td>
                                    <td>
                                        <select name="students[{{ $index }}][student_type]" class="form-control">
                                            <option value="REG" {{ $student['student_type'] == 'REG' ? 'selected' : '' }}>REG
                                            </option>
                                            <option value="RPL" {{ $student['student_type'] == 'RPL' ? 'selected' : '' }}>RPL
                                            </option>
                                        </select>
                                    </td>
                                    <td>
                                        <input type="text" name="students[{{ $index }}][competency_status]"
                                            value="{{ $student['competency_status'] ?? '' }}" class="form-control"
                                            placeholder="Result">
                                    </td>
                                    <td>
                                        <input type="text" name="students[{{ $index }}][competency_remarks]"
                                            value="{{ $student['competency_remarks'] ?? '' }}" class="form-control"
                                            placeholder="Remarks">
                                    </td>
                                    <td>
                                        <input type="text" name="students[{{ $index }}][candidate_id]"
                                            value="{{ $student['preview_id'] ?? '' }}" class="form-control" style="min-width: 180px;">
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card-footer">
                <button type="submit" class="btn btn-success">Save All Students</button>
                <a href="{{ route('students.import_page') }}" class="btn btn-default">Back to Upload</a>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('#btn_apply_district').click(function() {
            var selectedDistrict = $('#bulk_district_select').val();
            if (selectedDistrict) {
                $('.district-select').val(selectedDistrict).trigger('change');
                alert('District updated for all students!');
            } else {
                alert('Please select a district to apply.');
            }
        });

        // Handle form submission with chunked data
        $('form').on('submit', function(e) {
            e.preventDefault();
            
            const form = this;
            const formData = new FormData(form);
            
            // Extract students data
            const studentsData = [];
            const studentInputs = $('input[name^="students"]');
            const totalRows = $('table tbody tr').length;
            
            // Parse form data into structured array - IMPROVED
            for (let i = 0; i < totalRows; i++) {
                const student = {};
                
                // Get all form elements for this row
                const rowInputs = $(`input[name^="students\\[${i}\\]"]`);
                const rowSelects = $(`select[name^="students\\[${i}\\]"]`);
                const rowHiddens = $(`input[type="hidden"][name^="students\\[${i}\\]"]`);
                
                rowInputs.each(function() {
                    const name = $(this).attr('name');
                    const match = name.match(/students\[(\d+)\]\[([^\]]+)\]/);
                    if (match) {
                        const fieldName = match[2];
                        student[fieldName] = $(this).val();
                    }
                });
                
                rowSelects.each(function() {
                    const name = $(this).attr('name');
                    const match = name.match(/students\[(\d+)\]\[([^\]]+)\]/);
                    if (match) {
                        const fieldName = match[2];
                        student[fieldName] = $(this).val();
                    }
                });
                
                rowHiddens.each(function() {
                    const name = $(this).attr('name');
                    const match = name.match(/students\[(\d+)\]\[([^\]]+)\]/);
                    if (match) {
                        const fieldName = match[2];
                        student[fieldName] = $(this).val();
                    }
                });
                
                if (Object.keys(student).length > 0) {
                    studentsData.push(student);
                }
            }
            
            console.log('Total students to import:', studentsData.length);
            
            if (studentsData.length === 0) {
                alert('No student data found!');
                return false;
            }
            
            // Show progress UI
            showProgressUI(studentsData.length);
            
            // Submit in chunks of 50
            const chunkSize = 10;
            submitInChunks(studentsData, chunkSize);
            
            return false;
        });

        function showProgressUI(totalStudents) {
            const html = `
                <div id="import-progress" style="margin-top: 20px; padding: 20px; background: #f8f9fa; border-radius: 5px;">
                    <h5>Importing Students...</h5>
                    <div class="progress" style="height: 25px;">
                        <div id="progress-bar" class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 0%;">
                            <span id="progress-text">0%</span>
                        </div>
                    </div>
                    <p style="margin-top: 10px;">
                        <span id="imported-count">0</span> / <span id="total-count">${totalStudents}</span> students imported
                    </p>
                    <p id="chunk-status" style="color: #666; font-size: 0.9em;"></p>
                </div>
            `;
            
            $('form').after(html);
            $('form').hide();
            $('.card-footer').hide();
        }

        function updateProgress(importedCount, totalCount, chunkNum, totalChunks) {
            const percentage = Math.round((importedCount / totalCount) * 100);
            $('#progress-bar').css('width', percentage + '%');
            $('#progress-text').text(percentage + '%');
            $('#imported-count').text(importedCount);
            $('#total-count').text(totalCount);
            $('#chunk-status').text(`Chunk ${chunkNum} of ${totalChunks} processed...`);
        }

        function submitInChunks(data, chunkSize) {
            let currentChunk = 0;
            const totalChunks = Math.ceil(data.length / chunkSize);
            let totalImported = 0;
            
            function refreshCSRFToken() {
                // CSRF verification is bypassed for this route, but keeping this
                // in case we need to re-enable it later
                console.log('CSRF bypass enabled for import_store route');
                return Promise.resolve(true);
            }
            
            function sendChunk(chunkIndex) {
                const start = chunkIndex * chunkSize;
                const end = Math.min(start + chunkSize, data.length);
                const chunkData = data.slice(start, end);
                
                console.log(`Sending chunk ${chunkIndex + 1} of ${totalChunks} (${chunkData.length} students)`);
                updateProgress(totalImported, data.length, chunkIndex + 1, totalChunks);
                
                // Ensure fresh CSRF token for each chunk
                const csrfToken = $('meta[name="csrf-token"]').attr('content');
                console.log('Using CSRF token:', csrfToken.substring(0, 10) + '...');
                
                // Create FormData for this chunk
                const formData = new FormData();
                
                chunkData.forEach((student, idx) => {
                    Object.keys(student).forEach(key => {
                        const value = student[key];
                        if (value !== undefined && value !== null) {
                            formData.append(`students[${idx}][${key}]`, value);
                        }
                    });
                });
                
                // Add fresh CSRF token
                formData.append('_token', csrfToken);
                
                $.ajax({
                    url: '{{ route("students.import_store") }}',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    statusCode: {
                        200: function(response) {
                            totalImported += response.saved || chunkData.length;
                            console.log(`Chunk ${chunkIndex + 1} uploaded successfully. Total imported: ${totalImported}`);
                            console.log('Response:', response);
                            
                            updateProgress(totalImported, data.length, chunkIndex + 1, totalChunks);
                            
                            // Refresh token and send next chunk
                            if (chunkIndex + 1 < totalChunks) {
                                console.log('Refreshing CSRF token before next chunk...');
                                refreshCSRFToken().then(() => {
                                    setTimeout(() => sendChunk(chunkIndex + 1), 500);
                                });
                            } else {
                                // All chunks sent
                                console.log('All chunks uploaded! Total imported:', totalImported);
                                $('#chunk-status').text('✓ All chunks processed successfully!').css('color', 'green');
                                $('#progress-bar').removeClass('progress-bar-animated');
                                
                                setTimeout(() => {
                                    alert(`Import complete! ${totalImported} students have been imported successfully.`);
                                    window.location.href = '{{ route("students.index") }}';
                                }, 1500);
                            }
                        },
                        400: function(xhr) {
                            console.error(`Chunk ${chunkIndex + 1} bad request`);
                            console.error('Response:', xhr.responseJSON);
                            $('#chunk-status').text(`❌ Error in chunk ${chunkIndex + 1}: ${xhr.responseJSON?.message || 'Bad request'}`).css('color', 'red');
                            alert(`Error in chunk ${chunkIndex + 1}: ${xhr.responseJSON?.message || 'Bad request'}`);
                        },
                        401: function(xhr) {
                            $('#chunk-status').text('❌ Unauthorized - Session expired').css('color', 'red');
                            alert('Unauthorized. Please log in again.');
                            window.location.href = '{{ route("login") }}';
                        },
                        403: function(xhr) {
                            $('#chunk-status').text('❌ Forbidden - Permission denied').css('color', 'red');
                            alert('Forbidden. You do not have permission.');
                        },
                        404: function(xhr) {
                            console.error(`Route not found. URL: {{ route("students.import_store") }}`);
                            $('#chunk-status').text('❌ Route not found').css('color', 'red');
                            alert('Route not found. Please contact administrator.');
                        },
                        419: function(xhr) {
                            console.error('❌ CSRF validation failed (419)');
                            console.error('This should not happen - CSRF is bypassed for this route');
                            console.error('Response:', xhr.responseText);
                            $('#chunk-status').text('❌ Unexpected CSRF error').css('color', 'red');
                            alert('Unexpected session error. Please refresh and try again.');
                        },
                        422: function(xhr) {
                            console.error('Validation error:', xhr.responseJSON);
                            $('#chunk-status').text(`❌ Validation error: ${xhr.responseJSON?.message}`).css('color', 'red');
                            alert('Validation error: ' + (xhr.responseJSON?.message || 'Invalid data'));
                        },
                        500: function(xhr) {
                            console.error(`Chunk ${chunkIndex + 1} server error`);
                            console.error('Response:', xhr.responseJSON);
                            $('#chunk-status').text(`❌ Server error in chunk ${chunkIndex + 1}: ${xhr.responseJSON?.message || 'Internal server error'}`).css('color', 'red');
                            alert(`Server error in chunk ${chunkIndex + 1}: ${xhr.responseJSON?.message || 'Internal server error'}`);
                        }
                    },
                    error: function(xhr, status, error) {
                        const statusCode = xhr.status;
                        let responseJSON = {};
                        let errorMsg = '';
                        
                        // Try to parse JSON response
                        try {
                            responseJSON = xhr.responseJSON || JSON.parse(xhr.responseText);
                            errorMsg = responseJSON.message || responseJSON.error || 'Unknown error';
                        } catch (e) {
                            // If not JSON, it might be HTML error page - extract first 200 chars
                            errorMsg = xhr.responseText ? xhr.responseText.substring(0, 200) : 'Unknown error';
                            if (errorMsg.includes('<')) {
                                errorMsg = 'Server returned HTML error (see console for details)';
                            }
                        }
                        
                        console.error(`Chunk ${chunkIndex + 1} AJAX Error:`);
                        console.error('Status:', statusCode);
                        console.error('Status Text:', xhr.statusText);
                        console.error('Error:', error);
                        console.error('Response Text:', xhr.responseText);
                        console.error('Parsed Response:', responseJSON);
                        
                        const fullMsg = `Error uploading chunk ${chunkIndex + 1}:\nStatus: ${statusCode}\nError: ${error}\nMessage: ${errorMsg}`;
                        $('#chunk-status').text(`❌ ${fullMsg.replace(/\n/g, ' ')}`).css('color', 'red');
                        alert(fullMsg);
                    }
                });
            }
            
            // Start sending chunks
            sendChunk(0);
        }
    });
</script>
@endsection