@extends('layouts.app')

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

    <div class="content px-3">

        @include('adminlte-templates::common.errors')

        <div class="card">
            {!! Form::open(['route' => 'students.import_store']) !!}
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
                                <th>Mobile</th>
                                <th>Email</th>
                                <th>DOB</th>
                                <th>Gender</th>
                                <th>Address</th>
                                <th>Qual.</th>
                                <th>Training Start</th>
                                <th>Type</th>
                                <th>Candidate ID Preview</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($students as $index => $student)
                            <tr>
                                <td>
                                    <input type="text" name="students[{{ $index }}][candidate_name]" value="{{ $student['candidate_name'] }}" class="form-control" required>
                                </td>
                                <td>
                                    <input type="text" name="students[{{ $index }}][candidate_name_bn]" value="{{ $student['candidate_name_bn'] }}" class="form-control">
                                </td>
                                <td>
                                    <select name="students[{{ $index }}][institutionName]" class="form-control" required style="width: 200px;">
                                        @foreach($institutes as $id => $name)
                                            <option value="{{ $id }}" {{ $student['institutionName'] == $id ? 'selected' : '' }}>{{ $name }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <select name="students[{{ $index }}][occupation_id]" class="form-control" required style="width: 150px;">
                                        @foreach($occupations as $id => $title)
                                            <option value="{{ $id }}" {{ $student['occupation_id'] == $id ? 'selected' : '' }}>{{ $title }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <select name="students[{{ $index }}][district_id]" class="form-control" required style="width: 150px;">
                                        @foreach($districts as $id => $name)
                                            <option value="{{ $id }}" {{ $student['district_id'] == $id ? 'selected' : '' }}>{{ $name }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <select name="students[{{ $index }}][upajila_id]" class="form-control" style="width: 150px;">
                                        <option value="">Select Upazila</option>
                                        @foreach($upazilas as $id => $name)
                                            <option value="{{ $id }}" {{ isset($student['upajila_id']) && $student['upajila_id'] == $id ? 'selected' : '' }}>{{ $name }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <input type="number" name="students[{{ $index }}][program_id]" value="{{ $student['program_id'] }}" class="form-control" required style="width: 70px;">
                                </td>
                                <td>
                                    <input type="text" name="students[{{ $index }}][father_name]" value="{{ $student['father_name'] }}" class="form-control">
                                </td>
                                <td>
                                    <input type="text" name="students[{{ $index }}][mother_name]" value="{{ $student['mother_name'] }}" class="form-control">
                                </td>
                                <td>
                                    <input type="text" name="students[{{ $index }}][nid]" value="{{ $student['nid'] }}" class="form-control">
                                </td>
                                <td>
                                    <input type="text" name="students[{{ $index }}][brn]" value="{{ $student['brn'] ?? '' }}" class="form-control">
                                </td>
                                <td>
                                    <input type="text" name="students[{{ $index }}][mobile_number]" value="{{ $student['mobile_number'] }}" class="form-control">
                                </td>
                                <td>
                                    <input type="email" name="students[{{ $index }}][email]" value="{{ $student['email'] ?? '' }}" class="form-control">
                                </td>
                                <td>
                                    <input type="date" name="students[{{ $index }}][date_of_birth]" value="{{ $student['date_of_birth'] }}" class="form-control">
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
                                    <input type="text" name="students[{{ $index }}][address]" value="{{ $student['address'] ?? '' }}" class="form-control">
                                </td>
                                <td>
                                    <input type="text" name="students[{{ $index }}][educational_qualification]" value="{{ $student['educational_qualification'] ?? '' }}" class="form-control">
                                </td>
                                <td>
                                    <input type="date" name="students[{{ $index }}][training_start_date]" value="{{ $student['training_start_date'] ?? '' }}" class="form-control">
                                </td>
                                <td>
                                    <select name="students[{{ $index }}][student_type]" class="form-control">
                                        <option value="REG" {{ $student['student_type'] == 'REG' ? 'selected' : '' }}>REG</option>
                                        <option value="RPL" {{ $student['student_type'] == 'RPL' ? 'selected' : '' }}>RPL</option>
                                    </select>
                                </td>
                                <td>
                                    <span class="badge badge-info">{{ $student['preview_id'] ?? 'Pending' }}</span>
                                    <small class="d-block text-muted">ID will be re-generated on save</small>
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
