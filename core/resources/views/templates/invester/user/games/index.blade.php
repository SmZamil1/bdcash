@extends($activeTemplate . 'layouts.master')
@section('content')

    <div class="px-2 mt-4 mb-6">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h1 class="text-3xl font-bold text-white font-orbitron">@lang('Games Hub')</h1>
                <p class="text-blue-200/80 text-sm">@lang('Play & Earn Crypto')</p>
            </div>
            <a href="{{ route('user.deposit.index') }}" class="bg-gradient-to-r from-blue-500 to-purple-600 p-[1px] rounded-full">
                <div class="bg-black/40 backdrop-blur-sm rounded-full px-4 py-2 hover:bg-white/10 transition-all">
                    <span class="text-xs font-bold text-white flex items-center gap-1">
                        <i class="las la-plus-circle text-yellow-400 text-base"></i> @lang('Deposit')
                    </span>
                </div>
            </a>
        </div>

        <div class="grid grid-cols-1 gap-4">






            @foreach($games as $game)
                @if(in_array($game->game_key, ['head-tail', 'cyber-dice'])) @continue @endif
                <div class="glass-card p-0 rounded-2xl relative overflow-hidden group">
                    <div class="absolute inset-0 bg-gradient-to-br from-blue-600/20 to-purple-600/20 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    
                    <div class="p-4 flex items-center gap-4 relative z-10">
                        <div class="w-20 h-20 rounded-xl overflow-hidden shadow-lg shadow-blue-500/20 border border-white/10 shrink-0">
                             @if($game->image)
                                <img src="{{ url('assets/games') }}/{{ $game->image }}" class="h-full w-full object-cover transform group-hover:scale-110 transition-transform duration-500" alt="{{ $game->name }}">
                            @else
                                <div class="w-full h-full bg-blue-900/50 flex items-center justify-center">
                                    <i class="las la-gamepad text-4xl text-blue-400"></i>
                                </div>
                            @endif
                        </div>
                        
                        <div class="flex-1 min-w-0">
                            <h4 class="text-lg font-bold text-white font-orbitron truncate">{{ $game->name }}</h4>
                            <p class="text-xs text-blue-200/60 truncate">@lang('Win up to 100x')</p>
                            <div class="flex gap-2 mt-2">
                                <span class="text-[10px] bg-green-500/20 text-green-400 border border-green-500/30 px-2 py-0.5 rounded-full font-bold">
                                    @lang('Live')
                                </span>
                            </div>
                        </div>

                        <div>
                            <a href="{{ route('user.games.' . str_replace('-', '_', $game->game_key)) }}" class="bg-gradient-to-r from-blue-500 to-blue-700 text-white px-4 py-2 rounded-lg text-sm font-bold shadow-lg hover:scale-105 transition-transform">
                                Play Now
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        @if($games->isEmpty())
             <div class="text-center py-20 glass-card rounded-3xl mt-4">
                <div class="w-20 h-20 bg-white/5 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="las la-gamepad text-4xl text-white/20"></i>
                </div>
                <h3 class="text-xl font-bold text-white mb-1">@lang('Coming Soon')</h3>
                <p class="text-white/40 text-sm">@lang('New games are on the way!')</p>
            </div>
        @endif
    </div>
@endsection
