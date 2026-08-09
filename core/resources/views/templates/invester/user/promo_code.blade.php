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
</style>

<div class="px-4 mb-3 mt-4">
    <div class="bg-gradient-to-br from-blue-400/30 via-blue-600/30 to-blue-800/30 border border-blue-400/40 shadow-xl rounded-2xl backdrop-blur-lg p-6 relative overflow-hidden">
            
            <!-- Decorative elements -->
            <div class="absolute top-0 right-0 w-32 h-32 bg-blue-500/20 rounded-full blur-3xl -mr-10 -mt-10 pointer-events-none"></div>
            <div class="absolute bottom-0 left-0 w-32 h-32 bg-blue-500/20 rounded-full blur-3xl -ml-10 -mb-10 pointer-events-none"></div>

            <div class="mb-6 text-center relative z-10">
                <div class="w-24 h-24 bg-white/10 rounded-full shadow-lg shadow-blue-500/20 flex items-center justify-center mx-auto mb-4 border border-blue-400/30 p-2">
                    <img src="https://cdn3d.iconscout.com/3d/premium/thumb/promo-3d-icon-png-download-10660367.png" class="w-full h-full object-contain" alt="Promo">
                </div>
                <h3 class="text-2xl font-bold font-orbitron text-white mb-2">@lang('Redeem Promo Code')</h3>
                <p class="text-sm text-blue-200/80 leading-relaxed max-w-sm mx-auto">@lang('Got a special code? Enter it below to unlock exclusive rewards and bonuses instantly!')</p>
            </div>
            
            <form action="{{ route('user.promocode.apply') }}" method="POST" class="space-y-4 relative z-10">
                @csrf
                
                <div class="space-y-2">
                    <label class="font-semibold text-blue-200 text-sm ml-1">@lang('Enter Your Code')</label>
                        <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="las la-ticket-alt text-blue-400 text-xl group-focus-within:text-blue-500 transition-colors"></i>
                        </div>
                        <input type="text" name="code" class="w-full pl-12 bg-blue-900/20 border border-blue-500/30 text-white text-lg font-medium rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 block p-4 shadow-sm outline-none transition-all duration-200 placeholder-blue-300/30 uppercase tracking-widest" placeholder="CODE2024" required>
                        </div>
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full py-4 bg-gradient-to-r from-blue-600 via-blue-500 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-bold font-orbitron rounded-xl shadow-lg shadow-blue-500/30 transform transition-all duration-200 hover:-translate-y-1 active:scale-95 flex items-center justify-center gap-2 text-lg">
                        <i class="las la-gift text-2xl"></i> @lang('Claim Reward')
                    </button>
                </div>

            </form>
    </div>
</div>
@endsection
