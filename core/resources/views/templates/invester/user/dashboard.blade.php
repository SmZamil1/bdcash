@extends($activeTemplate . 'layouts.master')
@section('content')

<div class="px-[5px] pb-[8px]">
    <h1 class="text-3xl font-bold text-white font-[Orbitron]">Dashboard</h1>
    <p class="text-white text-sm">Hi, {{ auth()->user()->username }}</p>
</div>

<!-- Balance Card -->
<div class="mt-2">
    <div class="bg-[linear-gradient(135deg,#0b64f4,#516dfb)] rounded-3xl p-4 shadow-xl relative overflow-hidden">
        <div class="absolute top-1 right-1 w-40 h-40 rounded-full bg-white/20"></div>
        <div class="absolute bottom-0 left-0 w-32 h-32 rounded-full bg-white/15"></div>

        <div class="absolute right-[5px] top-[44%] translate-y-[-50%] flex flex-col items-end justify-center me-0">
            <img src="https://imgur.com/pnlD9mD.png" alt="Wallet" class="w-[9rem] h-[9rem] opacity-60">
        </div>
        <div class="relative z-10">
            <div>
                <p class="text-[12px] mb-1" style="opacity: 0.6;">Current Balance</p>
                <div class="flex items-baseline gap-1">
                    <span class="text-xl font-bold font-[Orbitron]">
                        {{ showAmount($user->interest_wallet, 2, true, false, false) }}
                    </span>
                    <span class="text-xl font-bold font-[Orbitron]">{{ gs('cur_sym') }}</span>
                </div>
            </div>
        </div>
        <div class="backdrop-blur rounded-lg p-2">
            <h3 class="!text-white font-semibold !text-center mb-1">Referral Link</h3>
            <div class="flex items-center gap-2 rounded-lg p-0 border border-white">
                <input type="text" value="{{ route('home') }}?{{ gs('referral_parameter') ?? 'reference' }}={{ base64_encode(auth()->user()->username) }}" readonly
                    class="flex-1 bg-transparent text-sm text-white font-medium pl-1" id="referralURL" />
                <button id="copyBoard" class="copy_ref bg-gradient-to-b from-blue-400 via-blue-600 to-blue-800 border border-white text-white p-2 rounded">
                    <i class="fa fa-copy text-xl"></i>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Daily Reward Section -->
<div class="mt-4 mb-3">
    <a href="{{ route('user.daily.reward') }}" class="block">
        <div class="!bg-gradient-to-r from-violet-600 to-indigo-600 rounded-2xl p-4 shadow-lg shadow-indigo-500/20 flex items-center justify-between relative overflow-hidden border border-white/10">
            <div class="relative z-10 flex items-center gap-3">
                 <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center backdrop-blur-sm border border-white/20">
                    <img src="https://cdn-icons-png.flaticon.com/512/8832/8832108.png" class="w-7 h-7 object-contain drop-shadow-md" alt="Gift">
                 </div>
                 <div>
                    <h3 class="text-white font-bold text-lg font-[Orbitron]">Daily Check-In</h3>
                    <p class="text-indigo-100 text-[11px]">Collect your daily reward</p>
                 </div>
            </div>
            <div class="relative z-10">
                <span class="bg-white/20 w-8 h-8 rounded-full flex items-center justify-center backdrop-blur-sm border border-white/10">
                    <i class="las la-angle-right text-white text-sm"></i>
                </span>
            </div>
             <div class="absolute -right-6 -bottom-6 w-24 h-24 bg-pink-500/30 rounded-full blur-xl"></div>
             <div class="absolute left-10 top-0 w-20 h-20 bg-blue-500/20 rounded-full blur-xl"></div>
        </div>
    </a>
</div>

<!-- Quick Actions -->
<div class="grid grid-cols-4 gap-3 my-4">
    <a href="{{ route('user.deposit.index') }}" class="flex flex-col items-center justify-center ">
        <div class="!border !border-blue-400/40 !shadow-xl !shadow-blue-400/20 rounded-full mb-1">
            <img src="https://imgur.com/gHU17z7.png" class="w-[56px] h-[56px] aspect-square !p-[8px]" alt="Deposit">
        </div>
        <span class="relative text-xs font-medium">Deposit</span>
    </a>
    <a href="{{ route('user.withdraw') }}" class="flex flex-col items-center justify-center ">
        <div class="!border !border-blue-400/40 !shadow-xl !shadow-blue-400/20 rounded-full mb-1">
            <img src="https://imgur.com/R0hewV8.png" class="w-[56px] h-[56px] aspect-square !p-[8px]" alt="Withdraw">
        </div>
        <span class="relative text-xs font-medium">Withdraw</span>
    </a>
    <a href="{{ route('user.important.links') }}" class="flex flex-col items-center justify-center ">
        <div class="!border !border-blue-400/40 !shadow-xl !shadow-blue-400/20 rounded-full mb-1">
             <img src="https://cdn3d.iconscout.com/3d/premium/thumb/link-3d-icon-png-download-7597626.png" class="w-[56px] h-[56px] aspect-square !p-[12px]" alt="Links">
        </div>
        <span class="relative text-xs font-medium">Important Links</span>
    </a>
    <a href="{{ route('user.profile.setting') }}" class="flex flex-col items-center justify-center ">
        <div class="!border !border-blue-400/40 !shadow-xl !shadow-blue-400/20 rounded-full mb-1">
            <img src="https://imgur.com/svIvUop.png" class="w-[56px] h-[56px] aspect-square !p-[8px]" alt="Profile">
        </div>
        <span class="relative text-xs font-medium">Profile</span>
    </a>
</div>

<!-- Stats Grid -->
<div class="grid grid-cols-2 gap-3 my-3">
    <div class="relative overflow-hidden flex-1 flex items-center justify-start gap-2 !bg-blue-800/60 !backdrop-blur-sm text-white !rounded-lg !border !border-blue-700/40 !shadow-lg !shadow-blue-500/20 !p-3">
        <div>
            <p class="font-bold text-[16px] text-white">{{ showAmount($totalInvest, 2, true, false, false) }}</p>
            <p class="text-[10px] text-white">Total Deposit</p>
        </div>
        <img class="absolute z-[1] -bottom-[15px] -right-[20px] w-[75px] -rotate-[65deg]" src="https://imgur.com/8USpeBu.png" alt="">
    </div>
    <div class="relative overflow-hidden flex-1 flex items-center justify-start gap-2 !bg-blue-800/60 !backdrop-blur-sm text-white !rounded-lg !border !border-blue-700/40 !shadow-lg !shadow-blue-500/20 !p-3">
        <div>
            <p class="font-bold text-[16px] text-white">{{ showAmount($totalWithdraw, 2, true, false, false) }}</p>
            <p class="text-[10px] text-white">Total Withdraw</p>
        </div>
        <img class="absolute z-[1] -bottom-[15px] -right-[20px] w-[75px] -rotate-[65deg]" src="https://imgur.com/8USpeBu.png" alt="">
    </div>
    <div class="relative overflow-hidden flex-1 flex items-center justify-start gap-2 !bg-blue-800/60 !backdrop-blur-sm text-white !rounded-lg !border !border-blue-700/40 !shadow-lg !shadow-blue-500/20 !p-3">
        <div>
            <p class="font-bold text-[16px] text-white">{{ showAmount($pendingWithdrawals, 2, true, false, false) }}</p>
            <p class="text-[10px] text-white">Pending Withdraw</p>
        </div>
        <img class="absolute z-[1] -bottom-[15px] -right-[20px] w-[75px] -rotate-[65deg]" src="https://imgur.com/8USpeBu.png" alt="">
    </div>
    <div class="relative overflow-hidden flex-1 flex items-center justify-start gap-2 !bg-blue-800/60 !backdrop-blur-sm text-white !rounded-lg !border !border-blue-700/40 !shadow-lg !shadow-blue-500/20 !p-3">
        <div>
            <p class="font-bold text-[16px] text-white">{{ showAmount($totalInvest, 2, true, false, false) }}</p>
            <p class="text-[10px] text-white">Recent Invest</p>
        </div>
        <img class="absolute z-[1] -bottom-[15px] -right-[20px] w-[75px] -rotate-[65deg]" src="https://imgur.com/8USpeBu.png" alt="">
    </div>
    <div class="relative overflow-hidden flex-1 flex items-center justify-start gap-2 !bg-blue-800/60 !backdrop-blur-sm text-white !rounded-lg !border !border-blue-700/40 !shadow-lg !shadow-blue-500/20 !p-3">
        <div>
            <p class="font-bold text-[16px] text-white">{{ showAmount($pendingDeposits, 2, true, false, false) }}</p>
            <p class="text-[10px] text-white">Pending Invest</p>
        </div>
        <img class="absolute z-[1] -bottom-[15px] -right-[20px] w-[75px] -rotate-[65deg]" src="https://imgur.com/8USpeBu.png" alt="">
    </div>
    <div class="relative overflow-hidden flex-1 flex items-center justify-start gap-2 !bg-blue-800/60 !backdrop-blur-sm text-white !rounded-lg !border !border-blue-700/40 !shadow-lg !shadow-blue-500/20 !p-3">
        <div>
            <p class="font-bold text-[16px] text-white">{{ showAmount($referralEarnings, 2, true, false, false) }}</p>
            <p class="text-[10px] text-white">Ref Earn</p>
        </div>
        <img class="absolute z-[1] -bottom-[15px] -right-[20px] w-[75px] -rotate-[65deg]" src="https://imgur.com/8USpeBu.png" alt="">
    </div>
</div>

<!-- Secondary Actions -->

<div class="grid grid-cols-4 gap-3 my-4">
    @if(gs('ptc_module'))
    <a href="{{ route('user.vip.index') }}" class="flex flex-col items-center justify-center ">
        <div class="!border !border-blue-400/40 !shadow-xl !shadow-blue-400/20 rounded-full mb-1">
            <img src="https://cdn-icons-png.flaticon.com/128/13636/13636286.png" class="w-[56px] h-[56px] aspect-square !p-[8px]" alt="VIP Plans">
        </div>
        <span class="relative text-xs font-medium">PTC Plan</span>
    </a>
    <a href="{{ route('user.ptc.index') }}" class="flex flex-col items-center justify-center ">
        <div class="!border !border-blue-400/40 !shadow-xl !shadow-blue-400/20 rounded-full mb-1">
            <img src="https://cdn3d.iconscout.com/3d/premium/thumb/ppc-3d-icon-png-download-9052916.png" class="w-[56px] h-[56px] aspect-square !p-[8px]" alt="PTC Ads">
        </div>
        <span class="relative text-xs font-medium">PTC Ads</span>
    </a>
    @endif
@if(gs('investment_module'))

    <a href="{{ route('user.invest.log') }}" class="flex flex-col items-center justify-center ">
        <div class="!border !border-blue-400/40 !shadow-xl !shadow-blue-400/20 rounded-full mb-1">
            <img src="https://imgur.com/8EjcyV0.png" class="w-[56px] h-[56px] aspect-square !p-[8px]" alt="My Plans">
        </div>
        <span class="relative text-xs font-medium">My Plans</span>
    </a>
    <a href="{{ route('plan') }}" class="flex flex-col items-center justify-center ">
         <div class="!border !border-blue-400/40 !shadow-xl !shadow-blue-400/20 rounded-full mb-1">
            <img src="https://cdn-icons-png.flaticon.com/128/7756/7756169.png" class="w-[56px] h-[56px] aspect-square !p-[8px]" alt="Plans">
        </div>
        <span class="relative text-xs font-medium">Plans</span>
    </a>
    @endif
    @if(gs('mining_module'))
    <a href="{{ route('user.miners') }}" class="flex flex-col items-center justify-center ">
        <div class="!border !border-blue-400/40 !shadow-xl !shadow-blue-400/20 rounded-full mb-1">
            <img src="https://cdn3d.iconscout.com/3d/premium/thumb/crypto-mining-3d-icon-png-download-6166097.png" class="w-[56px] h-[56px] aspect-square !p-[8px]" alt="Mining">
        </div>
        <span class="relative text-xs font-medium">Crypto Mining</span>
    </a>
    @endif

    @if(gs('ai_bot_module'))
    <a href="{{ route('user.ai.bots') }}" class="flex flex-col items-center justify-center ">
        <div class="!border !border-blue-400/40 !shadow-xl !shadow-blue-400/20 rounded-full mb-1">
            <img src="https://cdn3d.iconscout.com/3d/premium/thumb/bot-3d-icon-png-download-9666270.png" class="w-[56px] h-[56px] aspect-square !p-[8px]" alt="AI Bots">
        </div>
        <span class="relative text-xs font-medium">AI Bots</span>
    </a>
    @endif

    @if(gs('trading_module'))
    <a href="{{ route('user.trading') }}" class="flex flex-col items-center justify-center ">
        <div class="!border !border-blue-400/40 !shadow-xl !shadow-blue-400/20 rounded-full mb-1">
            <img src="https://cdn-icons-png.flaticon.com/128/9084/9084248.png" class="w-[56px] h-[56px] aspect-square !p-[8px]" alt="Option Trading">
        </div>
        <span class="relative text-xs font-medium">Option Trading</span>
    </a>
    @endif

    @if(gs('forex_module'))
    <a href="{{ route('user.forex') }}" class="flex flex-col items-center justify-center ">
        <div class="!border !border-blue-400/40 !shadow-xl !shadow-blue-400/20 rounded-full mb-1">
            <img src="https://cdn3d.iconscout.com/3d/premium/thumb/forex-click-3d-icon-png-download-11513781.png" class="w-[56px] h-[56px] aspect-square !p-[8px]" alt="Forex">
        </div>
        <span class="relative text-xs font-medium">Forex</span>
    </a>
    @endif

    @if(gs('stock_module'))
    <a href="{{ route('user.stock.trading') }}" class="flex flex-col items-center justify-center ">
        <div class="!border !border-blue-400/40 !shadow-xl !shadow-blue-400/20 rounded-full mb-1">
            <img src="https://cdn3d.iconscout.com/3d/premium/thumb/stock-3d-icon-png-download-11202857.png" class="w-[56px] h-[56px] aspect-square !p-[8px]" alt="Stock">
        </div>
        <span class="relative text-xs font-medium">Stock</span>
    </a>
    @endif

    @if(gs('games_module'))
    <a href="{{ route('user.games.index') }}" class="flex flex-col items-center justify-center ">
        <div class="!border !border-blue-400/40 !shadow-xl !shadow-blue-400/20 rounded-full mb-1">
            <img src="https://cdn3d.iconscout.com/3d/premium/thumb/games-3d-icon-png-download-12460828.png" class="w-[56px] h-[56px] aspect-square !p-[8px]" alt="Games">
        </div>
        <span class="relative text-xs font-medium">Games</span>
    </a>
    @endif

    

    <a href="{{ route('user.salary') }}" class="flex flex-col items-center justify-center ">
        <div class="!border !border-blue-400/40 !shadow-xl !shadow-blue-400/20 rounded-full mb-1">
            <img src="https://cdn3d.iconscout.com/3d/premium/thumb/salary-3d-icon-png-download-8160491.png" class="w-[56px] h-[56px] aspect-square !p-[8px]" alt="Salary">
        </div>
        <span class="relative text-xs font-medium">Salary</span>
    </a>

    <a href="{{ route('user.referrals') }}" class="flex flex-col items-center justify-center ">
        <div class="!border !border-blue-400/40 !shadow-xl !shadow-blue-400/20 rounded-full mb-1">
            <img src="https://i.imgur.com/efJcnux.png" class="w-[56px] h-[56px] aspect-square !p-[8px]" alt="My Team">
        </div>
        <span class="relative text-xs font-medium">My Team</span>
    </a>

    @if(gs('b_transfer'))
    <a href="{{ route('user.transfer.balance') }}" class="flex flex-col items-center justify-center ">
        <div class="!border !border-blue-400/40 !shadow-xl !shadow-blue-400/20 rounded-full mb-1">
            <img src="https://cdn3d.iconscout.com/3d/premium/thumb/balance-transfer-3d-icon-png-download-12966652.png" class="w-[56px] h-[56px] aspect-square !p-[8px]" alt="Transfer Balance">
        </div>
        <span class="relative text-xs font-medium">Transfer Balance</span>
    </a>
    @endif

    <a href="{{ route('user.transactions') }}" class="flex flex-col items-center justify-center ">
        <div class="!border !border-blue-400/40 !shadow-xl !shadow-blue-400/20 rounded-full mb-1">
            <img src="https://cdn3d.iconscout.com/3d/premium/thumb/transaction-3d-icon-png-download-9326149.png" class="w-[56px] h-[56px] aspect-square !p-[8px]" alt="Transactions">
        </div>
        <span class="relative text-xs font-medium">Transactions</span>
    </a>

    <a href="{{ route('user.promocode') }}" class="flex flex-col items-center justify-center ">
        <div class="!border !border-blue-400/40 !shadow-xl !shadow-blue-400/20 rounded-full mb-1">
            <img src="https://cdn3d.iconscout.com/3d/premium/thumb/promo-code-3d-icon-png-download-12797740.png" class="w-[56px] h-[56px] aspect-square !p-[8px]" alt="Promo Code">
        </div>
        <span class="relative text-xs font-medium">Promo Code</span>
    </a>

    <a href="{{ route('user.app.download') }}" class="flex flex-col items-center justify-center ">
        <div class="!border !border-blue-400/40 !shadow-xl !shadow-blue-400/20 rounded-full mb-1">
            <img src="https://i.imgur.com/ES0Fumm.png" class="w-[56px] h-[56px] aspect-square !p-[8px]" alt="Download App">
        </div>
        <span class="relative text-xs font-medium">Download App</span>
    </a>

    <a href="{{ route('user.portfolio') }}" class="flex flex-col items-center justify-center ">
        <div class="!border !border-blue-400/40 !shadow-xl !shadow-blue-400/20 rounded-full mb-1">
            <img src="https://cdn3d.iconscout.com/3d/premium/thumb/portfolio-3d-icon-png-download-10002494.png" class="w-[56px] h-[56px] aspect-square !p-[8px]" alt="Portfolio">
        </div>
        <span class="relative text-xs font-medium">Portfolio</span>
    </a>

    <a href="{{ route('ticket.index') }}" class="flex flex-col items-center justify-center ">
        <div class="!border !border-blue-400/40 !shadow-xl !shadow-blue-400/20 rounded-full mb-1">
            <img src="https://cdn3d.iconscout.com/3d/premium/thumb/support-3d-icon-png-download-7838090.png" class="w-[56px] h-[56px] aspect-square !p-[8px]" alt="Support">
        </div>
        <span class="relative text-xs font-medium">Support</span>
    </a>

    <a href="{{ route('user.logout') }}" class="flex flex-col items-center justify-center ">
        <div class="!border !border-blue-400/40 !shadow-xl !shadow-blue-400/20 rounded-full mb-1">
            <img src="https://imgur.com/Int8D8M.png" class="w-[56px] h-[56px] aspect-square !p-[8px]" alt="Logout">
        </div>
        <span class="relative text-xs font-medium">Logout</span>
    </a>
</div>

<!-- Promo Banner -->
<div class="relative border border-blue-400/40 shadow-xl shadow-blue-400/20 bg-gradient-to-br from-blue-400 to-blue-800 text-white p-3 rounded-2xl w-full max-w-md">
    <img src="https://i.imgur.com/qh56hjI.png" class="w-[10rem]" alt="">
    <div class="flex flex-col items-end absolute bottom-6 right-4 max-w-[250px]">
        <h1 class="!text-[12px] text-white text-end mb-2 bg-black/10 backdrop-blur-sm p-1 rounded-xl">
            If you want to become a team leader, complete our task to get a 5% to 20% deposit discount. The larger the team, the higher the profit.
        </h1>
        <a href="#" class="bg-gradient-to-b from-blue-400 via-blue-600 to-blue-800 border border-white text-white py-1 px-4 rounded-full text-[12px]">
            Become A Leader
        </a>
    </div>
</div>

<!-- Transaction Log -->
<div class="mt-3">
    <div class="flex justify-between items-center mb-1">
        <h3 class="text-lg font-bold text-white">Recent Transactions</h3>
        <a href="{{ route('user.transactions') }}" class="text-xs font-semibold text-white">View All</a>
    </div>

    <div class="bg-transparent border border-blue-400/40 shadow-xl shadow-blue-400/20 rounded-2xl backdrop-blur-lg">
        @forelse($transactions->take(5) as $trx)
            <div class="flex justify-between items-center py-3 px-3">
                <div class="flex items-center gap-3">
                    <div class="border border-blue-400/40 shadow-lg shadow-blue-400/70 rounded-full mb-1">
                        <img src="https://imgur.com/gSzhcXY.png" class="w-[50px] h-[50px] aspect-square p-2"
                            alt="History">
                    </div>
                    <div>
                        <p class="font-semibold text-white text-sm">{{ strLimit($trx->details, 30) }}</p>
                        <p class="text-xs text-white">
                            Trx: <b class="text-info">{{ $trx->trx }}</b>
                            <br>
                            {{ showDateTime($trx->created_at, 'd-m-Y') }} |
                            {{ $trx->created_at->diffForHumans() }}
                        </p>
                    </div>
                </div>
                <p class="font-bold text-white">{{ showAmount($trx->amount) }}</p>
            </div>
            <div class="px-3">
                <div class="border-b border-blue-400/50"></div>
            </div>
        @empty
            <div class="p-4">
                <div class="text-center">
                    <img class="mx-auto w-[60px]" src="https://i.imgur.com/D7pxgR7.png" alt="">
                    <h1 class="text-center font-bold !text-white !text-[10px] mt-2">No Transactions Found</h1>
                </div>
            </div>
        @endforelse
    </div>
</div>

@push('script')
<script>
    $('.copy_ref').on('click', function() {
        var copyText = document.getElementById("referralURL");
        copyText.select();
        copyText.setSelectionRange(0, 99999);
        document.execCommand("copy");
        notify("success", "Copied: " + copyText.value)
    });
    
    // Initialize Swiper if needed
    const swiper = new Swiper('.swiper', { 
        loop: true, autoplay: { delay: 2500 } 
    });
</script>
@endpush

@endsection
