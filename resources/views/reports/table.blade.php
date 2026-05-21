@extends('layouts.default')
@section('content')

@php
/**
 * Column definitions per report type (from official spec):
 * 01 project_wise         : Division, District, Upazila, Name, Father, Mother, Reg No, BRN/NID
 * 02 district_wise        : Name, Father, Mother, Reg No, BRN/NID
 * 03 upazila_wise         : Name, Father, Mother, Reg No, BRN/NID
 * 04 gender_wise          : Name, Father, Mother, Reg No, BRN/NID
 * 05 occupation_wise      : Division, District, Upazila, Name, Father, Mother, Reg No, BRN/NID
 * 06 student_id           : Division, District, Upazila, Name, Father, Mother, Candidate ID, BRN/NID
 * 07 certificate_distribution : Division, District, Upazila, Name, Father, Mother, Reg No, BRN/NID
 * 08 nyc_students         : Division, District, Upazila, Name, Father, Mother, Reg No, BRN/NID
 */

$colDefs = [
    'project_wise' => [
        'show_division' => false, // no division in students table yet; use district as grouper
        'show_district' => true,
        'show_upazila'  => true,
        'show_name'     => true,
        'show_father'   => true,
        'show_mother'   => true,
        'show_reg'      => true,
        'show_brn_nid'  => true,
        'show_cand_id'  => false,
        'show_program'  => true,   // extra: grouped by program
        'show_occupation'=> true,  // extra: show occupation
        'show_gender'   => false,
        'show_exam'     => false,
        'show_cert_no'  => false,
    ],
    'district_wise' => [
        'show_district' => true,
        'show_upazila'  => false,
        'show_name'     => true,
        'show_father'   => true,
        'show_mother'   => true,
        'show_reg'      => true,
        'show_brn_nid'  => true,
        'show_cand_id'  => false,
        'show_program'  => false,
        'show_occupation'=> false,
        'show_gender'   => false,
        'show_exam'     => false,
        'show_cert_no'  => false,
    ],
    'upazila_wise' => [
        'show_district' => true,
        'show_upazila'  => true,
        'show_name'     => true,
        'show_father'   => true,
        'show_mother'   => true,
        'show_reg'      => true,
        'show_brn_nid'  => true,
        'show_cand_id'  => false,
        'show_program'  => false,
        'show_occupation'=> false,
        'show_gender'   => false,
        'show_exam'     => false,
        'show_cert_no'  => false,
    ],
    'gender_wise' => [
        'show_district' => false,
        'show_upazila'  => false,
        'show_name'     => true,
        'show_father'   => true,
        'show_mother'   => true,
        'show_reg'      => true,
        'show_brn_nid'  => true,
        'show_cand_id'  => false,
        'show_program'  => false,
        'show_occupation'=> false,
        'show_gender'   => true,   // grouped by gender
        'show_exam'     => false,
        'show_cert_no'  => false,
    ],
    'occupation_wise' => [
        'show_district' => true,
        'show_upazila'  => true,
        'show_name'     => true,
        'show_father'   => true,
        'show_mother'   => true,
        'show_reg'      => true,
        'show_brn_nid'  => true,
        'show_cand_id'  => false,
        'show_program'  => false,
        'show_occupation'=> true,  // grouped by occupation
        'show_gender'   => false,
        'show_exam'     => false,
        'show_cert_no'  => false,
    ],
    'student_id' => [
        'show_district' => true,
        'show_upazila'  => true,
        'show_name'     => true,
        'show_father'   => true,
        'show_mother'   => true,
        'show_reg'      => false,
        'show_brn_nid'  => true,
        'show_cand_id'  => true,   // student ID report uses candidate_id
        'show_program'  => false,
        'show_occupation'=> false,
        'show_gender'   => false,
        'show_exam'     => false,
        'show_cert_no'  => false,
    ],
    'certificate_distribution' => [
        'show_district' => true,
        'show_upazila'  => true,
        'show_name'     => true,
        'show_father'   => true,
        'show_mother'   => true,
        'show_reg'      => true,
        'show_brn_nid'  => true,
        'show_cand_id'  => false,
        'show_program'  => false,
        'show_occupation'=> false,
        'show_gender'   => false,
        'show_exam'     => false,
        'show_cert_no'  => true,   // certificate number for distribution
    ],
    'nyc_students' => [
        'show_district' => true,
        'show_upazila'  => true,
        'show_name'     => true,
        'show_father'   => true,
        'show_mother'   => true,
        'show_reg'      => true,
        'show_brn_nid'  => true,
        'show_cand_id'  => false,
        'show_program'  => false,
        'show_occupation'=> false,
        'show_gender'   => false,
        'show_exam'     => true,   // show exam status for NYC
        'show_cert_no'  => false,
    ],
];

$c = $colDefs[$type] ?? array_fill_keys(array_keys($colDefs['project_wise']), true);
@endphp

<div class="content">
    <div class="card">
        {{-- Card Header --}}
        <section class="card-header d-flex align-items-center justify-content-between flex-wrap gap-2">
            <div>
                <h5 class="mb-0 text-white"><i class="icon im im-icon-File-ClipboardFileText me-2"></i>{{ $title }}</h5>
                <nav aria-label="breadcrumb" class="mt-1">
                    <ol class="breadcrumb mb-0" style="font-size:11px; color:rgba(255,255,255,.7);">
                        <li class="breadcrumb-item"><a href="{{ route('reports.index') }}" style="color:rgba(255,255,255,.7);">Reports</a></li>
                        <li class="breadcrumb-item active" style="color:#fff;">{{ $title }}</li>
                    </ol>
                </nav>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('reports.export_excel', array_merge(request()->except('page'), ['type'=>$type])) }}"
                   class="btn btn-sm btn-light text-success fw-bold">
                    <i class="icon im im-icon-File-Excel me-1"></i>Excel
                </a>
                <a href="{{ route('reports.export_pdf', array_merge(request()->except('page'), ['type'=>$type,'title'=>$title])) }}"
                   class="btn btn-sm btn-light text-danger fw-bold">
                    <i class="icon im im-icon-File-PDF me-1"></i>PDF
                </a>
            </div>
        </section>

        {{-- Filter Bar --}}
        <div style="background:#f8f9fa; border-bottom:1px solid #e0e0e0; padding:8px 14px;">
            <form method="GET" class="d-flex align-items-end flex-wrap gap-2">
                <input type="hidden" name="type" value="{{ $type }}">

                <div>
                    <label class="d-block" style="font-size:10.5px; font-weight:600; color:#555; margin-bottom:2px;">District</label>
                    <select name="district_id" class="form-control form-control-sm" style="width:140px;">
                        @foreach($filters['districts'] as $k=>$v)
                            <option value="{{ $k }}" @selected(request('district_id')==$k)>{{ $v }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="d-block" style="font-size:10.5px; font-weight:600; color:#555; margin-bottom:2px;">Upazila</label>
                    <select name="upazila_id" class="form-control form-control-sm" style="width:130px;">
                        <option value="">All Upazilas</option>
                        @foreach($filters['upazilas'] as $k=>$v)
                            <option value="{{ $k }}" @selected(request('upazila_id')==$k)>{{ $v }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="d-block" style="font-size:10.5px; font-weight:600; color:#555; margin-bottom:2px;">Program</label>
                    <select name="program_id" class="form-control form-control-sm" style="width:130px;">
                        @foreach($filters['programs'] as $k=>$v)
                            <option value="{{ $k }}" @selected(request('program_id')==$k)>{{ $v }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="d-block" style="font-size:10.5px; font-weight:600; color:#555; margin-bottom:2px;">Occupation</label>
                    <select name="occupation_id" class="form-control form-control-sm" style="width:130px;">
                        @foreach($filters['occupations'] as $k=>$v)
                            <option value="{{ $k }}" @selected(request('occupation_id')==$k)>{{ $v }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="d-block" style="font-size:10.5px; font-weight:600; color:#555; margin-bottom:2px;">Gender</label>
                    <select name="gender" class="form-control form-control-sm" style="width:90px;">
                        <option value="">All</option>
                        <option value="Male" @selected(request('gender')=='Male')>Male</option>
                        <option value="Female" @selected(request('gender')=='Female')>Female</option>
                    </select>
                </div>
                <div>
                    <label class="d-block" style="font-size:10.5px; font-weight:600; color:#555; margin-bottom:2px;">Search</label>
                    <input type="text" name="search" class="form-control form-control-sm" placeholder="Name / ID / Reg." value="{{ request('search') }}" style="width:160px;">
                </div>
                <div>
                    <label class="d-block" style="font-size:10.5px; font-weight:600; color:#555; margin-bottom:2px;">From</label>
                    <input type="date" name="date_from" class="form-control form-control-sm" value="{{ request('date_from') }}" style="width:120px;">
                </div>
                <div>
                    <label class="d-block" style="font-size:10.5px; font-weight:600; color:#555; margin-bottom:2px;">To</label>
                    <input type="date" name="date_to" class="form-control form-control-sm" value="{{ request('date_to') }}" style="width:120px;">
                </div>
                <div class="d-flex gap-1" style="padding-top:16px;">
                    <button class="btn btn-primary btn-sm">Filter</button>
                    <a href="{{ url()->current() }}" class="btn btn-outline-secondary btn-sm">Reset</a>
                </div>
            </form>
        </div>

        {{-- Record count --}}
        <div class="px-3 py-2 d-flex justify-content-between align-items-center" style="background:#fff; border-bottom:1px solid #eee;">
            <span class="text-muted" style="font-size:12px;">
                Showing <strong>{{ $students->firstItem() }}–{{ $students->lastItem() }}</strong>
                of <strong>{{ $students->total() }}</strong> records
            </span>
            {{ $students->links() }}
        </div>

        {{-- Table --}}
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-sm mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            @if(!empty($c['show_district'])) <th>District</th> @endif
                            @if(!empty($c['show_upazila']))  <th>Upazila</th> @endif
                            @if(!empty($c['show_program']))  <th>Program</th> @endif
                            @if(!empty($c['show_occupation']))<th>Occupation</th>@endif
                            @if(!empty($c['show_gender']))   <th>Gender</th> @endif
                            @if(!empty($c['show_name']))
                                <th>Name (English)</th>
                                <th>নাম (বাংলা)</th>
                            @endif
                            @if(!empty($c['show_father']))   <th>Father's Name</th> @endif
                            @if(!empty($c['show_mother']))   <th>Mother's Name</th> @endif
                            @if(!empty($c['show_cand_id']))  <th>Candidate ID</th> @endif
                            @if(!empty($c['show_reg']))      <th>Reg. No.</th> @endif
                            @if(!empty($c['show_brn_nid']))  <th>BRN / NID</th> @endif
                            @if(!empty($c['show_exam']))     <th>Exam Status</th> @endif
                            @if(!empty($c['show_cert_no']))  <th>Certificate No.</th> @endif
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($students as $i=>$s)
                        <tr>
                            <td>{{ $students->firstItem() + $i }}</td>
                            @if(!empty($c['show_district']))  <td>{{ $s->district_name }}</td> @endif
                            @if(!empty($c['show_upazila']))   <td>{{ $s->upazila_name }}</td> @endif
                            @if(!empty($c['show_program']))   <td>{{ $s->program_title }}</td> @endif
                            @if(!empty($c['show_occupation'])) <td>{{ $s->occupation_title }}</td> @endif
                            @if(!empty($c['show_gender']))    <td>{{ $s->gender }}</td> @endif
                            @if(!empty($c['show_name']))
                                <td>{{ $s->candidate_name }}</td>
                                <td style="font-family:'Noto Serif Bengali',serif;">{{ $s->candidate_name_bn }}</td>
                            @endif
                            @if(!empty($c['show_father']))
                                <td>{{ $s->father_name }}<br>
                                    <small class="text-muted" style="font-family:'Noto Serif Bengali',serif;">{{ $s->father_name_bn }}</small></td>
                            @endif
                            @if(!empty($c['show_mother']))
                                <td>{{ $s->mother_name }}<br>
                                    <small class="text-muted" style="font-family:'Noto Serif Bengali',serif;">{{ $s->mother_name_bn }}</small></td>
                            @endif
                            @if(!empty($c['show_cand_id']))  <td>{{ $s->candidate_id }}</td> @endif
                            @if(!empty($c['show_reg']))      <td>{{ $s->registration_number }}</td> @endif
                            @if(!empty($c['show_brn_nid']))  <td>{{ $s->nid ?? $s->brn }}</td> @endif
                            @if(!empty($c['show_exam']))
                                <td>
                                    @php $es = $s->exam_status ?? '—'; @endphp
                                    <span class="badge bg-{{ $es=='Passed'?'success':($es=='Fail'?'danger':($es=='Absent'?'warning':'secondary')) }}">{{ $es }}</span>
                                </td>
                            @endif
                            @if(!empty($c['show_cert_no']))  <td>{{ $s->certificate_number }}</td> @endif
                        </tr>
                        @empty
                        <tr><td colspan="20" class="text-center text-muted py-4">No records found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="px-3 py-2 d-flex justify-content-end" style="border-top:1px solid #eee;">
            {{ $students->links() }}
        </div>
    </div>
</div>
@endsection
