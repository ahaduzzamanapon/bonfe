<div class="table-responsive">
    <table class="table" id="students-table">
        <thead>
            <tr>
                <th>SL</th>
                <th>Candidate Name</th>
                <th>Father Name</th>
                <th>Mother Name</th>
        <th>Occupation</th>
        <th>Registration Number</th>
        <th>Candidate Id</th>
        <th>Nid</th>
        <th>District</th>
        <th>Address</th>
        <th>Date Of Birth</th>
        <th>Mobile Number</th>
        <th>Email</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        @foreach($students as $key => $student)
            <tr>
                <td>{{ $key+1 }}</td>
                <td>{{ $student->candidate_name }}</td>
                <td>{{ $student->father_name }}</td>
                <td>{{ $student->mother_name }}</td>
            <td>{{ $student->occupation_id }}</td>
            <td>{{ $student->registration_number }}</td>
            <td>{{ $student->candidate_id }}</td>
            <td>{{ $student->nid }}</td>
            <td>{{ $student->district_id }}</td>
            <td>{{ $student->address }}</td>
            <td>{{ $student->date_of_birth }}</td>
            <td>{{ $student->mobile_number }}</td>
            <td>{{ $student->email }}</td>
                <td>
                    {!! Form::open(['route' => ['students.destroy', $student->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('students.show', [$student->id]) }}" class='btn btn-outline-primary btn-xs'><i class="im im-icon-Eye" data-placement="top" title="View"></i></a>
                        <a href="{{ route('students.edit', [$student->id]) }}" class='btn btn-outline-primary btn-xs'><i
                                class="im im-icon-Pen"  data-toggle="tooltip" data-placement="top" title="Edit"></i></a>
                        {!! Form::button('<i class="im im-icon-Remove" data-toggle="tooltip" data-placement="top" title="Delete"></i>', ['type' => 'submit', 'class' => 'btn btn-outline-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                    </div>
                    {!! Form::close() !!}
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
