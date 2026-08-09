@extends($activeTemplate . 'layouts.master')
@section('content')

<style>
    @import url('https://fonts.googleapis.com/css2?family=Orbitron:wght@400..900&display=swap');
    :root {
        --font-orbitron: 'Orbitron', 'sans-serif';
    }
    .font-orbitron {
        font-family: var(--font-orbitron);
    }
    .glass-card {
        background: linear-gradient(135deg, rgba(17, 24, 39, 0.8), rgba(31, 41, 55, 0.8));
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.08);
        box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.3);
    }
    
    /* Portfolio Frame Styles */
    #portfolio-frame {
        position: relative;
        overflow: hidden;
        background: linear-gradient(145deg, #0f172a, #1e293b);
        border: 1px solid rgba(255, 255, 255, 0.1);
    }
    
    .watermark {
        position: absolute;
        bottom: 20px;
        right: 20px;
        font-family: var(--font-orbitron);
        font-weight: 900;
        font-size: 1.5rem;
        color: rgba(255, 255, 255, 0.05);
        pointer-events: none;
        z-index: 0;
    }

    .stat-item {
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid rgba(255, 255, 255, 0.05);
        transition: transform 0.3s ease;
    }
    .stat-item:hover {
        transform: translateY(-2px);
        background: rgba(255, 255, 255, 0.06);
    }
</style>

<div class="px-[5px] pb-[8px] mt-4 flex items-center justify-between">
    <div>
        <h1 class="text-3xl font-bold text-white font-orbitron">Portfolio</h1>
        <p class="text-white/70 text-sm">Your Investment Overview</p>
    </div>
    <button id="downloadBtn" class="bg-gradient-to-r from-blue-500 to-indigo-600 text-white px-4 py-2 rounded-xl text-sm font-bold shadow-lg shadow-blue-500/30 flex items-center gap-2 hover:scale-105 transition-transform">
        <i class="las la-download text-lg"></i>
        <span>Save</span>
    </button>
</div>

<div class="mt-4" id="portfolio-capture-area">
    <div id="portfolio-frame" class="rounded-[2rem] p-6 shadow-2xl relative">
        <!-- Abstract Background Decoration -->
        <div class="absolute top-0 right-0 w-[300px] h-[300px] bg-blue-500/10 rounded-full blur-[80px] -translate-y-1/2 translate-x-1/2 pointer-events-none"></div>
        <div class="absolute bottom-0 left-0 w-[250px] h-[250px] bg-purple-500/10 rounded-full blur-[60px] translate-y-1/2 -translate-x-1/3 pointer-events-none"></div>
        <div class="watermark">{{ gs('site_name') }}</div>

        <!-- Header: User Profile -->
        <div class="flex items-center gap-5 mb-8 relative z-10">
            <div class="relative">
                <div class="w-20 h-20 rounded-full p-[2px] bg-gradient-to-br from-blue-400 to-purple-500 shadow-xl shadow-blue-500/20">
                    <img src="https://i.imgur.com/rAPve5a.png" 
                         class="w-full h-full rounded-full object-cover border-4 border-[#0f172a]" 
                         alt="Profile" crossorigin="anonymous">
                </div>
                <!-- Status Badge -->
                <div class="absolute -bottom-1 -right-1 bg-[#0f172a] rounded-full p-1">
                    @if($user->vip_id && $user->vip_expiry > now())
                    <div class="bg-gradient-to-r from-yellow-400 to-yellow-600 w-6 h-6 rounded-full flex items-center justify-center text-[10px] text-white shadow-lg">
                        <i class="las la-crown"></i>
                    </div>
                    @else
                    <div class="bg-white/20 w-6 h-6 rounded-full flex items-center justify-center text-[10px] text-white">
                        <i class="las la-user"></i>
                    </div>
                    @endif
                </div>
            </div>
            
            <div>
                <h2 class="text-2xl font-bold text-white font-orbitron tracking-wide">{{ $user->username }}</h2>
                <div class="flex items-center gap-3 mt-1">
                    <span class="text-gray-300 text-sm flex items-center gap-2 font-orbitron tracking-wide">
                        <i class="las la-calendar text-blue-400 text-lg"></i> Since {{ showDateTime($user->created_at, 'M Y') }}
                    </span>
                </div>
            </div>
            
        </div>

        <!-- Main Balance Display -->
        <div class="mb-8 relative z-10">
            <div class="p-5 rounded-3xl bg-[linear-gradient(135deg,#0b64f4,#516dfb)] text-white shadow-xl shadow-blue-500/30 overflow-hidden relative">
                <!-- Decorative Circles -->
                <div class="absolute top-[-20px] right-[-20px] w-32 h-32 rounded-full bg-white/20 blur-xl"></div>
                <div class="absolute bottom-[-20px] left-[-20px] w-24 h-24 rounded-full bg-white/10 blur-lg"></div>
                
                <div class="relative z-10 flex justify-between items-center">
                    <div class="overflow-hidden">
                        <p class="text-blue-100 text-sm mb-1 font-medium">Total Balance</p>
                        <h1 class="text-4xl font-bold font-orbitron tracking-tight whitespace-normal break-words">
                            {{ showAmount($user->interest_wallet + $user->deposit_wallet, 2, true, false, false) }} <span class="text-lg font-normal opacity-80">{{ gs('cur_text') }}</span>
                        </h1>
                    </div>
                    <div class="text-right flex-shrink-0">
                         <div class="w-14 h-14 bg-white/20 rounded-2xl flex items-center justify-center backdrop-blur-md ml-auto shadow-inner border border-white/20">
                            <img src="https://img.icons8.com/3d-fluency/94/wallet.png" class="w-10 h-10 object-contain drop-shadow-md" alt="Wallet" crossorigin="anonymous">
                         </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Grid -->
        <div class="grid grid-cols-2 gap-3 mb-6 relative z-10">
            
            <!-- Total Deposit -->
            <div class="bg-gradient-to-br from-blue-600/10 to-indigo-600/10 backdrop-blur-xl border border-blue-400/20 shadow-lg rounded-2xl p-4 relative overflow-hidden group hover:border-blue-400/40 transition-all">
                <div class="absolute top-0 right-0 p-3 opacity-10 group-hover:opacity-20 transition-opacity">
                    <i class="las la-wallet text-4xl text-blue-400"></i>
                </div>
                <div class="flex items-center gap-3 mb-2">
                    <div class="w-10 h-10 rounded-lg bg-blue-500/10 flex items-center justify-center p-1.5 flex-shrink-0 shadow-inner border border-blue-500/20">
                        <img src="https://img.icons8.com/3d-fluency/94/money-bag.png" class="w-full h-full object-contain drop-shadow-sm" alt="Deposit" crossorigin="anonymous">
                    </div>
                    <span class="text-gray-300 text-xs uppercase font-bold tracking-wider whitespace-normal break-words">Total Deposit</span>
                </div>
                <h3 class="text-xl font-bold text-white font-orbitron whitespace-normal break-words">{{ showAmount($totalDeposit, 2, true, false, false) }} <span class="text-xs text-gray-500">{{ gs('cur_text') }}</span></h3>
            </div>

            <!-- Total Withdraw -->
            <div class="bg-gradient-to-br from-blue-600/10 to-indigo-600/10 backdrop-blur-xl border border-blue-400/20 shadow-lg rounded-2xl p-4 relative overflow-hidden group hover:border-blue-400/40 transition-all">
                <div class="absolute top-0 right-0 p-3 opacity-10 group-hover:opacity-20 transition-opacity">
                    <i class="las la-hand-holding-usd text-4xl text-blue-400"></i>
                </div>
                <div class="flex items-center gap-3 mb-2">
                    <div class="w-10 h-10 rounded-lg bg-blue-500/10 flex items-center justify-center p-1.5 flex-shrink-0 shadow-inner border border-blue-500/20">
                         <img src="https://img.icons8.com/3d-fluency/94/bank-cards.png" class="w-full h-full object-contain drop-shadow-sm" alt="Withdraw" crossorigin="anonymous">
                    </div>
                    <span class="text-gray-300 text-xs uppercase font-bold tracking-wider whitespace-normal break-words">Total Withdraw</span>
                </div>
                <h3 class="text-xl font-bold text-white font-orbitron whitespace-normal break-words">{{ showAmount($totalWithdraw, 2, true, false, false) }} <span class="text-xs text-gray-500">{{ gs('cur_text') }}</span></h3>
            </div>

            <!-- Total Invest -->
            <div class="stat-item p-4 rounded-xl">
                <div class="flex items-center gap-3 mb-2">
                    <div class="w-10 h-10 rounded-lg bg-indigo-500/10 flex items-center justify-center p-1.5 flex-shrink-0">
                         <img src="https://img.icons8.com/3d-fluency/94/briefcase.png" class="w-full h-full object-contain" alt="Invest" crossorigin="anonymous">
                    </div>
                    <span class="text-gray-400 text-xs uppercase font-bold tracking-wider whitespace-normal break-words">Total Invest</span>
                </div>
                <h3 class="text-xl font-bold text-white font-orbitron whitespace-normal break-words">{{ showAmount($totalInvest, 2, true, false, false) }} <span class="text-xs text-gray-500">{{ gs('cur_text') }}</span></h3>
            </div>

            <!-- Total Commission -->
            <div class="stat-item p-4 rounded-xl">
                <div class="flex items-center gap-3 mb-2">
                    <div class="w-10 h-10 rounded-lg bg-orange-500/10 flex items-center justify-center p-1.5 flex-shrink-0">
                         <img src="https://img.icons8.com/3d-fluency/94/coins.png" class="w-full h-full object-contain" alt="Commission" crossorigin="anonymous">
                    </div>
                    <span class="text-gray-400 text-xs uppercase font-bold tracking-wider whitespace-normal break-words">Commission</span>
                </div>
                <h3 class="text-xl font-bold text-white font-orbitron whitespace-normal break-words">{{ showAmount($totalCommission, 2, true, false, false) }} <span class="text-xs text-gray-500">{{ gs('cur_text') }}</span></h3>
            </div>

            <!-- Total Earning -->
            <div class="stat-item p-4 rounded-xl col-span-2 bg-gradient-to-r from-blue-500/5 to-purple-500/5 border-blue-500/10">
                <div class="flex items-center justify-between mb-2">
                    <div class="flex items-center gap-3 overflow-hidden">
                        <div class="w-10 h-10 rounded-lg bg-yellow-500/10 flex items-center justify-center p-1.5 flex-shrink-0">
                            <img src="https://img.icons8.com/3d-fluency/94/chart.png" class="w-full h-full object-contain" alt="Chart" crossorigin="anonymous">
                        </div>
                        <span class="text-gray-300 text-xs uppercase font-bold tracking-wider whitespace-normal break-words">Net Earnings</span>
                    </div>
                    <span class="bg-green-500/20 text-green-400 text-[10px] px-2 py-0.5 rounded font-bold flex-shrink-0">PROFIT</span>
                </div>
                <div class="flex justify-between items-end">
                    <h3 class="text-2xl font-bold text-white font-orbitron whitespace-normal break-words">{{ showAmount($totalEarnings, 2, true, false, false) }} <span class="text-sm text-gray-500">{{ gs('cur_text') }}</span></h3>
                    <div class="text-right pl-2">
                        <p class="text-[10px] text-gray-500 whitespace-nowrap">Includes Commission</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer Info -->
        <div class="flex justify-between items-center text-[10px] text-gray-500 border-t border-white/5 pt-4 relative z-10">
            <p>Generated on {{ now()->format('d M, Y') }}</p>
            <p class="flex items-center gap-1">
                <i class="las la-shield-alt"></i> Verified Investor
            </p>
        </div>
    </div>
</div>

@endsection

@push('script')
<script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
<script>
    document.getElementById('downloadBtn').addEventListener('click', function() {
        const btn = this;
        const icon = btn.querySelector('i');
        const text = btn.querySelector('span');
        
        // Loading State
        btn.disabled = true;
        icon.className = 'las la-spinner la-spin text-lg';
        text.innerText = 'Capturing...';

        const element = document.getElementById('portfolio-frame');
        
        html2canvas(element, {
            scale: 2, // Higher quality
            backgroundColor: null, // Transparent if needed, but element has bg
            useCORS: true, // For images like profile pic
            logging: false
        }).then(canvas => {
            // Create download link
            const link = document.createElement('a');
            link.download = '{{ $user->username }}_portfolio.png';
            link.href = canvas.toDataURL('image/png');
            link.click();

            // Reset Button
            btn.disabled = false;
            icon.className = 'las la-download text-lg';
            text.innerText = 'Save';
            
            // Optional: Success toast
            if(typeof notify === 'function') notify('success', 'Portfolio saved successfully!');
        }).catch(err => {
            console.error('Capture failed:', err);
            btn.disabled = false;
            icon.className = 'las la-exclamation-circle text-lg';
            text.innerText = 'Error';
            if(typeof notify === 'function') notify('error', 'Failed to save portfolio image.');
        });
    });
</script>
@endpush
