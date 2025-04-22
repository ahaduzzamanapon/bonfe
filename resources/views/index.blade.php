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
        <h1>
            Dashboard
           
        </h1>
       
    </section>
    <style>
        
        .custom-card {
    display: flex;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    border-radius: 10px;
    overflow: hidden;
    margin-bottom: 20px;
    transition: transform 0.3s ease;
    background-color: #fff;
    min-height: 90px;
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
      margin: 10px !important;
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
    <section class="content">
        <div class="dashboard_cards_header">
            <div class="dashboard_card">
               <a href="#" style="text-decoration: none!important;">
                  <div class="custom-card">
                     <div class="card-icon teal">
                        <i class="icon im im-icon-User"></i>
                     </div>
                     <div class="card-content">
                        <h3>1</h3>
                        <p>Total Students</p>
                     </div>
                  </div>
               </a>
            </div>
            <div class="dashboard_card">
               <a href="#" style="text-decoration: none!important;">
                  <div class="custom-card">
                     <div class="card-icon blue">
                        <i class="icon im im-icon-Map-Marker"></i>
                     </div>
                     <div class="card-content">
                        <h3>64</h3>
                        <p>Total Passed</p>
                     </div>
                  </div>
               </a>
            </div>
            <div class="dashboard_card">
               <a href="#" style="text-decoration: none!important;"> 
                  <div class="custom-card ">
                     <div class="card-icon green">
                        <i class="icon im im-icon-Map"></i>
                     </div>
                     <div class="card-content">
                        <h3>64</h3>
                        <p>Total Failed </p>
                     </div>
                  </div>
               </a>
            </div>
         </div>
    </section>
@stop

