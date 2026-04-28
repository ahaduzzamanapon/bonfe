@extends('layouts.default')

@section('title') Edit Learner @parent @stop

@section('content')
<div class="content">
    @include('adminlte-templates::common.errors')
    <div class="card">
        <section class="card-header d-flex align-items-center justify-content-between">
            <h5 class="mb-0">Edit Learner — {{ $student->candidate_name_bn }}</h5>
            <a href="{{ Request::is('general_students*') ? route('general_students.index') : route('students.index') }}"
               class="btn btn-danger btn-sm">← Back</a>
        </section>
        <div class="card-body">
            {!! Form::model($student, [
                'route'  => ['students.update', $student->id],
                'files'  => true,
                'method' => 'patch',
                'class'  => 'form-horizontal',
            ]) !!}
            <div class="row">
                @include('students.fields')
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
@endsection
