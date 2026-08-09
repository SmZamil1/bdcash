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
        background: linear-gradient(135deg, rgba(59, 130, 246, 0.1), rgba(147, 51, 234, 0.1));
        backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.1);
    }
    
    .plan-card {
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid rgba(255, 255, 255, 0.05);
        border-radius: 20px;
        transition: all 0.3s ease;
        position: relative;
        overflow: visible;
    }
    .plan-card:hover {
        transform: translateY(-5px);
        background: rgba(255, 255, 255, 0.06);
        border-color: rgba(59, 130, 246, 0.3);
    }
    .plan-card.active {
        border: 1px solid rgba(16, 185, 129, 0.5);
        box-shadow: 0 0 20px rgba(16, 185, 129, 0.2);
    }
    
    .active-bar {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 4px;
        background: linear-gradient(90deg, #10b981, #34d399);
        box-shadow: 0 2px 10px rgba(16, 185, 129, 0.4);
        border-radius: 20px 20px 0 0;
    }
</style>
<link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">

<div id="appCapsule" class="pb-20">
    <div class="px-2 py-4">
        
        {{-- Header Section --}}
        <div class="mb-6 ps-2">
            <h1 class="text-3xl font-bold text-white font-orbitron mb-1">@lang('VIP Membership')</h1>
            <p class="text-blue-200/60 text-sm">@lang('Upgrade to unlock higher earning limits')</p>
        </div>

        {{-- Current Status Card --}}
        <div class="relative overflow-hidden glass-card-primary rounded-[25px] p-5 mb-8 group">
            <div class="absolute -right-10 -top-10 w-40 h-40 bg-blue-500/20 rounded-full blur-3xl group-hover:bg-blue-500/30 transition-all"></div>
            
            <div class="flex justify-between items-center relative z-10">
                <div class="flex-1">
                    <span class="bg-blue-500/20 border border-blue-400/30 text-blue-300 text-[10px] font-bold px-3 py-1 rounded-full uppercase tracking-wider mb-3 inline-block">
                        @lang('Current Status')
                    </span>
                    
                    @if(auth()->user()->vip_id && auth()->user()->vip_expiry > now())
                        <h3 class="text-white text-2xl font-bold font-orbitron mb-1">{{ __(auth()->user()->vipPlan->name) }}</h3>
                        <p class="text-green-400 text-xs font-bold mb-4 flex items-center gap-1">
                            <i class="las la-check-circle"></i> @lang('Plan Active')
                        </p>

                        <div class="flex items-center gap-3">
                             <div class="bg-black/30 p-2 px-3 rounded-xl border border-white/10 backdrop-blur-md">
                                <span class="block text-[10px] text-gray-400 uppercase font-bold text-center mb-0.5">@lang('Expires')</span>
                                <span class="block text-white text-lg font-bold font-orbitron text-center">
                                    {{ intval(abs(\Carbon\Carbon::parse(auth()->user()->vip_expiry)->diffInDays(now()))) }}<span class="text-[10px] font-normal text-gray-400 ml-1">Days</span>
                                </span>
                            </div>
                            <div class="bg-black/30 p-2 px-3 rounded-xl border border-white/10 backdrop-blur-md">
                                <span class="block text-[10px] text-gray-400 uppercase font-bold text-center mb-0.5">@lang('Limit')</span>
                                <span class="block text-white text-lg font-bold font-orbitron text-center">
                                    {{ auth()->user()->vipPlan->daily_ad_limit }}<span class="text-[10px] font-normal text-gray-400 ml-1">PTC</span>
                                </span>
                            </div>
                        </div>
                    @else
                        <h3 class="text-white text-2xl font-bold font-orbitron mb-1">@lang('No Active Plan')</h3>
                        <p class="text-gray-400 text-xs font-medium mb-4">@lang('Limited functionality')</p>
                        <a href="#plans-grid" class="bg-gradient-to-r from-blue-500 to-indigo-600 text-white px-6 py-2 rounded-xl font-bold text-sm shadow-lg shadow-blue-500/20 inline-flex items-center gap-2 hover:scale-105 transition-transform">
                            @lang('View Plans') <i class="las la-arrow-down text-xs"></i>
                        </a>
                    @endif
                </div>

                <div class="opacity-100 relative">
                    <div class="w-24 h-24 rounded-full bg-indigo-500/20 blur-xl absolute inset-0"></div>
                     @if(auth()->user()->vip_id && auth()->user()->vip_expiry > now())
                        <img src="https://cdn-icons-png.flaticon.com/512/12417/12417933.png" class="w-16 h-16 object-contain drop-shadow-[0_0_15px_rgba(59,130,246,0.5)] active-wiggle" alt="VIP">
                     @else
                        <img src="https://cdn-icons-png.flaticon.com/512/12417/12417933.png" class="w-16 h-16 object-contain grayscale opacity-40" alt="VIP">
                     @endif
                </div>
            </div>
        </div>

        {{-- Plans Grid --}}
        <div id="plans-grid" class="mb-4">
            <h3 class="text-white font-bold font-orbitron text-lg flex items-center gap-2 mb-4 px-2">
                <i class="las la-layer-group text-blue-400"></i> @lang('Available Plans')
            </h3>

            <div class="space-y-6">
                @foreach ($plans as $plan)
                    @php
                        $isUserPlan = (auth()->user()->vip_id == $plan->id && auth()->user()->vip_expiry > now());
                    @endphp
                    
                    <div class="glass-card rounded-[25px] p-5 relative overflow-hidden group {{ $isUserPlan ? 'border-green-500/50 shadow-[0_0_20px_rgba(16,185,129,0.1)]' : '' }}">
                         @if($isUserPlan)
                            <div class="active-bar"></div>
                            <div class="absolute top-4 right-4 bg-green-500/20 border border-green-500/30 text-green-400 text-[10px] font-bold px-3 py-1 rounded-full uppercase z-20">
                                @lang('Active')
                            </div>
                         @endif

                        <div class="flex items-start justify-between mb-6 relative z-10">
                            <div>
                                <h2 class="text-2xl font-bold text-white font-orbitron mb-1">{{ __($plan->name) }}</h2>
                                <h3 class="text-blue-400 font-bold font-orbitron text-xl">
                                    {{ showAmount($plan->price) }}
                                </h3>
                            </div>
                        </div>

                        <!-- Divider -->
                        <div class="h-px bg-gradient-to-r from-transparent via-white/10 to-transparent mb-6"></div>

                        <!-- Features -->
                        <div class="space-y-3 mb-8">
                            <div class="flex items-center justify-between">
                                <span class="text-gray-400 text-sm">@lang('Daily Ads Limit')</span>
                                <span class="text-white font-bold font-orbitron">{{ $plan->daily_ad_limit }} <span class="text-xs text-gray-500">PTC</span></span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-gray-400 text-sm">@lang('Validity Period')</span>
                                <span class="text-white font-bold font-orbitron">{{ $plan->validity }} <span class="text-xs text-gray-500">Days</span></span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-gray-400 text-sm">@lang('Support')</span>
                                <span class="text-green-400 font-bold text-xs bg-green-500/10 px-2 py-0.5 rounded">@lang('PREMIUM')</span>
                            </div>
                        </div>

                        {{-- Action Button --}}
                        <div class="relative z-10">
                            @if($isUserPlan)
                                <button disabled class="w-full bg-green-600/20 border border-green-500/30 text-green-400 py-3 rounded-xl font-bold text-sm flex items-center justify-center gap-2 cursor-not-allowed">
                                    <i class="las la-check-circle text-lg"></i> @lang('Currently Active')
                                </button>
                            @else
                                <form action="{{ route('user.vip.buy') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $plan->id }}">
                                    <button type="submit" class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white py-3 rounded-xl font-bold font-orbitron text-sm shadow-lg shadow-blue-600/30 active:scale-[0.98] transition-all flex items-center justify-center gap-2">
                                        @lang('Unlock Plan') <i class="las la-lock-open text-xs"></i>
                                    </button>
                                </form>
                            @endif
                        </div>

                        <!-- Decoration -->
                        <div class="absolute -bottom-10 -left-10 w-32 h-32 bg-purple-500/10 rounded-full blur-2xl"></div>
                        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full h-full bg-gradient-to-b from-white/5 to-transparent skew-y-12 opacity-0 group-hover:opacity-10 transition-opacity pointer-events-none"></div>
                    </div>
                @endforeach
            </div>
        </div>
        
    </div>
</div>

@endsection
