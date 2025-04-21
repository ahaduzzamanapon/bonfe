@extends('layouts.default')

{{-- Page title --}}
@section('title')
    Students @parent
@stop

@section('content')
<style>
   .card .card-header {
    padding: 5px;
    background: #71ef56 !important;
}
</style>

<div data-spy="scroll" data-target="#navId">
    
    <div id="navId">
        <ul class="nav nav-tabs" role="tablist">
            
        </ul>
    </div>
    
</div>


<script>
    $('div{1:div|body}').scrollspy({
        target: '#navId'
    });
</script>
<div class="container mt-5">
    <div class="card shadow-lg rounded-lg">
        <div class="card-header bg-primary text-white rounded-top">
            <h3 class="card-title font-weight-bold">Student Details</h3>
        </div>
        <div class="card-body p-4">
            <div class="row">
                <div class="col-md-6 mb-4">
                    <strong class="text-uppercase text-dark">Occupation:</strong> 
                    <span class="text-muted">{{ $student->occupation_id }}</span><br>
                    
                    <strong class="text-uppercase text-dark">Registration Number:</strong> 
                    <span class="text-muted">{{ $student->registration_number }}</span><br>
                    
                    <strong class="text-uppercase text-dark">Candidate Id:</strong> 
                    <span class="text-muted">{{ $student->candidate_id }}</span><br>
                    
                    <strong class="text-uppercase text-dark">Candidate Name:</strong> 
                    <span class="text-muted">{{ $student->candidate_name }}</span><br>
                    
                    <strong class="text-uppercase text-dark">Father Name:</strong> 
                    <span class="text-muted">{{ $student->father_name }}</span><br>
                    
                    <strong class="text-uppercase text-dark">Mother Name:</strong> 
                    <span class="text-muted">{{ $student->mother_name }}</span><br>
                    
                    <strong class="text-uppercase text-dark">Nid:</strong> 
                    <span class="text-muted">{{ $student->nid }}</span><br>
                    
                    <strong class="text-uppercase text-dark">District Id:</strong> 
                    <span class="text-muted">{{ $student->district_id }}</span><br>
                    
                    <strong class="text-uppercase text-dark">Upajila Id:</strong> 
                    <span class="text-muted">{{ $student->upajila_id }}</span><br>
                    
                    <strong class="text-uppercase text-dark">Address:</strong> 
                    <span class="text-muted">{{ $student->address }}</span><br>
                </div>
                <div class="col-md-6 mb-4">
                    <strong class="text-uppercase text-dark">Date Of Birth:</strong> 
                    <span class="text-muted">{{ $student->date_of_birth }}</span><br>
                    
                    <strong class="text-uppercase text-dark">Mobile Number:</strong> 
                    <span class="text-muted">{{ $student->mobile_number }}</span><br>
                    
                    <strong class="text-uppercase text-dark">Email:</strong> 
                    <span class="text-muted">{{ $student->email }}</span><br>
                    
                    <strong class="text-uppercase text-dark">Assessment Date:</strong> 
                    <span class="text-muted">{{ $student->assessment_date }}</span><br>
                    
                    <strong class="text-uppercase text-dark">Assessment Venue:</strong> 
                    <span class="text-muted">{{ $student->assessment_venue }}</span><br>
                    
                    <strong class="text-uppercase text-dark">Assessment Center:</strong> 
                    <span class="text-muted">{{ $student->assessment_center }}</span><br>
                    
                    <strong class="text-uppercase text-dark">Assessment Center Registration Number:</strong> 
                    <span class="text-muted">{{ $student->assessment_center_registration_number }}</span><br>
                    
                    <strong class="text-uppercase text-dark">Created At:</strong> 
                    <span class="text-muted">{{ $student->created_at }}</span><br>
                    
                    <strong class="text-uppercase text-dark">Updated At:</strong> 
                    <span class="text-muted">{{ $student->updated_at }}</span><br>
                </div>
            </div>
        </div>
        <div class="card-footer text-right bg-light rounded-bottom">
            <a href="{{ route('students.index') }}" class="btn btn-success btn-lg text-white">Back to List</a>
        </div>
    </div>
</div>
@endsection
