<div class="table-responsive">
    <table class="table" id="assessmentVenues-table">
        <thead>
            <tr>
                <th>Id</th>
        <th>Venue Name</th>
        <th>Address</th>
        <th>Created At</th>
        <th>Updated At</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        @foreach($assessmentVenues as $key => $assessmentVenue)
            <tr>
                <td>{{ $assessmentVenue->id }}</td>
            <td>{{ $assessmentVenue->venue_name }}</td>
            <td>{{ $assessmentVenue->address }}</td>
            <td>{{ $assessmentVenue->created_at }}</td>
            <td>{{ $assessmentVenue->updated_at }}</td>
                <td>
                    {!! Form::open(['route' => ['assessmentVenues.destroy', $assessmentVenue->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('assessmentVenues.show', [$assessmentVenue->id]) }}" class='btn btn-outline-primary btn-xs'><i class="im im-icon-Eye" data-placement="top" title="View"></i></a>
                        <a href="{{ route('assessmentVenues.edit', [$assessmentVenue->id]) }}" class='btn btn-outline-primary btn-xs'><i
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
