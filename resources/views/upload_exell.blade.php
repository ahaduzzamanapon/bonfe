@extends('layouts.default')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card card-primary card-outline shadow-sm">
                <div class="card-header">
                    <h5 class="card-title font-weight-bold">
                        <i class="fa fa-calendar mr-2"></i> Update Assessment Date
                    </h5>
                </div>
                <div class="card-body">
                    <p class="text-muted">
                        Upload Excel (<code>.xls</code>, <code>.xlsx</code>) to update only the assessment date in database by student ID.
                    </p>
                    <a href="{{ route('students.update_assessment_date_page') }}" class="btn btn-primary">
                        <i class="fa fa-arrow-right mr-1"></i> Go to Update Assessment Date Page
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="card card-secondary card-outline shadow-sm">
                <div class="card-header">
                    <h5 class="card-title font-weight-bold">
                        <i class="fa fa-users mr-2"></i> Import Users
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('import.users') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <input type="file" name="file[]" class="form-control" multiple>
                        </div>
                        <button class="btn btn-secondary mt-2">Import Users</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
