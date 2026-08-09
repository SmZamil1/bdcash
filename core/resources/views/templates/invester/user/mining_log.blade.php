@extends($activeTemplate . 'layouts.master', ['pageTitle' => trans('My Mining Rigs')])
@push('style')
<style>
    .glass-card {
        background: rgba(255, 255, 255, 0.05);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    }
</style>
<link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">
@endpush

@section('content')
<div class="px-4">
    <div class="px-[5px] pb-[8px] mt-4">
        <h1 class="text-3xl font-bold text-white font-orbitron">@lang('Mining Account')</h1>
        <p class="text-white text-sm">@lang('My Active Rigs & History')</p>
    </div>

    <div class="mb-4">
        <div class="bg-gradient-to-br from-blue-400 via-blue-600 to-blue-800 backdrop-blur-lg rounded-2xl shadow-xl border border-blue-400/30">
            <div class="p-4 text-center">
                <h3 class="pb-2 mb-3 border-b border-white/10 text-white font-semibold text-lg">@lang('All Logs')</h3>
                <div class="grid grid-cols-3 gap-2">
                    <a class="logNav py-2 rounded-full font-semibold text-xs text-white transition-all duration-300 text-center border border-white/10 {{ request()->routeIs('user.deposit.history') ? 'bg-blue-600 shadow-lg shadow-blue-500/50' : 'bg-white/20 hover:bg-white/30' }}" href="{{ route('user.deposit.history') }}">@lang('Deposit')</a>
                    <a class="logNav py-2 rounded-full font-semibold text-xs text-white transition-all duration-300 text-center border border-white/10 {{ request()->routeIs('user.withdraw.history') ? 'bg-blue-600 shadow-lg shadow-blue-500/50' : 'bg-white/20 hover:bg-white/30' }}" href="{{ route('user.withdraw.history') }}">@lang('Withdraw')</a>
                    <a class="logNav py-2 rounded-full font-semibold text-xs text-white transition-all duration-300 text-center border border-white/10 {{ request()->routeIs('user.invest.log') ? 'bg-blue-600 shadow-lg shadow-blue-500/50' : 'bg-white/20 hover:bg-white/30' }}" href="{{ route('user.invest.log') }}">@lang('Invest')</a>
                    <a class="logNav py-2 rounded-full font-semibold text-xs text-white transition-all duration-300 text-center border border-white/10 {{ request()->routeIs('user.transactions') ? 'bg-blue-600 shadow-lg shadow-blue-500/50' : 'bg-white/20 hover:bg-white/30' }}" href="{{ route('user.transactions') }}">@lang('Wallet')</a>
                    <a class="logNav py-2 rounded-full font-semibold text-xs text-white transition-all duration-300 text-center border border-white/10 {{ request()->routeIs('user.commissions') ? 'bg-blue-600 shadow-lg shadow-blue-500/50' : 'bg-white/20 hover:bg-white/30' }}" href="{{ route('user.commissions') }}">@lang('Commis.')</a>
                    <a class="logNav py-2 rounded-full font-semibold text-xs text-white transition-all duration-300 text-center border border-white/10 {{ request()->routeIs('user.mining.log') ? 'bg-blue-600 shadow-lg shadow-blue-500/50' : 'bg-white/20 hover:bg-white/30' }}" href="{{ route('user.mining.log') }}">@lang('Rig Log')</a>
                </div>
            </div>
        </div>
    </div>

    {{-- Stats Summary --}}
    <div class="grid grid-cols-2 gap-3 mb-6">
        <div class="glass-card rounded-2xl p-4 relative overflow-hidden group">
            <div class="absolute right-0 top-0 p-3 opacity-10">
                <i class="las la-microchip text-4xl text-blue-400"></i>
            </div>
            <h3 class="text-gray-400 text-xs font-medium uppercase">Active Rigs</h3>
            <p class="text-2xl font-bold text-white mt-1">{{ $minings->where('status', 1)->count() }}</p>
        </div>
        <div class="glass-card rounded-2xl p-4 relative overflow-hidden group">
            <div class="absolute right-0 top-0 p-3 opacity-10">
                <i class="las la-coins text-4xl text-yellow-400"></i>
            </div>
            <h3 class="text-gray-400 text-xs font-medium uppercase">Total Rented</h3>
            <p class="text-2xl font-bold text-white mt-1">{{ $minings->count() }}</p>
        </div>
    </div>

    <div class="space-y-4">
        @forelse($minings as $mining)
            @php
                $plan = $mining->miningPlan;
                $dailyProfit = ($mining->amount * $plan->min_return) / 100;
            @endphp
            <div class="transition-all duration-300 hover:scale-[1.02] hover:shadow-2xl bg-gradient-to-br from-blue-400/20 via-blue-600/20 to-blue-800/20 border border-blue-400/30 shadow-lg rounded-2xl backdrop-blur-md p-4 relative overflow-hidden">
                {{-- Decorative background glow --}}
                <div class="absolute -right-10 -top-10 w-32 h-32 bg-blue-500/20 rounded-full blur-3xl"></div>

                <div class="flex items-start gap-4 relaltive z-10">
                    <div class="flex-shrink-0">
                        <div class="w-14 h-14 rounded-full p-1 bg-gradient-to-b from-blue-400 to-blue-600 shadow-lg">
                            <img class="w-full h-full rounded-full object-cover border-2 border-black/50" 
                                 src="{{ getImage('assets/images/mining/' . @$plan->icon) }}" 
                                 alt="Miner">
                        </div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex justify-between items-start mb-2">
                            <div>
                                <h3 class="text-white font-bold text-lg truncate pr-2">{{ __($plan->name) }}</h3>
                                <p class="text-blue-300 text-xs font-medium">Inv: {{ showAmount($mining->amount, 0, currencyFormat: false) }} {{ gs('cur_text') }}</p>
                            </div>
                            <div class="text-right">
                                @if ($mining->status == 1)
                                    @if($mining->next_profit_at && now()->greaterThanOrEqualTo($mining->next_profit_at))
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-500/20 text-yellow-500 border border-yellow-500/30">
                                            <span class="w-1.5 h-1.5 rounded-full bg-yellow-500 mr-1.5 animate-pulse"></span>
                                            @lang('Processing')
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-500/20 text-green-400 border border-green-500/30">
                                            <span class="w-1.5 h-1.5 rounded-full bg-green-400 mr-1.5 animate-pulse"></span>
                                            @lang('Running')
                                        </span>
                                    @endif
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-500/20 text-gray-400 border border-gray-500/30">
                                        @lang('Completed')
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-2 mt-3 pt-3 border-t border-white/10">
                            <div>
                                <p class="text-gray-400 text-xs mb-0.5">@lang('Profit Return')</p>
                                <p class="text-white text-sm font-semibold">
                                    {{ showAmount($dailyProfit, 2, currencyFormat: false) }} {{ gs('cur_text') }}
                                    <span class="text-[10px] text-green-400 ml-1">({{ number_format($plan->min_return, 2) }}%)</span>
                                </p>
                            </div>
                            <div class="text-right">
                                <p class="text-gray-400 text-xs mb-0.5">@lang('Start Date')</p>
                                <p class="text-white text-sm font-semibold">{{ showDateTime($mining->created_at, 'M d, Y') }}</p>
                            </div>
                        </div>

                        <div class="mt-3 bg-black/20 rounded-lg p-2 flex justify-between items-center border border-white/5">
                            <span class="text-xs text-gray-400">@lang('Next Profit')</span>
                            <span class="text-xs text-blue-400 font-bold next-profit-timer" 
                                  data-time="{{ $mining->next_profit_at ? $mining->next_profit_at->timestamp * 1000 : '' }}">
                                  @if($mining->status == 1 && $mining->next_profit_at)
                                    @if(now()->greaterThanOrEqualTo($mining->next_profit_at))
                                        @lang('Processing...')
                                    @else
                                        @lang('Calculating...')
                                    @endif
                                  @else
                                    @lang('N/A')
                                  @endif
                            </span>
                        </div>
                        <div class="mt-2 bg-black/20 rounded-lg p-2 flex justify-between items-center border border-white/5">
                            <span class="text-xs text-gray-400">@lang('Plan End')</span>
                            <span class="text-xs text-white font-medium plan-end-timer" 
                                  data-time="{{ $mining->end_at ? $mining->end_at->timestamp * 1000 : '' }}">
                                @if($mining->end_at && now()->greaterThanOrEqualTo($mining->end_at))
                                    <span class="text-red-400">@lang('Expired')</span>
                                @elseif($mining->end_at)
                                    {{ diffForHumans($mining->end_at) }}
                                @else
                                    @lang('N/A')
                                @endif
                            </span>
                        </div>
                        <div class="mt-2 bg-black/20 rounded-lg p-2 flex justify-between items-center border border-white/5">
                            <span class="text-xs text-gray-400">@lang('Duration')</span>
                            <span class="text-xs text-white font-medium">{{ $mining->duration }} @lang('Days')</span>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="flex flex-col items-center justify-center py-10 glass-card rounded-2xl">
                <div class="w-16 h-16 bg-white/5 rounded-full flex items-center justify-center mb-3">
                    <i class="las la-server text-3xl text-gray-500"></i>
                </div>
                <h3 class="text-white font-bold mb-1">No Mining Rigs</h3>
                <p class="text-gray-400 text-sm mb-4">You haven't rented any mining rigs yet.</p>
                <a href="{{ route('user.miners') }}" class="bg-gradient-to-r from-blue-500 to-blue-700 text-white px-6 py-2 rounded-full font-semibold shadow-lg hover:from-blue-600 hover:to-blue-800 transition-all">
                    Rent a Rig
                </a>
            </div>
        @endforelse

        @if($minings->hasPages())
            <div class="mt-6">
                {{ paginateLinks($minings) }}
            </div>
        @endif
    </div>
</div>
@endsection

@push('script')
<script>
    $(document).ready(function() {
        function updateTimers() {
            // Next Profit Timers
            $('.next-profit-timer').each(function() {
                try {
                    let nextProfitTime = $(this).attr('data-time');
                    if (!nextProfitTime || nextProfitTime == '') return;

                    let targetDate = parseInt(nextProfitTime);
                    let now = new Date().getTime();
                    let distance = targetDate - now;

                    if (distance < 0) {
                        $(this).text('Processing...');
                        return;
                    }

                    let hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    let minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                    let seconds = Math.floor((distance % (1000 * 60)) / (1000));

                    $(this).text(hours + "h " + minutes + "m " + seconds + "s");
                } catch (e) {}
            });

            // Plan End Timers
            $('.plan-end-timer').each(function() {
                try {
                    let endTime = $(this).attr('data-time');
                    if (!endTime || endTime == '') return;

                    let targetDate = parseInt(endTime);
                    let now = new Date().getTime();
                    let distance = targetDate - now;

                    if (distance < 0) {
                        $(this).html('<span class="text-red-400">Expired</span>');
                        return;
                    }

                    let days = Math.floor(distance / (1000 * 60 * 60 * 24));
                    let hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    let minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                    let seconds = Math.floor((distance % (1000 * 60)) / (1000));

                    if (days > 0) {
                        $(this).text(days + "d " + hours + "h " + minutes + "m");
                    } else {
                        $(this).text(hours + "h " + minutes + "m " + seconds + "s");
                    }
                } catch (e) {}
            });
        }

        setInterval(updateTimers, 1000);
        updateTimers();
    });
</script>
@endpush
