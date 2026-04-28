@extends('layouts.default')
@section('title') Dashboard @parent @stop

@section('content')
@php
    $occupation_id = auth()->user()->occupation;
    $occupation    = \App\Models\Occupation::find($occupation_id);
    if (can('assessment_centers_controller')) {
        if ($occupation && $occupation->program_type == 'General') {
            $programs    = \App\Models\Program::where('program_type', 'General')->latest()->get();
            $occupations = \App\Models\Occupation::where('title', 'General')->latest()->get();
        } else {
            $programs    = \App\Models\Program::where('program_type', 'Technical')->latest()->get();
            $occupations = \App\Models\Occupation::where('title', '!=', 'General')->latest()->get();
        }
    } else {
        $programs    = \App\Models\Program::latest()->get();
        $occupations = \App\Models\Occupation::latest()->get();
    }
@endphp

<style>
    .dash-card {
        display: flex; border-radius: 8px; overflow: hidden;
        box-shadow: 0 2px 8px rgba(0,0,0,.12); margin-bottom: 12px;
        background: #fff; transition: transform .2s ease, box-shadow .2s ease;
        min-height: 78px; text-decoration: none !important;
    }
    .dash-card:hover { transform: translateY(-3px); box-shadow: 0 6px 16px rgba(0,0,0,.18); }
    .dash-icon { flex: 0 0 70px; display: flex; align-items: center; justify-content: center; color: #fff; font-size: 28px; }
    .dash-info { flex: 1; display: flex; flex-direction: column; justify-content: center; padding: 0 12px; }
    .dash-info h3 { margin: 0; font-size: 22px; font-weight: 700; color: #222; }
    .dash-info p  { margin: 2px 0 0; font-size: 12px; color: #666; font-weight: 600; }
    .dash-spinner { width: 1rem; height: 1rem; border-width: .18em; }
    .bg-teal    { background-color: #0aa699; }
    .bg-blue2   { background-color: #007bff; }
    .bg-green2  { background-color: #28a745; }
    .bg-aqua    { background-color: #17a2b8; }
    .bg-fuchsia { background-color: #e83e8c; }
    .bg-orange2 { background-color: #fd7e14; }
</style>

<div class="content" style="overflow-x:hidden;">

    {{-- ── Filter Bar (plain div, NOT .card to avoid 88vh min-height) ── --}}
    <div style="background:#fff; border:1px solid #acacac; border-radius:6px; padding:7px 12px; margin-bottom:10px; display:flex; align-items:center; gap:10px; flex-wrap:wrap;">
        <strong style="font-size:13px; white-space:nowrap;">Dashboard</strong>
        <div style="flex:0 0 210px;">
            <select id="dashboard_program" class="form-control" onchange="fetchDashboardData()">
                @foreach ($programs as $p)
                    <option value="{{ $p->id }}">{{ $p->program_title }}</option>
                @endforeach
            </select>
        </div>
        <div style="flex:0 0 210px;">
            <select id="dashboard_occupation" class="form-control" onchange="fetchDashboardData()">
                <option value="">All Trade/Course</option>
                @foreach ($occupations as $o)
                    <option value="{{ $o->id }}">{{ $o->title }}</option>
                @endforeach
            </select>
        </div>
    </div>

    {{-- ── Stat Cards ── --}}
    @php
        $cards = [
            ['id' => 'total_students',                   'label' => 'Total Learner',                        'icon' => 'im-icon-User',       'color' => 'bg-teal'],
            ['id' => 'total_passed_students',            'label' => 'Total <span id="com_pass"></span>',    'icon' => 'im-icon-Map-Marker', 'color' => 'bg-blue2'],
            ['id' => 'total_failed_students',            'label' => 'Total <span id="com_fail"></span>',    'icon' => 'im-icon-Map',        'color' => 'bg-green2'],
            ['id' => 'total_absent_students',            'label' => 'Total Absent',                         'icon' => 'im-icon-User',       'color' => 'bg-teal'],
            ['id' => 'total_dropout_students',           'label' => 'Total Dropout',                        'icon' => 'im-icon-Map-Marker', 'color' => 'bg-blue2'],
            ['id' => 'waiting_for_assessment_center',    'label' => 'Waiting – Assessment Center Result',   'icon' => 'im-icon-Map',        'color' => 'bg-aqua'],
            ['id' => 'waiting_for_district',             'label' => 'Waiting – District Approval',          'icon' => 'im-icon-Map',        'color' => 'bg-aqua'],
            ['id' => 'waiting_for_assessment_controller','label' => 'Waiting – Assessment Controller',      'icon' => 'im-icon-Map',        'color' => 'bg-aqua'],
            ['id' => 'waiting_for_chairman',             'label' => "Waiting – Chairman's Approval",        'icon' => 'im-icon-Map',        'color' => 'bg-fuchsia'],
            ['id' => 'generated_certificate',            'label' => 'Generated Certificates',               'icon' => 'im-icon-Map',        'color' => 'bg-orange2'],
        ];
    @endphp

    <div class="row mx-0" style="gap-y:0;">
        @foreach ($cards as $c)
        <div class="col-md-4 col-sm-6">
            <a href="{{ route('students.index') }}" class="dash-card indexLink">
                <div class="dash-icon {{ $c['color'] }}">
                    <i class="icon im {{ $c['icon'] }}"></i>
                </div>
                <div class="dash-info">
                    <h3 id="{{ $c['id'] }}">
                        <div class="spinner-border dash-spinner text-secondary" role="status">
                            <span class="sr-only">…</span>
                        </div>
                    </h3>
                    <p>{!! $c['label'] !!}</p>
                </div>
            </a>
        </div>
        @endforeach
    </div>

</div>

@section('footer_scripts')
<script>
    function fetchDashboardData() {
        const sp = '<div class="spinner-border dash-spinner text-secondary" role="status"><span class="sr-only">…</span></div>';
        ['total_students','total_passed_students','total_failed_students','waiting_for_chairman',
         'waiting_for_district','generated_certificate','waiting_for_assessment_center',
         'waiting_for_assessment_controller','total_absent_students','total_dropout_students']
            .forEach(id => $('#' + id).html(sp));

        $.ajax({
            url: "{{ route('dashboard.data') }}",
            method: "GET",
            data: {
                program_id:    $('#dashboard_program').val(),
                occupation_id: $('#dashboard_occupation').val()
            },
            success: function(data) {
                const pt = data.program_type;
                if (pt === 'General') {
                    $('#com_pass').html('Promising');
                    $('#com_fail').html('Optainane');
                    $('.indexLink').attr('href', "{{ route('general_students.index') }}");
                    $('#dashboard_occupation').prop('disabled', true).val('');
                } else {
                    $('#com_pass').html('Competent');
                    $('#com_fail').html('Not Yet Competent');
                    $('.indexLink').attr('href', "{{ route('students.index') }}");
                    $('#dashboard_occupation').prop('disabled', false);
                }
                $('#total_students').html(data.total_students || 0);
                $('#total_passed_students').html(data.total_passed_students || 0);
                $('#total_failed_students').html(data.total_failed_students || 0);
                $('#waiting_for_chairman').html(data.waiting_for_chairman || 0);
                $('#waiting_for_district').html(data.waiting_for_district || 0);
                $('#generated_certificate').html(data.generated_certificate || 0);
                $('#waiting_for_assessment_center').html(data.waiting_for_assessment_center || 0);
                $('#waiting_for_assessment_controller').html(data.waiting_for_assessment_controller || 0);
                $('#total_absent_students').html(data.total_absent_students || 0);
                $('#total_dropout_students').html(data.total_dropout_students || 0);
            }
        });
    }
    $(document).ready(fetchDashboardData);
</script>
@endsection
@stop