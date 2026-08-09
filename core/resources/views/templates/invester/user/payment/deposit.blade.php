@extends($activeTemplate . 'layouts.master')

@section('content')
@php
    $planId = request()->query('plan_id');
    $plan = $planId ? App\Models\Plan::find($planId) : null;
@endphp

<div class="px-4">
    <div class="px-[5px] pb-[8px]">
        <h1 class="text-3xl font-bold text-white font-[Orbitron]">
            {{ $plan ? 'Deposit' : 'Deposit Funds' }}
        </h1>
        <p class="text-white text-sm">
            {{ $plan ? 'Invest on - ' . __($plan->name) : 'Add funds to your wallet' }}
        </p>
    </div>

    <div class="grid grid-cols-2 gap-4 my-4 mb-24">
        @foreach ($gatewayCurrency as $data)
            <div class="transition-all duration-300 hover:scale-[1.02] hover:shadow-2xl bg-gradient-to-br from-blue-400/30 via-blue-600/30 to-blue-800/30 border border-blue-400/30 shadow-xl rounded-2xl backdrop-blur-lg p-3 flex flex-col items-center gap-3">
                <div class="w-[7rem] h-[7rem] rounded-xl overflow-hidden border-4 border-white/30 shadow-xl shadow-blue-400/20 flex items-center justify-center bg-white/10 mb-2">
                    <img src="{{ getImage(getFilePath('gateway') . '/' . $data->method->image, getFileSize('gateway')) }}" alt="{{ $data->name }}" class="w-full h-full object-cover">
                </div>
                <h5 class="text-white text-base font-bold text-center mb-2">
                    {{ __($data->name) }}
                </h5>
                 @if($plan)
                    <button 
                        data-href="{{ route('user.invest.submit') }}" 
                        data-id="{{ $data->id }}"
                        data-gateway-id="{{ $data->method_code }}"
                        data-currency="{{ $data->currency }}"
                        class="bg-gradient-to-r from-blue-500 to-blue-700 hover:from-blue-600 hover:to-blue-800 text-white px-4 py-2 rounded-full font-semibold w-full shadow transition paynow">
                        Pay Now
                    </button>
                @else
                    <button 
                        data-href="{{ route('user.deposit.insert') }}" 
                        data-id="{{ $data->method_code }}"
                        data-currency="{{ $data->currency }}"
                        class="bg-gradient-to-r from-blue-500 to-blue-700 hover:from-blue-600 hover:to-blue-800 text-white px-4 py-2 rounded-full font-semibold w-full shadow transition paynow">
                        Pay Now
                    </button>
                @endif
            </div>
        @endforeach
    </div>
</div>

<!-- Modal -->
<div id="paynow" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm"
     onclick="if(event.target === this) closePaynowModal()">
    <div id="paynow-modal-content"
         class="bg-gradient-to-br from-blue-900/95 via-blue-800/95 to-blue-900/95 rounded-2xl shadow-2xl w-full max-w-lg mx-4 border border-blue-400/30
         opacity-0 scale-95 pointer-events-none transition-all duration-300 transform">
        <form action="" method="post">
            @csrf
            <input type="hidden" name="gateway"> 
            <input type="hidden" name="currency">
            
            @if($plan)
                 <input type="hidden" name="plan_id" value="{{ $plan->id }}">
                 <input type="hidden" name="wallet_type"> 
                 <input type="hidden" name="invest_time" value="invest_now">
            @endif

            <div class="flex justify-between items-center p-4 border-b border-white/10">
                <h5 class="text-lg font-bold text-white">
                    {{ $plan ? 'Invest on ' . $plan->name : 'Deposit Funds' }}
                </h5>
                <button type="button" onclick="closePaynowModal()" class="text-white/80 hover:text-white text-2xl leading-none">
                    &times;
                </button>
            </div>
            
            <div class="p-4">
                <div class="row">
                    <div class="form-group w-full">
                        <label class="text-white/80 text-sm font-semibold mb-1">Amount</label>
                        <input type="number" step="any" name="amount" class="form-control rounded-lg bg-white/10 border border-white/20 text-white px-3 py-2 w-full"
                            value="{{ $plan ? ($plan->fixed_amount > 0 ? getAmount($plan->fixed_amount) : '') : '' }}"
                            placeholder="Enter Amount"
                            {{ ($plan && $plan->fixed_amount > 0) ? 'readonly' : '' }}
                            required >
                    </div>
                </div>
            </div>
            
            <div class="p-4 border-t border-white/10 text-right">
                <button type="button" onclick="closePaynowModal()" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg text-sm font-semibold transition">
                    Close
                </button>
                <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-semibold transition main-btn">
                    {{ $plan ? 'Invest Now' : 'Deposit Now' }}
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('script')
<script>
    function openPaynowModal(action, id, currency, gatewayId) {
        var modal = $('#paynow');
        var form = modal.find('form');
        form.attr('action', action);
        
        @if($plan)
            // For investment: wallet_type is the gateway id in gateway_currencies table
            form.find('input[name=wallet_type]').val(id);
            // gateway is method_code
            form.find('input[name=gateway]').val(gatewayId);
        @else
            // For deposit: gateway is method_code (which is passed as id in the else block above)
            form.find('input[name=gateway]').val(id); 
        @endif
        
        form.find('input[name=currency]').val(currency);

        // Show modal with animation
        modal.removeClass('hidden');
        setTimeout(function() {
            $('#paynow-modal-content').removeClass('opacity-0 scale-95 pointer-events-none')
                                     .addClass('opacity-100 scale-100 pointer-events-auto');
        }, 10);
    }

    function closePaynowModal() {
        $('#paynow-modal-content').removeClass('opacity-100 scale-100 pointer-events-auto')
                                 .addClass('opacity-0 scale-95 pointer-events-none');
        setTimeout(function() {
            $('#paynow').addClass('hidden');
        }, 300);
    }

    $(function() {
        'use strict'
        $('.paynow').on('click', function() {
            openPaynowModal(
                $(this).data('href'), 
                $(this).data('id'), 
                $(this).data('currency'),
                $(this).data('gateway-id') // This is method_code
            );
        });
    });
</script>
@endpush
