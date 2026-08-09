@extends($activeTemplate . 'layouts.master', ['pageTitle' => trans('Transactions')])
@section('content')
<style>
    @keyframes spin-slow {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
    @keyframes pulse-slow {
        0%, 100% { opacity: 0.25; }
        50% { opacity: 0.5; }
    }
    .animate-spin-slow { animation: spin-slow 20s linear infinite; }
    .animate-pulse-slow { animation: pulse-slow 3s ease-in-out infinite; }
    
    /* Font from Portfolio */
    @import url('https://fonts.googleapis.com/css2?family=Orbitron:wght@400..900&display=swap');
    .font-orbitron { font-family: 'Orbitron', 'sans-serif'; }
</style>

<div class="px-4">
    <div class="px-[5px] pb-[8px]">
        <h1 class="text-3xl font-bold text-white font-orbitron">@lang('Wallet')</h1>
        <p class="text-white text-sm">@lang('Wallet History')</p>
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

    <div class="space-y-4 pb-20">
        @forelse($transactions as $trx)
        <!-- Transaction Item -->
        <div class="transition-all duration-300 hover:scale-[1.02] hover:shadow-2xl bg-gradient-to-br from-blue-400/30 via-blue-600/30 to-blue-800/30 border border-blue-400/40 shadow-xl rounded-2xl backdrop-blur-lg px-5 py-4 flex items-center gap-5">
            <div class="flex-shrink-0 border border-blue-400/40 shadow-xl shadow-blue-500/20 rounded-full bg-white/10 p-2">
                @if($trx->trx_type == '+')
                    <img src="https://i.imgur.com/3sktsD4.png" class="w-14 h-14 rounded-full" alt="Income">
                @else
                    <img src="https://i.imgur.com/3sktsD4.png" class="w-14 h-14 rounded-full" alt="Expense">
                @endif
            </div>
            <div class="flex-1">
                <strong class="text-white font-bold text-lg block mb-1">
                    {{ __($trx->details) }}
                </strong>
                <div class="text-xs text-white leading-relaxed">
                    <span class="font-semibold text-white">@lang('Transaction ID'):</span>
                    <b class="font-bold text-yellow-400">{{ $trx->trx }}</b>
                    <br>
                    <span class="font-semibold text-white">@lang('Amount'):</span>
                    <b class="font-bold {{ $trx->trx_type == '+' ? 'text-green-400' : 'text-red-400' }}">{{ $trx->trx_type }} {{ showAmount($trx->amount, 2, true, false, false) }} {{ gs('cur_text') }}</b>
                    <br>
                    <span class="font-semibold text-white">@lang('Post Balance'):</span>
                    <b class="font-bold text-yellow-400">{{ showAmount($trx->post_balance, 2, true, false, false) }} {{ gs('cur_text') }}</b>
                    <br>
                    <span class="text-white">{{ showDateTime($trx->created_at, 'd-m-Y') }}</span> | <span class="text-white">{{ $trx->created_at->diffForHumans() }}</span>
                </div>
            </div>
        </div>
        @empty
        <div class="relative bg-gray-900/40 backdrop-blur-xl border border-white/10 rounded-3xl p-8 text-center">
             <div class="w-24 h-24 mx-auto mb-4 opacity-50">
                <img src="https://img.icons8.com/3d-fluency/94/open-box.png" alt="Empty" class="w-full h-full object-contain">
            </div>
            <h3 class="text-white font-bold text-lg mb-1">@lang('No Transactions')</h3>
            <p class="text-white/40 text-sm">@lang('No transaction history found yet.')</p>
        </div>
        @endforelse

        @if($transactions->hasPages())
        <div class="mt-6">
            {{ paginateLinks($transactions) }}
        </div>
        @endif
    </div>
</div>
@endsection
