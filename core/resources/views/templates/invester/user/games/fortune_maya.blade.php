@extends($activeTemplate . 'layouts.master')
@section('content')

    <div class="px-2 mt-4 mb-6">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h1 class="text-3xl font-bold text-white font-orbitron">@lang('Fortune Maya')</h1>
                <p class="text-blue-200/80 text-sm">@lang('Spin & Win')</p>
            </div>
            <a href="{{ route('user.games.index') }}" class="bg-gradient-to-r from-blue-500 to-purple-600 p-[1px] rounded-full">
                <div class="bg-black/40 backdrop-blur-sm rounded-full px-4 py-2 hover:bg-white/10 transition-all">
                    <span class="text-xs font-bold text-white flex items-center gap-1">
                        <i class="las la-arrow-left text-yellow-400 text-base"></i> @lang('Back')
                    </span>
                </div>
            </a>
        </div>

        <div class="glass-card p-2 rounded-2xl relative overflow-hidden flex justify-center min-h-[600px]">
            <!-- Loading Screen -->
            <div id="game-loading" class="absolute inset-0 z-50 flex flex-col items-center justify-center bg-[#002e41] text-white transition-opacity duration-500">
                <div class="w-16 h-16 border-4 border-blue-500 border-t-transparent rounded-full animate-spin mb-4"></div>
                <h3 class="font-orbitron text-xl font-bold animate-pulse">@lang('Loading Game')</h3>
                <div class="w-48 h-2 bg-gray-700/50 rounded-full mt-4 overflow-hidden border border-white/10">
                    <div id="loading-bar" class="h-full bg-gradient-to-r from-blue-500 to-purple-500 w-0 transition-all duration-100"></div>
                </div>
                <p id="loading-text" class="text-xs text-blue-300 mt-2 font-orbitron">0%</p>
            </div>

            <div id="phaser-game" class="rounded-xl overflow-hidden shadow-2xl shadow-blue-500/20 max-w-full"></div>
        </div>
    </div>
@endsection

@push('style')
<style>
    canvas {
        max-width: 100%;
        height: auto;
        border-radius: 12px;
    }
</style>
@endpush

@push('script')
<script src="https://cdn.jsdelivr.net/npm/phaser@3.60.0/dist/phaser.js"></script>
<script>
    "use strict";
    const ASSET_PATH = "{{ asset('assets/games/fortune-maya') }}/";
    const CSRF_TOKEN = "{{ csrf_token() }}";
    
    // Server-side balance
    let playerCredits = parseFloat("{{ auth()->user()->interest_wallet ?? 0 }}") || 0;
    const CUR_SYM = "{{ gs('cur_sym') }}";
    let gameResult = null;

    // =============================================
    // THEME CONFIGURATION - FROM HTML
    // =============================================
    const THEME_CONFIG = {
        name: "Fortune Egypt",
        canvas: { width: 720, height: 1280 },
        grid: {
            rows: 3, cols: 3, symbolSize: 180, centerX: 360, centerY: 600,
        },
        assets: {
            sizes: {
                symbol: { width: 180, height: 180 },
                wild: { width: 200, height: 200 },
                bg: { width: 720, height: 1280 },
                frame: { width: 720, height: 1280 },
                coin: { width: 40, height: 40 },
                musicToggle: { width: 60, height: 60 },
                betButtons: { width: 100, height: 100 },
                spinButton: { width: 180, height: 120 },
                win: { width: 360, height: 120 },
                bigWin: { width: 480, height: 200 },
            },
            symbols: [
                "icon1", "icon2", "icon3", "icon4", "icon5",
                "icon6", "icon7", "icon8", "icon9", "wild",
            ],
            wildKey: "wild",
        },
        ui: {
            logo: { x: 360, y: 600, scale: 2 },
            background: { x: 360, y: 650 },
            frame: { x: 360, y: 650 },
            creditText: { x: 445, y: 975, fontSize: 48, color: "#fff" },
            betText: { x: 220, y: 975, fontSize: 42, color: "#fff" },
            creditButton: { x: 575, y: 975 },
            plusButton: { x: 300, y: 975 },
            minusButton: { x: 135, y: 975 },
            spinButton: { x: 360, y: 1170 },
            musicButton: { x: 635, y: 70 },
            winPopup: { x: 360, y: 200 },
            bigWinPopup: { x: 360, y: 200 },
        },
        animations: {
            logoFadeIn: { duration: 2000, delay: 800 },
            logoShake: { offset: 10, repeat: 5, duration: 50 },
            logoFadeOut: { duration: 500 },
            symbolPulse: { scale: 1.05, duration: 300 },
            buttonPulse: { scale: 1.05, duration: 600 },
            buttonClick: { scale: 0.9, duration: 100 },
            winScale: { scale: 1.6, duration: 500 },
            winFadeOut: { duration: 500 },
            bigWinScale: { scale: 1.3, duration: 700 },
            overlayAlpha: { alpha: 0.5, duration: 100 },
        },
        particles: {
            win: {
                speed: { min: 400, max: 600 }, gravity: 800, lifespan: 700,
                scale: { start: 0.5, end: 0 }, quantity: 10,
            },
            bigWin: {
                speed: { min: 400, max: 600 }, gravity: 800, lifespan: 700,
                scale: { start: 0.6, end: 0 }, quantity: 20,
            },
        },
        sounds: {
            bgm: { volume: 0.5, loop: true },
            spin: { volume: 0.6 },
            win: { volume: 1 },
            click: { volume: 1 },
            slotEnd: { volume: 1 },
        },
    };

    // Game Variables
    const FORCE_WIN_CHANCE = 0.3;
    const MULTIPLIER_NORMAL = 3;
    const MULTIPLIER_BIG = 10;
    const BIG_WIN_CHANCE = 0.15;
    const BET_OPTIONS = [{{ $setting->bet_options }}];
    
    let betIndex = 0;
    let bet = BET_OPTIONS[betIndex];
    let reels = [];
    let sprites = [];
    let spinning = false;
    let creditText, betText;
    let btnMinus, btnPlus, btnCredit, spinButton;
    let winEffects = [];

    // Phaser Config
    const config = {
        type: Phaser.WEBGL,
        parent: 'phaser-game',
        width: THEME_CONFIG.canvas.width,
        height: THEME_CONFIG.canvas.height,
        scale: { mode: Phaser.Scale.FIT, autoCenter: Phaser.Scale.CENTER_BOTH },
        scene: { preload, create, update },
        fps: { target: 60, forceSetTimeOut: false },
        transparent: true
    };

    new Phaser.Game(config);

    // =============================================
    // PRELOAD
    // =============================================
    function preload() {
        // Loading UI Events
        this.load.on('progress', function (value) {
            const percent = Math.floor(value * 100);
            const bar = document.getElementById('loading-bar');
            const text = document.getElementById('loading-text');
            if(bar) bar.style.width = percent + '%';
            if(text) text.innerText = percent + '%';
        });

        this.load.setBaseURL(ASSET_PATH);
        this.load.image("logo", "img/logo.png");
        this.load.image("bg", "img/bg.png");
        this.load.image("hiki", "img/hiki.png");
        this.load.image("frame", "img/frame.png");
        this.load.image("minus", "img/minus.png");
        this.load.image("plus", "img/plus.png");
        this.load.image("spin", "img/spin.png");
        this.load.image("win", "img/win.png");
        this.load.image("coin", "img/coin.png");
        this.load.image("bigwin", "img/bigwin.png");
        this.load.image("musicOn", "img/musicOn.png");
        this.load.image("musicOff", "img/musicOff.png");

        this.load.audio("bgm", "sounds/bgm.mp3");
        this.load.audio("spin", "sounds/spin.mp3");
        this.load.audio("win", "sounds/win.mp3");
        this.load.audio("click", "sounds/click.wav");
        this.load.audio("slot_end", "sounds/slot_end.wav");

        THEME_CONFIG.assets.symbols.forEach((key) =>
          this.load.image(key, `img/${key}.png`)
        );
    }

    // =============================================
    // CREATE
    // =============================================
    function create() {
        // Hide Loading Screen
        const loader = document.getElementById('game-loading');
        if(loader) {
            loader.style.opacity = '0';
            setTimeout(() => loader.remove(), 500);
        }

        this.cameras.main.setBackgroundColor(0x002e41);
        
        const logo = this.add.image(THEME_CONFIG.ui.logo.x, THEME_CONFIG.ui.logo.y, "logo")
          .setOrigin(0.5).setAlpha(0).setScale(THEME_CONFIG.ui.logo.scale);

        this.tweens.add({
          targets: logo, alpha: 1, scale: THEME_CONFIG.ui.logo.scale,
          duration: THEME_CONFIG.animations.logoFadeIn.duration, ease: "Power2",
          onComplete: () => {
            this.time.delayedCall(THEME_CONFIG.animations.logoFadeIn.delay, () => {
                this.tweens.add({
                  targets: logo, x: THEME_CONFIG.ui.logo.x + THEME_CONFIG.animations.logoShake.offset,
                  yoyo: true, repeat: THEME_CONFIG.animations.logoShake.repeat,
                  duration: THEME_CONFIG.animations.logoShake.duration,
                  onComplete: () => {
                    this.tweens.add({
                      targets: logo, alpha: 0,
                      duration: THEME_CONFIG.animations.logoFadeOut.duration,
                      onComplete: () => { logo.destroy(); initGame.call(this); },
                    });
                  },
                });
            });
          },
        });
    }

    // =============================================
    // GAME INITIALIZATION
    // =============================================
    function initGame() {
        this.bgm = this.sound.add("bgm", THEME_CONFIG.sounds.bgm);
        // this.bgm.play(); // Auto-play might be blocked, button handles it

        this.sfx = {
          spin: this.sound.add("spin", THEME_CONFIG.sounds.spin),
          win: this.sound.add("win", THEME_CONFIG.sounds.win),
          click: this.sound.add("click", THEME_CONFIG.sounds.click),
          slot_end: this.sound.add("slot_end", THEME_CONFIG.sounds.slotEnd),
        };

        this.add.image(THEME_CONFIG.ui.background.x, THEME_CONFIG.ui.background.y, "bg");

        const hiki = this.add.image(THEME_CONFIG.ui.logo.x, 300, "hiki").setOrigin(0.5).setScale(1.5);
        this.tweens.add({ targets: hiki, scale: 1.4, alpha: 1, duration: 2000, yoyo: true, repeat: -1, ease: "Sine.easeInOut" });
        this.tweens.add({ targets: hiki, angle: 3, duration: 3000, yoyo: true, repeat: -1, ease: "Sine.easeInOut" });

        this.add.image(THEME_CONFIG.ui.frame.x, THEME_CONFIG.ui.frame.y, "frame");

        creditText = this.add.text(THEME_CONFIG.ui.creditText.x, THEME_CONFIG.ui.creditText.y, ` ${formatMoney(playerCredits)}`, {
            fontSize: `${THEME_CONFIG.ui.creditText.fontSize}px`, fontStyle: "bold", color: THEME_CONFIG.ui.creditText.color,
        }).setOrigin(0.5);

        betText = this.add.text(THEME_CONFIG.ui.betText.x, THEME_CONFIG.ui.betText.y, `${bet}`, {
            fontSize: `${THEME_CONFIG.ui.betText.fontSize}px`, color: THEME_CONFIG.ui.betText.color, fontStyle: "bold",
        }).setOrigin(0.5);

        btnCredit = makeButtonInteractive(this, this.add.image(THEME_CONFIG.ui.creditButton.x, THEME_CONFIG.ui.creditButton.y, "plus").setOrigin(0.5));
        
        // Use credit button to go to deposit
        btnCredit.on("pointerdown", () => {
             if (spinning) return;
             this.sfx.click.play();
             window.location.href = "{{ route('user.deposit.index') }}"; 
        });

        btnPlus = makeButtonInteractive(this, this.add.image(THEME_CONFIG.ui.plusButton.x, THEME_CONFIG.ui.plusButton.y, "plus").setOrigin(0.5));
        btnPlus.on("pointerdown", () => {
            if (spinning) return;
            this.sfx.click.play();
            betIndex = (betIndex + 1) % BET_OPTIONS.length;
            bet = BET_OPTIONS[betIndex];
            betText.setText(`${bet}`);
        });

        btnMinus = makeButtonInteractive(this, this.add.image(THEME_CONFIG.ui.minusButton.x, THEME_CONFIG.ui.minusButton.y, "minus").setOrigin(0.5));
        btnMinus.on("pointerdown", () => {
            if (spinning) return;
            this.sfx.click.play();
            betIndex = (betIndex - 1 + BET_OPTIONS.length) % BET_OPTIONS.length;
            bet = BET_OPTIONS[betIndex];
            betText.setText(`${bet}`);
        });

        spinButton = makeButtonInteractive(this, this.add.image(THEME_CONFIG.ui.spinButton.x, THEME_CONFIG.ui.spinButton.y, "spin").setOrigin(0.5));
        spinButton.on("pointerdown", () => {
            if (!spinning) startSpin(this);
            this.sfx.click.play();
        });

        let musicOn = false;
        const musicButton = this.add.image(THEME_CONFIG.ui.musicButton.x, THEME_CONFIG.ui.musicButton.y, "musicOff").setOrigin(0.5).setInteractive();
        musicButton.on("pointerdown", () => {
            musicOn = !musicOn;
            if (musicOn) {
                musicButton.setTexture("musicOn");
                this.bgm.play({ loop: true });
            } else {
                musicButton.setTexture("musicOff");
                this.bgm.pause();
            }
        });

        // Initialize Reels
        for (let c = 0; c < THEME_CONFIG.grid.cols; c++) {
            reels.push({
                symbols: [...THEME_CONFIG.assets.symbols],
                position: 0, speed: 0, isSpinning: false, stops: [],
            });
        }

        // Create Sprites
        const totalW = THEME_CONFIG.grid.cols * THEME_CONFIG.grid.symbolSize;
        const totalH = THEME_CONFIG.grid.rows * THEME_CONFIG.grid.symbolSize;
        const startX = THEME_CONFIG.grid.centerX - totalW / 2 + THEME_CONFIG.grid.symbolSize / 2;
        const startY = THEME_CONFIG.grid.centerY - totalH / 2 + THEME_CONFIG.grid.symbolSize / 2;

        for (let c = 0; c < THEME_CONFIG.grid.cols; c++) {
            sprites[c] = [];
            for (let r = 0; r < THEME_CONFIG.grid.rows; r++) {
                const key = Phaser.Utils.Array.GetRandom(THEME_CONFIG.assets.symbols);
                const s = this.add.image(startX + c * THEME_CONFIG.grid.symbolSize, startY + r * THEME_CONFIG.grid.symbolSize, key)
                    .setDisplaySize(THEME_CONFIG.grid.symbolSize, THEME_CONFIG.grid.symbolSize);

                s.blurFX = s.preFX.addBlur(0);
                s.blurFX.strength = 0;

                this.tweens.add({
                    targets: s, scale: THEME_CONFIG.animations.symbolPulse.scale,
                    duration: THEME_CONFIG.animations.symbolPulse.duration,
                    yoyo: true, repeat: -1,
                });

                sprites[c][r] = s;
            }
        }
    }

    // =============================================
    // BUTTON & WIN VISUALS
    // =============================================
    function makeButtonInteractive(scene, button, options = {}) {
        const pulseScale = options.pulseScale || THEME_CONFIG.animations.buttonPulse.scale;
        const pulseDuration = options.pulseDuration || THEME_CONFIG.animations.buttonPulse.duration;
        const clickScale = options.clickScale || THEME_CONFIG.animations.buttonClick.scale;
        const clickDuration = options.clickDuration || THEME_CONFIG.animations.buttonClick.duration;

        scene.tweens.add({
            targets: button, scale: pulseScale, duration: pulseDuration,
            yoyo: true, repeat: -1, ease: "Sine.easeInOut",
        });

        button.setInteractive();
        button.on("pointerdown", () => {
            scene.tweens.add({
                targets: button, scale: clickScale, yoyo: true,
                duration: clickDuration, ease: "Power1",
            });
        });
        return button;
    }

    function showWinEffect(scene) {
        scene.sfx.win.play();
        const winSprite = scene.add.image(THEME_CONFIG.ui.winPopup.x, THEME_CONFIG.ui.winPopup.y, "win")
             .setOrigin(0.5).setScale(0).setAlpha(1);

        const emitter = scene.add.particles(0, -300, "coin", {
            speed: THEME_CONFIG.particles.win.speed,
            angle: { min: 0, max: 360 },
            gravityY: THEME_CONFIG.particles.win.gravity,
            lifespan: THEME_CONFIG.particles.win.lifespan,
            scale: THEME_CONFIG.particles.win.scale,
            quantity: THEME_CONFIG.particles.win.quantity,
        });
        emitter.explode(THEME_CONFIG.particles.win.quantity, THEME_CONFIG.grid.centerX, THEME_CONFIG.grid.centerY);

        scene.tweens.add({
            targets: winSprite, scale: THEME_CONFIG.animations.winScale.scale,
            duration: THEME_CONFIG.animations.winScale.duration,
            yoyo: true, ease: "Power1",
            onComplete: () => {
                scene.tweens.add({
                    targets: winSprite, alpha: 0,
                    duration: THEME_CONFIG.animations.winFadeOut.duration,
                    onComplete: () => winSprite.destroy(),
                });
            },
        });
    }

    function showBigWinEffect(scene) {
        scene.sfx.win.play();
        const overlay = scene.add.rectangle(THEME_CONFIG.grid.centerX, THEME_CONFIG.canvas.height / 2, THEME_CONFIG.canvas.width, THEME_CONFIG.canvas.height, 0x000000, 0).setOrigin(0.5);
        scene.tweens.add({ targets: overlay, alpha: THEME_CONFIG.animations.overlayAlpha.alpha, duration: THEME_CONFIG.animations.overlayAlpha.duration });

        const emitter = scene.add.particles(0, -300, "coin", {
            speed: THEME_CONFIG.particles.bigWin.speed,
            angle: { min: 0, max: 360 },
            gravityY: THEME_CONFIG.particles.bigWin.gravity,
            lifespan: THEME_CONFIG.particles.bigWin.lifespan,
            scale: THEME_CONFIG.particles.bigWin.scale,
            quantity: THEME_CONFIG.particles.bigWin.quantity,
        });
        emitter.explode(THEME_CONFIG.particles.bigWin.quantity, THEME_CONFIG.grid.centerX, THEME_CONFIG.grid.centerY);

        const bigWinSprite = scene.add.image(THEME_CONFIG.ui.bigWinPopup.x, THEME_CONFIG.ui.bigWinPopup.y, "bigwin").setOrigin(0.5).setScale(0);
        scene.tweens.add({
            targets: bigWinSprite, scale: THEME_CONFIG.animations.bigWinScale.scale,
            duration: THEME_CONFIG.animations.bigWinScale.duration,
            yoyo: true, ease: "Power1",
            onComplete: () => {
                 // Nothing? Original code leaves it? No, it just fades.
            }
        });
        // original didn't fade out big win properly in loop, but let's assume it stays until next spin clears.
    }

    function showWinSymbolEffects(scene, lines) {
        lines.forEach((r) => {
            for (let c = 0; c < THEME_CONFIG.grid.cols; c++) {
                const sprite = sprites[c][r];
                const shine = sprite.preFX.addShine(1, 0.2, 3);
                const pulse = scene.tweens.add({
                    targets: sprite, scale: 1.1, duration: 200, yoyo: true, repeat: -1,
                });
                winEffects.push({ sprite, shine, pulse });
            }
        });
    }

    function clearWinEffects(scene) {
        winEffects.forEach((e) => {
            if (e.shine) e.sprite.preFX.remove(e.shine);
            if (e.pulse) e.pulse.stop();
            e.sprite.setScale(1);
        });
        winEffects = [];
    }

    // =============================================
    // GAME LOGIC (SERVER INTERGRATED)
    // =============================================
    function startSpin(scene) {
        if (playerCredits < bet) {
            notify('error', 'Insufficient Balance');
            setTimeout(() => {
                window.location.href = "{{ route('user.deposit.index') }}";
            }, 2000);
            return;
        }

        spinning = true;
        
        // Optimistic deduction
         playerCredits -= bet;
         creditText.setText(`${formatMoney(playerCredits)}`);
         clearWinEffects(scene); // Clear immediately
         
         // Start Blur
         for (let c = 0; c < THEME_CONFIG.grid.cols; c++) {
              for (let r = 0; r < THEME_CONFIG.grid.rows; r++) {
                sprites[c][r].blurFX.strength = 1.5;
              }
         }

         // Start Reels
         reels.forEach((reel) => {
              reel.isSpinning = true;
              reel.speed = 2;
              reel.stops = [];
         });

        fetch("{{ route('user.games.spin') }}", {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN },
            body: JSON.stringify({ bet: bet })
        })
        .then(response => response.json())
        .then(data => {
            if(data.error) {
                notify('error', data.error);
                if(data.redirect) {
                    setTimeout(() => {
                        window.location.href = data.redirect;
                    }, 2000);
                }
                spinning = false;
                 // Revert balance on error
                 playerCredits += bet; 
                 creditText.setText(`${formatMoney(playerCredits)}`);
                return;
            }

            // Sync True Data
            gameResult = data;
            playerCredits = data.balance - (gameResult.win ? gameResult.winAmount : 0); // Temporary sync before win animation
            if(!gameResult.win) playerCredits = data.balance; // If no win, balance is just balance
            
            // Wait - visuals update at end
            
            scene.sfx.spin.play();

            // Setup Stops
            reels.forEach((reel, c) => {
                for(let r=0; r<THEME_CONFIG.grid.rows; r++) {
                    reel.stops[r] = data.grid[c][r];
                }
            });

            // Stop Sequence
            reels.forEach((reel, c) => {
                scene.time.delayedCall(1000 + c * 300, () => stopReel(scene, c));
            });
        })
        .catch(err => {
            console.error(err);
            notify('error', "Network Error");
            spinning = false;
        });
    }

    function stopReel(scene, col) {
        const reel = reels[col];
        const decelerationSteps = 10;
        let step = 0;

        const interval = scene.time.addEvent({
            delay: 10, repeat: decelerationSteps - 1,
            callback: () => {
                scene.sfx.slot_end.play();
                step++;
                for (let r = 0; r < THEME_CONFIG.grid.rows; r++) {
                    const randomKey = Phaser.Utils.Array.GetRandom(THEME_CONFIG.assets.symbols);
                    sprites[col][r].setTexture(randomKey);
                }
                if (step === decelerationSteps) {
                     for (let r = 0; r < THEME_CONFIG.grid.rows; r++) {
                        sprites[col][r].setTexture(reel.stops[r]);
                        sprites[col][r].blurFX.strength = 0;
                     }
                     reel.isSpinning = false;
                     if (col === THEME_CONFIG.grid.cols - 1) {
                         scene.sfx.spin.stop();
                         handleWin(scene);
                     }
                }
            }
        });
    }

    function handleWin(scene) {
        if (gameResult.win) {
             const prize = gameResult.winAmount;
             playerCredits = gameResult.balance; // Final true balance
             creditText.setText(`${formatMoney(playerCredits)}`);
             
             showWinSymbolEffects(scene, gameResult.winLines);
             
             if (prize > bet * 5) {
                 showBigWinEffect(scene);
             } else {
                 showWinEffect(scene);
             }
        } else {
            // Ensure balance display is correct
             playerCredits = gameResult.balance;
             creditText.setText(`${formatMoney(playerCredits)}`);
        }
        spinning = false;
    }

    function update(time, delta) {
        reels.forEach((reel, c) => {
            if (!reel.isSpinning) return;
            reel.position += reel.speed * (delta / 1000);
            if (reel.position >= 1000) reel.position = 0;
            for (let r = 0; r < THEME_CONFIG.grid.rows; r++) {
                const randomKey = Phaser.Utils.Array.GetRandom(THEME_CONFIG.assets.symbols);
                sprites[c][r].setTexture(randomKey);
            }
        });
    }

    function formatMoney(amount) {
        let val = parseFloat(amount);
        if (isNaN(val)) val = 0;
        let formatted = val.toFixed(2);
        if (formatted.endsWith('.00')) {
            formatted = val.toFixed(0);
        } else if (formatted.endsWith('0')) {
            formatted = val.toFixed(1);
        }
        return formatted + ' ' + CUR_SYM;
    }
</script>
@endpush
