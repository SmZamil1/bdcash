<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, viewport-fit=cover" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="theme-color" content="#000000">
    
    @include('partials.seo')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <link rel="shortcut icon" type="image/png" href="{{ siteFavicon() }}">
    <link rel="apple-touch-icon" href="{{ siteLogo() }}">
    
    <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
    <title>{{ gs()->siteName(__($pageTitle ?? '')) }}</title>
    
    <link href="{{ asset('assets/theme/theme3/frontend/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/theme/theme3/frontend/vendor/boxicons/css/boxicons.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/theme/theme3/frontend/vendor/glightbox/css/glightbox.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/theme/theme3/frontend/vendor/remixicon/remixicon.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/theme/theme3/frontend/vendor/swiper/swiper-bundle.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/theme/theme3/frontend/css/selectric.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/theme/theme3/frontend/css/animate.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/theme/theme3/frontend/css/font-awsome.min.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/@flaticon/flaticon-uicons@3.3.1/css/all/all.min.css" rel="stylesheet">
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/3.0.0/uicons-regular-rounded/css/uicons-regular-rounded.css'>
    <link rel="stylesheet" href="{{ asset('assets/theme/theme3/frontend/css/iziToast.min.css') }}">
    <link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400..900&display=swap" rel="stylesheet">

    <!-- Material icons-->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <style>
        .modal-backdrop.show {
            display: none !important;
        }
    </style>

    <link rel="stylesheet" href="{{ asset('assets/admin/build/css/intlTelInput.css') }}">
    <!-- Owl -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/owl.carousel@2.3.4/dist/assets/owl.carousel.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <style>
        .owl-dots { display: none; }
        .imgEdit { width: 35px !important; height: 35px !important; margin-left: -3.5rem !important; margin-bottom: 1.5rem !important; }
        .intl-tel-input { position: relative; display: inline-block; width: 100% !important; }
        .profile-thumb { position: relative; width: 11.25rem; height: 11.25rem; border-radius: 15px; display: inline-flex; }
        .profile-thumb .profilePicPreview { width: 11.25rem; height: 11.25rem; border-radius: 15px; display: block; border: 3px solid #ffffff; box-shadow: 0 0 5px 0 rgba(0, 0, 0, 0.25); background-size: cover; background-position: center; }
        .profile-thumb .avatar-edit { position: absolute; right: -15px; bottom: -20px; }
        .profile-thumb .avatar-edit label { width: 45px; height: 45px; background-color: #37ebec; border-radius: 50%; text-align: center; line-height: 45px; border: 2px solid #ffffff; font-size: 18px; cursor: pointer; color: #000000; }
        .custom_preload { position: fixed; top: 0; bottom: 0; left: 0; right: 0; z-index: 9999999999; }
        .loderCustom { position: absolute; top: 50%; left: 50%; transform: translate(-50% -50%) }
        .d-none { display: none; }

        /* Global Log/History Navigation Styling */
        .logNav.disabled {
            background-color: rgba(255, 255, 255, 0.3);
            cursor: default;
        }
    </style>

    <!-- Tailwind Css -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        orbitron: ['Orbitron', 'sans-serif'],
                    },
                    backgroundImage: {
                        'gradient-radial': 'radial-gradient(var(--tw-gradient-stops))',
                    },
                    animation: {
                        'wiggle': 'wiggle 1s ease-in-out infinite',
                    },
                    keyframes: {
                        wiggle: {
                            '0%, 100%': { transform: rotate('-3deg') },
                            '50%': { transform: rotate('3deg') },
                        }
                    }
                },
            },
        };
    </script>

    <style>
        @keyframes wiggle { 0%, 100% { transform: rotate(-3deg); } 50% { transform: rotate(3deg); } }
        .wiggle-animation { animation: wiggle 1s ease-in-out infinite; }
        @keyframes left-right-move { 0%, 100% { transform: translateX(-10px); } 50% { transform: translateX(10px); } 75% { transform: translateX(5px); } }
        .left-right-animation { animation: left-right-move 1s ease-in-out infinite; }
        @keyframes up-down-move { 0%, 100% { transform: translateY(-10px); } 50% { transform: translateY(10px); } 75% { transform: translateY(5px); } }
        .up-down-animation { animation: up-down-move 5s ease-in-out infinite; }
        @keyframes fade-in { from { opacity: 0; } to { opacity: 1; } }
        .fade-in-animation { animation: fade-in 1s ease-in-out; }
        @keyframes slide-in { from { transform: translateX(-100%); opacity: 0; } to { transform: translateX(0); opacity: 1; } }
        .slide-in-animation { animation: slide-in 1s ease-in-out; }
        @keyframes bounce { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-20px); } }
        .bounce-animation { animation: bounce 1s ease-in-out infinite; }
        @keyframes rotate { 0% { transform: rotate(0deg); } 50% { transform: rotate(180deg); } 100% { transform: rotate(360deg); } }
        .rotate-animation { animation: rotate 1s ease-in-out infinite; }
        @keyframes zoom-in { from { transform: scale(0.5); opacity: 0; } to { transform: scale(1); opacity: 1; } }
        .zoom-in-animation { animation: zoom-in 1s ease-in-out; }
    </style>

    <!-- Swiper -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

    @stack('style')
</head>

<body class="pb-20 bg-black text-white">
    
    <main id="main">
        <!-- Background Gradients -->
        <div class="fixed top-0 right-[5rem] w-[600px] h-[300px] -translate-y-1/3 translate-x-1/3 rounded-full bg-[#0b64f4] blur-[120px] pointer-events-none"></div>
        <div class="fixed top-1/2 left-1/2 w-[500px] h-[500px] -translate-x-1/2 -translate-y-1/2 rounded-full bg-[#0b64f4]/20 blur-[120px] pointer-events-none"></div>
        <div class="fixed bottom-0 left-[10rem] w-[700px] h-[200px] translate-y-1/2 -translate-x-1/2 rounded-full bg-[#0b64f4] blur-[120px] pointer-events-none"></div>

        <div class="max-w-md mx-auto p-3 relative z-10" id="appCapsule">
            
            <!-- Header -->
            @include($activeTemplate . 'partials.app_header')

            @yield('content')

        </div>

    </main>

    <!-- Bottom Nav -->
    @include($activeTemplate . 'partials.app_bottom_nav')

    <script src="{{ asset('assets/theme/theme3/frontend/js/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/templates/invester/js/main.js') }}"></script>
    <script src="{{ asset('assets/theme/theme3/frontend/js/iziToast.min.js') }}"></script>
    @include('partials.notify')
    @stack('script')
</body>
</html>

