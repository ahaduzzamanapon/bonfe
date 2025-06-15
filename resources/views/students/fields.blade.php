@php
    if( Request::is('general_students*')){
        $Occupation = \App\Models\Occupation::where('title', 'General')->get()->pluck('title', 'id')->toArray();
    }else{
        $Occupation = \App\Models\Occupation::where('title', '!=', 'General')->get()->pluck('title', 'id')->prepend('Select Occupation', '')->toArray();
    }

    $AssessmentVenue = \App\Models\AssessmentVenue::all()
        ->pluck('venue_name', 'id')
        ->prepend('Select Venue', '')
        ->toArray();
    $AssessmentCenter = \App\Models\AssessmentCenter::all()
        ->pluck('center_name', 'id')
        ->prepend('Select Center', '')
        ->toArray();
    $Program = \App\Models\Program::orderBy('id', 'desc')->get()
        ->pluck('program_title', 'id')
        ->prepend('Select Program', '')
        ->toArray();
@endphp


<!-- Occupation Id Field -->
<div class="col-md-3">
    <div class="form-group">
        {!! Form::label('program_id', 'Program', ['class' => 'control-label']) !!}
        {!! Form::select('program_id', $Program, null, ['class' => 'form-control']) !!}
    </div>
</div>

<!-- Occupation Id Field -->
<div class="col-md-3 @if( Request::is('general_students*')) d-none @endif">
    <div class="form-group">
        {!! Form::label('occupation_id', 'Occupation', ['class' => 'control-label']) !!}
        {!! Form::select('occupation_id', $Occupation, null, ['class' => 'form-control']) !!}
    </div>
</div>


<!-- Registration Number Field -->
<div class="col-md-3">
    <div class="form-group">
        {!! Form::label('registration_number', 'Registration Number', ['class' => 'control-label']) !!}
        {!! Form::text('registration_number', null, ['class' => 'form-control']) !!}
    </div>
</div>


<!-- Candidate Id Field -->
<div class="col-md-3">
    <div class="form-group">
        {!! Form::label('candidate_id', 'Candidate Id', ['class' => 'control-label']) !!}
        {!! Form::text('candidate_id', null, ['class' => 'form-control']) !!}
    </div>
</div>


<!-- Candidate Name Field -->
<div class="col-md-3">
    <div class="form-group">
        {!! Form::label('candidate_name', 'Candidate Name (English)', ['class' => 'control-label']) !!}
        {!! Form::text('candidate_name', null, ['class' => 'form-control']) !!}
    </div>
</div>

<!-- Candidate Name Field -->
<div class="col-md-3">
    <div class="form-group">
        {!! Form::label('candidate_name_bn', 'Candidate Name (Bangla)', ['class' => 'control-label']) !!}
        {!! Form::text('candidate_name_bn', null, ['class' => 'form-control']) !!}
    </div>
</div>

<div class="col-md-3">
    <div class="form-group">
        {!! Form::label('brn', 'Birth Registration Number', ['class' => 'control-label']) !!}
        {!! Form::text('brn', null, ['class' => 'form-control']) !!}
    </div>
</div>



<!-- Father Name Field -->
<div class="col-md-3">
    <div class="form-group">
        {!! Form::label('father_name', 'Father Name', ['class' => 'control-label']) !!}
        {!! Form::text('father_name', null, ['class' => 'form-control']) !!}
    </div>
</div>


<!-- Mother Name Field -->
<div class="col-md-3">
    <div class="form-group">
        {!! Form::label('mother_name', 'Mother Name', ['class' => 'control-label']) !!}
        {!! Form::text('mother_name', null, ['class' => 'form-control']) !!}
    </div>
</div>


<!-- Nid Field -->
<div class="col-md-3">
    <div class="form-group">
        {!! Form::label('nid', 'Nid', ['class' => 'control-label']) !!}
        {!! Form::text('nid', null, ['class' => 'form-control']) !!}
    </div>
</div>

@php
    if (!can('chairman') && can('district_admin')) {
        $districts = \App\Models\District::where('id', auth()->user()->district_id)
            ->pluck('name_en', 'id')
            ->toArray();
        $upazilas = \App\Models\Upazila::where('dis_id', auth()->user()->district_id)
            ->pluck('name_en', 'id')
            ->prepend('Select Upazila', '')
            ->toArray();
    } else {
        $districts = \App\Models\District::all()->pluck('name_en', 'id')->prepend('Select District', '')->toArray();
        $upazilas = \App\Models\Upazila::all()->pluck('name_en', 'id')->prepend('Select Upazila', '')->toArray();
    }
@endphp
<!-- district_id Field -->
<div class="col-md-3">
    <div class="form-group">
        {!! Form::label('district_id', 'District', ['class' => 'control-label']) !!}
        {!! Form::select('district_id', $districts, null, ['class' => 'form-control select2']) !!}
    </div>
</div>


<!-- Upajila Id Field -->
<div class="col-md-3">
    <div class="form-group">
        {!! Form::label('upajila_id', 'Upazila', ['class' => 'control-label']) !!}
        {!! Form::select('upajila_id', $upazilas, null, ['class' => 'form-control select2']) !!}
    </div>
</div>


<!-- Address Field -->
<div class="col-md-3">
    <div class="form-group">
        {!! Form::label('address', 'Address', ['class' => 'control-label']) !!}
        {!! Form::text('address', null, ['class' => 'form-control']) !!}
    </div>
</div>


<!-- Date Of Birth Field -->
<div class="col-md-3">
    <div class="form-group">
        {!! Form::label('date_of_birth', 'Date Of Birth', ['class' => 'control-label']) !!}
        {!! Form::text('date_of_birth', null, ['class' => 'form-control date', 'id' => 'date_of_birth','autocomplete' => 'off']) !!}
    </div>
</div>





<!-- Mobile Number Field -->
<div class="col-md-3">
    <div class="form-group">
        {!! Form::label('mobile_number', 'Mobile Number', ['class' => 'control-label']) !!}
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text" style="padding: 2px;border: 1px solid;">+880</span>
            </div>
            {!! Form::number('mobile_number', null, ['class' => 'form-control','style' => 'padding: 1px 1px 1px 1px;']) !!}
        </div>
    </div>
</div>


<!-- Email Field -->
<div class="col-md-3">
    <div class="form-group">
        {!! Form::label('email', 'Email', ['class' => 'control-label']) !!}
        {!! Form::email('email', null, ['class' => 'form-control']) !!}
    </div>
</div>



<!-- Email Field -->
<div class="col-md-3">
    <div class="form-group">
        {!! Form::label('admitted_from', ' Admitted from', ['class' => 'control-label']) !!}
        {!! Form::select('admitted_from', ['From this institution' => 'From this institution', 'From another institution' => 'From another institution'], null, ['class' => 'form-control']) !!}
    </div>
</div>


<!-- Address Field -->
<div class="col-md-3 d-none" id="institutionName">
    <div class="form-group">
        {!! Form::label('institutionName', 'Institution Name', ['class' => 'control-label']) !!}
        {!! Form::text('institutionName', null, ['class' => 'form-control']) !!}
    </div>
</div>


@section('footer_scripts')
    <script>
        $(document).ready(function() {
            toggleInstitutionName() 
            $('#admitted_from').change(function() {
                toggleInstitutionName() 
            });
        });
        function toggleInstitutionName() {
            var admittedFrom = document.getElementById('admitted_from').value;
            var institutionNameDiv = document.getElementById('institutionName');
            if (admittedFrom === 'From another institution') {
                institutionNameDiv.classList.remove('d-none');
            } else {
                institutionNameDiv.classList.add('d-none');
            }
        }
    </script>
@endsection




<div class="col-md-12">
    <div class="row">
        <!-- Image Field -->
        <div class="col-md-3">
            <div class="form-group">
                {!! Form::label('image', 'Image', ['class' => 'control-label']) !!}
                {!! Form::file('image', ['onchange' => 'previewImage(event, "imagePreview")', 'accept' => 'image/*']) !!}
                <img id="imagePreview" src="{{ isset($student) ? asset($student->image) : '' }}" alt="Image Preview"
                    style="{{ isset($student) && $student->image ? '' : 'display: none;' }}margin-top:10px;max-width: 45%;height:auto;" />
            </div>
        </div>
        <!-- Attachment Field -->
        <div class="col-md-3">
            <div class="form-group">
                {!! Form::label('attachment', 'Attachment', ['class' => 'control-label']) !!}
                {!! Form::file('attachment', ['onchange' => 'previewImage(event, "attachmentPreview")', 'accept' => 'image/*']) !!}
                <img id="attachmentPreview" src="{{ isset($student) ? asset($student->attachment) : '' }}"
                    alt="Attachment Preview"
                    style="{{ isset($student) && $student->image ? '' : 'display: none;' }}margin-top:10px;max-width: 45%;height:auto;" />
            </div>
        </div>
    </div>
</div>



<div class="col-md-12 {{ $ifAssessment ?? false ? '' : 'd-none' }}">
    <div class="row">
        <!-- Assessment Venue Field -->
        <div class="col-md-3 d-none">
            <div class="form-group">
                {!! Form::label('assessment_venue', 'Assessment Venue', ['class' => 'control-label']) !!}
                {!! Form::select('assessment_venue', $AssessmentVenue, null, ['class' => 'form-control']) !!}
            </div>
        </div>


        <!-- Assessment Center Field -->
        <div class="col-md-3">
            <div class="form-group">
                {!! Form::label('assessment_center', 'Assessment Center', ['class' => 'control-label']) !!}
                {!! Form::select('assessment_center', $AssessmentCenter, null, ['class' => 'form-control']) !!}
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                {!! Form::label('assessment_center_registration_number', 'Assessment Center Registration Number', [
                    'class' => 'control-label',
                ]) !!}
                {!! Form::text('assessment_center_registration_number', null, ['class' => 'form-control']) !!}
            </div>
        </div>


        <!-- Assessment Date Field -->
        <div class="col-md-3">
            <div class="form-group">
                {!! Form::label('assessment_date', 'Assessment Date', ['class' => 'control-label']) !!}
                {!! Form::text('assessment_date', null, ['class' => 'form-control date', 'id' => 'assessment_date','autocomplete' => 'off']) !!}
            </div>
        </div>

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
@section('footer_scripts')
    <script>
        $(document).ready(function() {
            $('#district_id').change(function() {
                var districtId = $(this).val();
                $.ajax({
                    url: "{{ route('get_upazilas') }}",
                    type: "GET",
                    data: {
                        district_id: districtId
                    },
                    success: function(data) {
                        $('#upajila_id').empty();
                        $('#upajila_id').append('<option value="">Select Upazila</option>');
                        $.each(data, function(index, upajila) {
                            $('#upajila_id').append('<option value="' + upajila.id + '">' + upajila.name + '</option>');
                        });
                    }
                });
            });
        });

        $(document).ready(function() {
            $('#program_id').change(function() {
                var districtId = $(this).val();
                $.ajax({
                    url: "{{ route('get_upazilas') }}",
                    type: "GET",
                    data: {
                        district_id: districtId
                    },
                    success: function(data) {
                        $('#upajila_id').empty();
                        $('#upajila_id').append('<option value="">Select Upazila</option>');
                        $.each(data, function(index, upajila) {
                            $('#upajila_id').append('<option value="' + upajila.id + '">' + upajila.name + '</option>');
                        });
                    }
                });
            });
        });
    </script>





@endsection


<!-- Submit Field -->
<div class="form-group col-sm-12" style="text-align-last: right;">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{{ route('students.index') }}" class="btn btn-danger">Cancel</a>
</div>
