<div class="table-responsive">
    <table class="table" id="assessmentCenters-table">
        <thead>
            <tr>
                <th>SL</th>
        <th>Center Name</th>
        <th>District</th>
        <th>Registration Number</th>
        {{-- <th>Address</th> --}}
        
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        @foreach($assessmentCenters as $key => $assessmentCenter)
            <tr data-district-id="{{ optional($assessmentCenter->district)->id }}">
                <td>{{ ++$key }}</td>
            <td>{{ $assessmentCenter->center_name }}</td>
            <td>{{ optional($assessmentCenter->district)->name_en }}</td>
            <td>{{ $assessmentCenter->registration_number }}</td>
            {{-- <td>{{ $assessmentCenter->address }}</td> --}}
           
                <td>
                    {!! Form::open(['route' => ['assessmentCenters.destroy', $assessmentCenter->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('assessmentCenters.show', [$assessmentCenter->id]) }}" class='btn btn-outline-primary btn-xs'><i class="im im-icon-Eye" data-placement="top" title="View"></i></a>
                        <a href="{{ route('assessmentCenters.edit', [$assessmentCenter->id]) }}" class='btn btn-outline-primary btn-xs'><i
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
