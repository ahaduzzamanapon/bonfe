<!DOCTYPE html>
<html lang="en">

<head>
    <title>Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="{{ asset('img/favicon.ico') }}" />
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            background: #f0f2f5;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .login-card {
            background: #ffffff;
            padding: 30px 40px;
            border-radius: 16px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            max-width: 590px;
            width: 100%;
        }

        .login-card .logo {
            display: flex;
            flex-direction: row;
            align-items: center;
            margin-bottom: -12px;
            gap: 11px;
        }

        .login-card .logo img {
            width: 70px;
            height: 70px;
        }

       
       
        .logo-text span {
            font-size: 27px;
            font-weight: 900;
            color: black;
        }
        .form-group {
            position: relative;
            margin-bottom: 1.8rem;
        }

        .form-control {
            border: 1px solid #8dc641;
            border-radius: 10px;
            padding: 12px 12px 12px 61px;
            font-size: 1rem;
            background-color: #ffffff;
            color: #333;
        }

        .form-control::placeholder {
            color: #777;
        }

        .form-control:focus {
            border-color: #8dc641;
            background-color: #fff;
            box-shadow: none;
        }

        .input-icon {
            position: absolute;
            left: 0px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 1.2rem;
            color: #fff;
            background-color: #8dc641;
            border: 1px solid #8dc641;
            border-radius: 10px 0px 0px 10px;
            padding: 5px 0px 0px 0px;
            width: 45px;
            height: 52px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .btn-primary {
            background: #8dc641;
            border: none;
            padding: 0.75rem;
            font-weight: 600;
            border-radius: 12px;
            width: 100%;
            transition: background-color 0.3s ease;
        }

        .btn-primary:hover {
            background: #8dc641;
        }

        .invalid-feedback {
            font-size: 0.85rem;
            margin-top: 0.25rem;
            color: #dc3545;
        }

        .login-footer {
    font-size: 0.75rem;
    margin-top: 2rem;
    padding-top: 1rem;
    border-top: 1px solid #eee;
    flex-wrap: wrap;
    gap: 10px;
    color: #777;
    /* text-align: -webkit-center; */
    display: flex;
    flex-direction: row;
    justify-content: space-between;
}
        
    </style>
</head>

<body>
    <div class="login-card">
        <div class="logo text-center">
            <img src="{{ asset('images/Picture1.jpg') }}" alt="Logo">
            <div class="logo-text">
                <span>Board of Non-Formal Education</span>
            </div>
        </div>
        <div style="justify-self: center;margin-bottom: 15px;padding: 0px 38px 0px 38px;cursor: pointer;">
           <span class="text-center" style="font-weight: 650;font-size: 19px;">Sign In</span>
        </div>

        <form action="{{ route('login') }}" method="POST" class="sign_validator">
            @csrf

            <div class="form-group">
                <i class="bi bi-envelope input-icon"></i>
                <input type="email" id="email" name="email"
                    class="form-control @error('email') is-invalid @enderror"
                    placeholder="Email address" value="{{ old('email') }}" required />
                @error('email')
                    <span class="invalid-feedback d-block" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="form-group">
                <i class="bi bi-lock input-icon"></i>
                <input type="password" id="password" name="password"
                    class="form-control @error('password') is-invalid @enderror"
                    placeholder="Password" required />
                @error('password')
                    <span class="invalid-feedback d-block" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-primary">{{ __('Sign In') }}</button>
            </div>
        </form>

        <div class="login-footer">
            <span>Copyright © 2025 <strong>Board of Non-Formal Education</strong></span>
            <span>Developed by: <strong><a href="https://mysoftheaven.com">Mysoftheaven (BD) Ltd.</a></strong></span>
        </div>
    </div>
</body>

</html>
