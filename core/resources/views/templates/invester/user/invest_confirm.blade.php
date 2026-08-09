@extends($activeTemplate . 'layouts.master')
@section('content')

<div class="px-4">
    <div class="px-[5px] pb-[8px]">
        <h1 class="text-3xl font-bold text-white font-[Orbitron]">@lang('Confirm Investment')</h1>
        <p class="text-white text-sm">@lang('Select payment method and confirm your investment')</p>
    </div>

    <div class="max-w-4xl mx-auto mb-20">
        <div class="bg-gradient-to-br from-purple-900/40 via-blue-900/40 to-indigo-900/40 backdrop-blur-xl border border-blue-400/30 rounded-3xl p-6 shadow-2xl">
            
            <div class="flex flex-col md:flex-row gap-8">
                <!-- Plan Details Card -->
                <div class="md:w-1/3 space-y-4">
                    <div class="bg-white/5 rounded-2xl p-6 border border-white/10 text-center relative overflow-hidden group">
                        <div class="absolute inset-0 bg-gradient-to-br from-purple-600/20 to-blue-600/20 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                        
                        <h2 class="text-2xl font-bold font-[Orbitron] text-white mb-2">{{ __($plan->name) }}</h2>
                        <div class="text-3xl font-black text-yellow-400 mb-4 font-[Orbitron]">
                            @if ($plan->fixed_amount > 0)
                                {{ showAmount($plan->fixed_amount) }} {{ gs('cur_text') }}
                            @else
                                {{ showAmount($plan->minimum) }} - {{ showAmount($plan->maximum) }} {{ gs('cur_text') }}
                            @endif
                        </div>

                        <div class="space-y-2 text-sm text-gray-300">
                            <div class="flex justify-between border-b border-white/10 pb-2">
                                <span>@lang('Interest')</span>
                                <span class="text-green-400 font-bold">
                                    {{ showAmount($plan->interest) }} {{ $plan->interest_type == 1 ? '%' : gs('cur_text') }}
                                </span>
                            </div>
                            <div class="flex justify-between border-b border-white/10 pb-2">
                                <span>@lang('Repeat')</span>
                                <span class="text-blue-300 font-bold">
                                    @if ($plan->lifetime == 0)
                                        {{ __($plan->repeat_time) }} @lang('Times')
                                    @else
                                        @lang('Lifetime')
                                    @endif
                                </span>
                            </div>
                            <div class="flex justify-between">
                                <span>@lang('Every')</span>
                                <span class="text-purple-300 font-bold">{{ __($plan->timeSetting->name) }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Investment Form -->
                <div class="md:w-2/3">
                    <form action="{{ route('user.invest.submit') }}" method="post" id="investForm">
                        @csrf
                        <input type="hidden" name="plan_id" value="{{ $plan->id }}">

                        <div class="space-y-6">
                            <!-- Calculation Info -->
                            <div class="bg-blue-600/10 border border-blue-500/30 rounded-xl p-4 text-center">
                                <h6 class="text-lg font-bold text-white mb-1 investAmountRange">
                                    @if ($plan->fixed_amount > 0)
                                        @lang('Invest'): {{ showAmount($plan->fixed_amount) }} {{ gs('cur_text') }}
                                    @else
                                        @lang('Invest'): {{ showAmount($plan->minimum) }} - {{ showAmount($plan->maximum) }} {{ gs('cur_text') }}
                                    @endif
                                </h6>
                                <p class="text-green-400 font-bold text-xl mt-2 calculatedInterest">@lang('Total Profit'): <span class="profitAmount">0.00</span> {{ gs('cur_text') }}</p>
                            </div>

                            <div class="grid grid-cols-1 gap-6">
                                <!-- Wallet Selection -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-300 mb-2">@lang('Select Payment Method')</label>
                                    <div class="relative">
                                        <select class="w-full bg-[#1a1a2e] border border-blue-500/30 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-blue-500 transition-colors appearance-none" name="wallet_type" required>
                                            <option value="">@lang('Select One')</option>
                                            @if (auth()->user()->deposit_wallet > 0)
                                                <option value="deposit_wallet">@lang('Deposit Wallet - ' . showAmount(auth()->user()->deposit_wallet))</option>
                                            @endif
                                            @if (auth()->user()->interest_wallet > 0)
                                                <option value="interest_wallet">@lang('Interest Wallet - ' . showAmount(auth()->user()->interest_wallet))</option>
                                            @endif
                                            @foreach ($gatewayCurrency as $data)
                                                <option value="{{ $data->id }}" @selected(old('wallet_type') == $data->method_code) data-gateway="{{ $data }}">{{ __($data->name) }}</option>
                                            @endforeach
                                        </select>
                                        <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-white">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                        </div>
                                    </div>
                                    <code class="gateway-info rate-info hidden text-xs mt-2 text-cyan-300 block">@lang('Rate'): 1 {{ gs('cur_text') }} = <span class="gateway-rate"></span> <span class="method_currency"></span></code>
                                </div>

                                <!-- Amount Input -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-300 mb-2">@lang('Invest Amount')</label>
                                    <div class="relative flex items-center">
                                        <input type="number" step="any" min="0" class="w-full bg-[#1a1a2e] border border-blue-500/30 rounded-l-xl px-4 py-3 text-white focus:outline-none focus:border-blue-500 transition-colors" name="amount" value="{{ $plan->fixed_amount > 0 ? getAmount($plan->fixed_amount) : '' }}" {{ $plan->fixed_amount > 0 ? 'readonly' : 'required' }}>
                                        <div class="bg-blue-600 px-4 py-3 rounded-r-xl font-bold text-white border border-l-0 border-blue-500/30">{{ gs('cur_text') }}</div>
                                    </div>
                                    <code class="gateway-info hidden text-xs mt-2 text-pink-300 block">@lang('Charge'): <span class="charge"></span> {{ gs('cur_text') }}. @lang('Total amount'): <span class="total"></span> {{ gs('cur_text') }}</code>
                                </div>

                                @if($plan->compound_interest)
                                <div class="col-span-full compoundInterest">
                                    <div class="form-group">
                                        <label class="block text-sm font-medium text-gray-300 mb-2">@lang('Compound Interest') (@lang('optional'))</label>
                                        <div class="relative flex items-center">
                                            <input type="number" min="0" class="w-full bg-[#1a1a2e] border border-blue-500/30 rounded-l-xl px-4 py-3 text-white focus:outline-none focus:border-blue-500 transition-colors" name="compound_interest">
                                            <div class="bg-gray-700 px-4 py-3 rounded-r-xl font-bold text-white border border-l-0 border-gray-600">@lang('Times')</div>
                                        </div>
                                        <small class="text-xs text-gray-400 mt-1 block"><i class="las la-info-circle"></i> @lang('Your interest will add to the investment capital amount for a specific time that you\'re entering.')</small>
                                    </div>
                                </div>
                                @endif

                                @if(gs('schedule_invest'))
                                <div class="col-span-full">
                                    <div class="form-group">
                                        <label class="block text-sm font-medium text-gray-300 mb-2">@lang('Auto Schedule Invest')</label>
                                        <select class="w-full bg-[#1a1a2e] border border-blue-500/30 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-blue-500 transition-colors appearance-none" name="invest_time">
                                            <option value="invest_now">@lang('Invest Now')</option>
                                            <option value="schedule">@lang('Schedule Invest')</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-span-full schedule hidden space-y-4">
                                    <div class="form-group">
                                        <label class="block text-sm font-medium text-gray-300 mb-2">@lang('Schedule Times')</label>
                                        <input type="number" name="schedule_times" class="w-full bg-[#1a1a2e] border border-blue-500/30 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-blue-500 transition-colors">
                                    </div>
                                    <div class="form-group">
                                        <label class="block text-sm font-medium text-gray-300 mb-2">@lang('After')</label>
                                        <div class="relative flex items-center">
                                            <input type="number" name="hours" class="w-full bg-[#1a1a2e] border border-blue-500/30 rounded-l-xl px-4 py-3 text-white focus:outline-none focus:border-blue-500 transition-colors">
                                            <div class="bg-gray-700 px-4 py-3 rounded-r-xl font-bold text-white border border-l-0 border-gray-600">@lang('Hours')</div>
                                        </div>
                                    </div>
                                </div>
                                @endif

                            </div>
                        </div>

                        <div class="mt-8 flex gap-4">
                            <a href="{{ route('user.home') }}" class="w-1/3 py-3 rounded-xl bg-gray-600 hover:bg-gray-700 text-center text-white font-bold transition">@lang('Cancel')</a>
                            <button type="submit" class="w-2/3 py-3 rounded-xl bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white font-bold transition shadow-lg shadow-purple-900/20">@lang('Confirm Investment')</button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

@endsection

@push('script')
<script>
    (function($) {
        "use strict"
        var symbol = '{{ gs('cur_text') }}';
        var currency = '{{ gs('cur_text') }}';
        var plan = @json($plan);

        // Initial Calculation
        calculateInterest();

        $('[name=amount]').on('input', function() {
            $('[name=wallet_type]').trigger('change');
            calculateInterest();
        })

        $('[name=wallet_type]').change(function() {
            var amount = $('[name=amount]').val();
            if ($(this).val() && $(this).val() != 'deposit_wallet' && $(this).val() != 'interest_wallet' && amount) {
                var resource = $('select[name=wallet_type] option:selected').data('gateway');
                var fixed_charge = parseFloat(resource.fixed_charge);
                var percent_charge = parseFloat(resource.percent_charge);
                var charge = parseFloat(fixed_charge + (amount * percent_charge / 100)).toFixed(2);
                $('.charge').text(charge);
                $('.gateway-rate').text(parseFloat(resource.rate));
                $('.gateway-info').removeClass('hidden');
                if (resource.currency == '{{ gs('cur_text') }}') {
                    $('.rate-info').addClass('hidden');
                } else {
                    $('.rate-info').removeClass('hidden');
                }
                $('.method_currency').text(resource.currency);
                $('.total').text(parseFloat(charge) + parseFloat(amount));
            } else {
                $('.gateway-info').addClass('hidden');
            }
        });

        $('[name=invest_time]').on('change', function() {
            let investTime = $(this).find(':selected').val();
            if (investTime == 'invest_now') {
                $('.schedule').addClass('hidden');
            } else {
                $('.schedule').removeClass('hidden');
            }
        }).change();

        $('[name=schedule_times]').on('input', function() {
            let text = $(this).val() == 1 ? `@lang('After')` : `@lang('Every')`;
            $('[name=hours]').closest('.form-group').find('label').text(text);
        });

        $('[name=compound_interest]').on('input', function() {
            calculateInterest();
        })

        function calculateInterest() {
            let interest = parseFloat(plan.interest);
            let interestType = plan.interest_type; //1: percent, 0: fixed
            let repeatTime = plan.repeat_time;
            let capitalBack = plan.capital_back;
            let investAmount = $('[name=amount]').val() * 1;
            let compoundInterest = $('[name=compound_interest]').val() ?? 0;
            let totalInterest = 0;

            if (repeatTime == 0 || investAmount == 0) {
                // Keep 0
            } else {
                totalInterest = interest * repeatTime;

                if (interestType == '1') {
                    if (compoundInterest > 0) {
                        let remainingRepeatTime = repeatTime - compoundInterest;
                        let interestRatio = 1 + interest / 100;
                        let compoundCapital = investAmount * Math.pow(interestRatio, compoundInterest);
                        totalInterest = (compoundCapital * interest / 100) * remainingRepeatTime;
                    } else {
                        totalInterest = interest * investAmount / 100 * repeatTime;
                    }
                }
            }

            totalInterest = capitalBack ? totalInterest : totalInterest - investAmount;
            $('.profitAmount').text(totalInterest.toFixed(2));
        }

    })(jQuery);
</script>
@endpush
