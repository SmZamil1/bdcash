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
        background: linear-gradient(135deg, rgba(59, 130, 246, 0.2), rgba(147, 51, 234, 0.2));
        backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.1);
    }
</style>
<link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">

<div id="appCapsule" class="pb-20">
    <div class="px-2 py-4">
        
        {{-- Header Section --}}
        <div class="mb-6 ps-2">
            <h1 class="text-3xl font-bold text-white font-orbitron mb-1">@lang('PTC Advertisements')</h1>
            <p class="text-blue-200/60 text-sm">@lang('Verified ads, guaranteed rewards')</p>
        </div>

        {{-- Status Card --}}
        <div class="relative overflow-hidden glass-card-primary rounded-[25px] p-5 mb-8 group">
            <div class="absolute -right-10 -top-10 w-40 h-40 bg-blue-500/20 rounded-full blur-3xl group-hover:bg-blue-500/30 transition-all"></div>
            
            <div class="flex justify-between items-center relative z-10">
                <div class="flex-1">
                    <span class="bg-blue-500/20 border border-blue-400/30 text-blue-300 text-[10px] font-bold px-3 py-1 rounded-full uppercase tracking-wider mb-3 inline-block">
                        @lang('Daily Status')
                    </span>
                    
                    @if($noPlan)
                        <h3 class="text-white text-2xl font-bold font-orbitron mb-1">@lang('No Active Plan')</h3>
                        <p class="text-blue-200/70 text-xs font-medium mb-4">@lang('Please upgrade to view ads')</p>

                        <a href="{{ route('user.vip.index') }}" class="bg-gradient-to-r from-blue-500 to-indigo-600 text-white px-6 py-2 rounded-xl font-bold text-sm shadow-lg shadow-blue-500/20 inline-flex items-center gap-2 hover:scale-105 transition-transform">
                            @lang('Upgrade') <i class="las la-angle-right text-xs"></i>
                        </a>
                    @else
                        @php
                            $percent = $limit > 0 ? ($watchedToday / $limit) * 100 : 0;
                        @endphp
                        <h3 class="text-white text-3xl font-bold font-orbitron mb-1">
                            {{ $watchedToday }} <span class="text-xl text-white/50">/ {{ $limit }}</span>
                        </h3>
                        <p class="text-blue-200/70 text-xs font-medium mb-4">@lang('Ads Watched Today')</p>
                        
                        <div class="w-full bg-black/40 rounded-full h-2 mb-2 max-w-[180px] overflow-hidden">
                            <div class="bg-gradient-to-r from-blue-400 to-purple-500 h-2 rounded-full transition-all duration-1000 ease-out shadow-[0_0_10px_rgba(59,130,246,0.5)]" style="width: {{ $percent }}%"></div>
                        </div>
                        <p class="text-[10px] text-blue-300">{{ number_format($percent, 0) }}% @lang('Completed')</p>
                    @endif
                </div>

                <div class="opacity-100 relative">
                    <div class="w-20 h-20 rounded-full bg-indigo-500/20 blur-xl absolute inset-0"></div>
                     <i class="las la-mouse-pointer text-7xl text-blue-400 drop-shadow-[0_0_15px_rgba(96,165,250,0.5)]"></i>
                </div>
            </div>
        </div>

        {{-- Ads List --}}
        <div class="space-y-4">
            <div class="flex items-center justify-between px-2 mb-2">
                <h3 class="text-white font-bold font-orbitron text-lg flex items-center gap-2">
                    <i class="las la-list text-blue-400"></i> @lang('Available Ads')
                </h3>

                @if($ads->count() > 0)
                    <span class="text-[10px] text-white font-bold bg-gradient-to-r from-green-500 to-emerald-600 px-3 py-1 rounded-full shadow-lg shadow-green-500/20 border border-green-400/20">
                        {{ $ads->count() }} @lang('New')
                    </span>
                @endif
            </div>

            {{-- ADS LOOP --}}
            @forelse($ads as $ad)
            <div class="glass-card rounded-2xl p-1 relative overflow-hidden group hover:border-blue-500/30 transition-all duration-300">
                <div class="absolute inset-0 bg-gradient-to-r from-blue-600/5 to-purple-600/5 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                
                <div class="p-4 flex flex-col sm:flex-row sm:items-center justify-between gap-4 relative z-10">

                    {{-- LEFT CONTENT --}}
                    <div class="flex items-center gap-4 min-w-0">
                        <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-gray-800 to-black border border-white/10 flex items-center justify-center shadow-lg shrink-0 group-hover:scale-105 transition-transform">
                            <i class="las la-bullhorn text-2xl text-yellow-400"></i>
                        </div>

                        <div class="min-w-0 flex-1">
                            <h4 class="text-white font-bold font-orbitron text-base leading-tight mb-2 line-clamp-1 group-hover:text-blue-400 transition-colors">
                                {{ __($ad->title) }}
                            </h4>

                            <div class="flex items-center gap-3">
                                <span class="bg-blue-500/10 text-blue-300 text-[10px] font-bold px-2 py-0.5 rounded border border-blue-500/20 flex items-center gap-1">
                                    <i class="las la-clock text-[12px]"></i> {{ $ad->duration }}s
                                </span>

                                <span class="bg-green-500/10 text-green-400 text-[10px] font-bold px-2 py-0.5 rounded border border-green-500/20 flex items-center gap-1">
                                    <i class="las la-coins text-[12px]"></i> {{ showAmount($ad->amount) }}
                                </span>
                            </div>
                        </div>
                    </div>

                    {{-- BUTTON --}}
                    <div class="flex-shrink-0 w-full sm:w-auto">
                        @if($noPlan)
                            {{-- No VIP plan: show upgrade CTA --}}
                            <a href="{{ route('user.vip.index') }}" class="w-full sm:w-auto bg-gradient-to-r from-yellow-500 to-orange-500 hover:from-yellow-400 hover:to-orange-400 text-white px-6 py-2.5 rounded-xl font-bold font-orbitron text-sm shadow-lg shadow-yellow-500/20 active:scale-[0.98] transition-all flex items-center justify-center gap-2">
                                <i class="las la-crown text-sm"></i> @lang('Get VIP')
                            </a>
                        @elseif($limit > 0 && $watchedToday >= $limit)
                            {{-- Daily limit reached --}}
                            <button disabled class="w-full sm:w-auto bg-white/5 border border-white/10 text-gray-500 px-6 py-2.5 rounded-xl font-bold text-sm cursor-not-allowed uppercase tracking-wide">
                                <i class="las la-lock mr-1"></i> @lang('Limit')
                            </button>
                        @else
                            <a href="{{ route('user.ptc.show', $ad->id) }}" 
                               class="w-full sm:w-auto bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white px-6 py-2.5 rounded-xl font-bold font-orbitron text-sm shadow-lg shadow-blue-600/30 active:scale-[0.98] transition-all flex items-center justify-center gap-2">
                                @lang('View Ad') <i class="las la-external-link-alt text-xs"></i>
                            </a>
                        @endif
                    </div>

                </div>
            </div>
            @empty

            {{-- NO ADS --}}
            <div class="glass-card rounded-2xl p-8 text-center bg-white/5">
                <div class="w-20 h-20 mx-auto mb-4 bg-white/5 rounded-full flex items-center justify-center">
                    <i class="las la-inbox text-4xl text-gray-500"></i>
                </div>
                <h3 class="text-xl text-white font-bold mb-2">@lang('No Ads Available')</h3>
                <p class="text-gray-400 text-sm">@lang('Please check back later for new offers.')</p>
            </div>

            @endforelse
        </div>

    </div>
</div>
@endsection
