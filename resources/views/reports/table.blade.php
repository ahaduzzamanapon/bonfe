@extends('layouts.default')
@section('content')
<div class="container-fluid">
    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h5 class="fw-bold mb-0"><i class="icon im im-icon-File-ClipboardFileText me-2"></i>{{ $title }}</h5>
            <nav aria-label="breadcrumb"><ol class="breadcrumb mb-0 small"><li class="breadcrumb-item"><a href="{{ route('reports.index') }}">Reports</a></li><li class="breadcrumb-item active">{{ $title }}</li></ol></nav>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('reports.export_excel', array_merge(request()->except('page'), ['type'=>$type])) }}" class="btn btn-success btn-sm"><i class="icon im im-icon-File-Excel me-1"></i>Export Excel</a>
            <a href="{{ route('reports.export_pdf', array_merge(request()->except('page'), ['type'=>$type,'title'=>$title])) }}" class="btn btn-danger btn-sm"><i class="icon im im-icon-File-PDF me-1"></i>Export PDF</a>
        </div>
    </div>

    {{-- Filters --}}
    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body py-2">
            <form method="GET" class="row g-2 align-items-end">
                <input type="hidden" name="type" value="{{ $type }}">
                <div class="col-md-2">
                    <label class="form-label small mb-1">District</label>
                    <select name="district_id" class="form-control form-control-sm">
                        @foreach($filters['districts'] as $k=>$v)<option value="{{ $k }}" @selected(request('district_id')==$k)>{{ $v }}</option>@endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small mb-1">Program</label>
                    <select name="program_id" class="form-control form-control-sm">
                        @foreach($filters['programs'] as $k=>$v)<option value="{{ $k }}" @selected(request('program_id')==$k)>{{ $v }}</option>@endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small mb-1">Occupation</label>
                    <select name="occupation_id" class="form-control form-control-sm">
                        @foreach($filters['occupations'] as $k=>$v)<option value="{{ $k }}" @selected(request('occupation_id')==$k)>{{ $v }}</option>@endforeach
                    </select>
                </div>
                <div class="col-md-1">
                    <label class="form-label small mb-1">Gender</label>
                    <select name="gender" class="form-control form-control-sm">
                        <option value="">All</option>
                        <option value="Male" @selected(request('gender')=='Male')>Male</option>
                        <option value="Female" @selected(request('gender')=='Female')>Female</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small mb-1">Search</label>
                    <input type="text" name="search" class="form-control form-control-sm" placeholder="Name / Reg. No." value="{{ request('search') }}">
                </div>
                <div class="col-md-1">
                    <label class="form-label small mb-1">From</label>
                    <input type="date" name="date_from" class="form-control form-control-sm" value="{{ request('date_from') }}">
                </div>
                <div class="col-md-1">
                    <label class="form-label small mb-1">To</label>
                    <input type="date" name="date_to" class="form-control form-control-sm" value="{{ request('date_to') }}">
                </div>
                <div class="col-md-1">
                    <button class="btn btn-primary btn-sm w-100">Filter</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Summary --}}
    <div class="d-flex justify-content-between align-items-center mb-2">
        <span class="text-muted small">Showing <strong>{{ $students->firstItem() }}–{{ $students->lastItem() }}</strong> of <strong>{{ $students->total() }}</strong> records</span>
        {{ $students->links() }}
    </div>

    {{-- Table --}}
    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover table-sm mb-0" style="font-size:12px;">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Candidate ID</th>
                        <th>Name (English)</th>
                        <th>Name (বাংলা)</th>
                        <th>Father's Name</th>
                        <th>Mother's Name</th>
                        <th>Registration No.</th>
                        <th>NID / BRN</th>
                        <th>Gender</th>
                        <th>District</th>
                        <th>Upazila</th>
                        <th>Occupation</th>
                        <th>Program</th>
                        <th>Exam Status</th>
                        <th>Certificate No.</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($students as $i=>$s)
                    <tr>
                        <td>{{ $students->firstItem() + $i }}</td>
                        <td>{{ $s->candidate_id }}</td>
                        <td>{{ $s->candidate_name }}</td>
                        <td style="font-family:'Noto Serif Bengali',serif;">{{ $s->candidate_name_bn }}</td>
                        <td>{{ $s->father_name }}<br><small class="text-muted" style="font-family:'Noto Serif Bengali',serif;">{{ $s->father_name_bn }}</small></td>
                        <td>{{ $s->mother_name }}<br><small class="text-muted" style="font-family:'Noto Serif Bengali',serif;">{{ $s->mother_name_bn }}</small></td>
                        <td>{{ $s->registration_number }}</td>
                        <td>{{ $s->nid ?? $s->brn }}</td>
                        <td>{{ $s->gender }}</td>
                        <td>{{ $s->district_name }}</td>
                        <td>{{ $s->upazila_name }}</td>
                        <td>{{ $s->occupation_title }}</td>
                        <td>{{ $s->program_title }}</td>
                        <td>
                            @php $es = $s->exam_status ?? '—'; @endphp
                            <span class="badge bg-{{ $es=='Passed'?'success':($es=='Fail'?'danger':($es=='Absent'?'warning':'secondary')) }}">{{ $es }}</span>
                        </td>
                        <td>{{ $s->certificate_number }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="15" class="text-center text-muted py-3">No records found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-2 d-flex justify-content-end">{{ $students->links() }}</div>
</div>
@endsection
