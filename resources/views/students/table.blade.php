<table class="table table-striped table-hover " id="students-table">
    <thead>
        <tr>
            <th>SL</th>
            <th>Candidate Details</th>
            <th>Status</th>
            <th>Exam Status</th>
            <th>District App.</th>
            <th>Chairman App.</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($students as $key => $student)
            <tr>
                <td style="color: #000">{{ $key + 1 }}</td>

                <td>
                    <div style="line-height: 1px;">
                        <p style="font-weight: bold;color: #000"> {{ $student->candidate_name }}</p>
                        <div style="line-height: 2px;">
                            <p style="font-size: 10px;"><strong>Occupation:</strong> {{ $student->occupation }}</p>
                            <p style="font-size: 10px;"><strong>Regis. No:</strong> {{ $student->registration_number }}
                            </p>
                            <p style="font-size: 10px;"><strong>District:</strong> {{ $student->district }}</p>
                        </div>
                    </div>
                </td>

                <td><span
                        class="badge badge-{{ $student->status == 'Pending' ? 'warning' : 'success' }}">{{ $student->status }}</span>
                </td>
                <td><span
                        class="badge badge-{{ $student->exam_status == 'Pending' ? 'warning' : 'success' }}">{{ $student->exam_status }}</span>
                </td>
                <td><span
                        class="badge badge-{{ $student->districts_admin_status == 'Pending' ? 'warning' : 'success' }}">{{ $student->districts_admin_status }}</span>
                </td>
                <td><span
                        class="badge badge-{{ $student->chairmen_status == 'Pending' ? 'warning' : 'success' }}">{{ $student->chairmen_status }}</span>
                </td>
                <td>
                    <div class="btn-group">
                        <button type="button" class="btn btn-outline-primary btn-xs dropdown-toggle"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="im im-icon-List2" data-placement="top" title="Actions">Action</i>
                        </button>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="{{ route('students.show', [$student->id]) }}"><i
                                    class="im im-icon-Eye"></i> View</a>
                            @if ($student->status != 'Chairman Approved')
                                <a class="dropdown-item" href="{{ route('students.edit', [$student->id]) }}"><i
                                        class="im im-icon-Pen"></i> Edit</a>
                            @endif
                            @if (can('give_exam_result') && $student->exam_status == 'Pending')
                                <a class="dropdown-item" onclick="give_exam_result({{ $student->id }})"
                                    href="javascript:void(0);"><i class="im im-icon-Pencil-Ruler"></i> Give Exam
                                    Result</a>
                            @endif
                            @if (can('district_admin') && $student->status == 'Waiting for District Admin Approval')
                                <a class="dropdown-item"
                                    href="{{ route('students.forward_to_chairman', [$student->id]) }}"><i
                                        class="im im-icon-Arrow-Back"></i> Approve And Send To Chairman</a>
                            @endif
                            @if (can('chairman') && $student->status == 'Waiting for Chairman Approval')
                                <a class="dropdown-item"
                                    href="{{ route('students.chairman_approve', [$student->id]) }}"><i
                                        class="im im-icon-Approved-Window"></i> Approve</a>
                            @endif
                            @if ($student->status != 'Chairman Approved')
                                {!! Form::open(['route' => ['students.destroy', $student->id], 'method' => 'delete']) !!}
                                {!! Form::button('<i class="im im-icon-Remove"></i> Delete', [
                                    'type' => 'submit',
                                    'class' => 'dropdown-item',
                                    'onclick' => "return confirm('Are you sure?')",
                                ]) !!}
                                {!! Form::close() !!}
                            @endif
                            @if ($student->status == 'Chairman Approved')
                                <a class="dropdown-item" target="_blank"
                                    href="{{ route('students.generate_certificate', [$student->id]) }}"><i
                                        class="im im-icon-People-onCloud"></i> Generate Certificate</a>
                            @endif
                        </div>
                    </div>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

@section('footer_scripts')
    <script>
        function give_exam_result(id) {
            $('#exam_result_modal').modal('show');
            localStorage.setItem('student_id_for_exam_result', id);
        }
    </script>
@endsection
