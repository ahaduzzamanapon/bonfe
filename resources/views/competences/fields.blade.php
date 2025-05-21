<!-- Title Field -->
<div class="col-md-3">
    <div class="form-group">
        {!! Form::label('title', 'Title',['class'=>'control-label']) !!}
        {!! Form::text('title', null, ['class' => 'form-control']) !!}
    </div>
</div>

<?php
$Occupation = \App\Models\Occupation::all()->pluck('title', 'id')->prepend('Select Occupation', '')->toArray();

?>

<!-- Occupation Id Field -->
<div class="col-md-3">
    <div class="form-group">
        {!! Form::label('occupation_id', 'Occupation', ['class' => 'control-label']) !!}
        {!! Form::select('occupation_id', $Occupation, null, ['class' => 'form-control']) !!}
    </div>
</div>



<!-- Submit Field -->
<div class="form-group col-sm-12" style="text-align-last: right;">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{{ route('competences.index') }}" class="btn btn-danger">Cancel</a>
</div>
