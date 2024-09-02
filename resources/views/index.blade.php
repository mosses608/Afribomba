@extends('layout')

<x-error_login />

<x-logout_flash_mgs />

@section('content')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            background: url('https://siliconcompanies.com/assets/img/Service%20details/Logistics%20.png') no-repeat center center fixed;
            background-size: cover;
        }
        .container {
            display: flex;
            flex-direction: column; /* Stack items vertically on smaller screens */
            width: 90%;
            max-width: 1200px;
            height: auto;  /* Adjust height for responsiveness */
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .left-side {
            flex: 1;
            background: linear-gradient(to right, #4a90e2, #9013fe);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            text-align: center;
        }
        .left-side p {
            font-style: italic;
            font-weight: bold;
        }
        .right-side {
            flex: 1;
            background: white;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }
        .form-container {
            width: 100%;
            max-width: 400px;
            margin: 0 20px; /* Equal margin on both left and right sides */
        }
        h1, h2, h3 {
            margin: 0;
        }
        h1 {
            font-size: 2rem; /* Reduced font size for smaller screens */
            margin-bottom: 1rem;
        }
        h2 {
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
        }
        h3 {
            font-size: 1rem;
            color: #6b7280;
            margin-bottom: 1rem;
        }
        p {
            font-size: 0.875rem;
            color: #9ca5af;
            margin-bottom: 1.5rem;
        }
        .form-group {
            margin-bottom: 1rem;
        }
        .form-group label {
            display: block;
            font-size: 0.875rem;
            color: #4a4a4a;
            margin-bottom: 0.5rem;
        }
        .form-group input {
            width: 100%;
            padding: 0.5rem;
            border: 1px solid #d1d5db;
            border-radius: 0.375rem;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
        }
        .form-group input:focus {
            border-color: #4a90e2;
            box-shadow: 0 0 0 3px rgba(74, 144, 226, 0.3);
        }
        .form-group .error {
            color: #e53e3e;
            font-size: 0.875rem;
        }
        .button {
            width: 100%;
            padding: 0.75rem;
            background-color: #4a90e2;
            color: white;
            border: none;
            border-radius: 0.375rem;
            cursor: pointer;
            font-size: 1rem;
            margin-top: 1rem;
        }
        .button:hover {
            background-color: #357abd;
        }

        /* Media Queries for responsiveness */
        @media (min-width: 768px) {
            .container {
                margin-top:5%;
                flex-direction: row; /* Horizontal layout for tablets and larger screens */
                height: 500px;  /* Set height to 80% of viewport height */
            }
            .left-side, .right-side {
                flex: 1;
            }
            h1 {
                font-size: 2.5rem; /* Larger font size for tablets and desktops */
            }
        }

        @media (max-width: 767px) {
            .container {
                margin-left:5%;
                flex-direction: column; /* Stack items vertically on smaller screens */
            }
            .left-side {
                padding: 1rem;
            }
            h1 {
                font-size: 2rem; /* Smaller font size for mobile screens */
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="left-side">
            <div>
                <h1>Hello   👋 Afribomba</h1>
                <p>Make sure you're registered to login</p>
            </div>
        </div>
        <div class="right-side">
            <div class="form-container">
                <h2>AFRIBOMBA WHOLESALE SHOP</h2>
                <h3>Welcome Back!</h3>
                <form action="/authenticate" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="username">Username:</label>
                        <input type="text" id="username" name="username" placeholder="Username..." autocomplete="off" required>
                        @error('username')
                        <span class="error">Username is required!</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="password">Password:</label>
                        <input type="password" id="password" name="password" placeholder="Password" minlength="8" required>
                        @error('password')
                        <span class="error">Password is required!</span>
                        @enderror
                    </div>
                    <button type="submit" class="button">Login Now</button><br><br>
                    <a href="/"><i class="fa fa-home"></i> Go Home</a>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
@endsection