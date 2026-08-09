@extends($activeTemplate . 'layouts.master')
@section('content')
<script>
    "use strict";
    
    // Sync server time to prevent issues with incorrect device time
    const serverTimeStart = {{ now()->timestamp * 1000 }};
    const localTimeStart = Date.now();
    const serverTimeOffset = serverTimeStart - localTimeStart;

    function getServerTime() {
        return Date.now() + serverTimeOffset;
    }

    function createCountDown(elementId, targetTimestamp) {
        const targetTime = targetTimestamp * 1000;
        
        const x = setInterval(function() {
            const now = getServerTime();
            const distance = targetTime - now;
            
            const el = document.getElementById(elementId);
            if(!el) {
                clearInterval(x);
                return;
            }

            if (distance <= 0) {
                clearInterval(x);
                el.innerHTML = "@lang('Completed')";
                el.classList.add('text-green-400');
                return;
            }
            
            const days = Math.floor(distance / (1000 * 60 * 60 * 24));
            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);
            
            let str = "";
            if(days > 0) str += days + "d: ";
            str += hours + "h " + minutes + "m " + seconds + "s";
            
            el.innerHTML = str;
        }, 1000);
    }

    // Check for time sync issues
    window.addEventListener('load', function() {
        if (Math.abs(serverTimeOffset) > 120000) { // 2 minutes difference
            const warningEl = document.getElementById('timeSyncWarning');
            if (warningEl) {
                warningEl.classList.remove('hidden');
            }
        }
    });
</script>

<!-- Tailwind and Styles override to ensure the premium look -->
<style>
    @import url('https://fonts.googleapis.com/css2?family=Orbitron:wght@400..900&display=swap');
    :root {
        --font-orbitron: 'Orbitron', 'sans-serif';
    }
    .font-orbitron {
        font-family: var(--font-orbitron);
    }
    /* Glassmorphism utility */
    .glass-card {
        background: linear-gradient(135deg, rgba(59, 130, 246, 0.1), rgba(147, 51, 234, 0.1), rgba(236, 72, 153, 0.1));
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.37);
    }
    .modal-backdrop {
        background-color: rgba(0, 0, 0, 0.7);
        backdrop-filter: blur(5px);
    }
</style>

<!-- Add Line Awesome for Icons -->
<link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">
    <script src="https://unpkg.com/@lottiefiles/dotlottie-wc@0.8.11/dist/dotlottie-wc.js" type="module"></script>
    <div class="flex justify-center mb-[-20px] mt-2 relative z-0">
        <dotlottie-wc src="https://lottie.host/4f65ec4a-60ff-4236-8c98-5dcecb1ea9f6/3K1qNxHL5I.lottie" style="width: 280px; height: 280px; max-width: 100%;" autoplay loop></dotlottie-wc>
    </div>

        <!-- Header Content -->
        <div class="px-[5px] pb-[8px] mt-4">
            <h1 class="text-3xl font-bold text-white font-orbitron">@lang('AI Trading')</h1>
            <p class="text-blue-200/80 text-sm">@lang('Automated High-Frequency Bots')</p>
        </div>

        {{-- Time Sync Warning --}}
        <div id="timeSyncWarning" class="hidden px-4 mb-4">
            <div class="bg-red-500/10 border border-red-500/30 rounded-xl p-3 flex items-start gap-3">
                <i class="las la-exclamation-triangle text-xl text-red-500 mt-0.5"></i>
                <div class="flex-1">
                    <p class="text-xs text-red-200 font-bold">@lang('System Time Out of Sync')</p>
                    <p class="text-[10px] text-red-200/60 leading-tight">@lang('Your device time is incorrect. Please set your device time to "Automatic" for accurate trading data.')</p>
                </div>
            </div>
        </div>



        <!-- Start Trade Action -->
        @if($bots->count() > 0)
        <div class="px-4 mb-8">
            <button type="button" onclick="openInvestModal()" class="w-full relative group overflow-hidden rounded-2xl p-[1px]">
                <div class="absolute inset-0 bg-gradient-to-r from-cyan-400 via-blue-500 to-purple-600 animate-gradient-x"></div>
                <div class="relative bg-black/80 backdrop-blur-xl rounded-2xl p-4 flex items-center justify-center gap-3 transition-all group-hover:bg-black/60">
                    <i class="las la-rocket text-3xl text-blue-400 group-hover:scale-110 transition-transform"></i>
                    <div class="text-left">
                        <h3 class="text-lg font-bold text-white font-orbitron">@lang('Start New Bot')</h3>
                        <p class="text-xs text-blue-200">@lang('Activate AI Trading Engine')</p>
                    </div>
                </div>
            </button>
        </div>
        @endif

        <!-- Active Bots Section -->
        <div class="px-4 mb-6">
            <h3 class="text-white text-lg font-bold font-orbitron mb-3 flex items-center gap-2">
                <i class="las la-chart-area text-blue-400"></i> @lang('Active Bots')
            </h3>
            
            <div class="space-y-3">
                @forelse($myBots as $userBot)
                <div class="glass-card rounded-2xl p-4 relative overflow-hidden">

                    
                    <div class="flex justify-between items-start mb-3 relative z-10">
                        <div>
                            <h4 class="text-xl font-bold text-white font-orbitron">{{ $userBot->bot->name }}</h4>
                            <span class="text-xs bg-green-500/20 text-green-400 border border-green-500/30 px-2 py-0.5 rounded-full flex items-center gap-1 w-fit mt-1">
                                <span class="w-1.5 h-1.5 rounded-full bg-green-400 animate-pulse"></span> @lang('Running')
                            </span>
                        </div>
                        <div class="text-right">
                             <p class="text-xs text-gray-400">@lang('Invested')</p>
                             <p class="text-lg font-bold text-blue-300">{{ showAmount($userBot->invest_amount) }}</p>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-3 gap-2 border-t border-white/10 pt-3 relative z-10">
                        <div>
                             <p class="text-[10px] text-gray-400">@lang('Profit')</p>
                             <p class="text-xs font-bold text-green-400">+{{ showAmount($userBot->profit_earned ?? 0) }}</p>
                        </div>
                        <div class="text-center">
                             <p class="text-[10px] text-gray-400">@lang('Next Profit')</p>
                             @if($userBot->end_at > now())
                                 <p class="text-xs font-medium text-blue-300" id="next_profit_{{ $userBot->id }}"></p>
                                 <script>
                                     @php
                                         if($userBot->next_profit_at) {
                                             $nextProfitAt = $userBot->next_profit_at;
                                         } else {
                                             $hours = (float)(@$userBot->bot->timeSetting->time ?? 24);
                                             $lastPaid = $userBot->last_profit_at ?? $userBot->start_at ?? $userBot->created_at;
                                             $nextProfitAt = \Carbon\Carbon::parse($lastPaid)->addHours($hours);
                                         }
                                     @endphp
                                     createCountDown('next_profit_{{ $userBot->id }}', {{ $nextProfitAt->timestamp }});
                                 </script>
                             @else
                                 <p class="text-xs font-medium text-gray-500">@lang('N/A')</p>
                             @endif
                        </div>
                        <div class="text-right">
                             <p class="text-[10px] text-gray-400">@lang('Plan End')</p>
                             @if($userBot->end_at > now())
                                <p class="text-xs font-medium text-white" id="timer_{{ $userBot->id }}"></p>
                                <script>
                                    createCountDown('timer_{{ $userBot->id }}', {{ $userBot->end_at->timestamp }});
                                </script>
                             @else
                                <p class="text-xs font-medium text-green-400">@lang('Completed')</p>
                             @endif
                        </div>
                    </div>
                </div>
                @empty
                <div class="glass-card rounded-2xl p-6 text-center">
                    <div class="w-12 h-12 bg-white/5 rounded-full flex items-center justify-center mx-auto mb-3">
                        <i class="las la-robot text-2xl text-gray-400"></i>
                    </div>
                    <p class="text-gray-400 text-sm">@lang('No active trading bots running.')</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- History Section -->
        @if($completedBots->count() > 0)
        <div class="px-4 mb-20">
            <h3 class="text-white text-lg font-bold font-orbitron mb-3 flex items-center gap-2">
                <i class="las la-history text-purple-400"></i> @lang('History')
            </h3>
            
            <div class="space-y-3">
                @foreach($completedBots as $historyBot)
                <div class="glass-card rounded-xl p-3 flex items-center justify-between">
                    <div>
                        <h5 class="text-sm font-bold text-white">{{ $historyBot->bot->name }}</h5>
                        <p class="text-xs text-gray-400">{{ diffForHumans($historyBot->end_at) }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-bold text-green-400">+{{ showAmount($historyBot->profit_earned ?? 0) }}</p>
                        <span class="text-[10px] bg-white/10 text-white/70 px-1.5 py-0.5 rounded">@lang('Completed')</span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

<!-- Rent Modal -->
@if($bots->count() > 0)
@php $defaultBot = $bots->first(); @endphp
<div id="investModal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4 modal-backdrop transition-opacity duration-300 opacity-0">
    <div class="glass-card w-full max-w-md rounded-3xl p-6 relative transform scale-95 transition-transform duration-300" id="investModalContent">
        <button onclick="closeInvestModal()" class="absolute top-4 right-4 w-8 h-8 rounded-full bg-white/10 flex items-center justify-center hover:bg-white/20 transition text-white">
            <i class="las la-times"></i>
        </button>

        <div class="text-center mb-6">
            <img src="https://cdn-icons-png.flaticon.com/128/14657/14657058.png" class="w-16 h-16 mx-auto mb-4 rounded-2xl shadow-lg shadow-blue-500/20" alt="Bot Icon">
            <h3 class="text-xl font-bold font-orbitron text-white">@lang('Configure Bot')</h3>
            <p class="text-sm text-blue-200/70">@lang('Set your investment parameters')</p>
        </div>

        <form action="{{ route('user.ai.bot.rent') }}" method="POST" class="space-y-4">
            @csrf
            <input type="hidden" name="bot_id" id="bot_id_input" value="{{ $defaultBot->id }}">

            <!-- Bot Selection (if multiple) -->
            <div class="space-y-1">
                 <label class="text-sm font-medium text-blue-200 ml-1">@lang('Select Bot Strategy')</label>
                 <div class="grid grid-cols-1 gap-2 max-h-32 overflow-y-auto custom-scrollbar">
                     @foreach($bots as $bot)
                     <label class="cursor-pointer relative">
                         <input type="radio" name="bot_select" value="{{ $bot->id }}" class="peer sr-only" 
                                data-min="{{ $bot->price }}" 
                                data-max="{{ $bot->max_invest }}"
                                data-desc="{{ showAmount($bot->price, 0) }} - {{ showAmount($bot->max_invest, 0) }}"
                                data-duration="{{ $bot->duration }}"
                                {{ $loop->first ? 'checked' : '' }} 
                                onchange="updateBotSelection(this)">
                         <div class="p-3 rounded-xl bg-white/5 border border-white/10 peer-checked:bg-blue-600/20 peer-checked:border-blue-500 transition-all flex items-center justify-between">
                             <div>
                                 <div class="font-bold text-sm text-white">{{ $bot->name }}</div>
                                 <div class="text-xs text-gray-400">Return: {{ $bot->profit_type == 1 ? showAmount($bot->daily_profit) : $bot->daily_profit.'%' }} / Day</div>
                             </div>
                             <div class="w-4 h-4 rounded-full border border-white/30 peer-checked:bg-blue-500 peer-checked:border-blue-500"></div>
                         </div>
                     </label>
                     @endforeach
                 </div>
            </div>

            <div class="space-y-1">
                <label class="text-sm font-medium text-blue-200 ml-1">@lang('Investment Amount')</label>
                <div class="relative">
                    <input type="number" name="amount" id="amount_input" value="{{ $defaultBot->price }}" step="any"
                        class="w-full bg-black/20 border border-white/10 rounded-xl px-4 py-3 text-white placeholder-gray-500 focus:outline-none focus:border-blue-500 transition-colors font-orbitron"
                        placeholder="0.00">
                </div>
                <p class="text-xs text-gray-400 text-right" id="limit_text">
                    @lang('Limit'): {{ showAmount($defaultBot->price) }} - {{ showAmount($defaultBot->max_invest) }}
                </p>
            </div>

            <div class="flex items-center justify-between bg-white/5 rounded-xl p-3 border border-white/5">
                <span class="text-xs text-gray-400">@lang('Duration')</span>
                <span class="text-sm font-bold text-white" id="duration_text">{{ $defaultBot->duration }} @lang('Days')</span>
            </div>

            <button type="submit" class="w-full bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-500 hover:to-purple-500 text-white font-bold py-3.5 rounded-xl shadow-lg shadow-blue-500/25 transition-all transform active:scale-95 font-orbitron tracking-wide mt-2">
                @lang('ACTIVATE BOT')
            </button>
        </form>
    </div>
</div>
@endif

<script>
    function openInvestModal() {
        const modal = document.getElementById('investModal');
        const content = document.getElementById('investModalContent');
        modal.classList.remove('hidden');
        modal.classList.add('flex'); // Ensure flex is on so items-center works
        
        // Slight delay for animation
        setTimeout(() => {
            modal.classList.remove('opacity-0');
            content.classList.remove('scale-95');
            content.classList.add('scale-100');
        }, 10);
    }

    function closeInvestModal() {
        const modal = document.getElementById('investModal');
        const content = document.getElementById('investModalContent');
        modal.classList.add('opacity-0');
        content.classList.remove('scale-100');
        content.classList.add('scale-95');
        setTimeout(() => {
            modal.classList.add('hidden');
            modal.classList.remove('flex'); // Remove flex so it doesn't interfere
        }, 300);
    }

    function updateBotSelection(radio) {
        document.getElementById('bot_id_input').value = radio.value;
        const min = radio.dataset.min;
        const max = radio.dataset.max;
        const desc = radio.dataset.desc;
        const duration = radio.dataset.duration;

        const amountInput = document.getElementById('amount_input');
        amountInput.min = min;
        amountInput.max = max;
        // Optional: auto set min amount
        amountInput.value = min; 

        document.getElementById('limit_text').innerText = '@lang("Limit"): ' + parseFloat(min).toFixed(2) + ' - ' + parseFloat(max).toFixed(2);
        document.getElementById('duration_text').innerText = duration + ' @lang("Days")';
    }
</script>

@endsection
