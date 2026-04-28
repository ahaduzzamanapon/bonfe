@extends('layouts.default')

@section('title') Learner – {{ $student->candidate_name }} @parent @stop

@section('content')
<style>
    .profile-header { background: linear-gradient(135deg, #8dc542 0%, #5a9e20 100%); color: #fff; padding: 16px 20px; display: flex; align-items: center; gap: 16px; border-radius: 6px 6px 0 0; }
    .profile-header img { width: 80px; height: 80px; border-radius: 50%; border: 3px solid #fff; object-fit: cover; flex-shrink: 0; }
    .profile-header h5 { margin: 0 0 2px; font-size: 16px; font-weight: 700; }
    .profile-header .sub { font-size: 12px; opacity: .88; margin: 1px 0; }
    .info-table td:first-child { font-weight: 600; width: 40%; color: #444; padding: 5px 10px; }
    .info-table td:last-child { padding: 5px 10px; }
    .nav-tabs .nav-link { font-size: 12.5px; padding: 5px 14px; }
    .tab-content { padding: 12px 0 0; }
    @media print {
        .no-print { display: none !important; }
        .card { box-shadow: none !important; min-height: unset !important; }
    }
</style>

<div class="content">
    <div class="card p-0">
        {{-- Header --}}
        <div class="profile-header">
            <img src="{{ $student->image && file_exists(public_path($student->image)) ? asset($student->image) : asset('assets/images/avatars/01.png') }}"
                 alt="Photo">
            <div style="flex:1">
                <h5>{{ $student->candidate_name_bn }} &nbsp; <small style="font-size:12px;">({{ $student->candidate_name }})</small></h5>
                <p class="sub">Reg. No: <strong>{{ $student->registration_number ?: '—' }}</strong> &nbsp;|&nbsp; Candidate ID: <strong>{{ $student->candidate_id ?: '—' }}</strong></p>
                <p class="sub">Trade: <strong>{{ $student->occupation }}</strong> &nbsp;|&nbsp;
                    <span class="badge {{ $student->exam_status === 'Passed' ? 'bg-success' : ($student->exam_status === 'Fail' ? 'bg-danger' : 'bg-warning') }}">
                        {{ $student->exam_status }}
                    </span>
                </p>
            </div>
            <div class="no-print d-flex gap-2 flex-column align-items-end">
                <a href="{{ Request::is('general_students*') ? route('general_students.index') : route('students.index') }}"
                   class="btn btn-light btn-sm">← Back</a>
                <button class="btn btn-light btn-sm" onclick="window.print()">🖨 Print</button>
            </div>
        </div>

        <div class="card-body pt-2">
            {{-- Tabs --}}
            <ul class="nav nav-tabs" id="profileTabs">
                <li class="nav-item">
                    <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tab-info" type="button">Profile</button>
                </li>
                <li class="nav-item">
                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-assessment" type="button">Assessment</button>
                </li>
                <li class="nav-item">
                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-approval" type="button">Approval Status</button>
                </li>
            </ul>

            <div class="tab-content">
                {{-- Profile Tab --}}
                <div class="tab-pane fade show active" id="tab-info">
                    <table class="table table-sm info-table">
                        <tr><td>Father's Name</td><td>{{ $student->father_name ?: '—' }}</td></tr>
                        <tr><td>Mother's Name</td><td>{{ $student->mother_name ?: '—' }}</td></tr>
                        <tr><td>Date of Birth</td><td>{{ $student->date_of_birth ? \Carbon\Carbon::parse($student->date_of_birth)->format('d M Y') : '—' }}</td></tr>
                        <tr><td>NID</td><td>{{ $student->nid ?: '—' }}</td></tr>
                        <tr><td>Birth Reg. No</td><td>{{ $student->brn ?: '—' }}</td></tr>
                        <tr><td>Mobile</td><td>{{ $student->mobile_number ? '0'.$student->mobile_number : '—' }}</td></tr>
                        <tr><td>Email</td><td>{{ $student->email ?: '—' }}</td></tr>
                        <tr><td>Gender</td><td>{{ $student->gender ?: '—' }}</td></tr>
                        <tr><td>District</td><td>{{ $student->district ?: '—' }}</td></tr>
                        <tr><td>Address</td><td>{{ $student->address ?: '—' }}</td></tr>
                        <tr><td>Student Type</td><td>{{ $student->student_type ?: '—' }}</td></tr>
                        <tr><td>Program</td><td>{{ $student->program_title ?? '—' }}</td></tr>
                        @if($student->attachment)
                        <tr><td>Attachment</td><td><a href="{{ asset($student->attachment) }}" target="_blank">View File</a></td></tr>
                        @endif
                    </table>
                </div>

                {{-- Assessment Tab --}}
                <div class="tab-pane fade" id="tab-assessment">
                    <table class="table table-sm info-table">
                        <tr><td>Assessment Center</td><td>{{ $student->assessment_center ?? '—' }}</td></tr>
                        <tr><td>Center Reg. No</td><td>{{ $student->assessment_center_registration_number ?: '—' }}</td></tr>
                        <tr><td>Assessment Date</td><td>{{ $student->assessment_date ? \Carbon\Carbon::parse($student->assessment_date)->format('d M Y') : '—' }}</td></tr>
                        <tr><td>Exam Status</td><td>{{ $student->exam_status ?: '—' }}</td></tr>
                        <tr><td>Certificate No</td><td>{{ $student->certificate_number ?: '—' }}</td></tr>
                    </table>
                </div>

                {{-- Approval Tab --}}
                <div class="tab-pane fade" id="tab-approval">
                    <table class="table table-sm info-table">
                        <tr><td>Current Status</td>
                            <td><span class="badge bg-info text-dark">{{ $student->status }}</span></td></tr>
                        <tr><td>District Admin</td>
                            <td><span class="badge {{ $student->districts_admin_status === 'Pending' ? 'bg-warning text-dark' : 'bg-success' }}">{{ $student->districts_admin_status }}</span></td></tr>
                        <tr><td>Chairman</td>
                            <td><span class="badge {{ $student->chairmen_status === 'Pending' ? 'bg-warning text-dark' : 'bg-success' }}">{{ $student->chairmen_status }}</span></td></tr>
                        @if($student->controller_back_comments)
                        <tr><td>Back Comment</td><td class="text-danger">{{ $student->controller_back_comments }}</td></tr>
                        @endif
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
