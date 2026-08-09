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
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .glass-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 40px 0 rgba(0, 0, 0, 0.5);
    }
    .icon-box {
        background: rgba(255,255,255,0.05);
        border: 1px solid rgba(255,255,255,0.1);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
</style>
<link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">

<div class="px-[5px] pb-[8px] mt-4 mb-4">
    <h1 class="text-3xl font-bold text-white font-orbitron">@lang('Important Links')</h1>
    <p class="text-blue-200/80 text-sm">@lang('Quick access to useful resources')</p>
</div>

@php
    $links = getContent('links.element', false, null, true);
@endphp

<div class="flex flex-col gap-3">
    <a href="{{ route('ticket.index') }}" class="glass-card rounded-xl p-3 flex items-center gap-4 group hover:bg-white/5 transition-all">
        <!-- Left: Image -->
        <div class="w-12 h-12 shrink-0 rounded-lg overflow-hidden shadow-md border border-white/20 group-hover:border-blue-500/50 transition-colors">
            <img src="https://cdn3d.iconscout.com/3d/premium/thumb/support-3d-icon-png-download-7838090.png" class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-300" alt="Support">
        </div>
        
        <!-- Middle: Title -->
        <div class="flex-1 min-w-0">
            <h5 class="text-white font-bold font-orbitron text-sm group-hover:text-blue-400 transition-colors truncate">
                @lang('Help & Support')
            </h5>
            <p class="text-xs text-blue-200/60 truncate">@lang('Contact our 24/7 team')</p>
        </div>
        
        <!-- Right: Icon/Button -->
        <div class="shrink-0">
            <span class="w-8 h-8 rounded-full bg-white/10 flex items-center justify-center text-blue-300 group-hover:bg-blue-500/20 group-hover:text-blue-300 transition-colors">
                <i class="las la-headset text-lg"></i>
            </span>
        </div>
    </a>

    @forelse($links as $link)
        <a href="{{ $link->data_values->url }}" target="_blank" class="glass-card rounded-xl p-3 flex items-center gap-4 group hover:bg-white/5 transition-all">
            <!-- Left: Image -->
            <div class="w-12 h-12 shrink-0 rounded-lg overflow-hidden shadow-md border border-white/20 group-hover:border-pink-500/50 transition-colors">
                <img src="{{ getImage('assets/images/frontend/links/' . @$link->data_values->image, '65x65') }}" class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-300" alt="{{ @$link->data_values->name }}">
            </div>
            
            <!-- Middle: Title -->
            <div class="flex-1 min-w-0">
                <h5 class="text-white font-bold font-orbitron text-sm group-hover:text-pink-400 transition-colors truncate">
                    {{ __(@$link->data_values->name) }}
                </h5>
                <p class="text-xs text-blue-200/60 truncate">@lang('Click to visit')</p>
            </div>
            
            <!-- Right: Icon/Button -->
            <div class="shrink-0">
                <span class="w-8 h-8 rounded-full bg-white/10 flex items-center justify-center text-blue-300 group-hover:bg-pink-500/20 group-hover:text-pink-300 transition-colors">
                    <i class="las la-external-link-alt text-lg"></i>
                </span>
            </div>
        </a>
    @empty
        <div class="glass-card rounded-2xl p-8 text-center bg-white/5">
            <div class="w-20 h-20 mx-auto mb-4 bg-white/5 rounded-full flex items-center justify-center">
                <i class="las la-folder-open text-4xl text-gray-500"></i>
            </div>
            <h3 class="text-xl text-white font-bold mb-2">@lang('No Links Found')</h3>
            <p class="text-gray-400 text-sm">@lang('Important links will appear here once added by the admin.')</p>
        </div>
    @endforelse
</div>

@endsection
