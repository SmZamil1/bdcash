@extends($activeTemplate . 'layouts.master')
@section('content')

    <div class="px-2 mt-4 mb-6">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h1 class="text-3xl font-bold text-white font-orbitron">@lang('Cyber Dice')</h1>
                <p class="text-blue-200/80 text-sm">@lang('Roll the dice and beat the odds!')</p>
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
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            
            <!-- Result/Visual Area -->
            <div class="glass-card p-6 rounded-2xl relative overflow-hidden flex flex-col items-center justify-center min-h-[400px]">
                <div class="absolute inset-0 bg-gradient-to-br from-blue-600/10 to-purple-600/10 pointer-events-none"></div>

                <!-- Dice Visual -->
                <div class="relative w-48 h-48 mb-8">
                     <div id="diceValue" class="absolute inset-0 flex items-center justify-center text-7xl font-bold text-white font-orbitron z-10 drop-shadow-[0_0_15px_rgba(59,130,246,0.5)]">50</div>
                     <svg class="w-full h-full transform -rotate-90" viewBox="0 0 100 100">
                        <circle cx="50" cy="50" r="45" stroke="currentColor" stroke-width="2" fill="transparent" class="text-white/5"></circle>
                        <circle id="diceProgress" cx="50" cy="50" r="45" stroke="currentColor" stroke-width="4" fill="transparent" class="text-blue-500" stroke-dasharray="283" stroke-dashoffset="141" stroke-linecap="round"></circle>
                     </svg>
                </div>

                <div id="diceStatus" class="text-center h-8">
                    <p class="text-xl font-bold text-white opacity-0 transition-opacity">@lang('Rolling...')</p>
                </div>

            </div>

            <!-- Controls -->
            <div class="glass-card p-6 rounded-2xl relative overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-br from-blue-600/5 to-purple-600/5 pointer-events-none"></div>
                
                <form id="diceForm" class="relative z-10 space-y-6">
                    <div class="grid grid-cols-2 gap-4">
                        <button type="button" class="type-btn bg-white/5 border border-white/10 p-3 rounded-xl hover:bg-white/10 transition-all active" data-value="over">
                            <span class="text-sm font-bold text-white">@lang('ROLL OVER')</span>
                        </button>
                        <button type="button" class="type-btn bg-white/5 border border-white/10 p-3 rounded-xl hover:bg-white/10 transition-all" data-value="under">
                            <span class="text-sm font-bold text-white">@lang('ROLL UNDER')</span>
                        </button>
                    </div>

                    <div>
                        <label class="text-xs font-bold text-blue-200 ml-1 mb-1 block uppercase">@lang('Target Number')</label>
                        <input type="range" id="targetRange" min="1" max="99" value="50" class="w-full h-2 bg-white/10 rounded-lg appearance-none cursor-pointer">
                        <div class="flex justify-between text-white font-bold mt-2">
                            <span>1</span>
                            <span id="targetDisplay" class="text-2xl text-blue-400 font-orbitron">50</span>
                            <span>99</span>
                        </div>
                    </div>

                    <div>
                        <label class="text-xs font-bold text-blue-200 ml-1 mb-1 block uppercase">@lang('Bet Amount')</label>
                        <div class="relative">
                            <input type="number" id="betAmount" step="any" class="w-full bg-black/40 border border-white/10 rounded-xl py-3 px-4 text-white font-orbitron focus:outline-none focus:border-blue-500 transition-colors" placeholder="0.00" value="1.00">
                            <span class="absolute right-4 top-1/2 -translate-y-1/2 text-sm font-bold text-gray-400">{{ gs('cur_text') }}</span>
                        </div>
                    </div>

                    
                    <button type="submit" id="rollBtn" class="w-full bg-gradient-to-r from-blue-500 to-purple-600 text-white font-bold py-4 rounded-xl shadow-lg shadow-blue-500/20 hover:scale-[1.02] active:scale-95 transition-all text-lg font-orbitron">
                        @lang('ROLL DICE')
                    </button>
                </form>

                 <!-- Balance -->
                 <div class="mt-6 bg-white/5 px-4 py-3 rounded-xl border border-white/5 flex items-center justify-between">
                    <span class="text-sm text-gray-400">@lang('Your Balance')</span>
                    <span class="text-lg font-bold text-white font-orbitron" id="userBalance">{{ showAmount(auth()->user()->interest_wallet) }} {{ gs('cur_sym') }}</span>
                </div>
            </div>

        </div>
    </div>

@endsection

@push('style')
<style>
    .type-btn.active {
        border-color: #3b82f6;
        background: rgba(59, 130, 246, 0.2);
    }
</style>
@endpush

@push('script')
<script>
    "use strict";
    
    let selectedType = 'over';
    let targetValue = 50;

    $('.type-btn').on('click', function() {
        $('.type-btn').removeClass('active');
        $(this).addClass('active');
        selectedType = $(this).data('value');
    });

    $('#targetRange').on('input', function() {
        targetValue = $(this).val();
        $('#targetDisplay').text(targetValue);
        updateOdds();
    });

    function updateOdds() {
        // Just for visual effect if you want to show multiplier info
    }

    $('#diceForm').on('submit', function(e) {
        e.preventDefault();
        
        let amount = $('#betAmount').val();
        let btn = $('#rollBtn');
        
        if(amount <= 0) {
            notify('error', 'Please enter a valid amount');
            return;
        }

        btn.prop('disabled', true).addClass('opacity-75 cursor-not-allowed');
        $('#diceStatus p').text('Rolling...').addClass('opacity-100');

        $.ajax({
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: "{{ route('user.games.cyber_dice.play') }}",
            method: "POST",
            data: { 
                bet: amount,
                target: targetValue,
                type: selectedType
            },
            success: function(response) {
                animateRoll(response.roll, response.status, response.win_amount, response.balance);
                btn.prop('disabled', false).removeClass('opacity-75 cursor-not-allowed');
            },
            error: function(e) {
                notify('error', e.responseJSON?.error || 'Something went wrong');
                btn.prop('disabled', false).removeClass('opacity-75 cursor-not-allowed');
                $('#diceStatus p').removeClass('opacity-100');
            }
        });
    });

    function animateRoll(value, status, winAmount, balance) {
        let current = 0;
        let duration = 1000;
        let start = Date.now();
        
        let interval = setInterval(() => {
            let elapsed = Date.now() - start;
            let progress = elapsed / duration;
            
            if(progress >= 1) {
                clearInterval(interval);
                finishRoll(value, status, winAmount, balance);
            } else {
                let randomVal = Math.floor(Math.random() * 100) + 1;
                $('#diceValue').text(randomVal);
                updateProgress(randomVal);
            }
        }, 50);
    }

    function updateProgress(val) {
        let circ = 2 * Math.PI * 45;
        let offset = circ - (val / 100) * circ;
        $('#diceProgress').css('stroke-dashoffset', offset);
    }

    function finishRoll(value, status, winAmount, balance) {
        $('#diceValue').text(value);
        updateProgress(value);
        $('#userBalance').text(balance + " {{ gs('cur_sym') }}");

        let statusP = $('#diceStatus p');
        if(status === 'won') {
            statusP.text('WINNER! +' + winAmount).addClass('text-green-400 opacity-100').removeClass('text-red-400');
            notify('success', 'You won ' + winAmount);
        } else {
            statusP.text('TRY AGAIN!').addClass('text-red-400 opacity-100').removeClass('text-green-400');
            notify('error', 'Better luck next time!');
        }
    }

</script>
@endpush
