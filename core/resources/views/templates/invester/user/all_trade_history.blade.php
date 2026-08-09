@extends($activeTemplate . 'layouts.master')
@section('content')

    <div class="px-[5px] pb-[8px]">
        <h1 class="text-3xl font-bold text-white font-[Orbitron]">@lang('Trade History')</h1>
        <p class="text-white text-sm">@lang('All your trading activities')</p>
    </div>

    <!-- Trade History List -->
    <div class="my-3 space-y-4">
        @forelse($trades as $trade)
        <div class="transition-all duration-300 hover:scale-[1.02] hover:shadow-2xl bg-gradient-to-br from-blue-400/30 via-blue-600/30 to-blue-800/30 border border-blue-400/40 shadow-xl rounded-2xl backdrop-blur-lg px-5 py-4 flex items-center gap-5 relative overflow-hidden">
            <div class="absolute top-0 right-0 px-3 py-1 rounded-bl-xl text-[10px] font-bold uppercase
                @if($trade->status == 1) text-green-400 bg-green-500/10 border-b border-l border-green-500/20
                @elseif($trade->status == 2) text-red-400 bg-red-500/10 border-b border-l border-red-500/20
                @else text-yellow-400 bg-yellow-500/10 border-b border-l border-yellow-500/20
                @endif">
                @if($trade->status == 1) @lang('Win')
                @elseif($trade->status == 2) @lang('Loss')
                @else @lang('Running')
                @endif
            </div>
            <div class="flex-shrink-0 border border-blue-400/40 shadow-xl shadow-blue-500/20 rounded-full bg-white/10 p-2">
                <img src="https://i.imgur.com/3sktsD4.png" class="w-14 h-14 rounded-full" alt="">
            </div>
            <div class="flex-1">
                <strong class="text-white font-bold text-lg block mb-1">
                    {{ $trade->crypto_currency }} <span class="text-xs font-normal text-blue-200">({{ __($trade->category) }})</span>
                </strong>
                <div class="text-xs text-white leading-relaxed">
                    <span class="font-semibold text-white">@lang('Amount'):</span>
                    <b class="font-bold text-yellow-400">{{ showAmount($trade->amount) }}</b>
                    <br>
                    <span class="font-semibold text-white">@lang('Result'):</span>
                    @if($trade->status == 1)
                        <b class="font-bold text-green-400">+{{ showAmount($trade->profit) }}</b>
                    @elseif($trade->status == 2)
                        <b class="font-bold text-red-400">-{{ showAmount($trade->amount) }}</b>
                    @else
                        <b class="font-bold text-yellow-400">@lang('Pending')</b>
                    @endif
                    <br>
                    <span class="text-white">{{ showDateTime($trade->created_at, 'd-m-Y') }}</span> | <span class="text-white">{{ $trade->created_at->diffForHumans() }}</span>
                </div>
            </div>
        </div>
        @empty
             <div class="p-4 rounded-xl bg-gradient-to-br from-blue-400/30 via-blue-600/30 to-blue-800/30 border border-blue-400/40">
                <div class="text-center">
                    <img class="mx-auto w-[60px]" src="https://i.imgur.com/D7pxgR7.png" alt="">
                    <h1 class="text-center font-bold !text-white !text-[10px] mt-2">@lang('No Trades Found')</h1>
                </div>
            </div>
        @endforelse

        @if($trades->hasPages())
        <div class="mt-4">
            {{ paginateLinks($trades) }}
        </div>
        @endif
    </div>

@endsection
