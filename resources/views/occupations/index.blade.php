@extends('layouts.default')
@section('title') Trade(Course) @parent @stop

@section('content')
<div class="content">
    @include('flash::message')
    <div class="card">
        <section class="card-header d-flex align-items-center justify-content-between">
            <h5 class="mb-0">Trade / Course</h5>
            <a class="btn btn-primary btn-sm" href="{{ route('occupations.create') }}">+ Add New</a>
        </section>
        <div class="card-body table-responsive">
            @include('occupations.table')
        </div>
        <div class="card-footer">
            @include('adminlte-templates::common.paginate', ['records' => $occupations])
        </div>
    </div>
</div>
@endsection
