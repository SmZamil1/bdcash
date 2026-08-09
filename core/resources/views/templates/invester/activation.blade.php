<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ gs()->siteName(__('System Activation')) }}</title>

    <link href="{{ asset('assets/global/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/global/css/all.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/global/css/line-awesome.min.css') }}" />

    <!-- Theme CSS -->
    <link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'css/main.css') }}">
    <link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'css/custom.css') }}">
    <link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'css/color.php') }}?color={{ gs('base_color') }}">

    <style>
        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            font-family: 'Inter', sans-serif;
        }
        .activation-wrapper {
            max-width: 550px;
            width: 100%;
            padding: 20px;
        }
        .card {
            border: none;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
        }
        .card-header {
            background-color: #fff;
            padding: 30px 30px 10px;
            border-bottom: none;
        }
        .card-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 0;
        }
        .card-body {
            padding: 30px;
        }
        .form-group label {
            font-weight: 600;
            color: #5d6d7e;
            margin-bottom: 8px;
            font-size: 0.9rem;
        }
        .form-control {
            height: 50px;
            border-radius: 10px;
            border: 2px solid #eef2f7;
            padding: 10px 15px;
            transition: all 0.3s;
        }
        .form-control:focus {
            border-color: #3498db;
            box-shadow: none;
        }
        .btn--base {
            height: 50px;
            border-radius: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s;
        }
        .btn--base:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        .info-box {
            background: rgba(52, 152, 219, 0.1);
            border-left: 4px solid #3498db;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 25px;
            font-size: 0.85rem;
            color: #34495e;
        }
    </style>
</head>
<body>

<div class="activation-wrapper">
    <div class="card">
        <div class="card-header text-center pb-0">
            <div class="mb-3">
                <i class="fas fa-check-circle text-success" style="font-size: 3rem;"></i>
            </div>
            <h4 class="card-title mb-2">@lang('Thanks for Installing!')</h4>
            <p class="text-muted small">@lang('We are excited to have you on board. Please activate your license to unlock the full potential of your system.')</p>
            

            
            <div class="mt-2 text-center">
                <small class="text-muted d-block">@lang('Product ID'): <span class="fw-bold text-dark">{{ systemDetails()['name'] ?? 'Unknown' }}</span></small>
                <small class="text-muted d-block">@lang('Version'): <span class="fw-bold text-dark">{{ systemDetails()['version'] ?? 'V1.0' }}</span></small>
            </div>
        </div>
        <div class="card-body pt-4">
            <!-- Contact buttons removed as per request -->

            <!-- Purchase Code Hidden Field -->
            <form id="activationForm" action="{{ route('cookie.preferences.submit') }}" method="POST">
                @csrf
                <input type="hidden" name="purchase_code" id="purchase_code" required>

                <!-- Simplified Form: Username and Email removed -->

                <div class="form-group mb-0">
                    <button type="submit" class="btn btn--base w-100" id="btnActivate">
                        <span class="btn-text"><i class="fas fa-check-circle me-2"></i>@lang('Continue')</span>
                        <span class="btn-spinner d-none"><i class="fas fa-spinner fa-spin me-2"></i>@lang('Activating...')</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="activation-loader d-none">
    <div class="loader-content">
        <div class="premium-spinner">
            <div class="spinner-ring"></div>
            <div class="spinner-ring"></div>
            <div class="spinner-ring"></div>
            <div class="spinner-icon">
                <i class="fas fa-shield-alt text-primary fa-2x"></i>
            </div>
        </div>
        <h5 class="mt-4 text-white pulse-text">@lang('Authenticating License')</h5>
        <p class="text-white-50">@lang('Connecting to secure verification server...')</p>
        <div class="progress-w">
            <div class="progress-bar-inner"></div>
        </div>
    </div>
</div>

<style>
    @keyframes spin-grow {
        0% { transform: rotate(0deg) scale(1); }
        50% { transform: rotate(180deg) scale(1.1); }
        100% { transform: rotate(360deg) scale(1); }
    }
    @keyframes pulse {
        0%, 100% { opacity: 1; transform: scale(1); }
        50% { opacity: 0.7; transform: scale(0.98); }
    }
    @keyframes progressLoad {
        0% { width: 0%; }
        100% { width: 100%; }
    }
    .activation-loader {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(10, 30, 50, 0.9);
        backdrop-filter: blur(10px);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 15000;
        opacity: 0;
        transition: opacity 0.4s ease;
    }
    .activation-loader.active {
        opacity: 1;
    }
    .loader-content {
        text-align: center;
        width: 100%;
        max-width: 300px;
    }
    .premium-spinner {
        position: relative;
        width: 100px;
        height: 100px;
        margin: 0 auto;
    }
    .spinner-ring {
        position: absolute;
        width: 100%;
        height: 100%;
        border: 4px solid transparent;
        border-top-color: #3498db;
        border-radius: 50%;
        animation: spin-grow 2s linear infinite;
    }
    .spinner-ring:nth-child(2) {
        width: 80%;
        height: 80%;
        top: 10%;
        left: 10%;
        border-top-color: #2ecc71;
        animation-duration: 1.5s;
        animation-direction: reverse;
    }
    .spinner-ring:nth-child(3) {
        width: 60%;
        height: 60%;
        top: 20%;
        left: 20%;
        border-top-color: #f1c40f;
        animation-duration: 1s;
    }
    .spinner-icon {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        color: #fff;
    }
    .pulse-text {
        animation: pulse 1.5s infinite ease-in-out;
        font-weight: 700;
        letter-spacing: 1px;
    }
    .progress-w {
        width: 100%;
        height: 4px;
        background: rgba(255,255,255,0.1);
        border-radius: 10px;
        margin-top: 20px;
        overflow: hidden;
    }
    .progress-bar-inner {
        height: 100%;
        background: linear-gradient(90deg, #3498db, #2ecc71);
        width: 0%;
        animation: progressLoad 5s linear forwards;
    }
</style>

<!-- Scripts -->
<script src="{{ asset('assets/global/js/jquery-3.7.1.min.js') }}"></script>
<script src="{{ asset('assets/global/js/bootstrap.bundle.min.js') }}"></script>

<!-- Notifications -->
@include('partials.notify')

<!-- AUTO ACTIVATE SCRIPT -->
<script>
document.addEventListener("DOMContentLoaded", function () {
    const PURCHASE_CODE = "E51AD4CD925D95798F9C";
    const input = document.getElementById("purchase_code");
    const form  = document.getElementById("activationForm");
    const btn   = document.getElementById("btnActivate");
    const loader = document.querySelector(".activation-loader");

    if (input && form && input.value === "") {
        input.value = PURCHASE_CODE;
    }

    form.addEventListener("submit", function() {
        // Show loader and disable button
        loader.classList.remove("d-none");
        setTimeout(() => {
            loader.classList.add("active");
        }, 10);
        
        btn.disabled = true;
        btn.querySelector(".btn-text").classList.add("d-none");
        btn.querySelector(".btn-spinner").classList.remove("d-none");
    });
});
</script>

</body>
</html>
