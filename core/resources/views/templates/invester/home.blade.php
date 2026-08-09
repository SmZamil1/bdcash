@extends($activeTemplate . 'layouts.master')

@section('content')
    @include($activeTemplate . 'home.banner')

    <!-- Main Content -->
    <div class="pt-6">
        <!-- CTA Buttons -->
        @include($activeTemplate . 'home.actions')

        <!-- Tutorial Section -->
        @include($activeTemplate . 'home.tutorial')

        <!-- FBR Section -->
        @include($activeTemplate . 'home.fbr')

        <!-- Investment Plans -->
        @include($activeTemplate . 'home.plans')
    </div>

    <!-- Bottom Glow Effect -->
    <div class="absolute bottom-0 left-0 right-0 h-32 bg-gradient-to-t from-purple-900/50 via-blue-900/30 to-transparent pointer-events-none">
    </div>
@endsection

@push('style')
    <style>
        /* Extra styles from home.blade.php */
        @keyframes fade-out { from { opacity: 1; } to { opacity: 0; } }
        .fade-out-animation { animation: fade-out 1s ease-in-out; }
        @keyframes slide-out { from { transform: translateX(0); opacity: 1; } to { transform: translateX(-100%); opacity: 0; } }
        .slide-out-animation { animation: slide-out 1s ease-in-out; }
        @keyframes shake { 0%, 100% { transform: translateX(0); } 25% { transform: translateX(-10px); } 50% { transform: translateX(10px); } 75% { transform: translateX(-5px); } }
        .shake-animation { animation: shake 1s ease-in-out infinite; }
        @keyframes zoom-out { from { transform: scale(1); opacity: 1; } to { transform: scale(0.5); opacity: 0; } }
        .zoom-out-animation { animation: zoom-out 1s ease-in-out; }
        @keyframes flip { 0% { transform: rotateY(0deg); } 50% { transform: rotateY(180deg); } 100% { transform: rotateY(360deg); } }
        .flip-animation { animation: flip 1s ease-in-out infinite; }
        @keyframes slide-up { from { transform: translateY(100%); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
        .slide-up-animation { animation: slide-up 1s ease-in-out; }
        @keyframes slide-down { from { transform: translateY(0); opacity: 1; } to { transform: translateY(100%); opacity: 0; } }
        .slide-down-animation { animation: slide-down 1s ease-in-out; }
        @keyframes fade-slide-in { from { transform: translateX(-100%); opacity: 0; } to { transform: translateX(0); opacity: 1; } }
        .fade-slide-in-animation { animation: fade-slide-in 1s ease-in-out; }
        @keyframes fade-slide-out { from { transform: translateX(0); opacity: 1; } to { transform: translateX(-100%); opacity: 0; } }
        .fade-slide-out-animation { animation: fade-slide-out 1s ease-in-out; }
        @keyframes fade-zoom-in { from { transform: scale(0.5); opacity: 0; } to { transform: scale(1); opacity: 1; } }
        .fade-zoom-in-animation { animation: fade-zoom-in 1s ease-in-out; }
        @keyframes fade-zoom-out { from { transform: scale(1); opacity: 1; } to { transform: scale(0.5); opacity: 0; } }
        .fade-zoom-out-animation { animation: fade-zoom-out 1s ease-in-out; }
    </style>
@endpush

@push('script')
    <script>
        new Swiper('.swiper-banner', {
            loop: true,
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
            autoplay: {
                delay: 2500,
                disableOnInteraction: false,
            },
        });
    </script>
@endpush
