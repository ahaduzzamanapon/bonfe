<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Registration Card - {{ $student->candidate_name }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Serif+Bengali&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        @page {
            size: A4;
            margin: 0;
        }

        body {
            background: #fff;
            font-family: 'Times New Roman', Times, serif;
            font-size: 11pt;
            color: #000;
        }

        @media print {
            .no-print {
                display: none !important;
            }

            body {
                margin: 0;
            }
        }

        .page {
            width: 210mm;
            height: 297mm;
            margin: 0 auto;
            padding: 10mm 14mm 8mm 14mm;
            display: flex;
            flex-direction: column;
            position: relative;
            /* background: url('{{ asset('assets/images/logo.png') }}') no-repeat center center;
            background-size: cover;
            place-self: center; */
        }

        .card-body {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        /* ── HEADER ── */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-bottom: 5px;
            border-bottom: 3px solid #c8a400;
        }

        .header-logo {
            width: 62px;
            height: 62px;
            flex-shrink: 0;
        }

        .header-center {
            text-align: center;
            flex: 1;
            padding: 0 8px;
        }

        .header-org {
            font-size: 17pt;
            font-weight: bold;
            color: #006400;
            /* font-family: Arial, sans-serif; */
        }

        .header-url {
            font-size: 9pt;
            color: #444;
            margin: 1px 0;
        }

        .header-title {
            font-size: 11.5pt;
            font-weight: bold;
            color: #8b0000;
            margin-top: 2px;
        }

        /* ── TOP SECTION (occupation + photo) ── */
        .top-section {
            display: flex;
            align-items: flex-start;
            margin-top: 7px;
            gap: 8px;
        }

        .top-fields {
            flex: 1;
        }

        .top-field-row {
            display: flex;
            align-items: flex-end;
            margin-bottom: 6px;
            font-size: 10.5pt;
        }

        .top-field-label {
            white-space: nowrap;
            font-weight: bold;
        }

        .top-field-line {
            flex: 1;
            /* border-bottom: 1px solid #000; */
            margin-left: 5px;
            font-weight: 600;
            padding-bottom: 1px;
        }

        /* Photo box */
        .photo-box {
            width: 85px;
            height: 100px;
            border: 1px solid #888;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            background: #f9f9f9;
            overflow: hidden;
        }

        .photo-box img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .photo-label {
            font-size: 8pt;
            color: #666;
            text-align: center;
            /* font-family: 'Noto Serif Bengali', serif; */
            padding: 4px;
        }

        /* ── SECTION HEADER with arrow ribbon ── */
        .section-header {
            position: relative;
            display: flex;
            align-items: center;
            margin: 7px 0 4px 0;
            height: 22px;
        }

        .section-header-inner {
            background: linear-gradient(90deg, #006400 0%, #008000 75%, #2e8b57 100%);
            color: #fff;
            font-weight: bold;
            font-size: 10.5pt;
            /* font-family: Arial, sans-serif; */
            padding: 2px 10px 2px 8px;
            flex: 1;
            display: flex;
            align-items: center;
            clip-path: polygon(0 0, calc(100% - 12px) 0, 100% 50%, calc(100% - 12px) 100%, 0 100%);
            height: 100%;
        }

        /* ── FIELDS ── */
        .field-row {
            display: flex;
            align-items: flex-end;
            margin-bottom: 7px;
            min-height: 20px;
        }

        .field-num {
            width: 24px;
            font-size: 10.5pt;
            flex-shrink: 0;
        }

        .field-label {
            font-size: 10.5pt;
            white-space: nowrap;
            flex-shrink: 0;
        }

        .field-underline {
            flex: 1;
            /* border-bottom: 1px solid #000; */
            margin-left: 4px;
            font-size: 10.5pt;
            font-weight: 600;
            padding-bottom: 1px;
        }

        .field-bn {
            margin-left: 24px;
            font-family: 'Noto Serif Bengali', serif;
            font-size: 9.5pt;
            color: #333;
            margin-bottom: 6px;
            display: flex;
            align-items: flex-end;
        }

        .field-bn-underline {
            flex: 1;
            /* border-bottom: 1px solid #000; */
            margin-left: 4px;
            font-size: 10pt;
            font-weight: 600;
            padding-bottom: 1px;
        }

        /* Date boxes */
        .dob-row {
            display: flex;
            align-items: flex-end;
            gap: 1px;
            margin-left: 4px;
        }

        .dob-cell {
            /* border-bottom: 1px solid #000; */
            text-align: center;
            font-weight: 600;
            font-size: 10.5pt;
        }

        .dob-sep {
            font-size: 10pt;
            padding: 0 2px;
        }

        /* ── SIGNATURES ── */
        .signatures {
    display: flex;
    margin-top: 18px;
    position: relative;
    align-content: flex-end;
    flex-wrap: wrap;
    align-items: flex-end;
    justify-content: space-between;
}

        .sig-block {
            text-align: center;
        }

        .sig-line {
            border-top: 1px solid #000;
            width: 140px;
            margin: 0 auto 2px auto;
        }

        .sig-label {
            font-size: 10pt;
        }

        /* ── SECTION HEADER spacing ── */
        .section-header {
            margin: 10px 0 6px 0;
        }

        /* ── INSTRUCTIONS ── */
        .instructions {
            border: 1px solid #000;
            margin-top: 8px;
            padding: 5px 8px;
        }

        .inst-title {
            font-weight: bold;
            font-size: 10.5pt;
            text-align: center;
            text-decoration: underline;
            margin-bottom: 4px;
        }

        .instructions ul {
            margin-left: 16px;
            font-size: 9pt;
            line-height: 1.6;
        }

        .print-btn {
            display: block;
            margin: 8px auto;
            padding: 7px 22px;
            background: #006400;
            color: #fff;
            border: none;
            border-radius: 4px;
            font-size: 13px;
            cursor: pointer;
        }
    </style>
</head>

<body>

    <div class="no-print" style="background:#f0f0f0;padding:8px;text-align:center;">
        <button class="print-btn" onclick="window.print()">🖨️ Print / Save as PDF</button>
    </div>

    <div class="page">
        <div style="height: 100%;width: 87%;position: absolute;text-align: center;align-content: center;">

            <img style="height: 416px;width: fit-content;z-index: 0;opacity: 0.15;" src="{{ asset('assets/images/logo.png') }}" alt="">
        </div>

        {{-- ══ HEADER ══ --}}
        <div class="header">
            <img class="header-logo" src="{{ asset('images/bns.png') }}" alt="BNS">
            <div class="header-center">
                <div class="header-org">Non-Formal Education Board, Bangladesh</div>
                <div class="header-url">www.bnfe.gov.bd</div>
                <div class="header-title">Registration Card for Assessment</div>
            </div>
            <img class="header-logo" src="{{ asset('assets/images/logo.png') }}" alt="NFEB Logo">
        </div>

        {{-- ══ OCCUPATION + PHOTO + all content ══ --}}
        <div class="card-body">

            {{-- OCCUPATION + PHOTO --}}
            <div class="top-section" style="margin-top:8px;">
                <div class="top-fields">
                    <div class="top-field-row">
                        <span class="top-field-label">Name of Occupation:</span>
                        <span class="top-field-line">{{ $student->occupation }}</span>
                    </div>
                    <div class="top-field-row" style="margin-top:3px;">
                        <span class="top-field-label">NFEB Registration No:</span>
                        <span class="top-field-line" style="color:#8b0000;">{{ $student->registration_number }}</span>
                    </div>
                </div>
                <div>
                    @if($student->image && $student->image !== 'no-image.png')
                        <div class="photo-box"><img src="{{ asset($student->image) }}" alt="Photo"></div>
                    @else
                        <div class="photo-box"><span class="photo-label">আবেদনকারীর<br>ছবি</span></div>
                    @endif
                </div>
            </div>

            {{-- ══ CANDIDATE INFORMATION ══ --}}
            <div class="section-header">
                <div class="section-header-inner">:: Candidate Information</div>
            </div>

            <div class="field-row">
                <span class="field-num">01.</span>
                <span class="field-label">Candidate ID :</span>
                <span class="field-underline">{{ $student->candidate_id }}</span>
            </div>

            <div class="field-row">
                <span class="field-num">02.</span>
                <span class="field-label">Name:</span>
                <span class="field-underline">{{ $student->candidate_name }}</span>
            </div>
            <div class="field-bn">
                <span style="white-space:nowrap;">নাম (বাংলায়):</span>
                <span class="field-bn-underline">{{ $student->candidate_name_bn }}</span>
            </div>

            <div class="field-row">
                <span class="field-num">03.</span>
                <span class="field-label">Father's Name:</span>
                <span class="field-underline">{{ $student->father_name }}</span>
            </div>
            @if(!empty($student->father_name_bn))
                <div class="field-bn">
                    <span style="white-space:nowrap;">পিতার নাম (বাংলায়):</span>
                    <span class="field-bn-underline">{{ $student->father_name_bn }}</span>
                </div>
            @endif

            <div class="field-row">
                <span class="field-num">04.</span>
                <span class="field-label">Mother's Name:</span>
                <span class="field-underline">{{ $student->mother_name }}</span>
            </div>
            @if(!empty($student->mother_name_bn))
                <div class="field-bn">
                    <span style="white-space:nowrap;">মাতার নাম (বাংলায়) :</span>
                    <span class="field-bn-underline">{{ $student->mother_name_bn }}</span>
                </div>
            @endif

            <div class="field-row">
                <span class="field-num">05.</span>
                <span class="field-label">NID Number:</span>
                <span class="field-underline">{{ $student->nid }}</span>
            </div>

            <div class="field-row">
                <span class="field-num">06.</span>
                <span class="field-label">BRN Number:</span>
                <span class="field-underline">{{ $student->brn }}</span>
            </div>

            <div class="field-row">
                <span class="field-num">07.</span>
                <span class="field-label">Address (ঠিকানা):</span>
                <span
                    class="field-underline">{{ $student->address }}{{ $student->district_name ? ', ' . $student->district_name : '' }}</span>
            </div>

            @php $dob = $student->date_of_birth ? \Carbon\Carbon::parse($student->date_of_birth) : null; @endphp
            <div class="field-row">
                <span class="field-num">08.</span>
                <span class="field-label">Date of Birth (জন্ম তারিখ) (দিন-মাস-বছর):</span>
                <div class="dob-row">
                    <span class="dob-cell" style="width:30px;">{{ $dob?->format('d') }}</span>
                    <span class="dob-sep">/</span>
                    <span class="dob-cell" style="width:34px;">{{ $dob?->format('m') }}</span>
                    <span class="dob-sep">/</span>
                    <span class="dob-cell" style="width:50px;">{{ $dob?->format('Y') }}</span>
                </div>
            </div>

            <div class="field-row">
                <span class="field-num">09.</span>
                <span class="field-label">Mobile Number (মোবাইল নম্বর):</span>
                <span class="field-underline">+880{{ $student->mobile_number }}</span>
            </div>

            <div class="field-row">
                <span class="field-num">10.</span>
                <span class="field-label">Email Address:</span>
                <span class="field-underline">{{ $student->email }}</span>
            </div>

            {{-- ══ ASSESSMENT INFORMATION ══ --}}
            <div class="section-header">
                <div class="section-header-inner">:: Assessment Information</div>
            </div>

            @php $adate = $student->assessment_date ? \Carbon\Carbon::parse($student->assessment_date) : null; @endphp
            <div class="field-row">
                <span class="field-num">11.</span>
                <span class="field-label">Assessment Date:</span>
                <div class="dob-row">
                    <span class="dob-cell" style="width:30px;">{{ $adate?->format('d') }}</span>
                    <span class="dob-sep">/</span>
                    <span class="dob-cell" style="width:34px;">{{ $adate?->format('m') }}</span>
                    <span class="dob-sep">/</span>
                    <span class="dob-cell" style="width:50px;">{{ $adate?->format('Y') }}</span>
                </div>
            </div>

            <div class="field-row">
                <span class="field-num">12.</span>
                <span class="field-label">Assessment Venue:</span>
                <span class="field-underline">{{ $student->assessment_venue }}</span>
            </div>

            <div class="field-row">
                <span class="field-num">13.</span>
                <span class="field-label">Assessment Center:</span>
                <span
                    class="field-underline">{{ $student->assessment_center_name ?? $student->assessment_center }}</span>
            </div>

            <div class="field-row">
                <span class="field-num">14.</span>
                <span class="field-label">Assessment Center Registration No:</span>
                <span class="field-underline">{{ $student->assessment_center_registration_number }}</span>
            </div>

            {{-- ══ SIGNATURES ══ --}}
            <div class="signatures">
                <div class="sig-block">
                    <div class="sig-line"></div>
                    <div class="sig-label" style="margin-bottom: 16px;">Candidate's Signature</div>
                </div>
                <div class="sig-block">
                    @php
                        $reg = DB::table('users')
                            ->where('id', $student->assistant_registrar_id)
                            ->first();
                    @endphp
                    <img src="{{ asset($reg->signature) }}" alt="" style="width: 150px; height:40 ; object-fit: contain;">
                    <div class="sig-line"> </div>
                    <div class="sig-label">Assistant Registrar <br> (Registration)</div>
                </div>
            </div>

            {{-- ══ INSTRUCTIONS ══ --}}
            <div class="instructions">
                <div class="inst-title">Instruction to the Candidates</div>
                <ul>
                    <li>Candidate must show their registration card during the assessment</li>
                    <li>Photograph attached in this registration card will be matched with the candidate during the
                        assessment</li>
                    <li>NFEB reserves all rights to take any kind of punitive measure against any candidate who adopts
                        unfair means or misconduct during the assessment</li>
                    <li>No TA/DA will be admissible for attending the assessment</li>
                    <li>This registration card will be considered as admit card for assessment date <span>(for the date mentioned on SL. 11)</span></li>
                </ul>
            </div>{{-- end instructions --}}

        </div>{{-- end card-body --}}

    </div>
</body>

</html>