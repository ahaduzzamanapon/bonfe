<div class="table-responsive">
    <table class="table" id="programs-table">
        <thead>
            <tr>
                <th>SL</th>
        <th>Program Title</th>
        <th>Program Type</th>
        <th>Start Date</th>
        <th>End Date</th>
        <th>Created At</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        @foreach($programs as $key => $program)
            <tr>
                <td>{{ $key+1 }}</td>
            <td>{{ $program->program_title }}</td>
            <td>{{ $program->program_type }}</td>
            <td>{{ date('d-m-Y', strtotime($program->start_date)) }}</td>
            <td>{{ date('d-m-Y', strtotime($program->end_date)) }}</td>
            <td>{{ $program->created_at }}</td>
                <td>
                    {!! Form::open(['route' => ['programs.destroy', $program->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('programs.show', [$program->id]) }}" class='btn btn-outline-primary btn-xs'><i class="im im-icon-Eye" data-placement="top" title="View"></i></a>
                        <a href="{{ route('programs.edit', [$program->id]) }}" class='btn btn-outline-primary btn-xs'><i
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
