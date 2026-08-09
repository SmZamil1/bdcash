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
        background: linear-gradient(135deg, rgba(59, 130, 246, 0.1), rgba(147, 51, 234, 0.1), rgba(236, 72, 153, 0.1));
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.37);
    }
</style>
<!-- Add Line Awesome for Icons -->
<link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">

        <!-- Header Content -->All Logs

        <div class="px-[5px] pb-[8px] mt-4 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-white font-orbitron">@lang('Cloud Mining')</h1>
                <p class="text-blue-200/80 text-sm">@lang('Rent High-Power Mining Rigs')</p>
            </div>
            <a href="{{ route('user.mining.log') }}" class="bg-white/10 border border-white/20 text-white rounded-xl px-4 py-2 text-xs font-bold font-orbitron hover:bg-white/20 transition-all flex items-center gap-2">
                <i class="las la-history text-lg"></i> @lang('My Rigs')
            </a>
        </div>



        <!-- Hero / Stats Card -->
        <div class="px-4 mb-6">
            <div class="relative overflow-visible bg-gradient-to-br from-blue-600 via-purple-600 to-pink-600 rounded-3xl p-1 shadow-2xl shadow-purple-500/20">
                <div class="bg-black/40 backdrop-blur-md rounded-[22px] p-5 h-full relative overflow-hidden">
                    <!-- Decor -->
                    <div class="absolute -right-10 -top-10 w-40 h-40 bg-pink-500/30 rounded-full blur-3xl"></div>
                    
                    <div class="flex items-center gap-4 relative z-10">
                        <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-white/10 to-transparent border border-white/20 flex items-center justify-center shadow-inner relative overflow-hidden">
                            @php
                                $pulseSpeed = 2; // Default 2s
                                if (($activeHashrate ?? 0) > 0) {
                                    $pulseSpeed = max(0.4, 2 - (($activeHashrate ?? 0) / 100)); // Faster as TH/s increases
                                }
                            @endphp
                            <i class="las la-hammer text-4xl text-yellow-400" style="animation: pulse {{ $pulseSpeed }}s cubic-bezier(0.4, 0, 0.6, 1) infinite;"></i>
                            <div class="absolute inset-0 bg-yellow-400/5 animate-pulse"></div>
                        </div>
                        <div class="flex-grow">
                            <p class="text-blue-200 text-[10px] font-semibold uppercase tracking-widest flex justify-between items-center">
                                <span>@lang('Current Performance')</span>
                                <span class="flex items-center gap-1">
                                    <span class="w-1.5 h-1.5 rounded-full bg-green-500 animate-ping"></span>
                                    <span class="text-green-400">@lang('Live')</span>
                                </span>
                            </p>
                            <h2 class="text-3xl font-bold text-white font-orbitron mt-1">
                                <span id="live-total-mined" 
                                      data-balance="{{ $totalMined ?? 0 }}" 
                                      data-profit="{{ $totalProfitPerSecond ?? 0 }}"
                                      data-paid="{{ $totalPaid ?? 0 }}">
                                    {{ showAmount($totalMined ?? 0, currencyFormat: false) }}
                                </span>
                                <span class="text-sm font-normal text-white/70">{{ gs('cur_text') }}</span>
                            </h2>
                            <div class="mt-2 h-1 w-full bg-white/10 rounded-full overflow-hidden">
                                @php
                                    $powerWidth = min(100, (($activeHashrate ?? 0) / 500) * 100); // 100% at 500 TH/s
                                @endphp
                                <div class="h-full bg-gradient-to-r from-yellow-400 to-orange-500 rounded-full" style="width: {{ $powerWidth }}%;"></div>
                            </div>
                            <div class="flex items-center justify-between mt-1.5">
                                <span class="text-[10px] text-gray-400 uppercase">@lang('Live Session Profit')</span>
                                <span class="text-xs font-bold text-yellow-400 font-orbitron" id="live-session-profit">
                                    +{{ showAmount(($totalMined ?? 0) - ($totalPaid ?? 0), 4, currencyFormat: false) }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 pt-4 border-t border-white/10 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <div class="flex flex-col">
                                <span class="text-[10px] text-gray-400 uppercase leading-none mb-1">@lang('Total Power')</span>
                                <div class="flex items-center gap-2">
                                    <i class="las la-tachometer-alt text-green-400 text-lg"></i>
                                    <span class="text-sm font-bold text-white font-orbitron">{{ showAmount($activeHashrate ?? 0, 2, currencyFormat: false) }} TH/s</span>
                                </div>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="text-[10px] text-gray-400 uppercase leading-none mb-1 block">@lang('Efficiency')</span>
                            <span class="text-xs font-bold text-blue-400">99.8% @lang('Up')</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <p class="text-[10px] text-center text-gray-500 mt-3 italic px-4">
                "Top 5 Currencies selected for maximum mining efficiency. Contracts include hardware maintenance."
            </p>
        </div>

        <!-- Mining Rigs List -->
        <div class="px-4 mb-20 space-y-4">
            <h3 class="text-white text-lg font-bold font-orbitron flex items-center gap-2">
                <i class="las la-server text-blue-400"></i> @lang('Mining Rigs')
            </h3>

            @forelse($miners as $miner)
                <div class="glass-card rounded-3xl p-4 relative group hover:bg-white/5 transition-all duration-300">
                    <div class="flex items-start gap-4">
                        <!-- Icon -->
                        <div class="relative">
                            <div class="w-16 h-16 rounded-2xl p-[2px] bg-gradient-to-br from-blue-400 to-purple-500">
                                <img src="{{ getImage('assets/images/mining/' . $miner->icon) }}" 
                                     class="w-full h-full object-cover rounded-[14px] bg-black" 
                                     alt="{{ $miner->coin_code }}">
                            </div>
                            @if($loop->first || $miner->min_return > 5)
                            <span class="absolute -top-2 -right-2 bg-gradient-to-r from-red-500 to-pink-500 text-white text-[9px] font-bold px-2 py-0.5 rounded-full shadow-lg shadow-red-500/40">HOT</span>
                            @endif
                        </div>

                        <!-- Content -->
                        <div class="flex-1 min-w-0">
                            <div class="flex justify-between items-start mb-1">
                                <h3 class="text-lg font-bold text-white font-orbitron truncate">{{ $miner->name }}</h3>
                                <span class="bg-blue-500/20 text-blue-300 border border-blue-500/30 text-[10px] font-bold px-2 py-0.5 rounded uppercase">
                                    {{ $miner->tag }}
                                </span>
                            </div>
                            
                            <div class="flex items-end justify-between mt-2">
                                <div>
                                    <p class="text-xs text-gray-400">@lang('Price')</p>
                                    <p class="text-xl font-bold text-white font-orbitron text-transparent bg-clip-text bg-gradient-to-r from-blue-300 to-purple-300">
                                        {{ showAmount($miner->price, 0, currencyFormat: false) }} <span class="text-xs text-gray-500">{{ gs('cur_text') }}</span>
                                    </p>
                                </div>
                                <div>
                                     <p class="text-xs text-gray-400">@lang('Return')</p>
                                     <p class="text-sm font-bold text-green-400 font-orbitron">{{ number_format($miner->min_return, 2) }}% / Day</p>
                                </div>
                                <div class="text-right">
                                     <p class="text-xs text-gray-400">@lang('Duration')</p>
                                     <p class="text-sm font-bold text-blue-300 font-orbitron">{{ $miner->duration }} @lang('Days')</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action -->
                    <div class="mt-4 pt-3 border-t border-white/10 flex items-center justify-between gap-3">
                        <div class="text-[10px] text-gray-500 max-w-[50%] leading-tight">
                            @lang('Includes maintenance fee & electricity cost')
                        </div>
                        <form action="{{ route('user.mining.rent') }}" method="POST" class="flex-1">
                            @csrf
                            <input type="hidden" name="id" value="{{ $miner->id }}">
                            <button type="submit" class="w-full bg-white text-black hover:bg-gray-200 font-bold font-orbitron py-2.5 rounded-xl shadow-lg shadow-white/10 transition-all transform active:scale-95 text-xs flex items-center justify-center gap-2">
                                <i class="las la-bolt text-yellow-600 text-base"></i> @lang('RENT RIG')
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="glass-card rounded-2xl p-8 text-center">
                    <div class="w-16 h-16 bg-white/5 rounded-full flex items-center justify-center mx-auto mb-3">
                        <i class="las la-server text-3xl text-gray-500"></i>
                    </div>
                    <p class="text-gray-400">@lang('No Mining Plans Available')</p>
                </div>
            @endforelse
        </div>
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            let totalElement = $('#live-total-mined');
            let sessionElement = $('#live-session-profit');
            
            if (!totalElement.length) return;

            let currentTotal = parseFloat(totalElement.attr('data-balance')) || 0;
            let profitPerSecond = parseFloat(totalElement.attr('data-profit')) || 0;
            let totalPaid = parseFloat(totalElement.attr('data-paid')) || 0;
            
            if (profitPerSecond <= 0) return;

            let updateInterval = 50; 
            let profitPerTick = profitPerSecond * (updateInterval / 1000);

            setInterval(function() {
                currentTotal += profitPerTick;
                
                // 1. Update Grand Total
                totalElement.text(currentTotal.toLocaleString(undefined, {
                    minimumFractionDigits: 4,
                    maximumFractionDigits: 4
                }));

                // 2. Update Session Progress (Current Total - Total Paid)
                let sessionProfit = currentTotal - totalPaid;
                sessionElement.text('+' + sessionProfit.toLocaleString(undefined, {
                    minimumFractionDigits: 4,
                    maximumFractionDigits: 4
                }));
            }, updateInterval);
        });
    </script>
@endsection
