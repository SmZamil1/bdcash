@forelse($trades as $trade)
    <div class="relative overflow-hidden rounded-xl bg-white/5 border border-white/10 shadow-lg backdrop-blur-md group">
        <!-- Status Indicator Side Bar -->
        <div class="absolute left-0 top-0 bottom-0 w-1 
            @if($trade->status == 0) bg-blue-500/80
            @elseif($trade->status == 1) bg-green-500/80
            @elseif($trade->status == 2) bg-red-500/80
            @else bg-gray-500/80
            @endif">
        </div>

        <div class="p-3 pl-4 flex items-center justify-between gap-2">
            <!-- Asset & Type -->
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full flex items-center justify-center border 
                    @if($trade->type == 'up') bg-green-500/10 border-green-500/30 text-green-400
                    @else bg-red-500/10 border-red-500/30 text-red-400
                    @endif">
                    <i class="las @if($trade->type == 'up') la-arrow-up @else la-arrow-down @endif text-lg"></i>
                </div>
                <div>
                    <h4 class="text-white font-bold text-sm font-orbitron leading-tight">{{ $trade->crypto_currency }}</h4>
                    <span class="text-[9px] uppercase font-bold tracking-widest 
                        @if($trade->type == 'up') text-green-400 @else text-red-400 @endif">
                        {{ __($trade->type == 'up' ? 'Call' : 'Put') }}
                    </span>
                </div>
            </div>

            <!-- Investment & Result Stats -->
            <div class="text-right flex-1 pr-1">
                <div class="text-white text-xs font-bold">{{ showAmount($trade->amount) }}</div>
                
                @if($trade->status == 0)
                    <div class="flex items-center justify-end gap-1 mt-0.5">
                        <i class="las la-clock text-blue-400 text-xs animate-pulse"></i>
                        <span class="text-[10px] font-mono text-blue-400 font-bold countdown" data-finish="{{ $trade->finish_at->timestamp * 1000 }}">...</span>
                    </div>
                @elseif($trade->status == 1)
                    <div class="text-[10px] font-bold text-green-400 mt-0.5">
                        Profit: +{{ showAmount($trade->profit) }}
                    </div>
                @elseif($trade->status == 2)
                    <div class="text-[10px] font-bold text-red-400 mt-0.5">Loss: -{{ showAmount($trade->amount) }}</div>
                @elseif($trade->status == 3)
                    <div class="text-[10px] font-bold text-gray-400 mt-0.5">Draw</div>
                @endif
            </div>

            <!-- Status Icons/Time Badge -->
            <div class="text-right min-w-[50px]">
                @if($trade->status == 0)
                     <span class="inline-block text-[8px] font-bold text-blue-200 bg-blue-500/20 px-1.5 py-0.5 rounded-full border border-blue-500/30 uppercase">Open</span>
                @else
                    <div class="text-[9px] text-gray-500 font-mono flex flex-col items-end leading-none">
                        <span class="mb-0.5">{{ $trade->finish_at->format('d M') }}</span>
                        <span>{{ $trade->finish_at->format('H:i') }}</span>
                    </div>
                @endif
            </div>
        </div>
    </div>
@empty
    <div class="text-center py-8 bg-white/5 rounded-xl border border-white/10 border-dashed">
        <i class="las la-history text-3xl text-gray-600 mb-2"></i>
        <p class="text-gray-500 text-[11px] font-orbitron uppercase tracking-widest">No Recent Activity</p>
    </div>
@endforelse
