@extends($activeTemplate . 'layouts.master', ['pageTitle' => trans('Commission Log')])
@push('style')
<style>
    .d-none { display: none; }
</style>
@endpush

@section('content')
<div class="d-none" id="currentPath">Referral_Log</div>

<div class="px-4">
    <div class="px-[5px] pb-[8px]">
        <h1 class="text-3xl font-bold text-white font-orbitron">@lang('Commissions')</h1>
        <p class="text-white text-sm">@lang('Commission History')</p>
    </div>

    <!-- Logs Navigation -->
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

    <!-- Referral History -->
    <div class="my-3">
        <div class="space-y-4">
            @forelse($referralTransactions as $transaction)
            <div class="transition-all duration-300 hover:scale-[1.02] hover:shadow-2xl bg-gradient-to-br from-blue-400/30 via-blue-600/30 to-blue-800/30 border border-blue-400/40 shadow-xl rounded-2xl backdrop-blur-lg px-5 py-4 flex items-center gap-5">
                <div class="flex-shrink-0 border border-blue-400/40 shadow-xl shadow-blue-500/20 rounded-full bg-white/10 p-2">
                    <img src="https://i.imgur.com/lNjKfP8.png" class="w-14 h-14 rounded-full" alt="">
                </div>
                <div class="flex-1">
                    <strong class="text-white font-bold text-lg block mb-1">
                        {{ __($transaction->details) }}
                    </strong>
                    <div class="text-xs text-white leading-relaxed">
                        <span class="font-semibold text-white">@lang('Commission Type'):</span>
                        <b class="font-bold text-yellow-400">{{ __($transaction->remark) ?? 'Referral' }}</b>
                        <br>
                        <span class="font-semibold text-white">@lang('Earning Amount'):</span>
                        <b class="font-bold text-yellow-400">{{ showAmount($transaction->amount, 2, true, false, false) }} {{ gs('cur_text') }}</b>
                        <br>
                        <span class="text-white">{{ showDateTime($transaction->created_at, 'd-m-Y') }}</span> | <span class="text-white">{{ $transaction->created_at->diffForHumans() }}</span>
                    </div>
                </div>
            </div>
            @empty
            <div class="p-4 rounded-xl bg-gradient-to-br from-blue-400/30 via-blue-600/30 to-blue-800/30 border border-blue-400/40">
                <div class="text-center">
                    <img class="mx-auto w-[60px]" src="https://i.imgur.com/D7pxgR7.png" alt="">
                    <h1 class="text-center font-bold !text-white !text-[10px] mt-2">@lang('No Commission Logs Found')</h1>
                </div>
            </div>
            @endforelse

            @if($referralTransactions->hasPages())
            <div class="mt-4">
                {{ paginateLinks($referralTransactions) }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
