@extends($activeTemplate . 'layouts.master')
@section('content')

<!-- Tailwind and Styles override -->
<style>
    @import url('https://fonts.googleapis.com/css2?family=Orbitron:wght@400..900&display=swap');
    :root {
        --font-orbitron: 'Orbitron', 'sans-serif';
    }
    .font-orbitron {
        font-family: var(--font-orbitron);
    }
    .glass-bar {
        background: rgba(17, 24, 39, 0.8);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        box-shadow: 0 4px 30px rgba(0, 0, 0, 0.3);
    }
    #appCapsule {
        padding-top: 0 !important;
        padding-bottom: 0 !important;
        height: 100vh;
        overflow: hidden;
    }
</style>

<div class="h-screen flex flex-col bg-[#0f172a]">
    
    {{-- Header / Timer Bar --}}
    <div class="glass-bar z-50 p-3 flex items-center justify-between relative">
        <div class="flex items-center gap-4">
            {{-- Circular Timer --}}
            <div class="relative w-12 h-12 flex items-center justify-center">
                <svg class="w-full h-full transform -rotate-90 drop-shadow-[0_0_8px_rgba(59,130,246,0.5)]">
                    <circle cx="24" cy="24" r="20" stroke="currentColor" stroke-width="3" fill="transparent" class="text-gray-800" />
                    <circle id="progress-ring" cx="24" cy="24" r="20" stroke="currentColor" stroke-width="3" fill="transparent" class="text-blue-500 transition-all duration-1000 ease-linear" stroke-dasharray="125.6" stroke-dashoffset="0" />
                </svg>
                <div class="absolute inset-0 flex items-center justify-center">
                    <span id="counter" class="text-sm font-bold text-white font-orbitron">{{ $ad->duration }}</span>
                </div>
            </div>

            <div>
                <h4 class="text-white font-bold font-orbitron text-sm tracking-wide">@lang('Viewing Ad')</h4>
                <p class="text-blue-400 text-[10px] font-medium animate-pulse flex items-center gap-1">
                    <i class="las la-clock"></i> @lang('Please wait...')
                </p>
            </div>
        </div>

        <div id="action-area">
            <span id="loader-badge" class="bg-white/5 border border-white/10 text-gray-400 px-4 py-1.5 rounded-lg text-xs font-bold flex items-center gap-2">
                <i class="las la-circle-notch la-spin text-blue-500"></i> @lang('Loading...')
            </span>
            
            <form id="confirmForm" action="{{ route('user.ptc.confirm', $ad->id) }}" method="POST" class="hidden">
                @csrf
                <button type="submit" class="bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-400 hover:to-emerald-500 text-white px-6 py-2 rounded-lg font-bold font-orbitron text-sm shadow-[0_0_15px_rgba(16,185,129,0.4)] active:scale-[0.98] transition-all flex items-center gap-2 border border-green-400/20">
                   <i class="las la-check-circle text-lg"></i> @lang('Claim')
                </button>
            </form>
        </div>
    </div>

    {{-- Ad Content --}}
    <div class="flex-1 w-full bg-[#0f172a] relative overflow-hidden">
        @if($ad->ad_type == 1)
            {{-- Website / Iframe --}}
            <iframe src="{{ $ad->url }}" class="w-full h-full border-0"></iframe>
            
        @elseif($ad->ad_type == 2)
            {{-- Video --}}
             <div class="w-full h-full flex items-center justify-center bg-black">
                 @php
                    $url = $ad->url;
                    if(strpos($url, 'youtube.com') !== false || strpos($url, 'youtu.be') !== false){
                        $url = str_replace('watch?v=', 'embed/', $url);
                    }
                 @endphp
                 <iframe class="w-full h-full max-w-4xl max-h-[80vh] rounded-2xl shadow-2xl border border-white/10" src="{{ $url }}?autoplay=1&mute=1" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
            </div>
            
        @elseif($ad->ad_type == 3)
            {{-- Image --}}
            <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-gray-900 to-black p-4">
                <div class="relative group">
                    <div class="absolute -inset-1 bg-gradient-to-r from-blue-600 to-purple-600 rounded-2xl blur opacity-25 group-hover:opacity-50 transition duration-1000"></div>
                    <img src="{{ getImage(getFilePath('ptc') . '/' . $ad->image) }}" class="relative max-w-full max-h-[85vh] object-contain rounded-xl shadow-2xl border border-white/10" alt="Ad">
                </div>
            </div>
        @endif
    </div>

</div>

@endsection

@push('script')
<script>
    (function($){
        "use strict";
        let duration = {{ $ad->duration }};
        let counter = duration;
        
        // Circular Progress Logic
        const circle = document.getElementById('progress-ring');
        // r=20 -> circumference ~ 125.6
        const circumference = 2 * Math.PI * 20;
        
        circle.style.strokeDasharray = `${circumference} ${circumference}`;
        circle.style.strokeDashoffset = circumference;

        function setProgress(percent) {
            const offset = circumference - (percent / 100) * circumference;
            circle.style.strokeDashoffset = offset;
        }

        // Start animation
        circle.style.strokeDashoffset = 0; 
        
        let timer = setInterval(function(){
            counter--;
            $('#counter').text(counter);
            
            // Update Ring
            let percentObj = ((duration - counter) / duration) * 100;
            // Invert for countdown effect (ring shrinking)
            // setProgress(100 - percentObj); // if shrinking
            // Let's make it fill up or empty? Standard is empty out or fill up.
            // Let's make the ring 'consume' itself.
            const offset = (percentObj / 100) * circumference; 
            circle.style.strokeDashoffset = offset;

            if(counter <= 0){
                clearInterval(timer);
                $('#counter').html('<i class="las la-check text-green-400"></i>');
                circle.classList.remove('text-blue-500');
                circle.classList.add('text-green-500');
                circle.style.strokeDashoffset = 0; // Full circle green
                
                $('#loader-badge').addClass('hidden');
                $('#confirmForm').removeClass('hidden');
                
                // Add scale bounce animation
                $('#confirmForm button').addClass('animate-bounce');
                notify('success', 'You can now claim your reward!');
            }
        }, 1000);
    })(jQuery);
</script>
@endpush
