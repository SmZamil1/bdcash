@extends($activeTemplate . 'layouts.master')

@section('content')

<style>
    /* Custom Animations specific to this page */
    @keyframes bounce-x { 0%, 100% { transform: translateX(0); } 50% { transform: translateX(5px); } }
    .animate-bounce-x { animation: bounce-x 1s infinite; }

    /* Hide scrollbar but keep functionality */
    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
</style>

<!-- Page Content -->
<div>
    <!-- Page Title -->
    <div class="px-[5px] pb-[8px] mt-4">
        <h1 class="text-3xl font-bold text-white font-[Orbitron]">Withdraw</h1>
        <p class="text-white/70 text-sm">Manage your Withdrawals</p>
    </div>

    <!-- Methods Grid -->
    <div>
        <div class="bg-gradient-to-br from-blue-400/10 via-blue-600/10 to-blue-800/10 border border-blue-400/30 shadow-xl rounded-2xl backdrop-blur-lg p-4">
            <form id="withdrawConfirmForm" action="{{ route('user.withdraw.money') }}" method="post">
                @csrf
                <input type="hidden" name="method_code" id="method_code">
                
                <div class="mb-4">
                    <h5 class="text-white font-bold text-lg flex justify-between items-center">
                        <span>Current Balance:</span>
                        <span class="text-blue-300">{{ showAmount(auth()->user()->interest_wallet, currencyFormat: false) }} {{ gs('cur_text') }}</span>
                    </h5>
                </div>

                <!-- Method Selection Grid -->
                <div class="grid grid-cols-2 gap-4 all-methods">
                    @foreach ($withdrawMethod as $method)
                    <div class="bg-white/5 border border-blue-400/20 shadow-lg hover:shadow-blue-500/20 rounded-xl p-3 flex flex-col items-center gap-2 transition-all duration-300 hover:scale-[1.02] group">
                        <div class="relative w-full aspect-square mb-2 rounded-xl overflow-hidden border border-white/10 group-hover:border-blue-400/50">
                            <div class="absolute inset-0 bg-blue-500/10 group-hover:bg-blue-500/0 transition-colors"></div>
                            <img src="{{ getImage(getFilePath('withdrawMethod') . '/' . $method->image, getFileSize('withdrawMethod')) }}" 
                                 alt="{{ __($method->name) }}" 
                                 class="w-full h-full object-cover">
                        </div>
                        <h1 class="text-center text-white text-sm font-bold mb-1 truncate w-full">{{ __($method->name) }}</h1>
                        <button type="button" 
                                data-id="{{ $method->id }}" 
                                data-resource="{{ json_encode($method) }}"
                                data-min="{{ showAmount($method->min_limit) }}"
                                data-max="{{ showAmount($method->max_limit) }}"
                                data-fix="{{ showAmount($method->fixed_charge) }}"
                                data-percent="{{ showAmount($method->percent_charge) }}"
                                class="methodBtn w-full bg-gradient-to-r from-blue-500 to-blue-700 hover:from-blue-600 hover:to-blue-800 px-4 py-1.5 rounded-full border border-white/20 text-white text-center text-xs font-bold transition shadow-lg">
                            Withdraw
                        </button>
                    </div>
                    @endforeach
                </div>

                <!-- Selected Method View (Hidden initially) -->
                <div class="selected-method-view hidden mt-4 animate-fade-in-up">
                    <div class="flex justify-between items-center mb-4 bg-blue-500/20 p-2 rounded-lg border border-blue-500/30">
                        <h1 class="text-blue-200 text-sm">
                            Selected: <b class="text-white method-name">...</b>
                        </h1>
                        <button type="button" class="text-xs bg-white/10 hover:bg-white/20 text-white px-2 py-1 rounded" id="changeMethodBtn">Change</button>
                    </div>

                    <div class="mb-3">
                        <label class="text-white/80 text-sm font-semibold mb-1 block">Withdraw Amount <span class="text-red-400">*</span></label>
                        <div class="relative">
                            <input type="number" step="any" name="amount" class="w-full form-control rounded-lg bg-white/10 border border-white/20 text-white px-3 py-3 focus:border-blue-500 focus:outline-none transition-colors amount" required placeholder="0.00">
                            <span class="absolute right-3 top-1/2 -translate-y-1/2 text-white/50 font-bold">{{ gs('cur_text') }}</span>
                        </div>
                        <p class="text-xs text-blue-300 mb-0 mt-2 flex justify-between">
                            <span>Min: <span class="min-amount">0</span></span> 
                            <span>Max: <span class="max-amount">0</span></span>
                        </p>
                    </div>

                    <div class="bg-white/5 border border-white/10 rounded-xl mb-4 p-4 space-y-2">
                        <div class="flex justify-between items-center py-1 border-b border-white/10">
                            <span class="text-white/60 text-xs">Charge</span>
                            <span class="text-purple-300 font-bold"><span class="charge-amount">0.00</span> {{ gs('cur_text') }}</span>
                        </div>
                        <div class="flex justify-between items-center py-1 border-b border-white/10">
                            <span class="text-white/60 text-xs">Rate</span>
                            <span class="text-purple-300 font-bold">1 {{ gs('cur_text') }} = <span class="conversion-rate">1</span> <span class="base-currency"></span></span>
                        </div>
                        <div class="flex justify-between items-center py-1">
                            <span class="text-white/60 text-xs">You Will Receive</span>
                            <span class="text-green-400 font-bold text-lg"><span class="final-amount">0.00</span> <span class="base-currency"></span></span>
                        </div>
                    </div>

                    <button class="bg-gradient-to-b from-blue-400 via-blue-600 to-blue-800 hover:from-blue-500 hover:to-blue-700 border border-white/20 text-white py-3 rounded-xl w-full font-bold shadow-lg shadow-blue-900/40 transition-all transform hover:scale-[1.02]" type="submit">
                        Confirm Withdraw
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>

@endsection

@push('script')
<script>
    (function ($) {
        "use strict";
        
        var selectedMethod = null;

        $('.methodBtn').on('click', function () {
            selectedMethod = $(this).data('resource');
            var minAmount = $(this).data('min');
            var maxAmount = $(this).data('max');
            
            // UI Updates
            $('.all-methods').addClass('hidden');
            $('.selected-method-view').removeClass('hidden').addClass('block');
            $('.method-name').text(selectedMethod.name);
            $('.base-currency').text(selectedMethod.currency);
            $('.conversion-rate').text(parseFloat(selectedMethod.rate).toFixed(2));
            $('.min-amount').text(minAmount);
            $('.max-amount').text(maxAmount);
            
            // Form Updates
            $('input[name=method_code]').val(selectedMethod.id);
            $('.amount').val(''); // Clear amount
            $('.charge-amount').text('0.00');
            $('.final-amount').text('0.00');
        });

        $('#changeMethodBtn').on('click', function() {
             $('.selected-method-view').addClass('hidden').removeClass('block');
             $('.all-methods').removeClass('hidden');
             $('input[name=method_code]').val('');
        });

        $('.amount').on('input', function () {
            var amount = parseFloat($(this).val());
            if (!amount || amount <= 0) {
                $('.charge-amount').text('0.00');
                $('.final-amount').text('0.00');
                return;
            }

            if (!selectedMethod) return;

            var fixed_charge = parseFloat(selectedMethod.fixed_charge);
            var percent_charge = parseFloat(selectedMethod.percent_charge);
            var rate = parseFloat(selectedMethod.rate);

            var charge = fixed_charge + (amount * percent_charge / 100);
            var afterCharge = amount - charge;
            var finalAmount = afterCharge * rate;

            $('.charge-amount').text(charge.toFixed(2));
            $('.final-amount').text(finalAmount.toFixed(2));
        });
    })(jQuery);
</script>
<style>
    @keyframes fadeInUp {
        from { opacity: 0; transform: translate3d(0, 20px, 0); }
        to { opacity: 1; transform: translate3d(0, 0, 0); }
    }
    .animate-fade-in-up {
        animation: fadeInUp 0.5s ease-out both;
    }
</style>
@endpush
