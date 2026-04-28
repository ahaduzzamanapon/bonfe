@extends('layouts.default')

@section('title') View Institute @parent @stop

@section('content')
<div class="content">
    @include('flash::message')
    <div class="card">
        <section class="card-header d-flex align-items-center justify-content-between">
            <h5 class="mb-0">Institute Details</h5>
            <div>
                <a href="{{ route('insatitutes.edit', $insatitute->id) }}" class="btn btn-primary btn-sm">Edit</a>
                <a href="{{ route('insatitutes.index') }}" class="btn btn-danger btn-sm">← Back</a>
            </div>
        </section>
        <div class="card-body">
            <table class="table table-bordered table-sm" style="max-width:600px;">
                @include('insatitutes.show_fields')
            </table>
        </div>
    </div>
</div>
@endsection
