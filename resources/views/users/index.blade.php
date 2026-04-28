@extends('layouts.default')
@section('title') Users @parent @stop

@section('content')
<style>.dataTables_filter { display:flex; gap:11px; }</style>
<div class="content">
    @include('flash::message')
    <div class="card">
        <section class="card-header d-flex align-items-center justify-content-between">
            <h5 class="mb-0">Users</h5>
            <a class="btn btn-primary btn-sm" href="{{ route('users.create') }}">+ Add New</a>
        </section>
        <div class="card-body table-responsive">
            <div id="district_filter_container" style="display:none;">
                <select id="district_filter" class="form-control form-control-sm" style="width:200px;display:inline-block;">
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
        $($('#users-table_filter').parent()).css({ display:'flex', justifyContent:'flex-end' });
        $('#district_filter_container').css('display', 'inline-block');
        $($('#users-table_filter')).prepend($('#district_filter_container'));
        $('#district_filter').on('change', function() {
            table.column(4).search($(this).val()).draw();
        });
    });
</script>
@stop
