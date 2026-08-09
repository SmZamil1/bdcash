@php
    $planImages = $planImages ?? [];
    $planContent = getContent('plan_section.content', true);
@endphp
<div class="mb-6">
    <h2 class="text-white font-black text-xl mb-4">{{ __(@$planContent->data_values->heading ?? 'Our Investment Plans') }}</h2>
    <div class="grid gap-4">
        @foreach($plans as $key => $plan)
        <div
            class="relative bg-gradient-to-br from-purple-600/20 via-blue-600/20 to-indigo-800/30 backdrop-blur-xl border-2 border-purple-400/40 shadow-2xl overflow-hidden rounded-3xl transition-all duration-500 hover:scale-[1.05] hover:shadow-purple-500/60 hover:border-purple-300/60 ">
            <!-- Animated Background Effect -->
            <div class="absolute inset-0 bg-gradient-to-br from-purple-500/10 via-transparent to-blue-500/10 opacity-50"></div>
            <div class="absolute -top-20 -right-20 w-40 h-40 bg-purple-500/20 rounded-full blur-3xl"></div>
            <div class="absolute -bottom-20 -left-20 w-40 h-40 bg-blue-500/20 rounded-full blur-3xl"></div>

            <!-- Plan Image with Better Glow & Styling -->
            <div class="relative p-6 pb-2">
                <div class="flex items-center gap-4">

                    <!-- Plan Name & Price -->
                    <div class="flex-1 text-left">
                        <h1
                            class="mb-2 font-[Orbitron] bg-gradient-to-r from-purple-200 via-blue-200 to-purple-200 bg-clip-text text-transparent text-2xl font-black tracking-tight">
                            {{ __($plan->name) }}
                        </h1>
                        <div
                            class="font-[Orbitron] inline-flex items-baseline gap-1 bg-gradient-to-r from-yellow-400 to-orange-400 bg-clip-text text-transparent">
                            <span class="text-3xl font-black">
                                @if ($plan->fixed_amount > 0)
                                    {{ showAmount($plan->fixed_amount, 0, true, false, false) }}
                                @else
                                    {{ showAmount($plan->minimum, 0, true, false, false) }} - {{ showAmount($plan->maximum, 0, true, false, false) }}
                                @endif
                            </span>
                            <span class="text-2xl font-bold">{{ gs('cur_text') }}</span>
                        </div>
                    </div>

                    <!-- Image -->
                    <div class="relative w-[8rem] h-auto group flex-shrink-0">
                        <!-- Outer Glow Ring -->
                        <div
                            class="absolute inset-0 bg-gradient-to-br from-purple-400 via-pink-500 to-blue-500 rounded-full blur-xl opacity-40 group-hover:opacity-70 animate-pulse transition-opacity duration-700">
                        </div>
                        <!-- Middle Ring -->
                        <div
                            class="absolute inset-1 bg-gradient-to-br from-purple-500 to-blue-600 rounded-full blur-lg opacity-50 group-hover:opacity-80 transition-opacity duration-500">
                        </div>
                        <!-- Image Container -->
                        <div
                            class="relative rounded-full border-[3px] border-blue-400/50 overflow-hidden shadow-2xl shadow-purple-600/40 bg-gradient-to-br from-white/10 to-transparent p-1">
                            <div class="rounded-full overflow-hidden border-2 border-purple-300/30 bg-white/5">
                                @php
                                    $imgSrc = getImage('assets/images/default.png'); // Default fallback
                                    if (!empty($plan->image)) {
                                        $imgSrc = getImage('assets/images/plans/' . $plan->image);
                                    } elseif (!empty($planImages) && count($planImages) > 0) {
                                        $imgSrc = $planImages[$key % count($planImages)];
                                    }
                                @endphp
                                <img class="w-full h-full object-cover transform group-hover:scale-105 group-hover:rotate-3 transition-all duration-700"
                                    src="{{ $imgSrc }}"
                                    alt="{{ __($plan->name) }}">
                            </div>
                        </div>
                        <!-- Corner Sparkles -->
                        <div class="absolute top-0 right-0 w-2 h-2 bg-yellow-300 rounded-full animate-ping opacity-75"></div>
                        <div class="absolute bottom-0 left-0 w-1.5 h-1.5 bg-pink-400 rounded-full animate-pulse"></div>
                    </div>

                </div>
            </div>

            <!-- Plan Details with Icons -->
            <div class="relative z-10 p-5 pt-2 space-y-2.5">
                <!-- Daily Profit -->
                <div
                    class="group relative bg-gradient-to-r from-purple-500/15 to-blue-500/15 backdrop-blur rounded-2xl px-4 py-3 border border-purple-400/30 hover:border-purple-300/60 transition-all duration-300 hover:shadow-lg hover:shadow-purple-500/20">
                    <div class="flex justify-between items-center">
                        <div class="flex items-center gap-2">
                            <div
                                class="w-8 h-8 rounded-full bg-gradient-to-br from-purple-400 to-purple-600 flex items-center justify-center shadow-lg">
                                <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                            <span class="text-white font-bold text-sm">Daily Profit</span>
                        </div>
                        <span class="text-yellow-300 font-black text-xl">
                            {{ showAmount($plan->interest, 0, true, false, false) }} {{ $plan->interest_type == 1 ? '%' : gs('cur_text') }}
                        </span>
                    </div>
                </div>

                <!-- Monthly Profit (Calculated as Daily * 30) -->
                <div
                    class="group relative bg-gradient-to-r from-green-500/15 to-emerald-500/15 backdrop-blur rounded-2xl px-4 py-3 border border-green-400/30 hover:border-green-300/60 transition-all duration-300 hover:shadow-lg hover:shadow-green-500/20">
                    <div class="flex justify-between items-center">
                        <div class="flex items-center gap-2">
                            <div
                                class="w-8 h-8 rounded-full bg-gradient-to-br from-green-400 to-green-600 flex items-center justify-center shadow-lg">
                                <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                            <span class="text-white font-bold text-sm">Monthly Profit</span>
                        </div>
                        <span class="text-green-300 font-black text-xl">
                             @if($plan->interest_type == 1)
                                {{ showAmount($plan->interest * 30, 0, true, false, false) }} %
                             @else
                                {{ showAmount($plan->interest * 30, 0, true, false, false) }} {{ gs('cur_text') }}
                             @endif
                        </span>
                    </div>
                </div>

                <!-- Total Profit -->
                <div
                    class="group relative bg-gradient-to-r from-blue-500/15 to-cyan-500/15 backdrop-blur rounded-2xl px-4 py-3 border border-blue-400/30 hover:border-blue-300/60 transition-all duration-300 hover:shadow-lg hover:shadow-blue-500/20">
                    <div class="flex justify-between items-center">
                        <div class="flex items-center gap-2">
                            <div
                                class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center shadow-lg">
                                <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z" />
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                            <span class="text-white font-bold text-sm">Total Profit</span>
                        </div>
                        <span class="text-cyan-300 font-black text-xl">
                            @if ($plan->lifetime == 0)
                                @if($plan->interest_type == 1)
                                    {{ showAmount($plan->interest * $plan->repeat_time, 0, true, false, false) }} %
                                @else
                                    {{ showAmount($plan->interest * $plan->repeat_time, 0, true, false, false) }} {{ gs('cur_text') }}
                                @endif
                            @else
                                @lang('Unlimited')
                            @endif
                        </span>
                    </div>
                </div>

                <!-- Commission & Validity -->
                <div class="grid grid-cols-2 gap-2">
                    <div
                        class="bg-gradient-to-br from-orange-500/15 to-red-500/15 backdrop-blur rounded-xl px-3 py-2 border border-orange-400/30 text-center">
                        <div class="text-orange-300 font-black text-lg">
                            @php
                                $commission = $referrals->first()->percent ?? 0;
                            @endphp
                            {{ showAmount($commission, 0, currencyFormat: false) }}%
                        </div>
                        <div class="text-white/70 text-xs font-semibold">Commission</div>
                    </div>
                    <div
                        class="bg-gradient-to-br from-pink-500/15 to-purple-500/15 backdrop-blur rounded-xl px-3 py-2 border border-pink-400/30 text-center">
                        <div class="text-pink-300 font-black text-lg">
                             @if ($plan->lifetime == 0)
                                {{ $plan->repeat_time }} {{ __($plan->timeSetting->name) }}
                            @else
                                @lang('Lifetime')
                            @endif
                        </div>
                        <div class="text-white/70 text-xs font-semibold">Validity</div>
                    </div>
                </div>

                <!-- Invest Button with Glow -->
                <div class="mt-4">
                     <a href="{{ route('user.deposit.index', ['plan_id' => $plan->id]) }}" 
                       class="relative group block w-full text-center overflow-hidden rounded-xl py-3.5 transition-all duration-300 hover:scale-[1.02]">
                        <div class="absolute inset-0 bg-gradient-to-r from-purple-600 to-pink-600"></div>
                        <div class="relative z-10 font-black text-white uppercase tracking-widest text-sm flex items-center justify-center gap-2">
                            <span>Invest Now</span>
                            <svg class="w-4 h-4 animate-bounce-x" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                        </div>
                        <!-- Glow -->
                        <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity duration-300 shadow-[0_0_20px_rgba(168,85,247,0.6)]"></div>
                    </a>
                </div>
            </div>

            <!-- Bottom Glow Effect -->
            <div
                class="absolute bottom-0 left-0 right-0 h-32 bg-gradient-to-t from-purple-900/50 via-blue-900/30 to-transparent pointer-events-none">
            </div>
        </div>
        @endforeach
    </div>
</div>
