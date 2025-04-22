@extends('layouts.default')

@section('title')
    Students @parent
@stop

@section('content')
<style>
    body {
        background-color: #f4f6f8;
        font-family: 'Inter', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .card {
        border: none;
        border-radius: 1.5rem;
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.05);
        overflow: hidden;
    }

    .card-header {
        background: #8dc641;
        color: #fff;
        font-weight: 600;
        font-size: 1.75rem;
        text-align: center;
    }

    .card-body {
        background-color: #ffffff;
        padding: 2rem;
    }

    .card-body strong {
        display: block;
        font-size: 0.9rem;
        color: #37474f;
        margin-top: 1rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .card-body span {
        font-size: 1.05rem;
        color: #555;
        display: block;
        margin-top: 0.2rem;
    }

    .card-footer {
        background-color: #f0f4f8;
        padding: 1.5rem 2rem;
        text-align: right;
    }

    .btn-success {
        background-color: #66bb6a;
        border: none;
        font-size: 1rem;
        padding: 0.75rem 1.5rem;
        border-radius: 0.5rem;
        transition: all 0.3s ease;
    }

    .btn-success:hover {
        background-color: #57a05a;
    }

    @media (max-width: 768px) {
        .card-body .row {
            flex-direction: column;
        }

        .card-body .col-md-6 {
            margin-bottom: 2rem;
        }
    }
</style>

<div class="container my-5">
    <div class="card">
        <div class="card-header">
            Student Details
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <strong>Occupation</strong>
                    <span>{{ $student->occupation }}</span>

                    <strong>Registration Number</strong>
                    <span>{{ $student->registration_number }}</span>

                    <strong>Candidate Id</strong>
                    <span>{{ $student->candidate_id }}</span>

                    <strong>Candidate Name</strong>
                    <span>{{ $student->candidate_name }}</span>

                    <strong>Father Name</strong>
                    <span>{{ $student->father_name }}</span>

                    <strong>Mother Name</strong>
                    <span>{{ $student->mother_name }}</span>

                    <strong>NID</strong>
                    <span>{{ $student->nid }}</span>

                   
                </div>
                <div class="col-md-6">
                    <strong>District</strong>
                    <span>{{ $student->district }}</span>

                   

                    <strong>Address</strong>
                    <span>{{ $student->address }}</span> 

                    <strong>Date of Birth</strong>
                    <span>{{ $student->date_of_birth }}</span>

                    <strong>Mobile Number</strong>
                    <span>{{ $student->mobile_number }}</span>

                    <strong>Email</strong>
                    <span>{{ $student->email }}</span>

                    <strong>Assessment Date</strong>
                    <span>{{ $student->assessment_date }}</span>

                    {{-- <strong>Assessment Venue</strong>
                    <span>{{ $student->assessment_venue }}</span>

                    <strong>Assessment Center</strong>
                    <span>{{ $student->assessment_center }}</span> --}}

                    <strong>Center Reg. No.</strong>
                    <span>{{ $student->assessment_center_registration_number }}</span>

                </div>
            </div>
        </div>
        <div class="card-footer">
            <a href="{{ route('students.index') }}" class="btn btn-success">
                ← Back to List
            </a>
        </div>
    </div>
</div>
@endsection
