@extends($activeTemplate . 'layouts.master', ['pageTitle' => trans('Deposit History')])
@section('content')
<div class="px-4">
    <div class="px-[5px] pb-[8px]">
        <h1 class="text-3xl font-bold text-white font-orbitron">@lang('Earnings & Logs')</h1>
        <p class="text-white text-sm">@lang('Deposit History')</p>
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

    <div class="my-3 space-y-6">
        @forelse($deposits as $deposit)
        <div class="transition-all duration-300 hover:scale-[1.02] hover:shadow-2xl bg-gradient-to-br from-blue-400/30 via-blue-600/30 to-blue-800/30 border border-blue-400/40 shadow-xl rounded-2xl backdrop-blur-lg px-5 py-4 flex items-center gap-5 relative overflow-hidden">
            <div class="absolute top-0 right-0 px-3 py-1 rounded-bl-xl text-[10px] font-bold uppercase
                @if($deposit->status == 1) text-green-400 bg-green-500/10 border-b border-l border-green-500/20
                @elseif($deposit->status == 2) text-yellow-400 bg-yellow-500/10 border-b border-l border-yellow-500/20
                @elseif($deposit->status == 3) text-red-400 bg-red-500/10 border-b border-l border-red-500/20
                @endif">
                @if($deposit->status == 1) @lang('Complete')
                @elseif($deposit->status == 2) @lang('Pending')
                @elseif($deposit->status == 3) @lang('Rejected')
                @endif
            </div>
            <div class="flex-shrink-0 border border-blue-400/40 shadow-xl shadow-blue-500/20 rounded-full bg-white/10 p-2">
                <img src="https://i.imgur.com/JYmedi5.png" class="w-14 h-14 rounded-full" alt="">
            </div>
            <div class="flex-1">
                <strong class="text-white font-bold text-lg block mb-1">
                    @lang('Deposit via') {{ __($deposit->gateway?->name ?? 'Gateway') }}
                </strong>
                <div class="text-xs text-white leading-relaxed">
                    <span class="font-semibold text-white">@lang('Transaction ID'):</span>
                    <b class="font-bold text-yellow-400">{{ $deposit->trx }}</b>
                    <br>
                    <span class="font-semibold text-white">@lang('Amount'):</span>
                    <b class="font-bold text-yellow-400">{{ showAmount($deposit->amount, 2, true, false, false) }} {{ gs('cur_text') }}</b>
                    <br>
                    <span class="text-white">{{ showDateTime($deposit->created_at, 'd-m-Y') }}</span> | <span class="text-white">{{ diffForHumans($deposit->created_at) }}</span>
                </div>
            </div>
        </div>
        @empty
        <div class="flex flex-col items-center justify-center py-10">
            <img width="60px" src="https://cdn-icons-png.flaticon.com/128/14031/14031919.png" alt="No data" />
            <h1 class="text-gray-400 text-sm mt-2">@lang('No Deposits Found')</h1>
        </div>
        @endforelse

        @if($deposits->hasPages())
        <div class="mt-4">
            {{ paginateLinks($deposits) }}
        </div>
        @endif
    </div>
</div>
@endsection
