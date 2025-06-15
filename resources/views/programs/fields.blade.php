<!-- Program Title Field -->
<div class="col-md-3">
    <div class="form-group">
        {!! Form::label('program_title', 'Program Title',['class'=>'control-label']) !!}
        {!! Form::text('program_title', null, ['class' => 'form-control']) !!}
    </div>
</div>

<!-- Program Type Field -->
<div class="col-md-3">
    <div class="form-group">
        {!! Form::label('program_type', 'Program Type', ['class' => 'control-label']) !!}
        {!! Form::select('program_type', ['' => 'Select Program Type','Technical' => 'Technical', 'General' => 'General'], null, ['class' => 'form-control']) !!}
    </div>
</div>



<!-- Start Date Field -->
<div class="col-md-3">
    <div class="form-group">
        {!! Form::label('start_date', 'Start Date',['class'=>'control-label']) !!}
        {!! Form::text('start_date', null, ['class' => 'form-control date','autocomplete'=>'off']) !!}
    </div>
</div>


<!-- End Date Field -->
<div class="col-md-3">
    <div class="form-group">
        {!! Form::label('end_date', 'End Date',['class'=>'control-label']) !!}
        {!! Form::text('end_date', null, ['class' => 'form-control date','autocomplete'=>'off']) !!}
    </div>
</div>


<!-- Description Field -->
<div class="col-md-12">
    <div class="form-group">
        {!! Form::label('description', 'Description',['class'=>'control-label']) !!}
        {!! Form::textarea('description', null, ['class' => 'form-control','rows'=>'3']) !!}
    </div>
</div>


<!-- Submit Field -->
<div class="form-group col-sm-12" style="text-align-last: right;">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{{ route('programs.index') }}" class="btn btn-danger">Cancel</a>
</div>
