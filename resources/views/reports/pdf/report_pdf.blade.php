<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>{{ $title ?? 'Report' }}</title>
<style>
@font-face {
    font-family: 'NotoSansBengali';
    font-style: normal;
    font-weight: normal;
    src: url('{{ str_replace("\\", "/", storage_path("fonts/NotoSansBengali.ttf")) }}') format('truetype');
}
body { font-family: Arial, sans-serif; font-size: 10px; color: #000; margin: 8mm; }
h2  { text-align:center; font-size:13px; margin-bottom:2px; }
p.subtitle { text-align:center; color:#555; margin:0 0 8px; font-size:10px; }
table { width:100%; border-collapse:collapse; }
th { background:#1a3a5c; color:#fff; padding:4px 3px; text-align:left; font-size:9px; }
td { border-bottom:1px solid #ddd; padding:3px; vertical-align:top; font-size:9px; }
tr:nth-child(even) td { background:#f5f5f5; }
.badge-pass   { background:#198754; color:#fff; padding:1px 4px; border-radius:3px; }
.badge-fail   { background:#dc3545; color:#fff; padding:1px 4px; border-radius:3px; }
.badge-absent { background:#ffc107; color:#000; padding:1px 4px; border-radius:3px; }
.footer { margin-top:10px; text-align:right; font-size:8px; color:#888; }
.bn { font-family: 'NotoSansBengali', Arial, sans-serif; }
</style>
</head>
<body>

@php
$type = $type ?? 'project_wise';

$colDefs = [
    'project_wise' => [
        'show_district'=>true,'show_upazila'=>true,'show_program'=>true,'show_occupation'=>true,
        'show_gender'=>false,'show_name'=>true,'show_father'=>true,'show_mother'=>true,
        'show_cand_id'=>false,'show_reg'=>true,'show_brn_nid'=>true,'show_exam'=>false,'show_cert_no'=>false,
    ],
    'district_wise' => [
        'show_district'=>true,'show_upazila'=>false,'show_program'=>false,'show_occupation'=>false,
        'show_gender'=>false,'show_name'=>true,'show_father'=>true,'show_mother'=>true,
        'show_cand_id'=>false,'show_reg'=>true,'show_brn_nid'=>true,'show_exam'=>false,'show_cert_no'=>false,
    ],
    'upazila_wise' => [
        'show_district'=>true,'show_upazila'=>true,'show_program'=>false,'show_occupation'=>false,
        'show_gender'=>false,'show_name'=>true,'show_father'=>true,'show_mother'=>true,
        'show_cand_id'=>false,'show_reg'=>true,'show_brn_nid'=>true,'show_exam'=>false,'show_cert_no'=>false,
    ],
    'gender_wise' => [
        'show_district'=>false,'show_upazila'=>false,'show_program'=>false,'show_occupation'=>false,
        'show_gender'=>true,'show_name'=>true,'show_father'=>true,'show_mother'=>true,
        'show_cand_id'=>false,'show_reg'=>true,'show_brn_nid'=>true,'show_exam'=>false,'show_cert_no'=>false,
    ],
    'occupation_wise' => [
        'show_district'=>true,'show_upazila'=>true,'show_program'=>false,'show_occupation'=>true,
        'show_gender'=>false,'show_name'=>true,'show_father'=>true,'show_mother'=>true,
        'show_cand_id'=>false,'show_reg'=>true,'show_brn_nid'=>true,'show_exam'=>false,'show_cert_no'=>false,
    ],
    'student_id' => [
        'show_district'=>true,'show_upazila'=>true,'show_program'=>false,'show_occupation'=>false,
        'show_gender'=>false,'show_name'=>true,'show_father'=>true,'show_mother'=>true,
        'show_cand_id'=>true,'show_reg'=>false,'show_brn_nid'=>true,'show_exam'=>false,'show_cert_no'=>false,
    ],
    'certificate_distribution' => [
        'show_district'=>true,'show_upazila'=>true,'show_program'=>false,'show_occupation'=>false,
        'show_gender'=>false,'show_name'=>true,'show_father'=>true,'show_mother'=>true,
        'show_cand_id'=>false,'show_reg'=>true,'show_brn_nid'=>true,'show_exam'=>false,'show_cert_no'=>true,
    ],
    'nyc_students' => [
        'show_district'=>true,'show_upazila'=>true,'show_program'=>false,'show_occupation'=>false,
        'show_gender'=>false,'show_name'=>true,'show_father'=>true,'show_mother'=>true,
        'show_cand_id'=>false,'show_reg'=>true,'show_brn_nid'=>true,'show_exam'=>true,'show_cert_no'=>false,
    ],
];

$c = $colDefs[$type] ?? $colDefs['project_wise'];
@endphp

<h2>Non-Formal Education Board, Bangladesh</h2>
<p class="subtitle">{{ $title ?? 'Report' }} &mdash; Generated: {{ now()->format('d M Y, h:i A') }}</p>

<table>
    <thead>
        <tr>
            <th>#</th>
            @if(!empty($c['show_district']))    <th>District</th> @endif
            @if(!empty($c['show_upazila']))     <th>Upazila</th> @endif
            @if(!empty($c['show_program']))     <th>Program</th> @endif
            @if(!empty($c['show_occupation']))  <th>Occupation</th> @endif
            @if(!empty($c['show_gender']))      <th>Gender</th> @endif
            @if(!empty($c['show_name']))        <th>Name</th> @endif
            @if(!empty($c['show_father']))      <th>Father's Name</th> @endif
            @if(!empty($c['show_mother']))      <th>Mother's Name</th> @endif
            @if(!empty($c['show_cand_id']))     <th>Candidate ID</th> @endif
            @if(!empty($c['show_reg']))         <th>Reg. No.</th> @endif
            @if(!empty($c['show_brn_nid']))     <th>BRN / NID</th> @endif
            @if(!empty($c['show_exam']))        <th>Exam Status</th> @endif
            @if(!empty($c['show_cert_no']))     <th>Certificate No.</th> @endif
        </tr>
    </thead>
    <tbody>
        @foreach($students as $i=>$s)
        <tr>
            <td>{{ $i+1 }}</td>
            @if(!empty($c['show_district']))    <td>{{ $s->district_name }}</td> @endif
            @if(!empty($c['show_upazila']))     <td>{{ $s->upazila_name }}</td> @endif
            @if(!empty($c['show_program']))     <td>{{ $s->program_title }}</td> @endif
            @if(!empty($c['show_occupation']))  <td>{{ $s->occupation_title }}</td> @endif
            @if(!empty($c['show_gender']))      <td>{{ $s->gender }}</td> @endif
            @if(!empty($c['show_name']))        <td>{{ $s->candidate_name }}<br><small class="bn">{{ $s->candidate_name_bn }}</small></td> @endif
            @if(!empty($c['show_father']))      <td>{{ $s->father_name }}<br><small class="bn">{{ $s->father_name_bn }}</small></td> @endif
            @if(!empty($c['show_mother']))      <td>{{ $s->mother_name }}<br><small class="bn">{{ $s->mother_name_bn }}</small></td> @endif
            @if(!empty($c['show_cand_id']))     <td>{{ $s->candidate_id }}</td> @endif
            @if(!empty($c['show_reg']))         <td>{{ $s->registration_number }}</td> @endif
            @if(!empty($c['show_brn_nid']))     <td>{{ $s->nid ?? $s->brn }}</td> @endif
            @if(!empty($c['show_exam']))
                <td>
                    @php $es = $s->exam_status ?? '—'; @endphp
                    <span class="badge-{{ $es=='Passed'?'pass':($es=='Fail'?'fail':'absent') }}">{{ $es }}</span>
                </td>
            @endif
            @if(!empty($c['show_cert_no']))     <td>{{ $s->certificate_number }}</td> @endif
        </tr>
        @endforeach
    </tbody>
</table>
<div class="footer">Total Records: {{ count($students) }} &nbsp;|&nbsp; BONFE System &copy; {{ date('Y') }}</div>
</body>
</html>
