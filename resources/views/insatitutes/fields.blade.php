<div class="col-md-3">
    <div class="form-group">
        {!! Form::label('insatitute_name', 'Institute Name',['class'=>'control-label']) !!}
        {!! Form::text('insatitute_name', null, ['class' => 'form-control','required']) !!}
    </div>
</div>
<!-- Type Field -->
<div class="col-md-3">
    <div class="form-group">
        {!! Form::label('type', 'Type',['class'=>'control-label']) !!}
        {!! Form::select('type', ['IBC' => 'IBC', 'OBC' => 'OBC', 'EBC' => 'EBC'], null, ['class' => 'form-control', 'placeholder' => 'Select Type']) !!}
    </div>
</div>
<!-- Code Field -->
<div class="col-md-3">
    <div class="form-group">
        {!! Form::label('code', 'Code (e.g., 0001)',['class'=>'control-label']) !!}
        {!! Form::text('code', null, ['class' => 'form-control']) !!}
    </div>
</div>
@php
    if (!can('chairman') && can('district_admin')) {
        $districts = \App\Models\District::where('id', auth()->user()->district_id)
            ->pluck('name_en', 'id')
            ->toArray();
    } else {
        $districts = \App\Models\District::all()->pluck('name_en', 'id')->prepend('Select District', '')->toArray();
    }
@endphp

<!-- District Field -->
<div class="col-md-3">
    <div class="form-group">
        {!! Form::label('district', 'District',['class'=>'control-label']) !!}
        {!! Form::select('district',$districts , null, ['class' => 'form-control','required']) !!}
    </div>
</div>


<!-- Address Field -->
<div class="col-md-3">
    <div class="form-group">
        {!! Form::label('address', 'Address',['class'=>'control-label']) !!}
        {!! Form::text('address', null, ['class' => 'form-control','required']) !!}
    </div>
</div>


<!-- Status Field -->
<div class="col-md-3 @if (!can('approve_insatitutes')) d-none @endif">
    <div class="form-group">
        {!! Form::label('status', 'Status',['class'=>'control-label']) !!}
        {!! Form::select('status', ['Inactive' => 'Inactive', 'Active' => 'Active'], null, ['class' => 'form-control']) !!}
    </div>
</div>


<!-- Description Field -->
<div class="col-md-12 d-none">
    <div class="form-group ">
        {!! Form::label('description', 'Description',['class'=>'control-label']) !!}
        {!! Form::textarea('description', 'none', ['class' => 'form-control']) !!}
    </div>
</div>


<!-- Submit Field -->
<div class="form-group col-sm-12" style="text-align-last: right;">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{{ route('insatitutes.index') }}" class="btn btn-danger">Cancel</a>
</div>
