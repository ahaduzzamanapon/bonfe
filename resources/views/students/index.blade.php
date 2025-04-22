@extends('layouts.default')

{{-- Page title --}}
@section('title')
Students @parent
@stop

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    {{--<div aria-label="breadcrumb" class="card-breadcrumb">
        <h1>Students</h1>
    </div>
    <div class="separator-breadcrumb border-top"></div>--}}
</section>

<!-- Main content -->
<div class="content">
    <div class="clearfix"></div>
    @include('flash::message')
    <div class="clearfix"></div>
    <div class="card" width="88vw;">
        <section class="card-header">
            <h5 class="card-title d-inline">Students</h5>
            <span class="float-right">
                <a class="btn btn-primary pull-right" href="{{ route('students.create') }}">Add New</a>
            </span>
        </section>
        <div class="card-body table-responsive" >
            <div class="form-group">

                <a class="btn  selection:pull-right {{ Request::is('students') ? 'active btn-primary' : '' }}" href="{{ route('students.index') }}">All Students</a>
                @if(can('district_admin'))
                <a class="btn  pull-right {{ Request::is('students_waiting_for_district_approval') ? 'active btn-primary' : '' }}" href="{{ route('students.students_waiting_for_district_approval') }}">District Approval</a>
                @endif
                @if (can('chairman'))
                <a class="btn  pull-right {{ Request::is('students_waiting_for_chairman_approval') ? 'active btn-primary' : '' }}" href="{{ route('students.students_waiting_for_chairman_approval') }}">Chairman Approval</a>
                @endif
            </div>
            @include('students.table')
        </div>
    </div>
    <div class="text-center">
        
        @include('adminlte-templates::common.paginate', ['records' => $students])

    </div>
</div>
@endsection
