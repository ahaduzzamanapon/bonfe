@extends('layouts.default')

@section('title') Add Learner @parent @stop

@section('content')
<div class="content">
    @include('adminlte-templates::common.errors')
    <div class="card">
        <section class="card-header d-flex align-items-center justify-content-between">
            <h5 class="mb-0">
                @if(Request::is('general_students*'))
                    Add New NFPE Learner
                @else
                    Add New Pre-Vocational Learner
                @endif
            </h5>
            <a href="{{ Request::is('general_students*') ? route('general_students.index') : route('students.index') }}"
               class="btn btn-danger btn-sm">← Back</a>
        </section>
        <div class="card-body">
            {!! Form::open(['route' => 'students.store', 'files' => true, 'class' => 'form-horizontal']) !!}

            @if(Request::is('general_students*'))
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
@endsection
