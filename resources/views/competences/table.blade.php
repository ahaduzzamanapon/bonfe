<div class="table-responsive">
    <table class="table table_data" id="competences-table">
        <thead>
            <tr>
                <th>Id</th>
                <th>Title</th>
                <th>Occupation
                </th>
                <th>Created At</th>
                <th>Updated At</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        @foreach($competences as $key => $competence)
            <tr>
                <td>{{ $competence->id }}</td>
            <td>{{ $competence->title }}</td>
            <td>{{ $competence->occupation_title }}</td>
            <td>{{ $competence->created_at }}</td>
            <td>{{ $competence->updated_at }}</td>
                <td>
                    {!! Form::open(['route' => ['competences.destroy', $competence->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('competences.show', [$competence->id]) }}" class='btn btn-outline-primary btn-xs'><i class="im im-icon-Eye" data-placement="top" title="View"></i></a>
                        <a href="{{ route('competences.edit', [$competence->id]) }}" class='btn btn-outline-primary btn-xs'><i
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
