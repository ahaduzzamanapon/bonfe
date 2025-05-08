<tr>
    <th scopre="row">{!! Form::label('id', 'Id:') !!}</th>
    <td>{{ $program->id }}</td>
</tr>


<tr>
    <th scopre="row">{!! Form::label('program_title', 'Program Title:') !!}</th>
    <td>{{ $program->program_title }}</td>
</tr>


<tr>
    <th scopre="row">{!! Form::label('start_date', 'Start Date:') !!}</th>
    <td>{{ $program->start_date }}</td>
</tr>


<tr>
    <th scopre="row">{!! Form::label('end_date', 'End Date:') !!}</th>
    <td>{{ $program->end_date }}</td>
</tr>


<tr>
    <th scopre="row">{!! Form::label('description', 'Description:') !!}</th>
    <td>{{ $program->description }}</td>
</tr>


<tr>
    <th scopre="row">{!! Form::label('created_at', 'Created At:') !!}</th>
    <td>{{ $program->created_at }}</td>
</tr>


<tr>
    <th scopre="row">{!! Form::label('updated_at', 'Updated At:') !!}</th>
    <td>{{ $program->updated_at }}</td>
</tr>


