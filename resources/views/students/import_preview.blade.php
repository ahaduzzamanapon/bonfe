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
                                <th>Training Start</th>
                                <th>Type</th>
                                <th>Result</th>
                                <th>Remarks</th>
                                <th>Candidate ID Preview</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($students as $index => $student)
                                <tr>
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
    });
</script>
@endsection