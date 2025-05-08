<div class="table-responsive">
    <table class="table table_data" id="upazilas-table">
        <thead>
            <tr>
                <th>SL</th>
        <th>District</th>
        <th>Name En</th>
        <th>Name Bn</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        @foreach($upazilas as $key => $upazila)
            <tr>
                <td>{{ $key+1 }}</td>
            <td>{{ $upazila->district_name }}</td>
            <td>{{ $upazila->name_en }}</td>
            <td>{{ $upazila->name_bn }}</td>
          
                <td>
                    {!! Form::open(['route' => ['upazilas.destroy', $upazila->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('upazilas.show', [$upazila->id]) }}" class='btn btn-outline-primary btn-xs'><i class="im im-icon-Eye" data-placement="top" title="View"></i></a>
                        <a href="{{ route('upazilas.edit', [$upazila->id]) }}" class='btn btn-outline-primary btn-xs'><i
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
