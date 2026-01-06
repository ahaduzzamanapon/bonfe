<div class="table-responsive">
    <table class="table" id="insatitutes-table">
        <thead>
            <tr>
                <th>SL</th>
        <th>Insatitute Name</th>
        <th>District</th>
        <th>Address</th>
        <th>Status</th>
      
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        @foreach($insatitutes as $key => $insatitute)
            <tr>
                <td>{{ $key+1 }}</td>
            <td>{{ $insatitute->insatitute_name }}</td>
            <td>{{ $insatitute->district_name }}</td>
            <td>{{ $insatitute->address }}</td>
            <td>{{ $insatitute->status }}</td>
                <td>
                    {!! Form::open(['route' => ['insatitutes.destroy', $insatitute->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('insatitutes.show', [$insatitute->id]) }}" class='btn btn-outline-primary btn-xs'><i class="im im-icon-Eye" data-placement="top" title="View"></i></a>
                        <a href="{{ route('insatitutes.edit', [$insatitute->id]) }}" class='btn btn-outline-primary btn-xs'><i
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
