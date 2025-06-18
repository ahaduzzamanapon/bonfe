<!doctype html>
<html lang="en">
  <head>
    <title>Certificate</title>
    <meta charset="utf-8">
    <meta name="viewport" content="initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">

    <style>
      body {
    font-family: sans-serif;
}

      .certificate {
        margin: auto;
        padding: 75px 50px;
        background-image: url('{{ asset('images/certificate_background.jpg') }}'); /* Adjust as needed */
        background-repeat: no-repeat;
        background-size: cover;
        width: 982px;
        height: 1397px;
        position: relative;
      }

      .header {
        text-align: center;
        margin-bottom: 20px;
      }

      .seal {
        text-align: center;
        margin-top: -20px;
        margin-bottom: 20px;
      }

      .content {
        font-size: 18px;
        line-height: 1.8;
        padding: 0 40px;
      }

      .units {
    border: 1px solid #000;
    padding: 15px;
    margin-top: 81px;
}

      .footer {
        position: absolute;
        bottom: 50px;
        width: 100%;
        text-align: center;
        font-size: 16px;
      }

      .verified {
        position: absolute;
        bottom: 172px;
        left: 88px;
      }

      .chairman {
    position: absolute;
    bottom: 100px;
    right: 67px;
    text-align: center;
}
p{
    padding: 0px;
    margin: 0px;
}

      @media print {
        .btn {
          display: none;
        }
        @page {
          size: A4 portrait;
          margin: 0;
        }

        body {
          margin: 0;
        }
      }
    </style>
  </head>
  <body>
     <a href="javascript:window.print();" class="btn btn-primary" style="float: right;margin: 10px;">Print</a>
    <div class="certificate">
        @php
        $setting = DB::table('sitesettings')->first();
        $logo = $setting->logo;

        $chairmen = DB::table('users')->where('id', $student->chairmen_id)->first();
    @endphp
        <div class="seal">
          <img src="{{ !empty($setting) ? asset($setting->logo) : 'assets/images/Picture1.jpg' }} "alt="Logo" style="width: 168px;height: 169px;"> <!-- Replace with dynamic logo -->
        </div>
      <div class="header">
        <h4 style="font-weight: 900;">Non-Formal Education Board, Bangladesh</h4>
      </div>


      <div class="content">
        <p>
          This is to certify that {{ $student->candidate_name }}, Mother's Name: {{ $student->mother_name }}, Father's Name: {{ $student->father_name }}, Date of Birth: {{ $student->date_of_birth }}, Birth Registration No: {{ $student->nid }} has successfully completed Prevocational Level course in {{ $student->occupation }} (460 hours) under the Bangladesh National Qualification Framework (BNQF) with the following competencies conducted by {{ $student->institute_name }} from {{ $student->start_date }} to {{ $student->end_date }}. The student demonstrated satisfactory participation and performance during the course.
        </p>

        <div class="units">
          <div style="display: flex;line-height: 37px;flex-direction: row;">
            <div class="col-md-6">
              ✓ Apply basic Bangla proficiency<br>
              ✓ Apply foundation English skills<br>
              ✓ Apply fundamental mathematical concept<br>
              ✓ Apply occupational safety and health<br>
              ✓ Utilise life skills effectively<br>
              ✓ Use fundamental entrepreneurial skills<br>
              ✓ Work in construction sector<br>
            </div>
            <div class="col-md-6">
              ✓ Use construction tools and equipment<br>
              ✓ Apply sector on focus locus digital literacy<br>
              ✓ Use electrical drawing<br>
              ✓ Apply basic skills for electrical works<br>
              ✓ Practice wire and cable joints<br>
              ✓ Prepare basic electric circuit<br>
              ✓ Install electrical fittings<br>
            </div>
          </div>
        </div>
      </div>

      <div class="verified">
        <p>
          Serial No. NSC-T&A-CBT&AM-L4-0000<br>
          Registration No.: RPL-T&A-CBT&AM-L4-M000<br>
          Issued on: {{ date('d-m-Y') }}
          <br>
          <div style="position: relative;right: -352px;">

            {{ $qrCode }}
          </div>
        </p>
      </div>

      <div class="chairman">
          <img src="{{ !empty($chairmen) ? asset($chairmen->signature) : '' }} " style="height: 67px;"> 
        <p >

          <strong>Chairman</strong><br>
          Board of Non-Formal Education<br>
          &<br>
          Director General<br>
          Bureau of Non-Formal Education,<br>
          Ministry of Primary & Mass Education<br>
          Govt. of Bangladesh
        </p>
      </div>
    </div>

  </body>
</html>

