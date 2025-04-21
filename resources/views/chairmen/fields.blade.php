<!-- Name Field -->
<div class="col-md-3">
    <div class="form-group">
        {!! Form::label('name', 'Name',['class'=>'control-label']) !!}
        {!! Form::text('name', null, ['class' => 'form-control']) !!}
    </div>
</div>


<!-- Address Field -->
<div class="col-md-3">
    <div class="form-group">
        {!! Form::label('address', 'Address',['class'=>'control-label']) !!}
        {!! Form::text('address', null, ['class' => 'form-control']) !!}
    </div>
</div>


<!-- Signature Field -->
<div class="col-md-3">
    <div class="form-group">
        {!! Form::label('signature', 'Signature',['class'=>'control-label']) !!}
        {!! Form::file('signature') !!}
    </div>
</div>
 <div class="clearfix"></div>


<!-- Status Field -->
<div class="col-md-3">
    <div class="form-group">
        {!! Form::label('status', 'Status',['class'=>'control-label']) !!}
        {!! Form::select('status', ['Active' => 'Active', 'Inactive' => 'Inactive'], null, ['class' => 'form-control']) !!}
    </div>
</div>


<!-- Submit Field -->
<div class="form-group col-sm-12" style="text-align-last: right;">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{{ route('chairmen.index') }}" class="btn btn-danger">Cancel</a>
</div>
