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
    .glass-input {
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.1);
        color: white;
        transition: all 0.3s ease;
    }
    .glass-input:focus {
        background: rgba(255, 255, 255, 0.1);
        border-color: rgba(59, 130, 246, 0.5);
        box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.2);
    }
    .wallet-card {
        background: linear-gradient(135deg, rgba(59, 130, 246, 0.1), rgba(147, 51, 234, 0.1));
        backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.1);
    }
</style>

<div id="appCapsule" class="pb-20">
    <div class="px-3 py-4">
        
        {{-- Header Section --}}
        <div class="mb-6 ps-2">
            <h1 class="text-3xl font-bold text-white font-orbitron mb-1">@lang('Transfer Balance')</h1>
            <p class="text-blue-200/60 text-sm">@lang('Instantly transfer funds to another user.')</p>
        </div>

        <div class="glass-card rounded-[25px] p-6 relative overflow-hidden">
            <!-- Background Glows -->
            <div class="absolute top-0 right-0 w-64 h-64 bg-blue-500/10 rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 left-0 w-64 h-64 bg-purple-500/10 rounded-full blur-3xl"></div>

            <div class="relative z-10">
                <form method="post" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    
                    {{-- Wallet Display --}}
                    <div class="wallet-card p-4 rounded-2xl">
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="text-xs text-blue-400 font-semibold uppercase tracking-wider font-orbitron">@lang('Available Balance')</p>
                                <h4 class="text-2xl font-bold text-white mt-1">{{ showAmount($user->interest_wallet) }} <span class="text-sm text-gray-400">{{ gs('cur_text') }}</span></h4>
                            </div>
                            <div class="w-12 h-12 rounded-full bg-blue-500/20 flex items-center justify-center text-blue-400 border border-blue-500/30">
                                 <input type="hidden" name="wallet" value="interest_wallet">
                                 <i class="las la-wallet text-2xl"></i>
                            </div>
                        </div>
                    </div>

                    {{-- Username Input --}}
                    <div>
                         <label class="font-semibold text-gray-300 mb-2 block text-sm">@lang('Receiver Username')</label>
                         <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="las la-user text-gray-400 text-lg"></i>
                            </div>
                            <input type="text" name="username" class="w-full pl-11 glass-input text-sm rounded-xl block p-3.5 outline-none findUser" placeholder="@lang('Enter username')" required>
                         </div>
                          <code class="error-message text-red-500 text-xs block mt-1 min-h-[1.2rem] font-medium ml-1"></code>
                    </div>

                    {{-- Amount Input --}}
                    <div>
                        <label class="font-semibold text-gray-300 mb-2 block text-sm">@lang('Amount to Transfer')</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <span class="text-blue-400 font-bold">{{ gs('cur_sym') }}</span>
                            </div>
                            <input type="number" step="any" autocomplete="off" name="amount" class="w-full pl-10 pr-16 glass-input text-sm rounded-xl block p-3.5 outline-none" placeholder="0.00" required>
                            <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                                <span class="text-gray-400 font-medium">{{ gs('cur_text') }}</span>
                            </div>
                        </div>
                        
                        <div class="mt-3 flex flex-wrap gap-2 justify-between items-center text-xs text-gray-400 px-1">
                            <span>@lang('Limit'): <span class="text-white">{{ showAmount(gs('min_transfer')) }} - {{ showAmount(gs('max_transfer')) }} {{ gs('cur_text') }}</span></span>
                            <span>@lang('Charge'): <span class="text-blue-400">{{ showAmount(gs('f_charge')) }} {{ gs('cur_sym') }} + {{ getAmount(gs('p_charge')) }}%</span></span>
                        </div>
                         <small><code class="calculation text-blue-400 block mt-2 min-h-[1rem] font-semibold text-right"></code></small>
                    </div>

                    @if (auth()->user()->ts)
                         <div>
                            <label class="font-semibold text-gray-300 mb-2 block text-sm">@lang('Google 2FA Code')</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <i class="las la-shield-alt text-gray-400 text-lg"></i>
                                </div>
                                <input type="text" name="authenticator_code" class="w-full pl-11 glass-input text-sm rounded-xl block p-3.5 outline-none" placeholder="@lang('Enter 6-digit code')" required>
                            </div>
                        </div>
                    @endif

                    <div class="pt-2">
                        <button type="submit" class="w-full py-4 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white font-bold rounded-xl shadow-lg shadow-blue-600/30 transform transition-all duration-200 hover:-translate-y-0.5 active:scale-95 flex items-center justify-center gap-2">
                            <i class="las la-paper-plane text-xl"></i> @lang('Transfer Now')
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
    <script>
        $('input[name=amount]').on('input', function() {
            var amo = parseFloat($(this).val());
            var calculation = amo + (parseFloat({{ gs('f_charge') }}) + (amo * parseFloat({{ gs('p_charge') }})) / 100);
            if (calculation) {
                $('.calculation').text('@lang('Total Deduction'): ' + calculation.toFixed(2) + ' {{ gs('cur_text') }}');
            } else {
                $('.calculation').text('');
            }
        });

        $('.findUser').on('focusout', function(e) {
            var url = '{{ route('user.findUser') }}';
            var value = $(this).val();
            var token = '{{ csrf_token() }}';

            var data = {
                username: value,
                _token: token
            }
            $.post(url, data, function(response) {
                if (response.message) {
                    $('.error-message').text(response.message).removeClass('text-green-500').addClass('text-red-500');
                } else {
                    $('.error-message').text('@lang('User found!')').removeClass('text-red-500').addClass('text-green-500');
                }
            });
        });
    </script>
@endpush
