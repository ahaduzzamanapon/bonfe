






<!-- Emp Id Field -->
<div class="col-md-3">
    <div class="form-group">
        {!! Form::label('emp_id', 'Emp Id',['class'=>'control-label']) !!}
        {!! Form::text('emp_id', null , ['class' => 'form-control','required']) !!}
    </div>
</div>


<!-- Name Field -->
<div class="col-md-3">
    <div class="form-group">
        {!! Form::label('name', 'First Name',['class'=>'control-label']) !!}
        {!! Form::text('name', null, ['class' => 'form-control','required']) !!}
    </div>
</div>


<!-- Last Name Field -->
<div class="col-md-3">
    <div class="form-group">
        {!! Form::label('last_name', 'Last Name',['class'=>'control-label']) !!}
        {!! Form::text('last_name', null, ['class' => 'form-control','required']) !!}
    </div>
</div>


<!-- Email Field -->
<div class="col-md-3">
    <div class="form-group">
        {!! Form::label('email', 'Email',['class'=>'control-label']) !!}
        {!! Form::email('email', null, ['class' => 'form-control','required']) !!}
    </div>
</div>


<!-- Date Of Birth Field -->
<div class="col-md-3">
    <div class="form-group">
        {!! Form::label('date_of_birth', 'Date Of Birth',['class'=>'control-label']) !!}
        {!! Form::date('date_of_birth', null, ['class' => 'form-control','id'=>'date_of_birth','required']) !!}
    </div>
</div>


<!-- Date Of Join Field -->
<div class="col-md-3 d-none" >
    <div class="form-group">
        {!! Form::label('date_of_join', 'Date Of Join',['class'=>'control-label']) !!}
        {!! Form::date('date_of_join', null, ['class' => 'form-control','id'=>'date_of_join']) !!}
    </div>
</div>



<!-- Gender Field -->
<div class="col-md-3">
    <div class="form-group">
        {!! Form::label('gender', 'Gender',['class'=>'control-label']) !!}
        {!! Form::select('gender', ['Male' => 'Male', 'Female' => 'Female'], null, ['class' => 'form-control','required']) !!}
    </div>
</div>
@php
    $designations = \App\Models\Designation::all()->pluck('desi_name','id')->prepend('Select Designation', '')->toArray();
@endphp
<!-- Gender Field -->
<div class="col-md-3">
    <div class="form-group">
        {!! Form::label('designation_id', 'Designation',['class'=>'control-label']) !!}
        {!! Form::select('designation_id',$designations, null, ['class' => 'form-control','required']) !!}
    </div>
</div>

@php
  if(!can('chairman') && can('district_admin')) {
      $districts = \App\Models\District::where('id', auth()->user()->district_id)->get()->pluck('name_en','id')->toArray();
    }else {
        $districts = \App\Models\District::all()->pluck('name_en','id')->prepend('Select District', '')->toArray();
        $districts_multi = \App\Models\District::all()->pluck('name_en','id')->toArray();
    }

@endphp
<!-- Gender Field -->
<div class="col-md-3 @if(!can('chairman') && can('district_admin')) d-none @endif" >
    <div class="form-group">
        {!! Form::label('district_id', 'District',['class'=>'control-label']) !!}
        {!! Form::select('district_id',$districts, null, ['class' => 'form-control','required']) !!}
    </div>
</div>


<!-- Address Field -->
<div class="col-md-3">
    <div class="form-group">
        {!! Form::label('address', 'Address',['class'=>'control-label']) !!}
        {!! Form::text('address', null, ['class' => 'form-control']) !!}
    </div>
</div>


<!-- Phone Number Field -->
<div class="col-md-3">
    <div class="form-group">
        {!! Form::label('phone_number', 'Phone Number',['class'=>'control-label']) !!}
        {!! Form::number('phone_number', null, ['class' => 'form-control','required']) !!}
    </div>
</div>







<!-- Salary Field -->
<div class="col-md-3  d-none">
    <div class="form-group">
        {!! Form::label('salary', 'Salary',['class'=>'control-label']) !!}
        {!! Form::number('salary', null, ['class' => 'form-control']) !!}
    </div>
</div>


<!-- Nid Field -->
<div class="col-md-3  d-none">
    <div class="form-group">
        {!! Form::label('nid', 'Nid',['class'=>'control-label']) !!}
        {!! Form::text('nid', null, ['class' => 'form-control']) !!}
    </div>
</div>


@php
    $AssessmentCenter = \App\Models\AssessmentCenter::all()->pluck('center_name','id')->prepend('Select Center', '')->toArray();
    $Occupation = \App\Models\Occupation::all()->pluck('title','id')->prepend('Select Occupation', '')->toArray();

      if(!can('chairman') && can('district_admin')) {
          $roles = \App\Models\RoleAndPermission::where('key', 'assessment_centers_controller')->get()->pluck('name','id')->toArray();
        }else {
            $roles = \App\Models\RoleAndPermission::all()->pluck('name','id')->prepend('Select Roll', '')->toArray();
        }

@endphp


<!-- Group Id Field -->
<div class="col-md-3">
    <div class="form-group">
        {!! Form::label('group_id', 'Roll',['class'=>'control-label']) !!}
        {!! Form::select('group_id',$roles, null, ['class' => 'form-control']) !!}
    </div>
</div>

@php
    if(isset($users)){
        $user_id = $users->id;
        $multiple_district = \DB::table('multiple_district')->where('user_id', $user_id)->pluck('district_id')->toArray();
    }
@endphp


<!-- multiple district  Field -->
<div class="col-md-3">
    <div class="form-group">
        {!! Form::label('multiple_district', 'Multiple District',['class'=>'control-label']) !!}
        {!! Form::select('multiple_district[]',$districts_multi,isset($multiple_district) ? $multiple_district : [], ['class' => 'form-control select2','multiple'=>'multiple']) !!}
    </div>
</div>

<!-- Group Id Field -->
<div class="col-md-3">
    <div class="form-group">
        {!! Form::label('assessment_center', 'AssessmentCenter',['class'=>'control-label']) !!}
        {!! Form::select('assessment_center',$AssessmentCenter, null, ['class' => 'form-control']) !!}
    </div>
</div>
<!-- Group Id Field -->
<div class="col-md-3">
    <div class="form-group">
        {!! Form::label('occupation', 'Occupation',['class'=>'control-label']) !!}
        {!! Form::select('occupation',$Occupation, null, ['class' => 'form-control']) !!}
    </div>
</div>











<!-- Education Field -->
<div class="col-md-3  d-none">
    <div class="form-group">
        {!! Form::label('education', 'Education',['class'=>'control-label']) !!}
        {!! Form::text('education', null, ['class' => 'form-control']) !!}
    </div>
</div>


<!-- Blood Group Field -->
<div class="col-md-3 d-none">
    <div class="form-group">
        {!! Form::label('blood_group', 'Blood Group',['class'=>'control-label']) !!}
        {!! Form::select('blood_group', [
            'A+' => 'A+',
            'A-' => 'A-',
            'B+' => 'B+',
            'B-' => 'B-',
            'AB+' => 'AB+',
            'AB-' => 'AB-',
            'O+' => 'O+',
            'O-' => 'O-'
        ], null, ['class' => 'form-control']) !!}
    </div>
</div>



<!-- Religion Field -->
<div class="col-md-3">
    <div class="form-group">
        {!! Form::label('religion', 'Religion',['class'=>'control-label']) !!}
        {!! Form::select('religion', ['Islam' => 'Islam', 'Hindu' => 'Hindu'], null, ['class' => 'form-control']) !!}
    </div>
</div>


<!-- Marital Status Field -->
<div class="col-md-3 d-none">
    <div class="form-group">
        {!! Form::label('marital_status', 'Marital Status',['class'=>'control-label']) !!}
        {!! Form::select('marital_status', ['Single' => 'Single', 'Married' => 'Married'], null, ['class' => 'form-control']) !!}
    </div>
</div>


<!-- Punch Id Field -->
<div class="col-md-3  d-none">
    <div class="form-group">
        {!! Form::label('punch_id', 'Punch Id',['class'=>'control-label']) !!}
        {!! Form::text('punch_id', null, ['class' => 'form-control']) !!}
    </div>
</div>




<!-- Experience Field -->
<div class="col-md-3  d-none">
    <div class="form-group">
        {!! Form::label('experience', 'Experience',['class'=>'control-label']) !!}
        {!! Form::text('experience', null, ['class' => 'form-control']) !!}
    </div>
</div>


<!-- Email Verified At Field -->
<div class="col-md-3  d-none">
    <div class="form-group">
        {!! Form::label('email_verified_at', 'Email Verified At',['class'=>'control-label']) !!}
        {!! Form::text('email_verified_at', null, ['class' => 'form-control']) !!}
    </div>
</div>


<!-- Password Field -->
<div class="col-md-3">
    <div class="form-group">
        {!! Form::label('password', 'Password',['class'=>'control-label']) !!}
        {!! Form::password('password', ['class' => 'form-control']) !!}
    </div>
</div>


<!-- Remember Token Field -->
<div class="col-md-3  d-none">
    <div class="form-group">
        {!! Form::label('remember_token', 'Remember Token',['class'=>'control-label']) !!}
        {!! Form::text('remember_token', null, ['class' => 'form-control']) !!}
    </div>
</div>
<!-- Image Field -->
<div class="col-md-3">
    <div class="form-group">
        {!! Form::label('image', 'Image',['class'=>'control-label']) !!}
        {!! Form::file('image', ['onchange' => 'previewImage(event, "imagePreview")','accept' => 'image/*']) !!}
        <img id="imagePreview" src="{{ isset($users) ? asset($users->image) : '' }}" alt="Image Preview" style="{{ isset($users) && $users->image ?  '': 'display: none;' }}margin-top:10px;max-width: 45%;height:auto;" />
    </div>
</div>
<!-- Signature Field -->
<div class="col-md-3">
    <div class="form-group">
        {!! Form::label('signature', 'Signature',['class'=>'control-label']) !!}
        {!! Form::file('signature', ['onchange' => 'previewImage(event, "signaturePreview")','accept' => 'image/*']) !!}
        <img id="signaturePreview" src="{{ isset($users) ? asset($users->signature) : '' }}" alt="Signature Preview" style="{{ isset($users) && $users->image ?  '': 'display: none;' }}margin-top:10px;max-width: 45%;height:auto;" />
    </div>
</div>
<script>
    function previewImage(event, previewId) {

        var file = event.target.files[0];
        var reader = new FileReader();
        reader.onload = function() {
            document.getElementById(previewId).src = reader.result;
            document.getElementById(previewId).style.display = 'block';
        };
        reader.readAsDataURL(file);
    }
</script>
<div class="clearfix"></div>


<!-- Submit Field -->
<div class="form-group col-sm-12" style="text-align-last: right;">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{{ route('users.index') }}" class="btn btn-danger">Cancel</a>
</div>

@section('footer_scripts')

<script type="text/javascript">
    
</script>


@endsection
