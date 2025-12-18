@extends('layouts.default')

{{-- Page title --}}
@section('title')
Users @parent
@stop

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <div aria-label="breadcrumb" class="card-breadcrumb">
        <h5><a href="{{ url('/') }}"  style="text-decoration: none; color: black;">Dashboard</a> > Users </h5>
    </div>
    <div class="separator-breadcrumb border-top"></div>
</section>


<!-- Main content -->
<div class="content">
    <div class="clearfix"></div>

    <style>
        .dataTables_filter {
    display: flex;
    gap: 11px;
}
    </style>

    @include('flash::message')

    <div class="clearfix"></div>
    <div class="card" width="88vw;">
        <section class="card-header">
            <h5 class="card-title d-inline">Users</h5>
            <span class="float-right">
                <a class="btn btn-primary pull-right" href="{{ route('users.create') }}">Add New</a>
            </span>
        </section>
        <div class="card-body table-responsive" >
            <div id="district_filter_container" style="display:none;">
                <select id="district_filter" class="form-control form-control-sm" style="display: inline-block; width: 200px;">
                    <option value="">All Districts</option>
                    @foreach($districts as $district)
                        <option value="{{ $district->name_en }}">{{ $district->name_en }}</option>
                    @endforeach
                </select>
            </div>
            @include('users.table')
        </div>
    </div>
</div>
@endsection

@section('footer_scripts')
    <script>
        $(document).ready(function() {
            var table = $('#users-table').DataTable();

            $($('#users-table_filter').parent()).css('display', 'flex');
            $($('#users-table_filter').parent()).css('justify-content', 'flex-end');
            $('#district_filter_container').css('display', 'inline-block');
            $($('#users-table_filter')).prepend($('#district_filter_container'));

            $('#district_filter').on('change', function () {
                table.column(4).search($(this).val()).draw();
            });
        });
    </script>
@stop
