@extends($activeTemplate . 'layouts.master')
@section('content')

<div class="px-4 pb-20">
    <div class="px-[5px] pb-[8px] mt-4">
        <h1 class="text-3xl font-bold text-white font-[Orbitron]">@lang('My Team')</h1>
        <p class="text-white text-sm">@lang('Referral link and team details')</p>
    </div>
    

    
    <div class="grid grid-cols-2 gap-3 my-3">
        <div
            class="relative overflow-hidden flex-1 flex items-center justify-start gap-2 !bg-blue-800/60 !backdrop-blur-sm text-white !rounded-lg !border !border-blue-700/40 !shadow-lg !shadow-blue-500/20 !p-3">
            <div>
                <p class="font-bold text-[16px] text-white">{{ $teamCount }}</p>
                <p class="text-[10px] text-white">@lang('Team Members')</p>
            </div>
            <img class="absolute z-[1] -bottom-[15px] -right-[20px] w-[75px] -rotate-[65deg]"
                src="https://imgur.com/8USpeBu.png" alt="">
        </div>
        <div
            class="relative overflow-hidden flex-1 flex items-center justify-start gap-2 !bg-blue-800/60 !backdrop-blur-sm text-white !rounded-lg !border !border-blue-700/40 !shadow-lg !shadow-blue-500/20 !p-3">
            <div>
                <p class="font-bold text-[16px] text-white">{{ showAmount($totalInvest) }}</p>
                <p class="text-[10px] text-white">@lang('Team Investment')</p>
            </div>
            <img class="absolute z-[1] -bottom-[15px] -right-[20px] w-[75px] -rotate-[65deg]"
                src="https://imgur.com/8USpeBu.png" alt="">
        </div>
    </div>

    <!-- Referral Link Section -->
    <div class="!bg-gradient-to-br !from-blue-400 !via-blue-600 !to-blue-800 !text-white !p-3 rounded-2xl w-full mb-4">
        <h3 class="!text-white font-semibold !text-center mb-1">@lang('Your Referral Link')</h3>
        <div class="flex items-center gap-2 rounded-lg p-0 border border-white">
            <input type="text" value="{{ route('home') }}?{{ str_replace(['?', '='], '', gs('referral_parameter') ?: 'reference') }}={{ base64_encode(auth()->user()->username) }}" readonly
                class="flex-1 bg-transparent text-sm text-white font-medium pl-2 focus:outline-none" id="referralURL" />
            <button id="copyBoard"
                class="bg-gradient-to-b from-blue-400 via-blue-600 to-blue-800 border-l border-white text-white p-2 rounded-r h-full flex items-center justify-center">
                <i class="fa fa-copy h-5 w-5"></i>
            </button>
        </div>
    </div>

    <!-- Level Selector -->
    <div class="flex gap-2 justify-center mb-4 overflow-x-auto pb-2">
        <!-- Assuming maxLevel logic is handled in controller, hardcoding or iterating based on available variable if generic -->
        @for ($i = 1; $i <= $maxLevel; $i++)
            <a href="{{ route('user.referrals', $i) }}" 
               class="px-3 py-1 rounded-full text-sm font-bold border whitespace-nowrap transition-all duration-300
               {{ $level == $i ? 'bg-blue-600 text-white border-blue-400 shadow-[0_0_10px_rgba(37,99,235,0.5)]' : 'bg-white/10 text-white/70 border-white/10 hover:bg-white/20 hover:text-white' }}">
               @lang('Level') {{ $i }}
            </a>
        @endfor
    </div>

    <!-- Reference Tree -->
    <div class="border border-blue-400/40 shadow-xl shadow-blue-500/30 rounded-2xl backdrop-blur-lg mb-4 overflow-hidden">
        <div class="bg-gradient-to-r from-blue-500 via-blue-600 to-blue-700 p-4">
            <h5 class="mb-0 text-white !font-semibold text-[18px] flex items-center gap-2">
                <span class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-white/15">
                    <i class="fa fa-sitemap text-white/85"></i>
                </span>
                @lang('Reference Tree') (@lang('Level') {{ $level }})
            </h5>
        </div>
        <div class="bg-gradient-to-br from-blue-900/60 via-blue-900/40 to-blue-800/40 backdrop-blur-xl p-4">
           @if($referrals->count() > 0 && $user)
            <ul class="sp-referral space-y-3">
                <li class="single-child root-child">
                    <p class="flex items-center gap-3 rounded-xl border border-white/20 bg-white/10 px-3 py-2 shadow-lg shadow-blue-900/30 mb-2">
                        <img src="https://i.imgur.com/rAPve5a.png" class="h-10 w-10 rounded-full border border-white/40 object-cover">
                        <span class="text-sm text-white/90">
                            <span class="font-semibold text-white">{{ auth()->user()->username }}</span>
                            <span class="block text-xs text-white/65">@lang('Me')</span>
                        </span>
                    </p>
                    <ul class="sub-child-list step-2 space-y-3 pl-6 border-l border-white/15">
                            @foreach($referrals as $referral)
                            <li class="single-child">
                                <p class="flex items-center gap-3 rounded-xl border border-white/15 bg-blue-700/30 px-3 py-2 hover:border-white/30 hover:bg-blue-600/30 transition">
                                    <img src="https://i.imgur.com/rAPve5a.png" class="h-9 w-9 rounded-full border border-white/25 object-cover">
                                    <span class="text-sm text-white/85">
                                        <span class="font-medium text-white">{{ $referral->username }}</span>
                                        <span class="block text-xs text-white/60">@lang('Joined'): {{ $referral->created_at->format('M d, Y') }}</span>
                                    </span>
                                </p>
                            </li>
                            @endforeach
                    </ul>
                </li>
            </ul>
            @else
                <div class="text-center py-10">
                    <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-full border border-white/20 bg-blue-700/30 shadow-lg shadow-blue-900/40">
                        <img src="https://i.imgur.com/D7pxgR7.png" class="h-10 w-10" alt="No Team">
                    </div>
                    <p class="mt-4 text-base font-medium text-white">@lang('No Reference User Found')</p>
                    <p class="text-sm text-white/70">@lang('Share your referral link to grow a powerful network.')</p>
                </div>
            @endif
        </div>
    </div>
</div>

@endsection

@push('script')
<script>
    $('#copyBoard').on('click', function() {
        var copyText = document.getElementById("referralURL");
        copyText.select();
        copyText.setSelectionRange(0, 99999);
        document.execCommand("copy");
        // Use exact notifyMsg if available as per other pages, or standard notify
        if(typeof notifyMsg === 'function') {
            notifyMsg("Copied: " + copyText.value, 'success');
        } else if(typeof notify === 'function') {
            notify('success', 'Copied: ' + copyText.value);
        } else {
             // Fallback
             alert('Copied to clipboard');
        }
    });
</script>
@endpush
