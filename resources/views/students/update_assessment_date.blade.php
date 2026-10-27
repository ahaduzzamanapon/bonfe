@extends('layouts.default')

@section('title')
    Update Assessment Date @parent
@stop

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Update Assessment Date</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('students.index') }}">Learner</a></li>
                        <li class="breadcrumb-item active">Update Assessment Date</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <div class="content px-3">
        @include('flash::message')
        @include('adminlte-templates::common.errors')

        {{-- Results Summary Card (shown after upload) --}}
        @if (session('update_results'))
            @php $res = session('update_results'); @endphp
            <div class="card card-outline card-success shadow-sm mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="card-title mb-0">
                        <i class="fa fa-check-circle mr-2"></i> Update Summary
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row text-center mb-3">
                        <div class="col-md-3 col-6 mb-2">
                            <div class="p-3 bg-light rounded border">
                                <h3 class="font-weight-bold text-primary mb-0">{{ number_format($res['total_rows']) }}</h3>
                                <small class="text-muted">Total Rows in Excel</small>
                            </div>
                        </div>
                        <div class="col-md-3 col-6 mb-2">
                            <div class="p-3 bg-light rounded border">
                                <h3 class="font-weight-bold text-success mb-0">{{ number_format($res['updated_count']) }}</h3>
                                <small class="text-muted">Successfully Updated</small>
                            </div>
                        </div>
                        <div class="col-md-3 col-6 mb-2">
                            <div class="p-3 bg-light rounded border">
                                <h3 class="font-weight-bold {{ $res['not_found_count'] > 0 ? 'text-warning' : 'text-muted' }} mb-0">
                                    {{ number_format($res['not_found_count']) }}
                                </h3>
                                <small class="text-muted">Students Not Found</small>
                            </div>
                        </div>
                        <div class="col-md-3 col-6 mb-2">
                            <div class="p-3 bg-light rounded border">
                                <h3 class="font-weight-bold {{ $res['invalid_date_count'] > 0 ? 'text-danger' : 'text-muted' }} mb-0">
                                    {{ number_format($res['invalid_date_count']) }}
                                </h3>
                                <small class="text-muted">Invalid Dates</small>
                            </div>
                        </div>
                    </div>

                    @if (!empty($res['sample_updated']))
                        <div class="mt-3">
                            <h6 class="font-weight-bold"><i class="fa fa-list mr-1"></i> Sample of Updated Records:</h6>
                            <div class="table-responsive">
                                <table class="table table-sm table-bordered table-striped">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>Student ID</th>
                                            <th>Student Name</th>
                                            <th>Updated Assessment Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($res['sample_updated'] as $sample)
                                            <tr>
                                                <td><span class="badge badge-info">{{ $sample['id'] }}</span></td>
                                                <td>{{ $sample['name'] ?: '-' }}</td>
                                                <td><strong class="text-success">{{ $sample['assessment_date'] }}</strong></td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif

                    @if (!empty($res['not_found_ids']))
                        <div class="alert alert-warning mt-3">
                            <strong><i class="fa fa-exclamation-triangle"></i> Student IDs not found in database (first {{ count($res['not_found_ids']) }}):</strong>
                            <div class="mt-1" style="max-height: 120px; overflow-y: auto;">
                                <code>{{ implode(', ', $res['not_found_ids']) }}</code>
                            </div>
                        </div>
                    @endif

                    @if (!empty($res['invalid_date_rows']))
                        <div class="alert alert-danger mt-3">
                            <strong><i class="fa fa-times-circle"></i> Invalid date entries:</strong>
                            <ul class="mb-0 mt-1 pl-3">
                                @foreach ($res['invalid_date_rows'] as $invRow)
                                    <li>{{ $invRow }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
            </div>
        @endif

        {{-- Upload Card --}}
        <div class="card card-primary card-outline shadow-sm">
            <div class="card-header">
                <h5 class="card-title font-weight-bold">
                    <i class="fa fa-upload mr-2 text-primary"></i> Upload Excel to Update Assessment Date
                </h5>
            </div>

            <form action="{{ route('students.update_assessment_date_submit') }}" method="POST" enctype="multipart/form-data" id="uploadAssessmentDateForm">
                @csrf
                <div class="card-body">
                    <div class="callout callout-info mb-4" style="border-left: 4px solid #17a2b8; background-color: #f8fbfd; padding: 15px; border-radius: 4px;">
                        <h6 class="font-weight-bold text-info"><i class="fa fa-info-circle mr-1"></i> How this works:</h6>
                        <ul class="mb-0 pl-3 text-secondary">
                            <li>Upload your Excel (<code>.xls</code>, <code>.xlsx</code>) or <code>.csv</code> file (e.g. <strong>ASSESSMENT Date1.xls</strong>).</li>
                            <li>The file must have a <strong>student_id</strong> (or <strong>id</strong>) column and an <strong>assessment_date</strong> column.</li>
                            <li><strong>Only the Assessment Date</strong> will be updated in the database for each matching student ID.</li>
                            <li>All other student data (name, candidate ID, reg number, etc.) will remain completely unchanged.</li>
                        </ul>
                    </div>

                    <div class="row">
                        <div class="col-md-7">
                            <div class="form-group">
                                <label for="file" class="font-weight-bold">Select Excel / CSV File: <span class="text-danger">*</span></label>
                                <div class="custom-file">
                                    <input type="file" name="file" id="file" class="custom-file-input form-control" accept=".xls,.xlsx,.csv" required onchange="updateFileName(this)">
                                    <label class="custom-file-label" id="fileLabel" for="file">Choose file (.xls, .xlsx, .csv)...</label>
                                </div>
                                <small class="form-text text-muted mt-2">
                                    <i class="fa fa-file-excel-o text-success mr-1"></i> Supported formats: .xls, .xlsx, .csv | Maximum file size: 50 MB
                                </small>
                            </div>
                        </div>

                        <div class="col-md-5">
                            <div class="p-3 bg-light rounded border">
                                <h6 class="font-weight-bold text-muted mb-2"><i class="fa fa-table mr-1"></i> Expected Columns in Excel:</h6>
                                <table class="table table-sm table-bordered bg-white mb-2">
                                    <thead>
                                        <tr class="table-primary text-center">
                                            <th>student_id</th>
                                            <th>assessment_date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="text-center">
                                            <td>8848</td>
                                            <td>2026-04-24 <em>or 24-Apr-26</em></td>
                                        </tr>
                                        <tr class="text-center">
                                            <td>8856</td>
                                            <td>2026-04-24 <em>or 24-Apr-26</em></td>
                                        </tr>
                                    </tbody>
                                </table>
                                <a href="{{ route('students.download_assessment_date_sample') }}" class="btn btn-outline-secondary btn-sm btn-block">
                                    <i class="fa fa-download mr-1"></i> Download Sample CSV Template
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-footer bg-light d-flex justify-content-between align-items-center">
                    <div>
                        <button type="submit" class="btn btn-primary px-4" id="submitBtn">
                            <i class="fa fa-check mr-1" id="btnIcon"></i> Update Assessment Date
                        </button>
                        <a href="{{ route('students.index') }}" class="btn btn-default ml-2">Cancel</a>
                    </div>
                    <span class="text-muted small">
                        <i class="fa fa-shield text-success mr-1"></i> Safe update: Matches by student ID and updates only assessment date
                    </span>
                </div>
            </form>
        </div>
    </div>

    <script>
        function updateFileName(input) {
            var fileName = input.files && input.files[0] ? input.files[0].name : 'Choose file (.xls, .xlsx, .csv)...';
            var label = document.getElementById('fileLabel');
            if (label) {
                label.innerText = fileName;
            }
        }

        document.getElementById('uploadAssessmentDateForm').addEventListener('submit', function() {
            var btn = document.getElementById('submitBtn');
            var icon = document.getElementById('btnIcon');
            if (btn) {
                btn.disabled = true;
                btn.innerHTML = '<span class="spinner-border spinner-border-sm mr-2" role="status" aria-hidden="true"></span> Updating Database... Please wait';
            }
        });
    </script>
@endsection
