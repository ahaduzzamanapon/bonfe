<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Re-Assessment Certificate</title>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Serif+Bengali&display=swap" rel="stylesheet">
    <style>
        @page { size: A4; margin: 0; }
        body { margin: 0; padding: 0; background: #fff; font-family: 'Georgia', serif; }
        .certificate { width: 210mm; height: 297mm; position: relative; place-self: center; }
        .content { position: absolute; top: 0; left: 0; width: 100%; height: 100%; display: flex; flex-direction: column; align-items: center; text-align: center; color: #000; }
        .header_text { font-family: 'Playfair Display', Georgia, serif; font-weight: 700; font-size: 26px; font-variant: small-caps; }
        .description { max-width: 166mm; margin-bottom: 30px; min-width: 77%; font-size: 16.6px; line-height: 21px; text-align: justify; }
        .skills { display: flex; gap: 35px; text-align: left; border: 1px solid #000; padding: 6px 19px; min-width: 75%; justify-content: space-between; }
        .skills ul { margin: 0; padding: 0; }
        .skills ul li { font-size: 14px; line-height: 22px; max-width: 280px; }
        .footer { position: absolute; bottom: 30mm; width: 80%; display: flex; justify-content: space-between; font-size: 16px; }
        .badge-attempt { background: #1a3a5c; color: #fff; padding: 3px 12px; border-radius: 20px; font-size: 12px; font-family: sans-serif; display: inline-block; margin-bottom: 8px; }
        @media print { .no-print { display: none !important; } }
    </style>
</head>
<body>
    <div class="no-print" style="background:#f0f0f0;padding:8px;text-align:center;">
        <button onclick="window.print()" style="padding:7px 22px;background:#006400;color:#fff;border:none;border-radius:4px;cursor:pointer;">🖨️ Print / Save as PDF</button>
    </div>
    <div class="certificate">
        <div class="content">
            <div style="padding-top: 77px; width: 82%;">
                <div style="text-align:center; margin-bottom:4px;">
                    <span class="badge-attempt">Re-Assessment Certificate — Attempt #{{ $ra->attempt_number }}</span>
                </div>
                <div style="align-items:anchor-center; padding:24px 26px; display:flex; justify-content:space-between;">
                    <div style="display:flex; flex-direction:column; align-items:flex-start; font-family:sans-serif;">
                        <span>Candidate ID:</span>
                        <span>{{ $student->candidate_id }}</span>
                    </div>
                    <div style="display:flex; flex-direction:column; align-items:flex-start; font-family:sans-serif; text-align:start;">
                        <span>Registration No:</span>
                        <span>{{ $student->registration_number }}</span>
                        <span>Date of Issue: {{ date('d-m-Y') }}</span>
                    </div>
                </div>

                <div class="description" style="margin-top:25px; margin-bottom:46px;">
                    This is to certify that, <strong style="text-transform:capitalize;">
                        {{ $student->candidate_name ?: $student->candidate_name_bn }}</strong>,
                    Mother's Name: <span style="text-transform:capitalize;">{{ $student->mother_name }}</span>,
                    Father's Name: <span style="text-transform:capitalize;">{{ $student->father_name }}</span>,
                    Date of Birth: {{ \Carbon\Carbon::parse($student->getRawOriginal('date_of_birth'))->format('d M Y') }}
                    has <strong>successfully completed</strong> the Re-Assessment for
                    <strong>{{ optional($student->occupation)->title }}</strong>
                    under the Bangladesh National Qualification Framework (BNQF),
                    @php $center = \DB::table('assessment_centers')->where('id', $ra->scheduled_center_id)->first(); @endphp
                    assessed by <b>{{ $center ? $center->center_name : 'Assessment Center' }}</b>
                    on {{ $ra->scheduled_date ? \Carbon\Carbon::parse($ra->scheduled_date)->format('d M Y') : date('d M Y') }}.
                    The learner demonstrated satisfactory participation and performance on all the following competencies:
                </div>

                <span style="font-weight:700; font-size:17px; margin-bottom:9px; display:block;">Achieved Units of Competency</span>
                <div class="skills">
                    @php
                        $competences = \DB::table('competences')->where('occupation_id', $student->occupation_id)->get();
                        $total = count($competences); $half = ceil($total / 2);
                    @endphp
                    <ul>
                        @foreach($competences as $k => $comp)
                            @if($k == $half) </ul><ul> @endif
                            <li>{{ $comp->title }}</li>
                        @endforeach
                    </ul>
                </div>

                <div class="footer">
                    <div style="display:flex; flex-direction:column; align-items:center; padding:28px 0; justify-content:flex-end;">
                        <span style="font-size:16px; font-weight:600; margin-top:5px; border-top:1px solid #000;">Assistant Director</span>
                        <span style="font-size:14px;">(DBNFE)</span>
                    </div>
                    @php $chairman = \DB::table('users')->where('id', $student->chairmen_id)->first(); @endphp
                    <div style="display:flex; flex-direction:column; align-items:center; padding:10px 0; justify-content:flex-end;">
                        @if($chairman && $chairman->signature)
                        <img src="{{ asset($chairman->signature) }}" alt="" style="width:150px; object-fit:contain;">
                        @endif
                        <span style="font-size:16px; font-weight:600; margin-top:5px; border-top:1px solid #000;">Chairman</span>
                        <span style="font-size:14px;">Non-Formal Education Board</span>
                        <span style="font-size:14px;">Bangladesh</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
