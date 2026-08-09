@extends($activeTemplate . 'layouts.master')
@section('content')

    <div class="px-2 mt-4 mb-6">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h1 class="text-3xl font-bold text-white font-orbitron">@lang('Heads & Tails')</h1>
                <p class="text-blue-200/80 text-sm">@lang('Flip & Win Double')</p>
            </div>
            <a href="{{ route('user.games.index') }}" class="bg-gradient-to-r from-blue-500 to-purple-600 p-[1px] rounded-full">
                <div class="bg-black/40 backdrop-blur-sm rounded-full px-4 py-2 hover:bg-white/10 transition-all">
                    <span class="text-xs font-bold text-white flex items-center gap-1">
                        <i class="las la-arrow-left text-yellow-400 text-base"></i> @lang('Back')
                    </span>
                </div>
            </a>
        </div>

        <!-- Game Container -->
        <div class="glass-card p-6 rounded-2xl relative overflow-hidden flex flex-col items-center justify-center min-h-[400px]">
            <div class="absolute inset-0 bg-gradient-to-br from-blue-600/10 to-purple-600/10 pointer-events-none"></div>

            <!-- Coin Container -->
            <div id="coin" class="w-40 h-40 relative mb-8 transform-style-3d transition-transform duration-[3000ms] ease-out">
                <div class="absolute inset-0 backface-hidden">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/a/a0/2006_Quarter_Proof.png" class="w-full h-full object-contain drop-shadow-2xl" alt="Heads">
                </div>
                <!-- Tails Image (flipped) -->
                <div class="absolute inset-0 backface-hidden rotate-y-180" style="transform: rotateY(180deg);">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/7/70/2006_Quarter_Reverse_Proof.png" class="w-full h-full object-contain drop-shadow-2xl" alt="Tails">
                </div>
            </div>

            <!-- Result Message -->
            <div id="resultMessage" class="h-8 mb-4 text-center">
                <p class="text-white text-lg font-bold opacity-0 transition-opacity duration-300">@lang('You Won!')</p>
            </div>

            <!-- Controls -->
            <div class="w-full max-w-sm space-y-4 relative z-10">
                <!-- Selection -->
                <div class="grid grid-cols-2 gap-4">
                    <button type="button" class="selection-btn bg-white/5 border border-white/10 p-3 rounded-xl hover:bg-white/10 transition-all flex flex-col items-center gap-2 group active" data-value="heads">
                        <span class="w-4 h-4 rounded-full border-2 border-yellow-400"></span>
                        <span class="text-sm font-bold text-white group-hover:text-yellow-400">@lang('HEADS')</span>
                    </button>
                    <button type="button" class="selection-btn bg-white/5 border border-white/10 p-3 rounded-xl hover:bg-white/10 transition-all flex flex-col items-center gap-2 group" data-value="tails">
                        <span class="w-4 h-4 rounded-full border-2 border-gray-400"></span>
                        <span class="text-sm font-bold text-white group-hover:text-blue-400">@lang('TAILS')</span>
                    </button>
                </div>

                <!-- Input -->
                <div class="relative">
                    <label class="text-xs font-bold text-blue-200 ml-1 mb-1 block uppercase">@lang('Bet Amount')</label>
                    <div class="relative">
                        <input type="number" id="betAmount" step="any" class="w-full bg-black/40 border border-white/10 rounded-xl py-3 px-4 text-white font-orbitron focus:outline-none focus:border-blue-500 transition-colors" placeholder="0.00">
                        <span class="absolute right-4 top-1/2 -translate-y-1/2 text-sm font-bold text-gray-400">{{ gs('cur_text') }}</span>
                    </div>
                </div>

                <!-- Action -->
                <button type="button" id="flipBtn" class="w-full bg-gradient-to-r from-yellow-400 to-orange-500 text-black font-bold py-4 rounded-xl shadow-lg shadow-orange-500/20 hover:scale-[1.02] active:scale-95 transition-all text-lg font-orbitron">
                    @lang('FLIP COIN')
                </button>
            </div>
            
             <!-- Balance -->
            <div class="mt-6 bg-white/5 px-4 py-2 rounded-full border border-white/5">
                <span class="text-xs text-gray-400 block text-center">@lang('Your Balance')</span>
                <span class="text-lg font-bold text-white font-orbitron" id="userBalance">{{ showAmount(auth()->user()->interest_wallet) }} {{ gs('cur_sym') }}</span>
            </div>

        </div>
    </div>

@endsection

@push('style')
<style>
    .transform-style-3d { transform-style: preserve-3d; }
    .backface-hidden { backface-visibility: hidden; -webkit-backface-visibility: hidden; }
    .rotate-y-180 { transform: rotateY(180deg); }
    
    .selection-btn.active { background: rgba(59, 130, 246, 0.2); border-color: #3b82f6; }
    .selection-btn.active span:first-child { background: #3b82f6; border-color: #3b82f6; }

    @keyframes spinHeads {
        0% { transform: rotateY(0); }
        100% { transform: rotateY(1800deg); } /* 5 spins landing on heads (0deg mod 360) */
    }
    @keyframes spinTails {
        0% { transform: rotateY(0); }
        100% { transform: rotateY(1980deg); } /* 5.5 spins landing on tails (180deg mod 360) */
    }
</style>
@endpush

@push('script')
<script>
    "use strict";
    
    let selectedSide = 'heads';
    
    $('.selection-btn').on('click', function() {
        $('.selection-btn').removeClass('active');
        $(this).addClass('active');
        selectedSide = $(this).data('value');
    });

    $('#flipBtn').on('click', function() {
        let amount = parseFloat($('#betAmount').val());
        if(isNaN(amount) || amount <= 0) {
            notify('error', 'Please enter a valid amount');
            return;
        }

        let btn = $(this);
        let coin = $('#coin');
        let msg = $('#resultMessage p');
        
        // Reset state
        msg.addClass('opacity-0');
        btn.prop('disabled', true).addClass('opacity-75 cursor-not-allowed');
        
        // Optimistic UI - start generic spin
        // We need server result to know where to land
        
        $.ajax({
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: "{{ route('user.games.head_tail.play') }}",
            method: "POST",
            data: { amount: amount, choose: selectedSide },
            success: function(response) {
                if(response.error) {
                    notify('error', response.error);
                    btn.prop('disabled', false).removeClass('opacity-75 cursor-not-allowed');
                    return;
                }
                
                // Determine rotation based on result
                // If result is 'heads', we want rotation to be multiple of 360 (0 deg visual)
                // If result is 'tails', we want rotation to be 180 deg visual
                
                // We add lots of spins (e.g. 5 full spins = 1800deg)
                // 1800 + 0 = 1800 (Heads)
                // 1800 + 180 = 1980 (Tails)
                
                let spins = 1800; 
                let targetRotation = spins + (response.result === 'tails' ? 180 : 0);
                
                // Apply rotation via JS to control the exact degree
                coin.css({
                    'transition': 'transform 3s cubic-bezier(0.25, 1, 0.5, 1)',
                    'transform': `rotateY(${targetRotation}deg)`
                });

                setTimeout(function() {
                    // Update Balance
                    $('#userBalance').text(response.balance);
                    
                    // visual feedback
                    if(response.win) {
                        msg.text("@lang('You Won') " + response.win_amount + " {{ gs('cur_sym') }}!").removeClass('text-red-500').addClass('text-green-400 opacity-100');
                        notify('success', 'You Won!');
                    } else {
                         msg.text("@lang('You Lost!')").removeClass('text-green-400').addClass('text-red-500 opacity-100');
                         notify('error', 'You Lost!');
                    }
                    
                    btn.prop('disabled', false).removeClass('opacity-75 cursor-not-allowed');
                    
                    // Reset rotation logic for next spin (remove transition to reset instantly or keep adding?)
                    // For simplicity, we keep adding or just leave it. If we click again, we need to reset or add more.
                    // Better to reset 'transform' after a delay if we want to spin from 0 again, 
                    // BUT resetting without transition might flicker.
                    // Instead, we just keep incrementing the rotation in a real implementation.
                    // For this simple version, let's reset after a moment silently?
                    setTimeout(() => {
                        coin.css({'transition': 'none', 'transform': response.result === 'tails' ? 'rotateY(180deg)' : 'rotateY(0deg)'});
                    }, 100);

                }, 3000);
            },
            error: function(e) {
                notify('error', 'Something went wrong');
                btn.prop('disabled', false).removeClass('opacity-75 cursor-not-allowed');
            }
        });
    });
</script>
@endpush
