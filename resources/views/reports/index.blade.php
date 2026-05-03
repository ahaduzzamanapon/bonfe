@extends('layouts.default')
@section('content')
<div class="content">
    <div class="card">
        <section class="card-header d-flex align-items-center justify-content-between">
            <h5 class="mb-0 text-white"><i class="icon im im-icon-File-ClipboardFileText me-2"></i> Reports & Analytics</h5>
        </section>
        <div class="card-body bg-light p-4">
            <div class="row g-3">
                @php
                $reportCards = [
                    ['title'=>'Project-wise Report','icon'=>'im-icon-Book','color'=>'primary','route'=>'reports.project_wise','desc'=>'Filter by program and occupation'],
                    ['title'=>'District-wise Report','icon'=>'im-icon-Structure','color'=>'success','route'=>'reports.district_wise','desc'=>'Grouped by district'],
                    ['title'=>'Upazila-wise Report','icon'=>'im-icon-Map2','color'=>'info','route'=>'reports.upazila_wise','desc'=>'Grouped by upazila'],
                    ['title'=>'Gender-wise Report','icon'=>'im-icon-User','color'=>'warning','route'=>'reports.gender_wise','desc'=>'Grouped by gender'],
                    ['title'=>'Occupation-wise Report','icon'=>'im-icon-Diploma-2','color'=>'danger','route'=>'reports.occupation_wise','desc'=>'Grouped by trade/course'],
                    ['title'=>'Student ID Report','icon'=>'im-icon-ID-Card','color'=>'secondary','route'=>'reports.student_id','desc'=>'Search by candidate ID or registration number'],
                    ['title'=>'Certificate Distribution','icon'=>'im-icon-Diploma-1','color'=>'dark','route'=>'reports.certificate_distribution','desc'=>'Approved students with certificates'],
                    ['title'=>'Failed / NYC Students','icon'=>'im-icon-Student-Hat','color'=>'danger','route'=>'reports.nyc_students','desc'=>'Not Yet Competent learners'],
                ];
                @endphp
                @foreach($reportCards as $card)
                <div class="col-md-3">
                    <a href="{{ route($card['route']) }}" class="text-decoration-none">
                        <div class="card h-100 border-0 shadow-sm hover-card" style="border-radius:8px;">
                            <div class="card-body text-center p-4">
                                <div class="mb-3">
                                    <span class="bg-{{ $card['color'] }} bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center" style="width:56px;height:56px;">
                                        <i class="icon im {{ $card['icon'] }} text-{{ $card['color'] }} fs-4"></i>
                                    </span>
                                </div>
                                <h6 class="fw-semibold mb-1" style="color:#222;">{{ $card['title'] }}</h6>
                                <p class="text-muted small mb-0" style="font-size:11.5px;">{{ $card['desc'] }}</p>
                            </div>
                        </div>
                    </a>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
<style>.hover-card:hover{transform:translateY(-4px);transition:.25s ease; box-shadow: 0 10px 20px rgba(0,0,0,.08) !important;}</style>
@endsection
