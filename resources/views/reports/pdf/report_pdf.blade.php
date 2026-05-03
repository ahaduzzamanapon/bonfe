<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>{{ $title ?? 'Report' }}</title>
<style>
body{font-family:Arial,sans-serif;font-size:11px;color:#000;margin:10mm;}
h2{text-align:center;font-size:14px;margin-bottom:4px;}
p.subtitle{text-align:center;color:#555;margin:0 0 8px;}
table{width:100%;border-collapse:collapse;}
th{background:#1a3a5c;color:#fff;padding:5px 4px;text-align:left;font-size:10px;}
td{border-bottom:1px solid #ddd;padding:4px;vertical-align:top;}
tr:nth-child(even) td{background:#f5f5f5;}
.badge-pass{background:#198754;color:#fff;padding:1px 5px;border-radius:3px;}
.badge-fail{background:#dc3545;color:#fff;padding:1px 5px;border-radius:3px;}
.badge-absent{background:#ffc107;color:#000;padding:1px 5px;border-radius:3px;}
.footer{margin-top:15px;text-align:right;font-size:9px;color:#888;}
</style>
</head>
<body>
<h2>Non-Formal Education Board, Bangladesh</h2>
<p class="subtitle">{{ $title ?? 'Report' }} &mdash; Generated: {{ now()->format('d M Y, h:i A') }}</p>
<table>
    <thead>
        <tr>
            <th>#</th><th>Candidate ID</th><th>Name</th><th>Father / Mother</th>
            <th>Reg. No.</th><th>NID/BRN</th><th>Gender</th>
            <th>District</th><th>Upazila</th><th>Occupation</th><th>Exam Status</th><th>Certificate No.</th>
        </tr>
    </thead>
    <tbody>
        @foreach($students as $i=>$s)
        <tr>
            <td>{{ $i+1 }}</td>
            <td>{{ $s->candidate_id }}</td>
            <td>{{ $s->candidate_name }}<br><small>{{ $s->candidate_name_bn }}</small></td>
            <td>{{ $s->father_name }}<br>{{ $s->mother_name }}</td>
            <td>{{ $s->registration_number }}</td>
            <td>{{ $s->nid ?? $s->brn }}</td>
            <td>{{ $s->gender }}</td>
            <td>{{ $s->district_name }}</td>
            <td>{{ $s->upazila_name }}</td>
            <td>{{ $s->occupation_title }}</td>
            <td><span class="badge-{{ $s->exam_status=='Passed'?'pass':($s->exam_status=='Fail'?'fail':'absent') }}">{{ $s->exam_status ?? '—' }}</span></td>
            <td>{{ $s->certificate_number }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
<div class="footer">Total Records: {{ count($students) }} | BONFE System &copy; {{ date('Y') }}</div>
</body>
</html>
