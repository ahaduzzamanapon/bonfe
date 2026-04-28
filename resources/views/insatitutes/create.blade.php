@extends('layouts.default')

@section('title') Add Institute @parent @stop

@section('content')
<div class="content">
    @include('adminlte-templates::common.errors')
    <div class="card">
        <section class="card-header d-flex align-items-center justify-content-between">
            <h5 class="mb-0">Add New Institute</h5>
            <a href="{{ route('insatitutes.index') }}" class="btn btn-danger btn-sm">← Back</a>
        </section>
        <div class="card-body">
            {!! Form::open(['route' => 'insatitutes.store', 'class' => 'form-horizontal']) !!}
            <div class="row">
                @include('insatitutes.fields')
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
@endsection
