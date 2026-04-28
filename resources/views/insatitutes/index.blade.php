@extends('layouts.default')

@section('title') Institutes @parent @stop

@section('content')
<div class="content">
    @include('flash::message')
    <div class="card">
        <section class="card-header d-flex align-items-center justify-content-between">
            <h5 class="card-title mb-0">Institutes</h5>
            <a class="btn btn-primary" href="{{ route('insatitutes.create') }}">+ Add New</a>
        </section>
        <div class="card-body table-responsive">
            @include('insatitutes.table')
        </div>
        <div class="card-footer">
            @include('adminlte-templates::common.paginate', ['records' => $insatitutes])
        </div>
    </div>
</div>
@endsection
