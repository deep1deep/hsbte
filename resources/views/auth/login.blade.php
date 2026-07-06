<!DOCTYPE html>
<html>

<head>
    <title>HSBTE Training Portal</title>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>

        body {
            background: #e9eef5;
            font-family: Arial, sans-serif;
        }

        .gov-header {
            background: #0b3d91;
            color: white;
            padding: 12px 0;
        }

        .gov-header h4 {
            margin: 0;
            font-weight: bold;
        }

        .gov-subheader {
            background: #f5f7fa;
            padding: 8px;
            text-align: center;
            font-size: 14px;
            border-bottom: 1px solid #ddd;
        }

        .login-box {
            width: 420px;
            margin: auto;
            margin-top: 60px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.15);
            overflow: hidden;
        }

        .login-header {
            background: #0b3d91;
            color: white;
            padding: 15px;
            text-align: center;
        }

        .login-body {
            padding: 25px;
        }

        .form-control {
            border-radius: 4px;
            padding: 10px;
        }

        .btn-gov {
            background: #ff8c00;
            color: white;
            width: 100%;
            padding: 10px;
            font-weight: bold;
            border-radius: 4px;
        }

        .btn-gov:hover {
            background: #e67e00;
        }

        .error-box {
            background: #ffe5e5;
            color: #b30000;
            padding: 8px;
            border-radius: 4px;
            margin-bottom: 10px;
            font-size: 14px;
        }

        .footer-text {
            text-align: center;
            font-size: 12px;
            margin-top: 15px;
            color: gray;
        }

    </style>

</head>

<body>

<!-- Header -->
<div class="gov-header text-center">
    <h4>Haryana State Board of Technical Education</h4>
</div>

<div class="gov-subheader">
     Skill Development & Training | HSBTE Training System
</div>

<!-- Login Box -->
<div class="login-box">

    <div class="login-header">
        <h5>Login to Training Portal</h5>
    </div>

    <div class="login-body">

        {{-- ERROR FIX (Laravel way) --}}
        @if(session('error'))
            <div class="error-box">
                {{ session('error') }}
            </div>
        @endif

        <form method="POST" >
            @csrf

            <label class="mb-1">Email Address</label>
            <input type="email" name="email" class="form-control mb-3" required>

            <label class="mb-1">Password</label>
            <input type="password" name="password" class="form-control mb-3" required>

            <button type="submit" class="btn btn-gov">
                Login
            </button>
        </form>

        <div class="footer-text">
            © 2026 HSBTE Training Portal | Government Project
        </div>

    </div>

</div>

</body>

</html>