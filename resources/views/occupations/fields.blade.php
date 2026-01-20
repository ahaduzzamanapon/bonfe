<div class="col-md-3">
    <div class="form-group">
        {!! Form::label('title', 'Title',['class'=>'control-label']) !!}
        {!! Form::text('title', null, ['class' => 'form-control']) !!}
    </div>
</div>
<!-- Code Field -->
<div class="form-group col-sm-6">
    {!! Form::label('code', 'Code:') !!}
    {!! Form::text('code', null, ['class' => 'form-control']) !!}
</div>

<!-- Category Field -->
<div class="form-group col-sm-6">
    {!! Form::label('category', 'Sector:') !!}
    {!! Form::select('category', ['ICT' => 'ICT', 'CON' => 'CON', 'INF' => 'INF', 'LE' => 'LE', 'T&H' => 'T&H'], null, ['class' => 'form-control', 'placeholder' => 'Select Sector']) !!}
</div>


<!-- Description Field -->
<div class="col-md-12">
    <div class="form-group ">
        {!! Form::label('description', 'Description',['class'=>'control-label']) !!}
        {!! Form::textarea('description', null, ['class' => 'form-control']) !!}
    </div>
</div>


<!-- Submit Field -->
<div class="form-group col-sm-12" style="text-align-last: right;">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{{ route('occupations.index') }}" class="btn btn-danger">Cancel</a>
</div>
