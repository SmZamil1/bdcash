@extends($activeTemplate . 'layouts.master')

@section('content')

<style>
    /* Custom Animations */
    @keyframes fadeInUp { from { opacity: 0; transform: translate3d(0, 20px, 0); } to { opacity: 1; transform: translate3d(0, 0, 0); } }
    .animate-fade-in-up { animation: fadeInUp 0.5s ease-out both; }
    
    @keyframes pulse-soft { 0%, 100% { opacity: 1; } 50% { opacity: 0.8; } }
    .animate-pulse-soft { animation: pulse-soft 2s infinite; }

    /* Remove previous form-control styling overrides as we now have a better updated viser-form component */
</style>

<!-- Page Content -->
<div>
    <!-- Page Title -->
    <div class="px-[5px] pb-[8px] mt-4">
        <h1 class="text-3xl font-bold text-white font-[Orbitron]">Withdraw Confirm</h1>
        <p class="text-white/70 text-sm">Review transfer details</p>
    </div>

    <!-- Confirmation Card -->
    <div class="animate-fade-in-up">
        <!-- Main Card Wrapper -->
        <div class="bg-gradient-to-br from-blue-400/20 via-blue-600/20 to-blue-800/20 border border-blue-400/30 shadow-xl rounded-2xl backdrop-blur-lg p-5">
            
            <!-- Method Icon Header -->
            <div class="text-center mb-6">
                <div class="w-16 h-16 mx-auto mb-3 bg-blue-500/20 rounded-full flex items-center justify-center border border-blue-400/30 animate-pulse-soft">
                     <img src="{{ getImage(getFilePath('withdrawMethod') . '/' . $withdraw->method->image, getFileSize('withdrawMethod')) }}" alt="" class="w-10 h-10 object-contain">
                </div>
                <h3 class="text-xl font-bold text-white">Withdraw via {{ __($withdraw->method->name) }}</h3>
                <p class="text-blue-200 text-sm mt-1">Please check details below</p>
            </div>

            <!-- Transaction Details -->
            <div class="bg-white/5 border border-white/10 rounded-xl p-4 mb-6 space-y-3">
                 <div class="flex justify-between items-center py-1 border-b border-white/10">
                    <span class="text-white/60 text-sm">Request Amount</span>
                    <span class="text-white font-bold">{{ showAmount($withdraw->amount, currencyFormat: false) }} {{ gs('cur_text') }}</span>
                </div>
                <div class="flex justify-between items-center py-1 border-b border-white/10">
                    <span class="text-white/60 text-sm">Withdrawal Charge</span>
                    <span class="text-red-300 font-bold">{{ showAmount($withdraw->charge, currencyFormat: false) }} {{ gs('cur_text') }}</span>
                </div>
                <div class="flex justify-between items-center py-1 border-b border-white/10">
                    <span class="text-white/60 text-sm">Payable Amount</span>
                    <span class="text-white font-bold">{{ showAmount($withdraw->after_charge, currencyFormat: false) }} {{ gs('cur_text') }}</span>
                </div>
                <div class="flex justify-between items-center py-1 border-b border-white/10">
                    <span class="text-white/60 text-sm">Exchange Rate</span>
                    <span class="text-blue-300 font-bold">1 {{ gs('cur_text') }} = {{ showAmount($withdraw->rate, currencyFormat: false) }} {{ __($withdraw->currency) }}</span>
                </div>
                <div class="flex justify-between items-center py-1 pt-2">
                    <span class="text-white/60 text-sm">You Will Get</span>
                    <span class="text-green-400 font-bold text-lg">{{ showAmount($withdraw->final_amount, currencyFormat: false) }} {{ __($withdraw->currency) }}</span>
                </div>
            </div>

            <form action="{{ route('user.withdraw.submit') }}" method="post" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="amount" value="{{ $withdraw->amount }}">
                
                @if($withdraw->method->description)
                <div class="mb-6 bg-blue-500/10 border border-blue-500/20 rounded-xl p-4">
                    <h5 class="text-blue-300 font-bold text-sm mb-2 flex items-center gap-2">
                        <i class="fas fa-info-circle"></i> Instructions
                    </h5>
                    <div class="text-white/80 text-sm leading-relaxed instruction-content prose prose-invert max-w-none">
                        @php echo $withdraw->method->description; @endphp
                    </div>
                </div>
                @endif
                
                <!-- Dynamic Form (ViserForm) -->
                <div>
                     <x-viser-form identifier="id" identifierValue="{{ $withdraw->method->form_id }}" />
                </div>

                @if(auth()->user()->ts)
                <div class="mt-4">
                    <label class="block mb-2 text-sm font-semibold text-white/80">Google Authenticator Code</label>
                    <input type="text" name="authenticator_code" class="w-full bg-white/5 border border-white/20 text-white text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block p-3 placeholder-white/30 transition-all focus:bg-white/10 outline-none" required>
                </div>
                @endif

                <div class="mt-6">
                    <button type="submit" class="bg-gradient-to-b from-blue-400 via-blue-600 to-blue-800 hover:from-blue-500 hover:to-blue-700 border border-white/20 text-white py-3.5 rounded-xl w-full font-bold shadow-lg shadow-blue-900/40 transition-all transform hover:scale-[1.02] flex items-center justify-center gap-2">
                        Confirm Withdraw
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    /* Ensure images in instructions don't overflow */
    .instruction-content img { max-width: 100%; height: auto; border-radius: 0.5rem; margin: 0.5rem 0; }
</style>

@endsection
