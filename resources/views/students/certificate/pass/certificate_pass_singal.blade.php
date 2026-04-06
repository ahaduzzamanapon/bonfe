<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Certificate</title>

    <style>
        @page {
            size: A4;
            margin: 0;
        }

        body {
            margin: 0;
            padding: 0;
            background: #ffffffff;
        }

        .certificate {
            width: 210mm;
            height: 297mm;
            position: relative;
            /* background: url('{{ asset('assets/images/bg_pass.jpg') }}') no-repeat center center; */
            background-size: cover;
            place-self: center;
        }

        .content {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            font-family: 'Georgia', serif;
            color: #000000ff;
        }

        h1 {
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .name {
            font-weight: 400;
            font-size: 42px;
            line-height: 100%;
            color: #3C3F3E;
            font-family: 'Brush Script MT', cursive;
            padding: 20px;
        }

      .description {
    max-width: 166mm;
    margin-bottom: 30px;
    min-width: 77%;
    font-family: Inter;
    font-weight: 400;
    font-size: 16.6px;
    line-height: 21px;
    color: #000000ff;
    text-align: justify;
}

        .skills {
    display: flex;
    gap: 35px;
    text-align: left;
    border: 1px solid #000000ff;
    padding: 6px 19px;
    min-width: 75%;
    justify-content: space-between;
}

        .skills ul {
            margin: 0;
            padding: 0;

        }

        .skills ul li {
            font-size: 14px;
            line-height: 22px;
            color: #000000ff;
            max-width: 280px;
        }

        .footer {
            position: absolute;
            bottom: 30mm;
            width: 80%;
            display: flex;
            justify-content: space-between;
            font-size: 16px;
            color: #000000ff;
        }

        .header_text {
            font-family: Playfair Display;
            font-weight: 700;
            font-size: 26px;
            line-height: 100%;
            font-variant: small-caps;
        }
    </style>
</head>

<body>
    <div class="certificate">
        <div class="content">
            <div style="padding-top: 77px;width: 82%;">
                <div class="row" style="justify-self: center;">
                    <span class="header_text" style="visibility: hidden;">NON-FORMAL EDUCATION BOARD, BANGLADESH</span>
                </div>
                <div class="row"
                    style="align-items: anchor-center;padding: 24px 26px;display: flex;justify-content: space-between;">
                    <div class="col-md-4">
                        <div class="row"
                            style="display: flex;flex-direction: column;align-items: flex-start;font-family: sans-serif;"">
                             <span>Candidate ID:</span>
                            <span>{{$student->candidate_id}}</span>
                        </div>
                    </div>
                    <div class=" col-md-4" style="visibility: hidden;">
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                width="166" height="166" viewBox="0 0 1144 1145">
                               </svg>
                        </div>
                        <div class="col-md-4"
                            style="display: flex;flex-direction: column;align-items: flex-start;font-family: sans-serif;text-align: start;padding: 0;">
                            <span>Registration No:</span>
                            <span>{{$student->registration_number}}</span>
                            <span>Date of Issue: {{date('d-m-Y', strtotime($student->updated_at))}}</span>
                        </div>
                    </div>
                </div>

                {{-- <div class="name">{{$student->candidate_name?$student->candidate_name:$student->candidate_name_bn}}
                </div> --}}
                <div class="description" style="margin-top: 25px;margin-bottom: 46px;">
                    This is to certify that, <strong style="text-transform: capitalize;">
                        {{$student->candidate_name ? $student->candidate_name : $student->candidate_name_bn}}</strong>, Mother's Name: <span style="text-transform: capitalize;">{{$student->mother_name}}</span>,
                    Father's Name: <span style="text-transform: capitalize;">{{$student->father_name}}</span>, Date of
                    Birth: 
                     {{ \Carbon\Carbon::parse($student->date_of_birth)->format('d M Y') }} has <strong>successfully
                    completed  460 hours’</strong> Prevocational
                    Level course in
                    <strong>{{$student->occupation}}</strong> under the
                    Bangladesh National
                    Qualification Framework (BNQF),
                    @php
                        $assessment_centers = DB::table('assessment_centers')
                            ->where('id', $student->assessment_center)
                            ->first();

                    @endphp
                assessed by <b>{{ $assessment_centers->center_name }}</b> on {{ date('d M Y', strtotime($student->assessment_date)) }}. The
                    learner  demonstrated satisfactory participation and performance on all the following competencies:
                </div>

                <span
                    style="font-family: Inter;font-weight: 700;font-size: 17px;line-height: 100%;margin-bottom: 9px;">Achieved
                    Units of Competency</span>
                <div class="skills">
                    @php



                        if ($student->exam_status != 'Passed') {


                            //dd($student);
                            $student_competence_models = DB::table('student_competence_models')
                                ->select('student_competence_models.*', 'competences.title')
                                ->join('competences', 'student_competence_models.competence_id', '=', 'competences.id')
                                ->where('student_id', $student->id)
                                ->get();
                        } else {
                            $student_competence_models = DB::table('competences')
                                ->where('competences.occupation_id', $student->occupation_id)
                                ->get();
                        }
                        //dd($student_competence_models,$student->occupation_id);

                        // $cimpitent_id = [];
                        // foreach ($student_competence_models as $key => $student_competence_model) {
                        //     $cimpitent_id[] = $student_competence_model->competence_id;
                        // }
                        $total = count($student_competence_models);
                        if ($total == 0) {
                            echo '<p>No Competency Found</p>';
                        }







                        $hulft = ceil($total / 2);
                    @endphp
                    <ul>
                        @foreach ($student_competence_models as $key => $student_competence_model)
                            @if ($key == $hulft)
                                </ul>
                                <ul>
                            @endif
                            <li>{{$student_competence_model->title}}</li>
                        @endforeach
                    </ul>
                </div>

                <div style="margin-top: 2px;position: absolute;bottom: 31mm;right: 93mm;">
                    {{ $qrCode }}
                </div>

                <div class="footer">

                    <div
                        style="display: flex;flex-direction: column;align-items: center;padding: 28px 0;justify-content: flex-end;">
                        <span
                            style="font-size: 16px;font-weight: 600;margin-top: 5px; border-top: 1px solid #000000ff;">Assistant
                            Director</span>
                        <span style="font-size: 14px;">(DBNFE)</span>
                    </div>
                    @php
                        $AssessmentController = DB::table('users')
                            ->where('id', $student->chairmen_id)
                            ->first();
                    @endphp

                    <div
                        style="display: flex;flex-direction: column;align-items: center;padding: 10px 0;justify-content: flex-end;">
                        <img src="{{ asset($AssessmentController->signature) }}" alt=""
                            style="width: 150px;object-fit: contain;">
                        <span
                            style="font-size: 16px;font-weight: 600;margin-top: 5px; border-top: 1px solid #000000ff;">Chairman</span>
                        <span style="font-size: 14px;">Non-Formal Education Board</span>
                        <span style="font-size: 14px;">Bangladesh</span>
                    </div>
                </div>
            </div>
        </div>
</body>

</html>