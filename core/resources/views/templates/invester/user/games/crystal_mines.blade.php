@extends($activeTemplate . 'layouts.master')
@section('content')

    <div class="px-2 mt-6 mb-12 relative z-10">
        
        <!-- Decorative Background Elements -->
        <div class="fixed top-20 left-0 w-64 h-64 bg-blue-600/10 rounded-full blur-[100px] pointer-events-none"></div>
        <div class="fixed bottom-20 right-0 w-64 h-64 bg-purple-600/10 rounded-full blur-[100px] pointer-events-none"></div>

        <!-- Header -->
        <div class="flex items-center justify-between mb-8 max-w-[420px] mx-auto relative z-20">
            <div class="flex items-center gap-4">
                 <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-[#1a1f35] to-[#0f1225] border border-white/5 flex items-center justify-center shadow-lg shadow-purple-500/10 relative overflow-hidden group">
                    <div class="absolute inset-0 bg-gradient-to-br from-blue-500/20 to-purple-500/20 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    <img src="https://cdn3d.iconscout.com/3d/premium/thumb/diamond-5264878-4402660.png" class="w-10 h-10 object-contain drop-shadow-md animate-float" alt="Gem">
                 </div>
                 <div>
                     <h1 class="text-3xl font-bold text-white font-orbitron tracking-wide text-transparent bg-clip-text bg-gradient-to-r from-white to-blue-200">Crystal Mines</h1>
                     <p class="text-xs text-blue-300/60 font-medium tracking-wider uppercase">Find Gems • Avoid Bombs</p>
                 </div>
            </div>
            
            <a href="{{ route('user.games.index') }}" class="w-10 h-10 rounded-full bg-white/5 border border-white/10 flex items-center justify-center hover:bg-white/10 hover:border-white/20 transition-all duration-300 hover:rotate-90">
                <i class="las la-times text-xl text-white/80"></i>
            </a>
        </div>

        <!-- Game Container -->
        <div class="flex flex-col gap-6 max-w-[420px] mx-auto relative z-20">
            
            <!-- Game Grid Area -->
            <div class="relative group perspective-1000">
                <!-- Border Gradient -->
                <div class="absolute -inset-[2px] bg-gradient-to-br from-blue-500/30 via-purple-500/30 to-pink-500/30 rounded-[2rem] blur-sm opacity-50 group-hover:opacity-100 transition-opacity duration-700"></div>
                
                <div class="glass-card p-1.5 rounded-[1.9rem] relative overflow-hidden bg-[#0a0f1e] shadow-2xl">
                    <!-- Internal Pattern -->
                    <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-[0.03]"></div>
                    <div class="absolute inset-0 bg-[radial-gradient(circle_at_center,_rgba(30,41,59,0.5)_0%,_rgba(10,15,30,0.8)_100%)]"></div>

                    <!-- The Grid -->
                    <div class="grid grid-cols-5 gap-2.5 p-4 relative z-10" id="mineGrid">
                        @for($i=0; $i<25; $i++)
                            <div class="mine-tile group/tile relative aspect-square cursor-pointer" data-index="{{ $i }}">
                                <div class="tile-inner w-full h-full transition-transform duration-500 transform-style-3d">
                                    <!-- Unclicked State -->
                                    <div class="tile-face front absolute inset-0 bg-[#1e293b]/50 border border-white/5 rounded-xl shadow-lg flex items-center justify-center transition-all duration-300 hover:bg-[#1e293b] hover:border-white/20 hover:shadow-blue-500/20 backdrop-blur-sm group-active/tile:scale-95">
                                        <div class="w-2 h-2 rounded-full bg-blue-500/20 shadow-[0_0_10px_rgba(59,130,246,0.5)] group-hover/tile:bg-blue-400/50 transition-colors"></div>
                                    </div>

                                    <!-- Clicked/Revealed State -->
                                    <div class="tile-face back absolute inset-0 bg-[#0f172a] border border-white/10 rounded-xl flex items-center justify-center shadow-inner overflow-hidden">
                                        <!-- Content injected via JS -->
                                    </div>
                                </div>
                            </div>
                        @endfor
                    </div>
                </div>
                
                <!-- Result Overlay -->
                <div id="resultOverlay" class="absolute inset-0 z-30 flex flex-col items-center justify-center bg-[#0a0f1e]/90 backdrop-blur-md rounded-[1.9rem] transition-all duration-500 opacity-0 pointer-events-none transform scale-110">
                     <div class="transform scale-90 transition-transform duration-300 text-center w-full px-6" id="resultCard">
                         <div class="mb-6 relative inline-block">
                             <div class="absolute inset-0 bg-gradient-to-r from-blue-500 to-purple-500 blur-[40px] opacity-40 animate-pulse"></div>
                             <img id="resImg" src="" class="w-32 h-32 relative z-10 object-contain mx-auto animate-float">
                         </div>
                         <h2 id="resTitle" class="text-4xl font-black text-white mb-2 font-orbitron tracking-wide drop-shadow-lg"></h2>
                         <p id="resDesc" class="text-blue-200/80 text-sm font-medium mb-8 tracking-wide"></p>
                         <button id="resBtn" class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white font-bold py-4 px-6 rounded-xl shadow-lg shadow-blue-600/30 transition-all duration-300 transform hover:-translate-y-1 active:scale-95 font-orbitron tracking-wider flex items-center justify-center gap-2">
                            <i class="las la-redo-alt text-xl"></i> PLAY AGAIN
                         </button>
                     </div>
                </div>

            </div>

            <!-- Controls Panel -->
            <div class="glass-card p-6 rounded-[2rem] relative overflow-hidden border border-white/5 bg-[#0a0f1e]/80 backdrop-blur-xl shadow-xl">
                
                <!-- Stats Row -->
                <div class="flex items-center justify-between mb-8 bg-[#131b2e] p-4 rounded-2xl border border-white/5 shadow-inner relative overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-r from-blue-500/5 to-purple-500/5"></div>
                    
                    <div class="text-center px-4 border-r border-white/5 w-1/2 relative z-10">
                        <span class="text-[10px] text-blue-300/50 uppercase tracking-widest font-bold mb-1 block">Multiplier</span>
                        <div class="text-2xl font-black text-transparent bg-clip-text bg-gradient-to-br from-emerald-300 to-emerald-500 font-orbitron">x<span id="curMult">1.00</span></div>
                    </div>
                     <div class="text-center px-4 w-1/2 relative z-10">
                        <span class="text-[10px] text-blue-300/50 uppercase tracking-widest font-bold mb-1 block">Current Win</span>
                        <div class="text-2xl font-black text-white font-orbitron drop-shadow-md"><span id="curWin">0.00</span> <span class="text-xs text-white/30 font-normal align-top">{{ gs('cur_sym') }}</span></div>
                    </div>
                </div>

                <!-- Input Controls -->
                <div id="controlPanel" class="space-y-6 transition-all duration-300">
                    
                    <!-- Mines Selector -->
                     <div class="space-y-2.5">
                        <div class="flex justify-between items-center">
                            <label class="text-xs font-bold text-white/70 uppercase tracking-wider">Mines Amount</label>
                            <span class="text-[10px] text-blue-400 font-medium bg-blue-500/10 px-2 py-0.5 rounded border border-blue-500/10" id="mineCountDisplay">3 Mines</span>
                        </div>
                        <div class="relative group">
                            <div class="absolute inset-0 bg-gradient-to-r from-blue-500/20 to-purple-500/20 rounded-xl blur opacity-0 group-hover:opacity-100 transition-opacity"></div>
                            <select id="mineSelect" class="relative w-full bg-[#131b2e] border border-white/10 rounded-xl py-4 px-5 text-white font-bold outline-none focus:border-blue-500/50 appearance-none cursor-pointer transition-colors shadow-inner text-sm">
                                @for($m=1; $m<=24; $m++)
                                    <option value="{{ $m }}" {{ $m == 3 ? 'selected' : '' }}>{{ $m }} Mines</option>
                                @endfor
                            </select>
                            <div class="absolute right-5 top-1/2 -translate-y-1/2 pointer-events-none text-white/30">
                                <i class="las la-angle-down text-lg"></i>
                            </div>
                        </div>
                     </div>

                     <!-- Bet Input -->
                     <div class="space-y-2.5">
                        <label class="text-xs font-bold text-white/70 uppercase tracking-wider">Bet Amount</label>
                        <div class="relative group">
                            <div class="absolute inset-0 bg-gradient-to-r from-blue-500/20 to-purple-500/20 rounded-xl blur opacity-0 group-hover:opacity-100 transition-opacity"></div>
                            <input type="number" id="betInput" class="relative w-full bg-[#131b2e] border border-white/10 rounded-xl py-4 px-5 text-white font-bold outline-none focus:border-blue-500/50 transition-colors shadow-inner text-sm" value="1.00" placeholder="0.00">
                             <div class="absolute right-4 top-1/2 -translate-y-1/2 flex items-center gap-3">
                                 <span class="text-xs font-bold text-white/30">{{ gs('cur_text') }}</span>
                                 <div class="h-5 w-[1px] bg-white/10"></div>
                                 <div class="flex gap-1">
                                     <button type="button" class="w-8 h-8 rounded-lg bg-white/5 hover:bg-white/10 text-[10px] font-bold text-blue-400 flex items-center justify-center transition-colors" onclick="$('#betInput').val((parseFloat($('#betInput').val()) * 0.5).toFixed(2))">½</button>
                                     <button type="button" class="w-8 h-8 rounded-lg bg-white/5 hover:bg-white/10 text-[10px] font-bold text-blue-400 flex items-center justify-center transition-colors" onclick="$('#betInput').val((parseFloat($('#betInput').val()) * 2).toFixed(2))">2x</button>
                                 </div>
                             </div>
                        </div>
                     </div>

                     <!-- Action Button -->
                     <button id="mainBtn" class="w-full bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 hover:from-blue-500 hover:via-indigo-500 hover:to-purple-500 text-white font-bold py-5 rounded-xl shadow-xl shadow-indigo-500/30 active:scale-[0.98] transition-all text-lg font-orbitron tracking-widest relative overflow-hidden group mt-4">
                         <div class="absolute top-0 left-[-100%] w-full h-full bg-linear-to-r from-transparent via-white/20 to-transparent skew-x-12 group-hover:animate-shine"></div>
                         <span class="relative z-10 flex items-center justify-center gap-2">
                             <i class="las la-play text-xl"></i> START MINING
                         </span>
                     </button>

                </div>

                 <!-- Cashout Button (Hidden initially) -->
                 <button id="cashoutBtn" class="hidden w-full bg-gradient-to-r from-emerald-500 to-teal-500 hover:from-emerald-400 hover:to-teal-400 text-white font-bold py-5 rounded-xl shadow-xl shadow-emerald-500/30 active:scale-[0.98] transition-all text-xl font-orbitron tracking-wider relative overflow-hidden group">
                    <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-10"></div>
                    <span class="relative z-10 flex flex-col items-center leading-none gap-1">
                        <span class="text-xs font-medium uppercase tracking-widest text-emerald-100 opacity-80">Take Win</span>
                        <span><span id="cashoutAmt"></span> {{ gs('cur_sym') }}</span>
                    </span>
                 </button>

                 <!-- Balance Footer -->
                 <div class="mt-8 text-center relative z-10">
                     <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/5 border border-white/5">
                         <i class="las la-wallet text-blue-400"></i>
                         <span class="text-xs text-white/60 font-medium">Balance:</span>
                         <span class="text-sm font-bold text-white font-orbitron">{{ showAmount(auth()->user()->interest_wallet) }} {{ gs('cur_sym') }}</span>
                     </div>
                 </div>

            </div>

        </div>
    </div>

    <!-- Audio Assets -->
    <audio id="sndClick" src="https://cdn.pixabay.com/audio/2022/03/24/audio_c8c8a73467.mp3" preload="auto"></audio>
    <audio id="sndWin" src="https://cdn.pixabay.com/audio/2022/01/18/audio_d0a13f69d2.mp3" preload="auto"></audio>
    <audio id="sndLose" src="https://cdn.pixabay.com/audio/2022/03/10/audio_5b66d6d45b.mp3" preload="auto"></audio>
    <audio id="sndGem" src="https://cdn.pixabay.com/audio/2022/03/15/audio_73147baa98.mp3" preload="auto"></audio>

@endsection

@push('style')
<style>
    /* 3D Flip */
    .perspective-1000 { perspective: 1000px; }
    .transform-style-3d { transform-style: preserve-3d; }
    .my-rotate-y-180 { transform: rotateY(180deg); }
    .backface-hidden { backface-visibility: hidden; }

    .tile-face {
        backface-visibility: hidden;
        -webkit-backface-visibility: hidden;
    }
    .tile-face.back {
        transform: rotateY(180deg);
    }
    .mine-tile.revealed .tile-inner {
        transform: rotateY(180deg);
    }

    /* Animations */
    @keyframes shine {
        0% { left: -100%; opacity: 0; }
        50% { opacity: 0.5; }
        100% { left: 100%; opacity: 0; }
    }
    .group-hover\:animate-shine:hover {
        animation: shine 1s;
    }

    @keyframes float {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-5px); }
    }
    .animate-float {
        animation: float 3s ease-in-out infinite;
    }

    /* Shake Animation */
    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        10%, 30%, 50%, 70%, 90% { transform: translateX(-4px); }
        20%, 40%, 60%, 80% { transform: translateX(4px); }
    }
    .animate-shake {
        animation: shake 0.5s cubic-bezier(.36,.07,.19,.97) both;
    }

    /* Pop In Effect for Icons */
    @keyframes popIn {
        0% { transform: scale(0) rotate(-180deg); opacity: 0; }
        70% { transform: scale(1.2) rotate(10deg); opacity: 1; }
        100% { transform: scale(1) rotate(0); }
    }
    .pop-in-icon {
        animation: popIn 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards;
    }

    /* select styling */
    #mineSelect option {
        background-color: #0f172a;
        color: white;
    }
</style>
@endpush

@push('script')
<script>
    "use strict";
    
    // Game State
    let isPlaying = false;
    let currentMines = 3;
    let tilesClicked = 0;
    
    // 3D Assets (IconScout / Flaticon)
    // Using high-res 3D rendered PNGs
    const ICON_GEM = 'https://cdn3d.iconscout.com/3d/premium/thumb/diamond-5264878-4402660.png';
    const ICON_BOMB = 'https://cdn3d.iconscout.com/3d/premium/thumb/bomb-4710188-3914840.png';
    const ICON_TROPHY = 'https://cdn3d.iconscout.com/3d/premium/thumb/trophy-4721733-3926017.png';

    // Mine Selection Update
    $('#mineSelect').on('change', function() {
        currentMines = $(this).val();
        $('#mineCountDisplay').text(currentMines + ' Mines');
    });
    
    // Initial value
    currentMines = $('#mineSelect').val();
    $('#mineCountDisplay').text(currentMines + ' Mines');

    // Start Game
    $('#mainBtn').on('click', function() {
        initAudio();
        
        let bet = parseFloat($('#betInput').val());
        if(isNaN(bet) || bet <= 0) {
            notify('error', 'Invalid bet amount');
            return;
        }

        playSound('click');
        $(this).prop('disabled', true).html('<i class="las la-circle-notch fa-spin text-xl"></i> STARTING...');
        $(this).addClass('opacity-75 cursor-not-allowed');

        $.ajax({
            url: "{{ route('user.games.crystal_mines.start') }}",
            method: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                bet: bet,
                mines: currentMines
            },
            success: function(res) {
                startGameUI(res);
            },
            error: function(err) {
                notify('error', err.responseJSON.error || 'Error starting game');
                resetMainBtn();
            }
        });
    });

    function resetMainBtn() {
        $('#mainBtn').prop('disabled', false).removeClass('opacity-75 cursor-not-allowed')
            .html('<span class="relative z-10 flex items-center justify-center gap-2"><i class="las la-play text-xl"></i> START MINING</span>');
    }

    function initAudio() {
        ['sndClick', 'sndWin', 'sndLose', 'sndGem'].forEach(id => {
            let audio = document.getElementById(id);
            if(audio) {
                audio.volume = 0;
                audio.play().then(() => {
                    audio.pause();
                    audio.currentTime = 0;
                    audio.volume = 0.6; // Better volume level
                }).catch(e => {}); 
            }
        });
    }

    function startGameUI(res) {
        isPlaying = true;
        tilesClicked = 0;
        
        // UI Reset
        $('.mine-tile').removeClass('revealed opacity-50 cursor-not-allowed');
        $('.tile-face.back').html('').css('background', '#0f172a');
        
        // Hide Controls / Show Game Status
        $('#controlPanel').addClass('hidden');
        $('#cashoutBtn').removeClass('hidden').prop('disabled', true).html('<span class="text-sm font-bold opacity-70">PICK A TILE TO START</span>'); 
        
        // Stats
        $('#curWin').text('0.00');
        $('#curMult').text('1.00');

        notify('success', 'Mines placed! Good Luck.');
    }

    // Tile Click Interaction
    $('.mine-tile').on('click', function() {
        if(!isPlaying) return;
        let tile = $(this);
        let idx = tile.data('index');

        if(tile.hasClass('revealed')) return;

        // Visual feedback immediate
        playSound('click');

        $.ajax({
            url: "{{ route('user.games.crystal_mines.action') }}",
            method: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                index: idx
            },
            success: function(res) {
                if(res.status === 'safe') {
                    handleSafe(tile, res);
                } else if(res.status === 'lost') {
                    handleLoss(tile, res);
                }
            },
            error: function(err) {
                 notify('error', 'Network Error');
            }
        });
    });

    function handleSafe(tile, res) {
        tilesClicked++;
        playSound('sndGem'); // Distinct sound for gems
        
        // 1. Styling for Gem
        // Radial gradient background for pop
        tile.find('.tile-face.back')
            .css('background', 'radial-gradient(circle at center, rgba(52, 211, 153, 0.2) 0%, rgba(15, 23, 42, 1) 70%)')
            .html(`
                <div class="w-full h-full flex items-center justify-center p-2">
                    <img src="${ICON_GEM}" class="w-full h-full object-contain pop-in-icon drop-shadow-[0_0_15px_rgba(52,211,153,0.5)]">
                </div>
            `);
        
        // 2. Trigger Flip
        tile.addClass('revealed');

        // 3. Update Stats with animation
        animateValue('curMult', parseFloat($('#curMult').text()), parseFloat(res.multiplier), 500);
        animateValue('curWin', parseFloat($('#curWin').text()), parseFloat(res.win_amount), 500);
        
        // 4. Update Cashout Button
        $('#cashoutBtn').prop('disabled', false).html(`
            <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-10"></div>
            <span class="relative z-10 flex flex-col items-center leading-none gap-1">
                <span class="text-xs font-medium uppercase tracking-widest text-emerald-100 opacity-80">Take Win</span>
                <span>${parseFloat(res.win_amount).toFixed(2)} {{ gs('cur_sym') }}</span>
            </span>
        `);
    }

    function handleLoss(tile, res) {
        playSound('sndLose');
        
        // 1. Reveal Exploded Mine
        tile.find('.tile-face.back')
            .css('background', 'radial-gradient(circle at center, rgba(239, 68, 68, 0.3) 0%, rgba(15, 23, 42, 1) 70%)')
            .html(`
                <div class="w-full h-full flex items-center justify-center p-2">
                    <img src="${ICON_BOMB}" class="w-full h-full object-contain pop-in-icon drop-shadow-[0_0_15px_rgba(239,68,68,0.6)]">
                </div>
            `);
            
        tile.addClass('revealed');
        tile.find('.tile-inner').addClass('animate-shake'); // Shake the tile

        isPlaying = false;
        
        // 2. Reveal All Other Mines
        setTimeout(() => {
            res.mines.forEach(mIdx => {
                let t = $(`.mine-tile[data-index="${mIdx}"]`);
                if(!t.hasClass('revealed')) {
                    t.addClass('revealed opacity-60');
                    t.find('.tile-face.back')
                     .css('background', 'rgba(239, 68, 68, 0.05)')
                     .html(`
                        <div class="w-full h-full flex items-center justify-center p-3">
                            <img src="${ICON_BOMB}" class="w-full h-full object-contain opacity-80 grayscale-[0.2]">
                        </div>
                    `);
                } else {
                     // Dim the safe tiles slightly to highlight the bomb
                     if(t.data('index') !== tile.data('index')) {
                         t.addClass('opacity-40');
                     }
                }
            });
            
            showResult('lost', 0);
        }, 800);
    }

    // Cashout Functionality
    $('#cashoutBtn').on('click', function() {
        if(!isPlaying) return;
        $(this).prop('disabled', true).html('<i class="las la-circle-notch fa-spin"></i> Processing...');
        playSound('click');

        $.ajax({
            url: "{{ route('user.games.crystal_mines.cashout') }}",
            method: "POST",
            data: { _token: "{{ csrf_token() }}" },
            success: function(res) {
                // Reveal remaining gems visually (optional nice-to-have, skipping for simplicity/speed)
                showResult('won', res.win_amount);
            },
            error: function(err) {
                notify('error', err.responseJSON.error);
                // Reset cashout btn text roughly if error? 
                // Mostly won't happen unless verified fail.
            }
        });
    });

    function showResult(status, amount) {
        isPlaying = false;
        let overlay = $('#resultOverlay');
        let card = $('#resultCard');
        
        overlay.removeClass('opacity-0 pointer-events-none scale-110');
        overlay.addClass('scale-100');
        card.removeClass('scale-90').addClass('scale-100');

        if(status === 'won') {
            $('#resImg').attr('src', ICON_TROPHY);
            $('#resTitle').text('VICTORY!').addClass('text-transparent bg-clip-text bg-gradient-to-r from-yellow-300 to-amber-500').removeClass('text-red-500');
            $('#resDesc').html(`You successfully mined <b class="text-white">${amount} {{ gs('cur_sym') }}</b>`);
            playSound('sndWin');
            
            // Trigger confetti (optional, if library existed, else just sound + visual)
        } else {
            $('#resImg').attr('src', ICON_BOMB);
            $('#resTitle').text('BOOM!').addClass('text-red-500').removeClass('text-transparent bg-clip-text bg-gradient-to-r from-yellow-300 to-amber-500');
            $('#resDesc').text('You hit a mine! Better luck next time.');
        }
    }

    $('#resBtn').on('click', function() {
        // Hide Overlay
        $('#resultOverlay').addClass('opacity-0 pointer-events-none scale-110').removeClass('scale-100');
        $('#resultCard').addClass('scale-90').removeClass('scale-100');
        
        // Show Controls
        $('#controlPanel').removeClass('hidden');
        $('#cashoutBtn').addClass('hidden');
        resetMainBtn();
        
        // Reset Grid
        $('.mine-tile').removeClass('revealed opacity-50 opacity-60 opacity-40');
        $('.tile-inner').removeClass('animate-shake');
        $('.tile-face.back').html('');
        
        playSound('click');
    });

    function playSound(type) {
        let audio;
        if(type === 'click') audio = document.getElementById('sndClick');
        if(type === 'win') audio = document.getElementById('sndWin');
        if(type === 'lose') audio = document.getElementById('sndLose');
        if(type === 'sndGem') audio = document.getElementById('sndGem');
        
        if(audio) {
            audio.currentTime = 0;
            audio.play().catch(e => {}); 
        }
    }
    
    // Animation Helper
    function animateValue(id, start, end, duration) {
        if (start === end) return;
        var range = end - start;
        var current = start;
        var increment = end > start ? 1 : -1;
        var stepTime = Math.abs(Math.floor(duration / (range * 100))); // simplistic
        if(stepTime < 5) stepTime = 5; // throttle
        var obj = document.getElementById(id);
        var timer = setInterval(function() {
            current += increment * 0.01; // increment by cent
            // simplistic approach for floating point animation
            if ((increment > 0 && current >= end) || (increment < 0 && current <= end)) {
                current = end;
                clearInterval(timer);
            }
            obj.innerHTML = current.toFixed(2);
        }, stepTime);
    }

</script>
@endpush
