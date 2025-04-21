<!-- Occupation Id Field -->
<div class="col-md-3">
    <div class="form-group">
        {!! Form::label('occupation_id', 'Occupation',['class'=>'control-label']) !!}
        {!! Form::select('occupation_id', ['ইলেকট্রিক্যাল ইন্সট্রলেশন এন্ড মেইনটেন্যান্স' => 'ইলেকট্রিক্যাল ইন্সট্রলেশন এন্ড মেইনটেন্যান্স', 'কনজুউমার ইলেকট্রনিক্স' => 'কনজুউমার ইলেকট্রনিক্স'], null, ['class' => 'form-control']) !!}
    </div>
</div>


<!-- Registration Number Field -->
<div class="col-md-3">
    <div class="form-group">
        {!! Form::label('registration_number', 'Registration Number',['class'=>'control-label']) !!}
        {!! Form::text('registration_number', null, ['class' => 'form-control']) !!}
    </div>
</div>


<!-- Candidate Id Field -->
<div class="col-md-3">
    <div class="form-group">
        {!! Form::label('candidate_id', 'Candidate Id',['class'=>'control-label']) !!}
        {!! Form::text('candidate_id', null, ['class' => 'form-control']) !!}
    </div>
</div>


<!-- Candidate Name Field -->
<div class="col-md-3">
    <div class="form-group">
        {!! Form::label('candidate_name', 'Candidate Name',['class'=>'control-label']) !!}
        {!! Form::text('candidate_name', null, ['class' => 'form-control']) !!}
    </div>
</div>


<!-- Father Name Field -->
<div class="col-md-3">
    <div class="form-group">
        {!! Form::label('father_name', 'Father Name',['class'=>'control-label']) !!}
        {!! Form::text('father_name', null, ['class' => 'form-control']) !!}
    </div>
</div>


<!-- Mother Name Field -->
<div class="col-md-3">
    <div class="form-group">
        {!! Form::label('mother_name', 'Mother Name',['class'=>'control-label']) !!}
        {!! Form::text('mother_name', null, ['class' => 'form-control']) !!}
    </div>
</div>


<!-- Nid Field -->
<div class="col-md-3">
    <div class="form-group">
        {!! Form::label('nid', 'Nid',['class'=>'control-label']) !!}
        {!! Form::text('nid', null, ['class' => 'form-control']) !!}
    </div>
</div>

@php
    $districts = \App\Models\District::all()->pluck('name_en','id')->prepend('Select District', '')->toArray();
@endphp
<!-- district_id Field -->
<div class="col-md-3">
    <div class="form-group">
        {!! Form::label('district_id', 'District',['class'=>'control-label']) !!}
        {!! Form::select('district_id', $districts, null, ['class' => 'form-control']) !!}
    </div>
</div>


<!-- Upajila Id Field -->
<div class="col-md-3">
    <div class="form-group">
        {!! Form::label('upajila_id', 'Upajila',['class'=>'control-label']) !!}
        {!! Form::select('upajila_id', ['' => ''], null, ['class' => 'form-control']) !!}
    </div>
</div>


<!-- Address Field -->
<div class="col-md-3">
    <div class="form-group">
        {!! Form::label('address', 'Address',['class'=>'control-label']) !!}
        {!! Form::text('address', null, ['class' => 'form-control']) !!}
    </div>
</div>


<!-- Date Of Birth Field -->
<div class="col-md-3">
    <div class="form-group">
        {!! Form::label('date_of_birth', 'Date Of Birth',['class'=>'control-label']) !!}
        {!! Form::text('date_of_birth', null, ['class' => 'form-control','id'=>'date_of_birth']) !!}
    </div>
</div>
@section('footer_scripts')
<script type="text/javascript">
    $('#date_of_birth').datetimepicker({
            format: 'YYYY-MM-DD HH:mm:ss',
            useCurrent: true,
            sideBySide: true
        })
</script>
@endsection


<!-- Mobile Number Field -->
<div class="col-md-3">
    <div class="form-group">
        {!! Form::label('mobile_number', 'Mobile Number',['class'=>'control-label']) !!}
        {!! Form::number('mobile_number', null, ['class' => 'form-control']) !!}
    </div>
</div>


<!-- Email Field -->
<div class="col-md-3">
    <div class="form-group">
        {!! Form::label('email', 'Email',['class'=>'control-label']) !!}
        {!! Form::email('email', null, ['class' => 'form-control']) !!}
    </div>
</div>


<!-- Assessment Date Field -->
<div class="col-md-3">
    <div class="form-group">
        {!! Form::label('assessment_date', 'Assessment Date',['class'=>'control-label']) !!}
        {!! Form::text('assessment_date', null, ['class' => 'form-control','id'=>'assessment_date']) !!}
    </div>
</div>
@section('footer_scripts')
<script type="text/javascript">
    $('#assessment_date').datetimepicker({
            format: 'YYYY-MM-DD HH:mm:ss',
            useCurrent: true,
            sideBySide: true
        })
</script>
@endsection


<!-- Assessment Venue Field -->
<div class="col-md-3">
    <div class="form-group">
        {!! Form::label('assessment_venue', 'Assessment Venue',['class'=>'control-label']) !!}
        {!! Form::select('assessment_venue', ['কক্সবাজার সরকারি টেকনিক্যাল স্কুল এন্ড কলেজ' => 'কক্সবাজার সরকারি টেকনিক্যাল স্কুল এন্ড কলেজ'], null, ['class' => 'form-control']) !!}
    </div>
</div>


<!-- Assessment Center Field -->
<div class="col-md-3">
    <div class="form-group">
        {!! Form::label('assessment_center', 'Assessment Center',['class'=>'control-label']) !!}
        {!! Form::select('assessment_center', ['কক্সবাজার সরকারি টেকনিক্যাল স্কুল এন্ড কলেজ' => 'কক্সবাজার সরকারি টেকনিক্যাল স্কুল এন্ড কলেজ'], null, ['class' => 'form-control']) !!}
    </div>
</div>


<!-- Assessment Center Registration Number Field -->
<div class="col-md-3">
    <div class="form-group">
        {!! Form::label('assessment_center_registration_number', 'Assessment Center Registration Number',['class'=>'control-label']) !!}
        {!! Form::text('assessment_center_registration_number', null, ['class' => 'form-control']) !!}
    </div>
</div>


<!-- Submit Field -->
<div class="form-group col-sm-12" style="text-align-last: right;">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{{ route('students.index') }}" class="btn btn-danger">Cancel</a>
</div>
