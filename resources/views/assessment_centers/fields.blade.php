<!-- Center Name Field -->
<div class="col-md-3">
    <div class="form-group">
        {!! Form::label('center_name', 'Center Name', ['class' => 'control-label']) !!}
                <span style="color: red">*</span>

        {!! Form::text('center_name', null, ['class' => 'form-control', 'required']) !!}
    </div>
</div>
@php
    $districts = \App\Models\District::pluck('name_en', 'id')
        ->toArray();
@endphp
<!-- District Id Field -->
<!-- district_id Field -->
<div class="col-md-3 ">
    <div class="form-group">

        {!! Form::label('district_id', 'District', ['class' => 'control-label']) !!}
        <span style="color: red">*</span>
        {!! Form::select('district_id', $districts, null, ['class' => 'form-control select2', 'required']) !!}
    </div>
</div>


<!-- Registration Number Field -->
<div class="col-md-3">
    <div class="form-group">
        {!! Form::label('registration_number', 'Registration Number', ['class' => 'control-label']) !!}
        
        {!! Form::text('registration_number', null, ['class' => 'form-control']) !!}
    </div>
</div>


<!-- Address Field -->
<div class="col-md-3">
    <div class="form-group">
        {!! Form::label('address', 'Address', ['class' => 'control-label']) !!}
        {!! Form::text('address', null, ['class' => 'form-control']) !!}
    </div>
</div>


<!-- Submit Field -->
<div class="form-group col-sm-12" style="text-align-last: right;">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{{ route('assessmentCenters.index') }}" class="btn btn-danger">Cancel</a>
</div>