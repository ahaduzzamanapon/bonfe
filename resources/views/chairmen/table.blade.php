<div class="table-responsive">
    <table class="table" id="chairmen-table">
        <thead>
            <tr>
                <th>Id</th>
        <th>Name</th>
        <th>Address</th>
        <th>Signature</th>
        <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        @foreach($chairmen as $key => $chairman)
            <tr>
                <td>{{ $chairman->id }}</td>
            <td>{{ $chairman->name }}</td>
            <td>{{ $chairman->address }}</td>
            <td><img src="{{asset($chairman->signature)}}" alt="" width="100"></td>
            <td>{{ $chairman->status }}</td>
                <td>
                    {!! Form::open(['route' => ['chairmen.destroy', $chairman->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('chairmen.show', [$chairman->id]) }}" class='btn btn-outline-primary btn-xs'><i class="im im-icon-Eye" data-placement="top" title="View"></i></a>
                        <a href="{{ route('chairmen.edit', [$chairman->id]) }}" class='btn btn-outline-primary btn-xs'><i
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
