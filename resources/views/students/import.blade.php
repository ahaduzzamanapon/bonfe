@extends('layouts.default')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1>Import Students</h1>
                </div>
            </div>
        </div>
    </section>

    <div class="content px-3">

        @include('adminlte-templates::common.errors')

        <div class="card">

            {!! Form::open(['route' => 'students.import_preview', 'files' => true]) !!}

            <div class="card-body">
                <div class="row">
                    <!-- File Field -->
                    <div class="col-sm-6">
                        <div class="form-group">
                            {!! Form::label('file', 'Select Excel File:', ['class' => 'control-label']) !!}
                            {!! Form::file('file', ['class' => 'form-control', 'accept' => '.xlsx, .xls', 'required' => true]) !!}
                            <small class="text-muted">Expected columns: Name, Name(BN), Father Name, Mother Name, NID, Mobile, DOB (YYYY-MM-DD), Address, Institute(Exact Name), Trade(Exact Code), Program(ID), District(Exact Name)</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-footer">
                {!! Form::submit('Preview Import', ['class' => 'btn btn-primary']) !!}
                <a href="{{ route('students.import_sample') }}" class="btn btn-info">Download Sample</a>
                <a href="{{ route('students.index') }}" class="btn btn-default">Cancel</a>
            </div>

            {!! Form::close() !!}

        </div>
    </div>
@endsection
