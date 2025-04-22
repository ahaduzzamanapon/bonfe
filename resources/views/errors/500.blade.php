@extends('layouts.default')

{{-- Page title --}}
@section('title')
    Error @parent
@stop

{{-- Page level styles --}}
@section('header_styles')
    <link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">
    <style>
        .error-page {
            text-align: center;
            padding: 100px 20px;
            color: #333;
        }

        .error-code {
            font-size: 120px;
            font-weight: 700;
            color: #3819e7;
        }

        .error-message {
            font-size: 24px;
            margin-top: 20px;
        }

        .error-description {
            font-size: 16px;
            margin-top: 10px;
            color: #666;
        }

        .home-button {
            margin-top: 30px;
        }

        .home-button a {
            background-color: #3819e7;
            color: white;
            padding: 12px 25px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }

        .home-button a:hover {
            background-color: #2c14c0;
        }
    </style>
@stop

{{-- Page content --}}
@section('content')
<section class="content error-page">
    <div class="error-code">404</div>
    <div class="error-message">Oops! Page Not Found</div>
    <div class="error-description">
        The page you're looking for might have been removed,<br>
        had its name changed, or is temporarily unavailable.
    </div>
    <div class="home-button">
        <a href="{{ url('/') }}">Back to Home</a>
    </div>
</section>
@stop
