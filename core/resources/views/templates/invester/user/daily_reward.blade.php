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
    .glass-card {
        background: linear-gradient(135deg, rgba(17, 24, 39, 0.7), rgba(31, 41, 55, 0.7));
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.08);
        box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.3);
    }
    .glass-card-primary {
        background: linear-gradient(135deg, rgba(59, 130, 246, 0.1), rgba(147, 51, 234, 0.1));
        backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.1);
    }
    
    .reward-card {
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid rgba(255, 255, 255, 0.05);
        border-radius: 16px;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    .reward-card:hover {
        transform: translateY(-5px);
        background: rgba(255, 255, 255, 0.06);
        border-color: rgba(59, 130, 246, 0.3);
    }
    .reward-card.active {
        background: radial-gradient(circle at center, rgba(59, 130, 246, 0.2), rgba(17, 24, 39, 0.8));
        border: 1px solid rgba(59, 130, 246, 0.5);
        box-shadow: 0 0 20px rgba(59, 130, 246, 0.2);
    }
    .reward-card.claimed {
        opacity: 0.6;
        border-color: rgba(34, 197, 94, 0.2);
    }
</style>
<script src="https://unpkg.com/@lottiefiles/dotlottie-wc@0.8.11/dist/dotlottie-wc.js" type="module"></script>
<link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">

<div id="appCapsule" class="pb-20">
    <div class="px-3 py-4">
        
        {{-- Header Section --}}
        <div class="mb-6 ps-2">
            <h1 class="text-3xl font-bold text-white font-orbitron mb-1">@lang('Daily Check-In')</h1>
            <p class="text-blue-200/60 text-sm">@lang('Collect your rewards every day!')</p>
        </div>

        <div class="glass-card rounded-[25px] p-4 relative overflow-hidden">
            <!-- Background Glows -->
            <div class="absolute top-0 right-0 w-64 h-64 bg-blue-500/10 rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 left-0 w-64 h-64 bg-purple-500/10 rounded-full blur-3xl"></div>

            <div class="grid grid-cols-2 gap-3 relative z-10">
                @forelse($rewards as $reward)
                    @php
                        $isToday = $loop->iteration == $currentDayIndex;
                        $isPast = $loop->iteration < $currentDayIndex;
                        $isLocked = $loop->iteration > $currentDayIndex;
                        
                        $isClaimedState = ($isToday && $isClaimed) || $isPast;
                    @endphp
                
                    <div class="reward-card p-3 flex flex-col items-center justify-center text-center {{ $isToday ? 'active' : '' }} {{ $isClaimedState ? 'claimed' : '' }}">
                        
                        <!-- Day Badge -->
                        <div class="mb-3">
                            <span class="text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded-full {{ $isToday ? 'bg-blue-500 text-white shadow-lg shadow-blue-500/40' : 'bg-white/10 text-gray-400' }}">
                                @lang('Day') {{ $reward->name ?? $loop->iteration }}
                            </span>
                        </div>
                        
                        <!-- Animation/Icon -->
                        <div class="mb-3 relative w-16 h-16 flex items-center justify-center">
                             @if($isClaimedState)
                                <div class="w-12 h-12 rounded-full bg-green-500/20 flex items-center justify-center text-green-400 border border-green-500/30">
                                    <i class="las la-check text-2xl"></i>
                                </div>
                             @elseif($isLocked)
                                <div class="w-12 h-12 rounded-full bg-white/5 flex items-center justify-center text-gray-500 border border-white/10">
                                    <i class="las la-lock text-2xl"></i>
                                </div>
                             @else
                                <dotlottie-wc src="https://lottie.host/013b4191-dae2-4ac0-ab47-8a3297f81fed/ZKZWnkvzTz.lottie" style="width: 100%; height: 100%;" autoplay loop></dotlottie-wc>
                             @endif
                        </div>
                        
                        <!-- Reward Amount -->
                        <div class="font-orbitron font-bold text-sm mb-3 {{ $isToday ? 'text-white' : 'text-gray-300' }}">
                            {{ showAmount($reward->amount) }}
                        </div>
                        
                        <!-- Action Button -->
                        <div class="w-full">
                            @if($isToday)
                                @if($isClaimed)
                                    <button class="w-full py-1.5 rounded-lg bg-green-500/20 border border-green-500/30 text-green-400 text-xs font-bold cursor-not-allowed">
                                        @lang('Collected')
                                    </button>
                                @else
                                    <form action="{{ route('user.daily.reward.claim') }}" method="POST" class="w-full">
                                        @csrf
                                        <button type="submit" class="w-full py-1.5 rounded-lg bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white text-xs font-bold shadow-lg shadow-blue-600/30 active:scale-95 transition-all">
                                            @lang('Collect')
                                        </button>
                                    </form>
                                @endif
                            @elseif($isPast)
                                 <button class="w-full py-1.5 rounded-lg bg-red-500/10 border border-red-500/20 text-red-400 text-xs font-bold cursor-not-allowed">
                                    @lang('Missed')
                                 </button>
                            @else
                                 <button class="w-full py-1.5 rounded-lg bg-white/5 border border-white/10 text-gray-500 text-xs font-bold cursor-not-allowed">
                                    @lang('Locked')
                                 </button>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-10 text-center">
                        <div class="w-16 h-16 mx-auto bg-white/5 rounded-full flex items-center justify-center mb-3">
                            <i class="las la-calendar-times text-3xl text-gray-500"></i>
                        </div>
                        <h3 class="text-white font-bold">@lang('No Rewards Configured')</h3>
                    </div>
                @endforelse
            </div>
            
            <div class="mt-8 p-4 bg-white/5 rounded-2xl border border-white/10">
                <h6 class="text-blue-400 font-bold mb-3 flex items-center gap-2">
                    <i class="las la-info-circle text-xl"></i> @lang('Instructions')
                </h6>
                <ul class="space-y-2 text-sm text-gray-400">
                    <li class="flex items-start gap-2">
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-500 mt-1.5 shrink-0"></span>
                        @lang('Rewards are available for 24 hours based on server time.')
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-500 mt-1.5 shrink-0"></span>
                        @lang('You must log in and click "Collect" to receive your reward.')
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-500 mt-1.5 shrink-0"></span>
                        @lang('Unclaimed rewards cannot be recovered.')
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-500 mt-1.5 shrink-0"></span>
                        @lang('Rewards are credited to your Interest Wallet.')
                    </li>
                </ul>
            </div>
            
        </div>
    </div>
</div>
@endsection
