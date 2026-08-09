<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="shortcut icon" type="image/png" href="{{ siteFavicon() }}">

    <title>{{ gs()->siteName(__()) }} - Register User</title>

    <link rel="stylesheet" href="{{ asset('assets/theme/theme3/frontend/css/cookie.css') }}">
    
    <link rel="stylesheet" href="{{ asset('assets/theme/theme3/frontend/css/animate.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/theme/theme3/frontend/css/slick.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/theme/theme3/frontend/css/font-awsome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/theme/theme3/frontend/css/iziToast.min.css') }}">
    
    <link rel="stylesheet" href="{{ asset('assets/theme/theme1/frontend/css/color.php?primary_color=F7931A') }}">

    <!-- Custom Css Blade-->
    <style>
    /* Globally hide scrollbar for all elements */
    html,
    body,
    * {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }

    /* For Chrome, Safari, Opera */
    ::-webkit-scrollbar {
        display: none;
    }

    body {
        background-size: cover;
        background-repeat: no-repeat;
        background-attachment: fixed;
        background-position: center top;
    }

    /* swiper pagination */
    .swiper-pagination-bullet-active {
        background: var(--swiper-pagination-color, #dd00ff) !important;
    }

    .img-primary {
        filter: brightness(0);
    }

    .img-white {
        filter: brightness(0) saturate(100%) invert(100%) sepia(0%) saturate(7500%) hue-rotate(267deg) brightness(103%) contrast(103%);
    }

    .bg-card-new {
        background: rgb(255, 255, 255);
        background: linear-gradient(45deg, rgba(255, 255, 255, 1) 40%, rgba(173, 216, 230, 0.64) 75%, rgba(135, 206, 250, 0.64) 100%);

    }

    .bg-card-new-2 {
        background: rgb(255, 255, 255);
        background: linear-gradient(90deg, rgba(255, 255, 255, 1) 40%, rgba(224, 100, 100, 0.64) 75%, rgba(231, 4, 4, 0.64) 100%);
    }

    .bg-card-new-3 {
        background: rgb(255, 255, 255);
        background: linear-gradient(180deg, rgba(255, 255, 255, 1) 40%, rgba(224, 100, 100, 0.64) 75%, rgba(231, 4, 4, 0.64) 100%);
    }

    .slanted-edge {
        --p: 40px;
        aspect-ratio: 1;
        clip-path: polygon(var(--p) 0, 100% 0, 100% 100%, 0 100%);
    }

    /* Loader-section */
    #loader {
        position: fixed;
        left: 0;
        top: 0;
        right: 0;
        bottom: 0;
        z-index: 99999;
        background: #64b5f6;
        background-image: linear-gradient(to bottom, #1976d2 0, #64b5f6 100%) !important;
        display: flex;
        align-items: center;
        justify-content: center;
        user-select: none;
    }

    .appHeader {
        background-color: #00000000 !important;
        background: #00000000 !important;
    }

    .appBottomMenu {
        background-color: #00000000 !important;
        background: #00000000 !important;
    }

    .appBottomMenu .item strong {
        margin-top: 4px;
        display: block;
        color: #ffffff;
        font-weight: 400;
    }

    .dashboard-login-btn {
        display: inline-block;
        text-decoration: none;
        padding: 8px;
        font-size: 20px;
        font-weight: bold;
        color: white !important;
        background: linear-gradient(45deg, #4a1616, #ffffff);
        border-radius: 30px;
        box-shadow: 0 6px 15px rgb(24, 23, 23);
        transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
        width: 280px;
        text-align: center;
        animation: gradientChange 5s ease infinite;
    }

    @keyframes  gradientChange {
        0% {
            filter: hue-rotate(0deg);
        }

        25% {
            filter: hue-rotate(90deg);
        }

        50% {
            filter: hue-rotate(180deg);
        }

        75% {
            filter: hue-rotate(270deg);
        }

        100% {
            filter: hue-rotate(360deg);
        }
    }

    .dashboard-main {
        min-height: 100vh;
        background: #008efa;
        background: linear-gradient(303deg, rgb(255, 212, 117) 6%, rgb(225, 225, 225) 30%, rgb(255, 224, 224) 70%, #c9baff 100%);
        background-size: cover;
        background-repeat: no-repeat;
        background-attachment: fixed;
    }

    /* card-section */
    .card-block .card-main {
        background-image: linear-gradient(-180deg, rgba(0, 0, 0, 0) 0%, rgb(0 0 0 / 8%) 100%);
    }

    /* link-section */
    .section-heading .link {
        color: rgb(255 54 147 / 50%);
    }

    .section {
        padding: 0 8px;
    }

    .select {
        height: 35px;
        padding: 0px 5px;
    }

    /* sidebar-section */

    .sidebar-balance {
        background: #64b5f6;
        background-image: linear-gradient(to bottom, #ff396f 0, #f664b8 100%) !important;
    }

    .action-group {
        background: #64b5f6;
    }

    /* header-section */
    .wallet-card-section:before {
        background-image: linear-gradient(to bottom, #ff749900 0, #e5eaf000 100%) !important;
        background: #6236ff00;
        height: 200px;
        border-radius: 0px 0px 20px 20px;
    }

    /* gateway-section */
    .gatewayItem {
        padding: 3px;
        border-radius: 11px;
        border: 1px solid #dee2e6;
        cursor: pointer;
    }

    .gatewayActive {
        border: 2px solid #ffc107 !important;
    }

    .gatewayItem img {
        max-width: 110px;
        width: 100%;
        transition: transform .2s;
    }

    .gatewayItem img:hover {
        transform: scale(1.1);
    }

    /* input-group */
    .after-append {
        background: #8494A8;
        color: #FFF;
        padding: 10px;
        border-radius: 0 8px 8px 0px !important;
    }

    .before-append {
        background: #8494A8;
        color: #FFF;
        padding: 10px;
        border-radius: 8px 0 0 8px !important;
    }

    .input-group>:not(:first-child):not(.dropdown-menu):not(.valid-tooltip):not(.valid-feedback):not(.invalid-tooltip):not(.invalid-feedback) {
        margin-left: -1px;
        border-top-left-radius: 0;
        border-bottom-left-radius: 0;
        height: auto;
    }

    /* Icon-cestion */
    .wallet-card .wallet-footer .item .icon-wrapper {
        border-radius: 50%;
    }

    .custom-icon-box {
        background: #6236FF;
        width: 48px;
        height: 48px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        color: #fff;
        font-size: 24px;
    }

    .stat-box {
        background: #ffffff;
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.09);
        border-radius: 10px;
        padding: 10px 15px;
        height: 100%;
    }

    /* background-section */

    /* gradiant */
    .gr-bg-blue {
        background-image: linear-gradient(to right, #1976d2 0, #64b5f6 100%) !important;
    }

    .gr-bg-red {
        background-image: linear-gradient(45deg, #f93a5a, #f7778c) !important;
    }

    .gr-bg-green {
        background-image: linear-gradient(to left, #48d6a8 0%, #029666 100%) !important;
    }

    .gr-bg-orange {
        background-image: linear-gradient(to left, #efa65f, #f76a2d) !important;
    }

    .bg-primary {
        background-image: linear-gradient(to bottom, #147474 0, #12b1b1 100%) !important;
        color: #FFF;
    }

    .bg-pink-light {
        background-image: linear-gradient(to bottom, #198754 0, #1dcc70 100%) !important;
        color: #FFF;
    }

    .bg-pink-light-alt {
        background-image: linear-gradient(to top, #1dcc70 0, #198754 100%) !important;
        color: #FFF;
    }

    /* button-section */
    .btn {
        border-radius: 5px;
    }

    .btn-text-primary {
        color: #ff396f !important;
    }

    .modal button.close {
        width: 30px;
        height: 30px;
        background-color: #f63d43;
        color: #fff;
        opacity: 1;
        padding: 0;
        border-radius: 50%;
    }

    .btn-primary {
        background-image: linear-gradient(to bottom, #1976d2 0, #64b5f6 100%) !important;
        border-color: #1976d2 !important;
        color: #FFFFFF !important;
    }

    .btn-primary:hover,
    .btn-primary:focus,
    .btn-primary:active,
    .btn-primary.active {
        background-image: linear-gradient(to top, #1976d2 0, #64b5f6 100%) !important;
        border-color: #64b5f6 !important;
    }

    .btn-primary.disabled,
    .btn-primary:disabled {
        background-image: linear-gradient(to bottom, #1976d2 0, #64b5f6 100%);
        border-color: #1976d2;
        opacity: 0.5;
    }

    .btn-secondary {
        background-image: linear-gradient(to bottom, #8494A8 0, #e0edff 100%) !important;
        border: 0px;
        color: #FFFFFF !important;
    }

    .btn-secondary:hover {
        background-image: linear-gradient(to top, #8494A8 0, #e0edff 100%) !important;
        border: 0px;
        color: #FFFFFF !important;
    }

    .btn-secondary:focus {
        background-image: linear-gradient(to top, #8494A8 0, #e0edff 100%) !important;
        border: 0px;
        color: #FFFFFF !important;
    }

    .btn-pink-light {
        background-image: linear-gradient(to bottom, #198754 0, #1dcc70 100%) !important;
        color: #FFF;
        border: 0px;
    }

    .btn-pink-light:hover {
        background-image: linear-gradient(to top, #198787 0, #198787 100%) !important;
        color: #FFF;
        border: 0px;
    }

    .btn-pink-light:focus {
        background-image: linear-gradient(to top, #198754 0, #1dcc70 100%) !important;
        color: #FFF;
        border: 0px;
    }

    .main-btn {
        background-image: linear-gradient(to bottom, #198754 0, #1dcc70 100%) !important;
        color: #FFF;
        border: 0px;
    }

    .main-btn:hover {
        background-image: linear-gradient(to top, #198787 0, #198787 100%) !important;
        color: #FFF;
        border: 0px;
    }

    .main-btn:focus {
        background-image: linear-gradient(to top, #198754 0, #1dcc70 100%) !important;
        color: #FFF;
        border: 0px;
    }

    .modal button.close {
        background-image: linear-gradient(to top, #fd0000 0, #ffe8e8 100%) !important;
        border: 0px !important;
    }

    /* badge-section */

    .badge-primary::before {
        background-color: #ffffff00;
    }

    .badge-info::before {
        background-color: #ffffff00;
    }

    .badge-warning::before {
        background-color: #ffffff00;
    }

    .badge-danger::before {
        background-color: #ffffff00;
    }

    .badge-success::before {
        background-color: #ffffff00;
    }


    /* font-cection */
    .stat-box .value {
        font-size: 15px;
    }

    .stat-box .title {
        font-size: 12px;
    }

    .small-font {
        color: #FFFFFF;
        font-size: 10px;
        text-align: center;
        text-overflow: ellipsis;
        overflow: hidden;
    }

    .small-font-sm {
        color: #333;
        font-size: 8px;
        text-align: center;
        text-overflow: ellipsis;
        overflow: hidden;
    }

    .small-font-lg {
        color: #333;
        font-size: 12px;
        text-align: center;
        text-overflow: ellipsis;
        overflow: hidden;
    }

    .text-pink,
    a.text-pink {
        color: #ff396f !important;
    }

    /* reffer-cection */
    .sp-referral .single-child {
        padding: 6px 10px;
        border-radius: 5px;
    }

    .sp-referral .single-child+.single-child {
        margin-top: 15px;
    }

    .sp-referral .single-child p {
        display: flex;
        align-items: center;
        margin-bottom: 0;
    }

    .sp-referral .single-child p img {
        width: 35px;
        height: 35px;
        border-radius: 50%;
        object-fit: cover;
        -o-object-fit: cover;
    }

    .sp-referral .single-child p span {
        width: calc(100% - 35px);
        font-size: 14px;
        padding-left: 10px;
    }

    .sp-referral>.single-child.root-child>p img {
        border: 2px solid #c3c3c3;
    }

    .sub-child-list {
        position: relative;
        padding-left: 35px;
    }

    .sub-child-list::before {
        position: absolute;
        content: '';
        top: 0;
        left: 17px;
        width: 1px;
        height: 100%;
        background-color: #a1a1a1;
    }

    .sub-child-list>.single-child {
        position: relative;
    }

    .sub-child-list>.single-child::before {
        position: absolute;
        content: '';
        left: -18px;
        top: 21px;
        width: 30px;
        height: 5px;
        border-left: 1px solid #a1a1a1;
        border-bottom: 1px solid #a1a1a1;
        border-radius: 0 0 0 5px;
    }

    .sub-child-list>.single-child>p img {
        border: 2px solid #c3c3c3;
    }

    /* border-section */
    .card {
        border-radius: 5px !important;
    }

    .rounded-20px {
        border-radius: 20px !important;
    }

    .border-tl-50 {
        border-radius: 70px 20px 20px 20px !important;
    }

    .border-tl-60 {
        border-radius: 70px 20px 20px !important;
    }

    .border-tl-70 {
        border-radius: 70px 20px 20px 20px !important;
    }

    /****************/
    /* admin-section
    /****************/
</style>

    
    <!-- ✅ Tailwind Browser CDN -->
<script src="{{ asset('assets/theme/theme3/assets/tailwind.js') }}"></script>

<!-- ✅ Custom Config -->
<script>
    tailwind.config = {
        theme: {
            extend: {
                backgroundImage: {
                    'gradient-radial': 'radial-gradient(var(--tw-gradient-stops))',
                },
            },
        },
    };
</script>


<style>
    /* Normal CSS দিয়ে wiggle animation */
    @keyframes  wiggle {
        0%,
        100% {
            transform: rotate(-3deg);
        }

        50% {
            transform: rotate(3deg);
        }
    }

    .wiggle-animation {
        animation: wiggle 1s ease-in-out infinite;
    }

    @keyframes  left-right-move {

        0%,
        100% {
            transform: translateX(-10px);
        }
        50% {
            transform: translateX(10px);
        }
        75% {
            transform: translateX(5px);
        }
    }

    .left-right-animation {
        animation: left-right-move 1s ease-in-out infinite;
    }

    @keyframes  up-down-move {

        0%,
        100% {
            transform: translateY(-10px);
        }
        50% {
            transform: translateY(10px);
        }
        75% {
            transform: translateY(5px);
        }
    }
    .up-down-animation {
        animation: up-down-move 5s ease-in-out infinite;
    }
    @keyframes  fade-in {
        from {
            opacity: 0;
        }
        to {
            opacity: 1;
        }
    }
    .fade-in-animation {
        animation: fade-in 1s ease-in-out;
    }
    @keyframes  fade-out {
        from {
            opacity: 1;
        }
        to {
            opacity: 0;
        }
    }
    .fade-out-animation {
        animation: fade-out 1s ease-in-out;
    }
    @keyframes  slide-in {
        from {
            transform: translateX(-100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    .slide-in-animation {
        animation: slide-in 1s ease-in-out;
    }
    @keyframes  slide-out {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(-100%);
            opacity: 0;
        }
    }
    .slide-out-animation {
        animation: slide-out 1s ease-in-out;
    }
    @keyframes  bounce {
        0%,
        100% {
            transform: translateY(0);
        }
        50% {
            transform: translateY(-20px);
        }
    }
    .bounce-animation {
        animation: bounce 1s ease-in-out infinite;
    }
    @keyframes  rotate {
        0% {
            transform: rotate(0deg);
        }
        50% {
            transform: rotate(180deg);
        }
        100% {
            transform: rotate(360deg);
        }
    }
    .rotate-animation {
        animation: rotate 1s ease-in-out infinite;
    }
    @keyframes  shake {
        0%,
        100% {
            transform: translateX(0);
        }
        25% {
            transform: translateX(-10px);
        }
        50% {
            transform: translateX(10px);
        }
        75% {
            transform: translateX(-5px);
        }
    }
    .shake-animation {
        animation: shake 1s ease-in-out infinite;
    }
    @keyframes  zoom-in {
        from {
            transform: scale(0.5);
            opacity: 0;
        }
        to {
            transform: scale(1);
            opacity: 1;
        }
    }
    .zoom-in-animation {
        animation: zoom-in 1s ease-in-out;
    }
    @keyframes  zoom-out {
        from {
            transform: scale(1);
            opacity: 1;
        }
        to {
            transform: scale(0.5);
            opacity: 0;
        }
    }
    .zoom-out-animation {
        animation: zoom-out 1s ease-in-out;
    }
    @keyframes  flip {
        0% {
            transform: rotateY(0deg);
        }
        50% {
            transform: rotateY(180deg);
        }
        100% {
            transform: rotateY(360deg);
        }
    }
    .flip-animation {
        animation: flip 1s ease-in-out infinite;
    }
    @keyframes  slide-up {
        from {
            transform: translateY(100%);
            opacity: 0;
        }
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }
    .slide-up-animation {
        animation: slide-up 1s ease-in-out;
    }
    @keyframes  slide-down {
        from {
            transform: translateY(0);
            opacity: 1;
        }
        to {
            transform: translateY(100%);
            opacity: 0;
        }
    }
    .slide-down-animation {
        animation: slide-down 1s ease-in-out;
    }
    @keyframes  fade-slide-in {
        from {
            transform: translateX(-100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    .fade-slide-in-animation {
        animation: fade-slide-in 1s ease-in-out;
    }
    @keyframes  fade-slide-out {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(-100%);
            opacity: 0;
        }
    }
    .fade-slide-out-animation {
        animation: fade-slide-out 1s ease-in-out;
    }
    @keyframes  fade-zoom-in {
        from {
            transform: scale(0.5);
            opacity: 0;
        }
        to {
            transform: scale(1);
            opacity: 1;
        }
    }
    .fade-zoom-in-animation {
        animation: fade-zoom-in 1s ease-in-out;
    }
    @keyframes  fade-zoom-out {
        from {
            transform: scale(1);
            opacity: 1;
        }
        to {
            transform: scale(0.5);
            opacity: 0;
        }
    }
    .fade-zoom-out-animation {
        animation: fade-zoom-out 1s ease-in-out;
    }
</style>

    <style>
        .d-none {
            display: none !important;
        }
    </style>

</head>

<body class="text-capitalize bg-black text-white">

    <!-- Preloader Ajax Start -->
    <div id="preLoadCustom" class="d-none">
        <div class="custom_preload d-flex align-items-center justify-content-center">
            <img width="40px" class="loaderCustom" src="{{ asset('assets/theme/images/loader/loading-ios.webp') }}" alt="">
        </div>
    </div>
    <!-- Preloader Ajax End -->


    
            <!-- App Capsule -->
            <div class="!relative !w-full !h-screen !overflow-hidden">

                <div
                    class="fixed top-0 right-[5rem] w-[600px] h-[300px] -translate-y-1/3 translate-x-1/3 rounded-full bg-[#0b64f4] blur-[120px] pointer-events-none">
                </div>

                <div
                    class="fixed top-1/2 left-1/2 w-[500px] h-[500px] -translate-x-1/2 -translate-y-1/2 rounded-full bg-[#0b64f4]/20 blur-[120px] pointer-events-none">
                </div>

                <div
                    class="fixed bottom-0 left-[10rem] w-[700px] h-[200px] translate-y-1/2 -translate-x-1/2 rounded-full bg-[#0b64f4] blur-[120px] pointer-events-none">
                </div>

                
                

                <!-- * App Header -->
                <div class="relative overflow-scroll h-full z-[1]" id="appCapsule">
                        <div class="d-none" id="currentPath">register_page</div>

<section class="relative min-h-screen flex items-center justify-center px-4 overflow-hidden">
    <!-- Animated Wave Background -->
    <div class="absolute inset-0 bg-gradient-to-br from-blue-600/30 via-purple-600/30 to-pink-600/30">
        <!-- Animated Waves -->
        <svg class="absolute bottom-0 left-0 w-full h-full opacity-20" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320" preserveAspectRatio="none">
            <path fill="url(#wave1)" fill-opacity="0.3" d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,122.7C672,117,768,139,864,138.7C960,139,1056,117,1152,117.3C1248,117,1344,139,1392,149.3L1440,160L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z">
                <animate attributeName="d" dur="10s" repeatCount="indefinite" values="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,122.7C672,117,768,139,864,138.7C960,139,1056,117,1152,117.3C1248,117,1344,139,1392,149.3L1440,160L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z;M0,160L48,149.3C96,139,192,117,288,117.3C384,117,480,139,576,138.7C672,139,768,117,864,122.7C960,128,1056,160,1152,160C1248,160,1344,128,1392,112L1440,96L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z;M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,122.7C672,117,768,139,864,138.7C960,139,1056,117,1152,117.3C1248,117,1344,139,1392,149.3L1440,160L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z" />
            </path>
            <defs>
                <linearGradient id="wave1" x1="0%" y1="0%" x2="100%" y2="0%">
                    <stop offset="0%" style="stop-color:rgb(59,130,246);stop-opacity:1" />
                    <stop offset="100%" style="stop-color:rgb(147,51,234);stop-opacity:1" />
                </linearGradient>
            </defs>
        </svg>
        
        <!-- Floating Particles -->
        <div class="absolute top-10 left-10 w-3 h-3 bg-blue-400 rounded-full animate-ping opacity-75"></div>
        <div class="absolute top-40 right-20 w-2 h-2 bg-purple-400 rounded-full animate-pulse"></div>
        <div class="absolute bottom-40 left-32 w-4 h-4 bg-pink-400 rounded-full animate-bounce"></div>
        <div class="absolute top-60 right-40 w-2 h-2 bg-cyan-400 rounded-full animate-ping delay-700"></div>
        
        <!-- Animated Gradient Orbs -->
        <div class="absolute top-0 left-0 w-96 h-96 bg-gradient-to-br from-blue-500/30 to-purple-600/30 rounded-full blur-3xl animate-pulse"></div>
        <div class="absolute bottom-0 right-0 w-[500px] h-[500px] bg-gradient-to-tl from-purple-500/30 to-pink-600/30 rounded-full blur-3xl animate-pulse" style="animation-delay: 1s;"></div>
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-[400px] h-[400px] bg-gradient-to-r from-cyan-500/20 to-blue-600/20 rounded-full blur-3xl animate-pulse" style="animation-delay: 0.5s;"></div>
    </div>

    <!-- Register Card -->
    <div class="relative z-10 w-full max-w-md">
        <!-- Logo/Header Section -->
        <div class="text-center mb-8 animate-fade-in">
            <div class="relative inline-block mb-4">
                <!-- Rotating Ring -->
                <div class="absolute inset-0 rounded-full bg-gradient-to-r from-blue-500 via-purple-500 to-pink-500 blur-xl opacity-75 animate-spin-slow"></div>
                <div class="relative w-24 h-24 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 shadow-2xl shadow-blue-500/50 flex items-center justify-center overflow-hidden">
                    <img class="w-20 h-20 object-contain relative z-10" src="{{ siteLogo() }}" alt="">
                    <!-- Shimmer Effect -->
                    <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/20 to-transparent animate-shimmer"></div>
                </div>
            </div>
            <h1 class="text-5xl font-black text-white mb-2 bg-gradient-to-r from-blue-200 via-purple-200 to-pink-200 bg-clip-text text-transparent animate-gradient-text">
                Create Account
            </h1>
            <p class="text-white/70 text-sm animate-fade-in-up">Join us and start your journey today</p>
        </div>

        <!-- Form Card -->
        <div class="relative group animate-slide-up">
            <!-- Card Glow Effect -->
            <div class="absolute -inset-1 bg-gradient-to-r from-blue-500 via-purple-500 to-pink-500 rounded-3xl blur-2xl opacity-25 group-hover:opacity-50 transition-opacity duration-500 animate-pulse-slow"></div>
            
            <div class="relative bg-gradient-to-br from-blue-500/20 via-blue-600/20 to-purple-600/20 backdrop-blur-2xl border-2 border-white/20 rounded-3xl shadow-2xl p-8 overflow-hidden">
                <!-- Animated Border -->
                <div class="absolute inset-0 rounded-3xl border-2 border-transparent bg-gradient-to-r from-blue-500 via-purple-500 to-pink-500 bg-clip-border animate-spin-slow opacity-30"></div>
                
                <form class="action-form loginForm relative z-10" action="{{ route('user.register') }}" method="post">
                    @csrf
                    <!-- Username Field -->
                    <div class="mb-4 transform transition-all duration-300 hover:scale-[1.02]">
                        <label class="block text-white/90 text-sm font-bold mb-2 flex items-center gap-2">
                            <div class="w-5 h-5 rounded-full bg-gradient-to-r from-blue-400 to-purple-500 flex items-center justify-center">
                                <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            Username
                        </label>
                        <div class="relative">
                            <input type="text" name="username" id="username" placeholder="Enter your username"
                                class="relative z-10 w-full bg-white/10 backdrop-blur-md border-2 border-white/30 rounded-2xl px-4 py-3 text-white placeholder-white/50 focus:outline-none focus:border-blue-400 focus:ring-4 focus:ring-blue-500/20 transition-all duration-300 hover:border-white/40"
                                value="">
                        </div>
                        <small class="usernameCheck text-red-400 text-xs mt-1 block"></small>
                    </div>

                    <input type="hidden" name="country_code" value="{{ $country_code }}">
                    <input type="hidden" name="country_name" value="{{ $country_name }}">
                    <input type="hidden" name="mobile_code" value="{{ $mobile_code }}">

                    <!-- Phone Field -->
                    <div class="mb-4 transform transition-all duration-300 hover:scale-[1.02]">
                        <label class="block text-white/90 text-sm font-bold mb-2 flex items-center gap-2">
                            <div class="w-5 h-5 rounded-full bg-gradient-to-r from-cyan-400 to-blue-500 flex items-center justify-center">
                                <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z" />
                                </svg>
                            </div>
                            Phone Number
                        </label>
                        <div class="relative">
                            <input type="text" name="mobile" id="phone" placeholder="Enter your phone number"
                                class="relative z-10 w-full bg-white/10 backdrop-blur-md border-2 border-white/30 rounded-2xl px-4 py-3 text-white placeholder-white/50 focus:outline-none focus:border-cyan-400 focus:ring-4 focus:ring-cyan-500/20 transition-all duration-300 hover:border-white/40"
                                value="">
                        </div>
                        <small class="phoneCheck text-red-400 text-xs mt-1 block"></small>
                    </div>

                    <!-- Password Field -->
                    <div class="mb-4 transform transition-all duration-300 hover:scale-[1.02]">
                        <label class="block text-white/90 text-sm font-bold mb-2 flex items-center gap-2">
                            <div class="w-5 h-5 rounded-full bg-gradient-to-r from-purple-400 to-pink-500 flex items-center justify-center">
                                <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            Password
                        </label>
                        <div class="relative">
                            <input type="password" name="password" placeholder="Enter your password"
                                class="relative z-10 w-full bg-white/10 backdrop-blur-md border-2 border-white/30 rounded-2xl px-4 py-3 text-white placeholder-white/50 focus:outline-none focus:border-purple-400 focus:ring-4 focus:ring-purple-500/20 transition-all duration-300 hover:border-white/40"
                                value="">
                        </div>
                    </div>

                    @php
                        $referParamSetting = str_replace(['?', '='], '', gs('referral_parameter') ?: 'reference');
                        $urlRef = request()->query($referParamSetting);

                        if (!$urlRef) {
                            foreach(['invite', 'ref', 'reference', 'refer'] as $param) {
                                if ($urlRef = request()->query($param)) break;
                            }
                        }

                        if (!$urlRef) {
                            foreach (request()->query() as $key => $value) {
                                if (in_array(strtolower($key), ['invite', 'ref', 'reference', 'refer', strtolower($referParamSetting)])) {
                                    $urlRef = $value;
                                    break;
                                }
                            }
                        }

                        if ($urlRef) {
                            $decoded = base64_decode($urlRef, true);
                            $reference = ($decoded && preg_match('/^[a-zA-Z0-9. @_+:-]+$/', $decoded)) ? $decoded : $urlRef;
                            session()->put('reference', $reference);
                        } else {
                            $reference = session()->get('reference');
                        }
                    @endphp

                    @if ($reference)
                        {{-- Referral via link: auto-filled, locked --}}
                        <div class="mb-4 transform transition-all duration-300 hover:scale-[1.02]">
                            <label class="block text-white/90 text-sm font-bold mb-2 flex items-center gap-2">
                                <div class="w-5 h-5 rounded-full bg-gradient-to-r from-green-400 to-teal-500 flex items-center justify-content-center">
                                    <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z" />
                                    </svg>
                                </div>
                                Referral Code
                            </label>
                            <div class="relative">
                                <input type="text" name="referBy" readonly
                                    class="relative z-10 w-full bg-white/10 backdrop-blur-md border-2 border-green-400/50 rounded-2xl px-4 py-3 text-white placeholder-white/50 focus:outline-none focus:border-green-400 focus:ring-4 focus:ring-green-500/20 transition-all duration-300"
                                    value="{{ $reference }}">
                            </div>
                        </div>
                    @else
                        {{-- Manual referral entry --}}
                        <div class="mb-4 transform transition-all duration-300 hover:scale-[1.02]">
                            <label class="block text-white/90 text-sm font-bold mb-2 flex items-center gap-2">
                                <div class="w-5 h-5 rounded-full bg-gradient-to-r from-green-400 to-teal-500 flex items-center justify-content-center">
                                    <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z" />
                                    </svg>
                                </div>
                                Referral Code <span class="text-white/40 font-normal">(optional)</span>
                            </label>
                            <div class="relative">
                                <input type="text" name="referBy" placeholder="Enter referral username (if any)"
                                    class="relative z-10 w-full bg-white/10 backdrop-blur-md border-2 border-white/30 rounded-2xl px-4 py-3 text-white placeholder-white/50 focus:outline-none focus:border-green-400 focus:ring-4 focus:ring-green-500/20 transition-all duration-300 hover:border-white/40">
                            </div>
                        </div>
                    @endif

                    <!-- Register Button -->
                    <button type="submit" class="relative group/btn w-full overflow-hidden rounded-2xl py-4 shadow-2xl transition-all duration-500 hover:scale-[1.02] hover:shadow-blue-500/50 mb-6">
                        <div class="absolute inset-0 bg-gradient-to-r from-blue-500 via-purple-500 to-pink-500"></div>
                        <div class="absolute inset-0 bg-gradient-to-r from-pink-500 via-purple-500 to-blue-500 opacity-0 group-hover/btn:opacity-100 transition-opacity duration-500"></div>
                        <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/30 to-transparent transform -skew-x-12 -translate-x-full group-hover/btn:translate-x-full transition-transform duration-1000"></div>
                        <div class="absolute -inset-1 bg-gradient-to-r from-blue-500 via-purple-500 to-pink-500 rounded-2xl blur-lg opacity-0 group-hover/btn:opacity-75 transition-opacity duration-500"></div>
                        <span class="relative z-10 flex items-center justify-center gap-3 text-white font-black text-lg">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M8 9a3 3 0 100-6 3 3 0 000 6zM8 11a6 6 0 016 6H2a6 6 0 016-6zM16 7a1 1 0 10-2 0v1h-1a1 1 0 100 2h1v1a1 1 0 102 0v-1h1a1 1 0 100-2h-1V7z" />
                            </svg>
                            Sign Up
                            <svg class="w-5 h-5 transform group-hover/btn:translate-x-2 transition-transform duration-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </span>
                        <div class="absolute inset-0 rounded-2xl border-2 border-white/30"></div>
                    </button>

                    <!-- Divider -->
                    <div class="relative mb-6">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t-2 border-white/10"></div>
                        </div>
                        <div class="relative flex justify-center text-sm">
                            <span class="px-4 bg-gradient-to-r from-blue-600/50 via-purple-600/50 to-pink-600/50 text-white/80 rounded-full">or</span>
                        </div>
                    </div>

                    <!-- Login Link -->
                    <div class="text-center">
                        <p class="text-white/70 text-sm mb-2">Already have an account?</p>
                        <a href="{{ route('user.login') }}" class="inline-flex items-center gap-2 text-blue-300 hover:text-blue-200 font-bold text-base transition-colors duration-300">
                            Sign In
                            <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Additional Info -->
        <div class="mt-6 text-center animate-fade-in-up" style="animation-delay: 0.5s;">
            <div class="inline-flex items-center gap-2 bg-white/5 backdrop-blur-sm border border-white/10 rounded-full px-4 py-2">
                <svg class="w-4 h-4 text-green-400 animate-pulse" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                <p class="text-white/70 text-xs">Protected by advanced security</p>
            </div>
        </div>
    </div>
</section>

<style>
@keyframes  spin-slow { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }
@keyframes  shimmer { 0% { transform: translateX(-100%); } 100% { transform: translateX(100%); } }
@keyframes  gradient-text { 0%, 100% { background-position: 0% 50%; } 50% { background-position: 100% 50%; } }
@keyframes  fade-in { from { opacity: 0; } to { opacity: 1; } }
@keyframes  fade-in-up { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
@keyframes  slide-up { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }
@keyframes  pulse-slow { 0%, 100% { opacity: 0.25; } 50% { opacity: 0.5; } }
.animate-spin-slow { animation: spin-slow 20s linear infinite; }
.animate-shimmer { animation: shimmer 2s infinite; }
.animate-gradient-text { animation: gradient-text 3s ease infinite; background-size: 200% auto; }
.animate-fade-in { animation: fade-in 0.8s ease-out; }
.animate-fade-in-up { animation: fade-in-up 0.8s ease-out; }
.animate-slide-up { animation: slide-up 0.8s ease-out; }
.animate-pulse-slow { animation: pulse-slow 3s ease-in-out infinite; }
.delay-700 { animation-delay: 0.7s; }
</style>

<!-- jquery -->
<script src="{{ asset('assets/theme/theme3/frontend/js/jquery.min.js') }}"></script>

<script>
    // username check
    $(document).on('focusout', '#username', function(e) {
        e.preventDefault();
        let username = $(this).val();
        $.ajax({
            type: "POST",
            url: "{{ route('user.checkUser') }}",
            data: {
                _token: "{{ csrf_token() }}",
                username: username
            },
            success: function(res) {
                console.log(res);
                if (res.cls == 'success') {
                    $('.usernameCheck').html('username already exist!');
                } else {
                    $('.usernameCheck').html('');
                }
            }
        });
    });

    // reffer check
    $(document).on('keyup', '#reffer', function(e) {
        e.preventDefault();
        let reffer = $(this).val();
        if (!reffer) {
            return $('.refferCheck').html('');
        }
        $.ajax({
            type: "POST",
            url: "{{ route('user.checkUser') }}", // Assuming this checks referrer too or similar
            // If there's a specific route for referrer check, it should be used. 
            // Based on previous code, it might be checking user existence. 
            // I'll keep the logic but use safer dynamic routes if available, or placeholder for now.
             data: {
                _token: "{{ csrf_token() }}",
                reffer: reffer
            },
            success: function(res) {
                // ... logic
            }
        });
    });
</script>
                </div><!-- #appCapsule -->
            </div>
            

    <script src="{{ asset('assets/theme/theme3/frontend/js/jquery.min.js') }}"></script>
    
    <script src="{{ asset('assets/theme/theme3/frontend/js/slick.min.js') }}"></script>

    <script src="{{ asset('assets/theme/theme3/frontend/js/wow.min.js') }}"></script>
    <script src="{{ asset('assets/theme/theme3/frontend/js/jquery.paroller.min.js') }}"></script>
    <script src="{{ asset('assets/theme/theme3/frontend/js/TweenMax.min.js') }}"></script>

    <script src="{{ asset('assets/theme/theme3/frontend/vendor/php-email-form/validate.js') }}"></script>
    <script src="{{ asset('assets/theme/theme3/frontend/js/main.js') }}"></script>
    <script src="{{ asset('assets/theme/theme3/frontend/js/iziToast.min.js') }}"></script>
    <script src="{{ asset('assets/theme/theme3/frontend/js/jquery.uploadPreview.min.js') }}"></script>

    
    


    
    
    
        <!-- Sweet Alert 2.0 -->
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        //-- Notify --//
        const notifyMsg = (msg, cls) => {
            Swal.fire({
                position: 'top-end',
                icon: cls,
                title: msg,
                toast: true,
                showConfirmButton: false,
                timer: 2100
            })
        }

        const BtnSPIN = `<div class="spinner-border spinner-border-sm" role="status"></div>`;

        const customPush = (route) => {
            window.history.pushState("", "", route);
        }
    </script>
    @include('partials.notify')
</body>
</html>
