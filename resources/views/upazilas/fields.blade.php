<!-- Dis Id Field -->
<div class="col-md-3">
    <div class="form-group">
        {!! Form::label('dis_id', 'Dis Id',['class'=>'control-label']) !!}
        {!! Form::select('dis_id', ['select' => 'select', 'selct' => 'selct'], null, ['class' => 'form-control']) !!}
    </div>
</div>


<!-- Name En Field -->
<div class="col-md-3">
    <div class="form-group">
        {!! Form::label('name_en', 'Name En',['class'=>'control-label']) !!}
        {!! Form::text('name_en', null, ['class' => 'form-control']) !!}
    </div>
</div>


<!-- Name Bn Field -->
<div class="col-md-3">
    <div class="form-group">
        {!! Form::label('name_bn', 'Name Bn',['class'=>'control-label']) !!}
        {!! Form::text('name_bn', null, ['class' => 'form-control']) !!}
    </div>
</div>


<!-- Submit Field -->
<div class="form-group col-sm-12" style="text-align-last: right;">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{{ route('upazilas.index') }}" class="btn btn-danger">Cancel</a>
</div>
