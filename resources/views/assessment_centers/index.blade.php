@extends('layouts.default')

{{-- Page title --}}
@section('title')
Assessment Centers @parent
@stop

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div aria-label="breadcrumb" class="card-breadcrumb">
            <h5><a href="{{ url('/') }}" style="text-decoration: none; color: black;">Dashboard</a> > Assessment Centers
            </h5>
        </div>
        <div class="separator-breadcrumb border-top"></div>
    </section>



    <!-- Main content -->
    <div class="content">
        <div class="clearfix"></div>

        @include('flash::message')

        <div class="clearfix"></div>
        <div class="card" width="88vw;">
            <section class="card-header">
                <h5 class="card-title d-inline">Assessment Centers</h5>
                <span class="float-right">
                    <a class="btn btn-primary pull-right" href="{{ route('assessmentCenters.create') }}">Add New</a>
                </span>
            </section>
          
            <div class="card-body table-responsive">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="district_id">Filter by District:</label>
                        <select name="district_id" id="district_id" class="form-control select2">
                            <option value="">All</option>
                            @foreach ($districts as $district)
                                <option value="{{$district->id}}">{{$district->name_en}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                @include('assessment_centers.table')
                <div class="text-center">
                    @include('adminlte-templates::common.paginate', ['records' => $assessmentCenters])
                </div>
            </div>
            @section('footer_scripts')
                <script>
                    $(document).ready(function () {
                        $('#district_id').on('change', function () {
                            var selectedDistrictId = $(this).val();
                            $('#assessmentCenters-table tbody tr').each(function () {
                                var rowDistrictId = $(this).data('district-id');
                                if (selectedDistrictId === '' || rowDistrictId == selectedDistrictId) {
                                    $(this).show();
                                } else {
                                    $(this).hide();
                                }
                            });
                        });
                    });
                </script>
            @endsection
        </div>
    </div>
@endsection