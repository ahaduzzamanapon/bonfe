<div class="table-responsive">
    @foreach($competences->groupBy('occupation_title') as $occupationTitle => $competencesByOccupationTitle)
        <table class="table mb-0">
            <thead>
                <tr>
                    <th colspan="3" class="bg-primary text-white text-center">
                        <button class="btn btn-link text-white w-100 text-start" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-{{ Str::slug($occupationTitle, '-') }}" aria-expanded="false" aria-controls="collapse-{{ Str::slug($occupationTitle, '-') }}">
                            {{ $occupationTitle }}
                            <span class="float-end">
                                <i class="bi bi-chevron-down"></i>
                            </span>
                        </button>
                    </th>
                </tr>
                <tr class="collapse" id="collapse-{{ Str::slug($occupationTitle, '-') }}">
                    <th>SL</th>
                    <th>Title</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody class="collapse" id="collapse-{{ Str::slug($occupationTitle, '-') }}">
                @foreach($competencesByOccupationTitle as $key => $competence)
                    <tr>
                        <td>{{ $key+1 }}</td>
                        <td>{{ $competence->title }}</td>
                        <td>
                            {!! Form::open(['route' => ['competences.destroy', $competence->id], 'method' => 'delete']) !!}
                            <div class='btn-group'>
                                <a href="{{ route('competences.show', [$competence->id]) }}" class='btn btn-outline-primary btn-xs'><i class="im im-icon-Eye" data-placement="top" title="View"></i></a>
                                <a href="{{ route('competences.edit', [$competence->id]) }}" class='btn btn-outline-primary btn-xs'><i class="im im-icon-Pen" data-toggle="tooltip" data-placement="top" title="Edit"></i></a>
                                {!! Form::button('<i class="im im-icon-Remove" data-toggle="tooltip" data-placement="top" title="Delete"></i>', ['type' => 'submit', 'class' => 'btn btn-outline-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                            </div>
                            {!! Form::close() !!}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endforeach
</div>
