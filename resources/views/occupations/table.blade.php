<div class="table-responsive">
    <table class="table" id="occupations-table">
        <thead>
            <tr>
                <th>Id</th>
        <th>Title</th>
        <th>Description</th>
        <th>Created At</th>
        <th>Updated At</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        @foreach($occupations as $key => $occupation)
            <tr>
                <td>{{ $occupation->id }}</td>
            <td>{{ $occupation->title }}</td>
            <td>{{ $occupation->description }}</td>
            <td>{{ $occupation->created_at }}</td>
            <td>{{ $occupation->updated_at }}</td>
                <td>
                    {!! Form::open(['route' => ['occupations.destroy', $occupation->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('occupations.show', [$occupation->id]) }}" class='btn btn-outline-primary btn-xs'><i class="im im-icon-Eye" data-placement="top" title="View"></i></a>
                        <a href="{{ route('occupations.edit', [$occupation->id]) }}" class='btn btn-outline-primary btn-xs'><i
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
