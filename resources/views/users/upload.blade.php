@extends('layouts.default')

{{-- Page title --}}
@section('title')
Upload Users @parent
@stop

@section('content')
<section class="content-header">
    <div aria-label="breadcrumb" class="card-breadcrumb">
        <h5><a href="{{ url('/') }}" style="text-decoration: none; color: black;">Dashboard</a> > <a href="{{ route('users.index') }}" style="text-decoration: none; color: black;">Users</a> > Upload</h5>
    </div>
    <div class="separator-breadcrumb border-top"></div>
</section>

<div class="content">
    <div class="clearfix"></div>
    @include('flash::message')
    <div class="card">
        <section class="card-header">
            <h5 class="card-title">Upload Users Excel</h5>
        </section>
        <div class="card-body">
            <form action="{{ route('users.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="file">Choose Excel File</label>
                    <input type="file" name="file" class="form-control" required accept=".xlsx, .xls, .csv">
                    <small class="text-muted">Expected columns: name, last_name, designation, district, e_mail, phone_number, religion, image, signature</small>
                </div>
                <button type="submit" class="btn btn-primary mt-2">Import Users</button>
                <a href="{{ route('users.index') }}" class="btn btn-default mt-2">Cancel</a>
            </form>
        </div>
    </div>
</div>
@endsection
