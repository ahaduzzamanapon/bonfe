@extends('layouts.default')
{{-- Page title --}}
@section('title')
    Dashboard @parent
@stop
{{-- page level styles --}}
@section('header_styles')
    <link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">
@stop
@section('content')
    <section class="content-header">
        <h3>
            Dashboard
        </h3>
        <br>
       
    </section>
    <style>
        
        .custom-card {
    display: flex;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    border-radius: 10px;
    overflow: hidden;
    margin-bottom: 15px;
    transition: transform 0.3s ease;
    background-color: #fff;
    min-height: 89px;
}

   .custom-card:hover {
      transform: translateY(-4px);
   }

   .card-icon {
      flex: 0 0 100px;
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
      font-size: 45px;
   }

   .card-content {
      flex: 1;
      display: flex;
      flex-direction: column;
      justify-content: center;
      /* <- vertical centering */
      padding: 0 15px;
   }

   .card-content h3 {
      margin: 0;
      font-size: 22px;
      color: #333;
      font-weight: bold;
   }

   .card-content p {
      margin: 4px 0 0;
      font-size: 15px;
      color: #666;
      font-weight: bold;
   }

   .teal {
      background-color: teal;
   }

   .green {
      background-color: #28a745;
   }

   .blue {
      background-color: #007bff;
   }

   .red {
      background-color: #dc3545;
   }

   .aqua {
      background-color: #17a2b8;
   }

   .fuchsia {
      background-color: #e83e8c;
   }

   .orange {
      background-color: #fd7e14;
   }

   .yellow {
      background-color: #ffc107;
   }

   .dashboard_cards_header {
      display: flex;
      flex-wrap: wrap;
      align-items: center;
   }

   .dashboard_card {
    width: 30% !important;
    margin: 0px 15px !important;
}

   .tiles-title {
      font-size: 18px !important;
   }

   .heading {
      margin-top: 8px !important;
      font-size: 18px !important;
   }

   .report-table td {
      font-size: 15px !important;
   }

   @media (max-width: 768px) {
      .dashboard_card {
         width: 100% !important;
      }
   }

   </style>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


@php
   $total_students = DB::table('students');
   if(!can('chairman') && can('district_admin')) {
      $total_students = $total_students->where('students.district_id', auth()->user()->district_id);
   }
   $total_students = $total_students->count();


   $total_passed_students = DB::table('students')
   ->where('exam_status', 'Passed');
   if(!can('chairman') && can('district_admin')) {
      $total_passed_students = $total_passed_students->where('students.district_id', auth()->user()->district_id);
   }
   $total_passed_students = $total_passed_students->count();

   $total_failed_students = $total_students - $total_passed_students;

   $waiting_for_chairman = DB::table('students')
   ->where('exam_status', 'Passed')
   ->where('status', 'Waiting for Chairman Approval');
   if(!can('chairman') && can('district_admin')) {
      $waiting_for_chairman = $waiting_for_chairman->where('students.district_id', auth()->user()->district_id);
   }
   $waiting_for_chairman = $waiting_for_chairman->count();

    $waiting_for_district = DB::table('students')
    ->where('exam_status', 'Passed')
    ->where('status', 'Waiting for District Admin Approval');
    if(!can('chairman') && can('district_admin')) {
      $waiting_for_district = $waiting_for_district->where('students.district_id', auth()->user()->district_id);
   }
   $waiting_for_district = $waiting_for_district ->count();
    $generated_certificate = DB::table('students')
    ->where('exam_status', 'Passed')
    ->where('status', 'Chairman Approved')
    ->count();
    if(!can('chairman') && can('district_admin')) {
      $generated_certificate = DB::table('students')
      ->where('exam_status', 'Passed')
      ->where('status', 'Chairman Approved')
      ->where('students.district_id', auth()->user()->district_id)
      ->count();
   }
@endphp






    <section class="content">
        <div class="dashboard_cards_header">
            <div class="dashboard_card">
               <a href="{{ route('students.index') }}" style="text-decoration: none!important;">
                  <div class="custom-card">
                     <div class="card-icon teal">
                        <i class="icon im im-icon-User"></i>
                     </div>
                     <div class="card-content">
                        <h3>{{ $total_students }}</h3>
                        <p>Total Students</p>
                     </div>
                  </div>
               </a>
            </div>
            <div class="dashboard_card">
               <a href="{{ route('students.index') }}" style="text-decoration: none!important;">
                  <div class="custom-card">
                     <div class="card-icon blue">
                        <i class="icon im im-icon-Map-Marker"></i>
                     </div>
                     <div class="card-content">
                        <h3>{{ $total_passed_students }}</h3>
                        <p>Total Passed</p>
                     </div>
                  </div>
               </a>
            </div>
            <div class="dashboard_card">
               <a href="{{ route('students.index') }}" style="text-decoration: none!important;"> 
                  <div class="custom-card ">
                     <div class="card-icon green">
                        <i class="icon im im-icon-Map"></i>
                     </div>
                     <div class="card-content">
                        <h3>{{ $total_students - $total_passed_students }}</h3>
                        <p>Total Failed </p>
                     </div>
                  </div>
               </a>
            </div>
            <div class="dashboard_card">
               <a href="{{ route('students.students_waiting_for_chairman_approval') }}" style="text-decoration: none!important;"> 
                  <div class="custom-card ">
                     <div class="card-icon fuchsia">
                        <i class="icon im im-icon-Map"></i>
                     </div>
                     <div class="card-content">
                        <h3>{{ $waiting_for_chairman }}</h3>
                        <p>Waiting for Chairmen Approval</p>
                     </div>
                  </div>
               </a>
            </div>
            <div class="dashboard_card">
               <a href="{{ route('students.students_waiting_for_district_approval') }}" style="text-decoration: none!important;"> 
                  <div class="custom-card ">
                     <div class="card-icon aqua">
                        <i class="icon im im-icon-Map"></i>
                     </div>
                     <div class="card-content">
                        <h3>{{ $waiting_for_district }}</h3>
                        <p>Waiting for District Approval</p>
                     </div>
                  </div>
               </a>
            </div>


            <div class="dashboard_card">
               <a href="{{ route('students.index') }}" style="text-decoration: none!important;"> 
                  <div class="custom-card ">
                     <div class="card-icon orange">
                        <i class="icon im im-icon-Map"></i>
                     </div>
                     <div class="card-content">
                        <h3>{{ $generated_certificate }}</h3>
                        <p>Generated Certificate</p>
                     </div>
                  </div>
               </a>
            </div>



            <div class="col-md-12" style="padding: 8px 28px 1px 86px;">
               <div class="row" style="gap: 50px;">
                  <div class="col-md-5" style="box-shadow: 0px 0px 7px 1px #bababa;background: #ffffff;border-radius: 7px;">
                     <div style="width: 100%; max-width: 270px; margin: 30px auto;">
                        <canvas id="studentPieChart"></canvas>
                    </div>
                  </div>
                  <div class="col-md-5" style="box-shadow: 0px 0px 7px 1px #bababa;background: #ffffff;border-radius: 7px;">
                     <div style="width: 100%; max-width: 320px; margin: 30px auto;">
                        <canvas id="studentapprovalPieChart"></canvas>
                    </div>
                  </div>
                 
               </div>
            </div>
           
           
         </div>
    </section>

    <script>
      const ctx = document.getElementById('studentPieChart').getContext('2d');
  
      const studentPieChart = new Chart(ctx, {
          type: 'pie',
          data: {
              labels: ['Passed', 'Failed'],
              datasets: [{
                  label: 'Students Status',
                  data: [{{ $total_passed_students }}, {{ $total_failed_students }}],
                  backgroundColor: ['#007bff', '#dc3545'],
                  borderColor: ['#ffffff', '#ffffff'],
                  borderWidth: 2
              }]
          },
          options: {
              responsive: true,
              plugins: {
                  legend: {
                      position: 'bottom',
                      labels: {
                          font: {
                              size: 14
                          }
                      }
                  }
              }
          }
      });
  </script>
    <script>
      const ctx2 = document.getElementById('studentapprovalPieChart').getContext('2d');
  
      const studentapprovalPieChart = new Chart(ctx2, {
          type: 'pie',
          data: {
              labels: ['Waiting for Chairman', 'Waiting for District', 'Generated Certificate'],
              datasets: [{
                  label: 'Students Status',
                  data: [{{ $waiting_for_chairman }}, {{ $waiting_for_district }}, {{ $generated_certificate }}],
                  backgroundColor: ['#007bff', '#28a745', '#dc3545'],
                  borderColor: ['#ffffff', '#ffffff', '#ffffff'],
                  borderWidth: 2
              }]
          },
          options: {
              responsive: true,
              plugins: {
                  legend: {
                      position: 'bottom',
                      labels: {
                          font: {
                              size: 14
                          }
                      }
                  }
              }
          }
      });
  </script>
  

@stop

