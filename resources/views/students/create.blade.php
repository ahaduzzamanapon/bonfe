@extends('layouts.default')

{{-- Page title --}}
@section('title')
Student @parent
@stop

@section('content')
    <section class="content-header">
    {{--<div aria-label="breadcrumb" class="card-breadcrumb">
        <h1>{{ __('Create New') }} Student</h1>
    </div>
    <div class="separator-breadcrumb border-top"></div>--}}
    </section>
    <div class="content">
        @include('adminlte-templates::common.errors')
        <div class="card">
            <div class="card-body">
                <div class="row">
                    {!! Form::open(['route' => 'students.store', 'files' => true, 'class' => 'form-horizontal col-md-12']) !!}
                    @if( Request::is('general_students*'))
                    <input type="hidden" name="student_type" value="general">
                    @else
                    <input type="hidden" name="student_type" value="technical">
                    @endif

                    <div class="row">
                        @include('students.fields')
                    </div>

                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
