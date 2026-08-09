
<!-- ========================================== -->
<!-- AVIATOR GAME LOGIC & CANVAS ANIMATION      -->
<!-- ALL JS LOGIC INLINED IN BLADE              -->
<!-- ========================================== -->

<script>
    // ============================================
    // 1. CANVAS ANIMATION LOGIC
    // ============================================
    // Global variables for canvas
    var cW, cH, canvas, ctx, screenHeight, screenWidth, x;
    var canvasHeight = 0, canvasWidth = 0, calcwidth = 0, calcheight = 0;
    var horizontalLine = 0, verticalLine = 0, verticaldots = 0, verticalDotSize = 0;
    var boardWidth = 0, boardheight = 0, widthDouble = 0, xPoint = 0, yPoint = 0;
    var diffx = 0, imgheight = 0, imgwidth = 0, imgyposition = 0, imgxposition = 0;
    var settimeinterval = 0, checkuplinedownlinecount = 0, diffy = 0, diffx1 = 0;
    var yend = 0, xend = 0, backgroundImage = '', start = null, progress = 0;
    var frameIndex = 0, countInterval = 0, estimateHeight = 0, estimateWidth = 0;
    var HorizontalDotsCountRun = 0, VerticalDotsCountRun = 0, lastUpdate = Date.now();
    var y0 = 0, x0 = 0, y1 = 0, x1 = 0, y2 = 0, x2 = 0;
    var intervalID, intervalID1, stopPlaneEvent = 0;
    var nx0 = 0, ny0 = 0, nx1 = 0, ny1 = 0, nx2 = 0, ny2 = 0;
    var StopPlaneIntervalID, StopPlaneIntervalID1 = 0, startupdown = 0;
    
    // Global Image Tag
    window.imgTag = new Image();

    $(document).ready(function() {
        cW = $('.stage-board').width();
        cH = $('.stage-board').height();
        $('#myCanvas').attr('width', cW).attr('height', cH);
        canvas = $('#myCanvas');
        if(canvas.length > 0) ctx = canvas[0].getContext('2d');
    });

    function setVariable(is_plan = '') {
        if(!ctx) {
             canvas = $('#myCanvas');
             if(canvas.length > 0) ctx = canvas[0].getContext('2d');
             else return;
        }

        cW = $('.stage-board').width();
        cH = $('.stage-board').height();
        $('#myCanvas').attr('width', cW).attr('height', cH);

        canvasHeight = $('canvas').innerHeight();
        canvasWidth = $('canvas').innerWidth();
        calcwidth = canvasWidth / 100;
        calcheight = canvasHeight / 100;
        
        if (canvasWidth < 992) {
            diffx = calcwidth * 45;
            horizontalLine = calcwidth * 10;
            verticalLine = calcheight * 10;
        } else {
            diffx = calcwidth * 30;
            horizontalLine = calcwidth * 5;
            verticalLine = calcheight * 5;
        }

        verticaldots = verticalLine / 100;
        verticalDotSize = (verticaldots * 50);
        boardWidth = canvasWidth;
        boardheight = canvasHeight;
        widthDouble = boardWidth * 2.5;
        xPoint = 0 - (boardWidth * 1.25);
        yPoint = boardheight - (boardWidth * 1.25);
        $(".rotateimage").css("width", widthDouble).css("height", widthDouble).css("top", yPoint).css("left", xPoint);
        $(".rotateimage").addClass('rotatebg');
        
        if(!window.imgTag) window.imgTag = new Image();

        if (canvasWidth < 992) {
            imgheight = 48;
            imgwidth = 200;
            imgyposition = 45;
            imgxposition = 10;
            window.imgTag.src = "{{ asset('assets/game_aviator/images/sprite2.png') }}";
            settimeinterval = 40;
            checkuplinedownlinecount = 50;
        } else {
            imgheight = 71;
            imgwidth = 300;
            imgyposition = 66;
            imgxposition = 15;
            window.imgTag.src = "{{ asset('assets/game_aviator/images/sprite3.png') }}";
            settimeinterval = 20;
            checkuplinedownlinecount = 150;
        }
        
        diffy = calcheight * 70;
        diffx1 = canvasWidth - (calcwidth * 60)

        yend = canvasHeight - diffy;
        xend = canvasWidth - diffx;
        backgroundImage = '';
        start = null;
        progress = 0;
        frameIndex = 0;
        countInterval = 0;
        estimateHeight = 0;
        estimateWidth = 0;
        HorizontalDotsCountRun = 1;
        VerticalDotsCountRun = 1;
        lastUpdate = Date.now();
        y0 = (ctx.canvas.height - verticalLine);
        x0 = verticalLine;
        y1 = (ctx.canvas.height - verticalLine);
        x1 = diffx1;
        y2 = yend;
        x2 = xend;
        startupdown = 0;
        stopPlaneEvent = 0;
    
        var is_plan_display = (is_plan != '') ? window.imgTag : '';
        
        // Fix: Wait for image load before starting animation
        if(is_plan_display && !is_plan_display.complete) {
            is_plan_display.onload = function() {
                 animatePathDrawing(ctx, verticalLine, (ctx.canvas.height - verticalLine), diffx1, (ctx.canvas.height - verticalLine), xend, yend, 40000, is_plan_display);
            };
        } else {
             // Image already loaded or no plane
             animatePathDrawing(ctx, verticalLine, (ctx.canvas.height - verticalLine), diffx1, (ctx.canvas.height - verticalLine), xend, yend, 40000, is_plan_display);
        }
    }

    function animatePathDrawing(ctx, x0, y0, x1, y1, x2, y2, duration, imgTag) {
        var step = function animatePathDrawingStep(timestamp) {
            if (start === null) start = timestamp;
            var delta = timestamp - start;
            var progress = Math.min(delta / duration, 1);

            if (imgTag != '') {
                drawBezierSplit(ctx, x0, y0, x1, y1, x2, y2, 0, progress, imgTag);
            }

            if (progress < 1 && stopPlaneEvent === 0) {
                window.requestAnimationFrame(step);
            }
        };
        window.requestAnimationFrame(step);
    }

    function stopPlane() {
        if(StopPlaneIntervalID1 == 0){
            ctx.beginPath();
            clearInterval(intervalID);
            clearInterval(intervalID1);
            stopPlaneEvent = 1; // Stops animation loop
            $(".rotateimage").removeClass('rotatebg');

            ctx.clearRect(0, 0, ctx.canvas.width, ctx.canvas.height);
            var intervalTimex = 100;
            var intervalTimey = 50;
            
            if (startupdown == 1) {
                nx2 = estimateWidth;
                ny2 = estimateHeight;
            }
            
            var stopPlaneCount = Math.round((ctx.canvas.width - nx2) / 4);

            StopPlaneIntervalID = setInterval(() => {
                ctx.beginPath();
                ctx.clearRect(0, 0, ctx.canvas.width, ctx.canvas.height);
                ctx.moveTo(nx0, ny0);
                ctx.quadraticCurveTo(nx1, ny1, nx2 + intervalTimex, ny2 - intervalTimey);
                GameObject(window.imgTag, (nx2 + intervalTimex) - imgxposition, (ny2 - intervalTimey) - imgyposition, imgwidth, imgheight, 300, 2, ctx);
                ctx.closePath();
                StopPlaneIntervalID1++;
                intervalTimex = intervalTimex + 4;
                intervalTimey = intervalTimey + 1;

                if (StopPlaneIntervalID1 >= (stopPlaneCount)) {
                    window.clearInterval(StopPlaneIntervalID);
                    StopPlaneIntervalID1 = 0;
                }
            }, 20);
            ctx.closePath();
        }
    }

    // Helper Draw Functions
    function drawLine() {
        ctx.beginPath();
        ctx.moveTo((verticalLine), 0);
        ctx.lineTo((verticalLine), (ctx.canvas.height - verticalLine));
        ctx.lineTo((ctx.canvas.width), (ctx.canvas.height - verticalLine));
        ctx.lineWidth = 1;
        ctx.strokeStyle = '#423033';
        ctx.stroke();
        ctx.closePath();
    }
    
    function drawHorizontalDots() {
        var HorizontalDotsCount = 1;
        var verticalLinedata;
        var horizontalLinedata;
        ctx.save();
        ctx.beginPath();
        if (canvasWidth < 992) {
            verticalLinedata = verticalLine / 2;
            horizontalLinedata = horizontalLine / 2;
        } else {
            verticalLinedata = verticalLine;
            horizontalLinedata = horizontalLine;
        }
        ctx.rect(verticalLine, (ctx.canvas.height - verticalLine), ctx.canvas.width, verticalLine);
        ctx.closePath();
        ctx.clip();
        for (let i = 0; i < 20; i++) {
            ctx.beginPath();
            ctx.arc(((horizontalLinedata * 2) * i) + 3, (ctx.canvas.height - verticalLine) + verticalDotSize, 2, 0, 2 * Math.PI);
            ctx.fillStyle = 'white';
            ctx.fill();
            ctx.closePath();
        }
        ctx.restore();
    }
    
    function animationHorizontalDots() {
        var verticalLinedata;
        var horizontalLinedata;
        ctx.beginPath();
        ctx.save();
        ctx.beginPath();
        if (canvasWidth < 992) {
            verticalLinedata = verticalLine / 2;
            horizontalLinedata = horizontalLine / 2;
        } else {
            verticalLinedata = verticalLine;
            horizontalLinedata = horizontalLine;
        }
        ctx.fillStyle = "rgba(0,0,0,0.1)";
        ctx.rect(verticalLine, (ctx.canvas.height - verticalLine), ctx.canvas.width, verticalLine);
        ctx.fill();
        ctx.closePath();
        ctx.clip();
        for (let i = 0; i < 2000; i++) {
            ctx.beginPath();
            ctx.arc((((horizontalLinedata * 2) * i) + 3) - HorizontalDotsCountRun, (ctx.canvas.height - verticalLine) + verticalDotSize, 2, 0, 2 * Math.PI);
            ctx.fillStyle = 'white';
            ctx.fill();
            ctx.closePath();
        }
        HorizontalDotsCountRun = HorizontalDotsCountRun + 1;
        ctx.restore();
    }

    function animationVerticalDots() {
        var verticalLinedata;
        var horizontalLinedata;
        ctx.beginPath();
        if (canvasWidth < 992) {
            verticalLinedata = verticalLine / 2;
            horizontalLinedata = horizontalLine / 2;
        } else {
            verticalLinedata = verticalLine;
            horizontalLinedata = horizontalLine;
        }
        ctx.save();
        ctx.beginPath();
        ctx.fillStyle = "rgba(0,0,0,0.1)";
        ctx.rect(0, 0, verticalLine, (ctx.canvas.height - verticalLine));
        ctx.closePath();
        ctx.clip();
        for (let i = 0; i < 2000; i++) {
            ctx.beginPath();
            ctx.arc((verticalLine - verticalDotSize), ((ctx.canvas.height - (verticalLinedata * i)) * 2 - 5) + VerticalDotsCountRun, 2, 0, 2 * Math.PI);
            ctx.fillStyle = '#1197D6';
            ctx.fill();
            ctx.closePath();
        }
        VerticalDotsCountRun = VerticalDotsCountRun + 1;
        ctx.restore();
    }

    function drawVerticalDots() {
        var verticalLinedata;
        var horizontalLinedata;
        var VerticalDotsCount = 0;
        ctx.save();
        ctx.beginPath();
        if (canvasWidth < 992) {
            verticalLinedata = verticalLine / 2;
            horizontalLinedata = horizontalLine / 2;
        } else {
            verticalLinedata = verticalLine;
            horizontalLinedata = horizontalLine;
        }
        ctx.rect(0, 0, verticalLine, (ctx.canvas.height - verticalLine));
        ctx.closePath();
        ctx.clip();
        for (let i = 0; i < 20; i++) {
            ctx.beginPath();
            ctx.arc((verticalLine - verticalDotSize), (verticalLinedata * i) * 2 +5, 2, 0, 2 * Math.PI);
            ctx.fillStyle = '#1197D6';
            ctx.fill();
            ctx.closePath();
        }
        ctx.restore();
    }

    function draw(spritesheet, x, y, width, height, timePerFrame, numberOfFrames, ctx, frameIndex) {
        ctx.drawImage(spritesheet, (frameIndex * width / numberOfFrames), 0, (width / numberOfFrames), height, x, y, (width / numberOfFrames), height);
    }
    
    function GameObject(spritesheet, x, y, width, height, timePerFrame, numberOfFrames, ctx) {
        if (Date.now() - lastUpdate >= timePerFrame) {
            frameIndex++;
            if (frameIndex >= numberOfFrames) {
                frameIndex = 0;
            }
            lastUpdate = Date.now();
        }
        draw(spritesheet, x, y, width, height, timePerFrame, numberOfFrames, ctx, frameIndex);
    }

    function drawBezierSplit(ctx, x0, y0, x1, y1, x2, y2, t0, t1, imgTag) {
        if (0.0 == t0 && t1 == 1.0) {
            if (stopPlaneEvent == 0) {
                startupdown = 1;
                ctx.beginPath();
                ctx.clearRect(0, 0, ctx.canvas.width, ctx.canvas.height);
                $.when(drawLine()).then(animationHorizontalDots());
                animationVerticalDots();
                ctx.moveTo(x0, y0);
                ctx.quadraticCurveTo(x1, y1, x2, y2);
                GameObject(imgTag, x2 - imgxposition, y2 - imgyposition, imgwidth, imgheight, 300, 2, ctx);
                ctx.lineWidth = 5;
                ctx.strokeStyle = '#F00B3E';
                ctx.stroke();
                ctx.closePath();
                fillShape(x2, y2, x0, y0, x1, y1, t1);
                startfirstinterval();
                animationHorizontalDots();
            }
        } else if (t0 != t1) {
            if (stopPlaneEvent == 0) {
                ctx.beginPath();
                ctx.clearRect(0, 0, ctx.canvas.width, ctx.canvas.height);
                $.when(drawLine()).then(drawHorizontalDots());
                $.when(drawHorizontalDots()).then(drawVerticalDots());
                var t00 = t0 * t0;
                var t01 = 1.0 - t0;
                var t02 = t01 * t01;
                var t03 = 2.0 * t0 * t01;
                nx0 = t02 * x0 + t03 * x1 + t00 * x2;
                ny0 = t02 * y0 + t03 * y1 + t00 * y2;

                t00 = t1 * t1;
                t01 = 1.0 - t1;
                t02 = t01 * t01;
                t03 = 2.0 * t1 * t01;
                nx2 = t02 * x0 + t03 * x1 + t00 * x2;
                ny2 = t02 * y0 + t03 * y1 + t00 * y2;

                nx1 = lerp(lerp(x0, x1, t0), lerp(x1, x2, t0), t1);
                ny1 = lerp(lerp(y0, y1, t0), lerp(y1, y2, t0), t1);
                ctx.moveTo(nx0, ny0);
                ctx.quadraticCurveTo(nx1, ny1, nx2, ny2);
                GameObject(imgTag, nx2 - imgxposition, ny2 - imgyposition, imgwidth, imgheight, 300, 2, ctx);
                ctx.lineWidth = 5;
                ctx.strokeStyle = '#F00B3E';
                ctx.stroke();
                ctx.closePath();
                fillShape(nx2, ny2, nx0, ny0, nx1, ny1, 0);
            }
        }
    }
    
    function startfirstinterval() {
        intervalID = setInterval(() => {
            downplane(x0, y0, x1, y1, x2, y2);
            if (++countInterval >= checkuplinedownlinecount) {
                window.clearInterval(intervalID);
                countInterval = 0;
                startsecondinterval();
            }
        }, settimeinterval);
    }
    
    function startsecondinterval() {
        intervalID1 = setInterval(() => {
            upplane(x0, y0, x1, y1, x2, y2);
            if (++countInterval >= checkuplinedownlinecount) {
                window.clearInterval(intervalID1);
                countInterval = 0;
                startfirstinterval();
            }
        }, settimeinterval);
    }
    
    function upplane(x0, y0, x1, y1, x2, y2) {
        ctx.beginPath();
        ctx.clearRect(0, 0, ctx.canvas.width, ctx.canvas.height);
        $.when(drawLine()).then(animationHorizontalDots());
        animationVerticalDots();
        var IncreaseY = estimateHeight - (countInterval);
        var DecreaseX = estimateWidth - (countInterval);
        ctx.moveTo(x0, y0);
        ctx.quadraticCurveTo(x1, y1, DecreaseX, IncreaseY);
        GameObject(window.imgTag, DecreaseX - imgxposition, IncreaseY - imgyposition, imgwidth, imgheight, 300, 2, ctx);
        ctx.lineWidth = 5;
        ctx.strokeStyle = '#F00B3E';
        ctx.stroke();
        ctx.closePath();
        ctx.beginPath();
        ctx.moveTo(x0, y0);
        ctx.quadraticCurveTo(x1, y1, DecreaseX, IncreaseY);
        ctx.lineTo(DecreaseX + 3, IncreaseY);
        ctx.lineTo(DecreaseX, y0);
        ctx.fillStyle = "rgba(104,1,14,0.8)";
        ctx.fill();
        ctx.closePath();
    }
    
    function downplane(x0, y0, x1, y1, x2, y2) {
        ctx.beginPath();
        ctx.clearRect(0, 0, ctx.canvas.width, ctx.canvas.height);
        $.when(drawLine()).then(animationHorizontalDots());
        animationVerticalDots();
        var DecreaseY = y2 + (countInterval);
        var IncreaseX = x2 + (countInterval);
        estimateHeight = DecreaseY;
        estimateWidth = IncreaseX;
        ctx.moveTo(x0, y0);
        ctx.quadraticCurveTo(x1, y1, IncreaseX, DecreaseY);
        GameObject(window.imgTag, IncreaseX - imgxposition, DecreaseY - imgyposition, imgwidth, imgheight, 300, 2, ctx);
        ctx.lineWidth = 5;
        ctx.strokeStyle = '#F00B3E';
        ctx.stroke();
        ctx.stroke();
        ctx.closePath();
        ctx.beginPath();
        ctx.moveTo(x0, y0);
        ctx.quadraticCurveTo(x1, y1, IncreaseX, DecreaseY);
        ctx.lineTo(IncreaseX + 3, DecreaseY);
        ctx.lineTo(IncreaseX, y0);
        ctx.fillStyle = "rgba(104,1,14,0.8)";
        ctx.fill();
        ctx.closePath();
    }
    
    function lerp(v0, v1, t) {
        return (1.0 - t) * v0 + t * v1;
    }
    
    function fillShape(nx2, ny2, nx0, ny0, nx1, ny1, t1) {
        if (t1 == 1.0) {
            ctx.beginPath();
            ctx.moveTo(nx0, ny0);
            ctx.quadraticCurveTo(nx1, ny1, nx2, ny2);
            ctx.lineTo(nx2 + 3, ny2);
            ctx.lineTo(nx2 + 3, y0);
            ctx.fillStyle = "rgba(104,1,14,0.8)";
            ctx.fill();
            ctx.closePath();
        } else {
            ctx.beginPath();
            ctx.moveTo(nx0, ny0);
            ctx.quadraticCurveTo(nx1, ny1, nx2, ny2);
            ctx.lineTo(nx2, ny2);
            ctx.lineTo(nx2, y0);
            ctx.fillStyle = "rgba(104,1,14,0.8)";
            ctx.fill();
            ctx.closePath();
        }
    }
    
    // ============================================
    // 2. OFFLINE CLIENT-SIDE GAME LOGIC
    // ============================================

    class CrashGameSimulator {
        constructor() {
            this.gameState = 'waiting';
            this.currentMultiplier = 1.00;
            this.crashPoint = 0;
            this.gameInterval = null;
            this.activeBets = [];
            this.myBets = [];
            this.gameHistory = [];
            this.currency = window.currency_id || "{{ gs('cur_sym') }}"; // Get currency from backend
            this.balance = parseFloat("{{ $user->interest_wallet ?? 0 }}") || 0;
            
            this.waitingTime = 5;
            this.soundEnabled = true;
            this.musicEnabled = true;
            this.maxPlayerBets = 1; 
            this.playerBetSlots = []; 
            this.autoBetEnabled = false;
            this.autoCashOutEnabled = false;
            this.autoCashOutAt = 2.00; 
            this.isCashingOut = false; 

            // Initialize Game Global Object immediately
            if(!window.gameServerDetails) window.gameServerDetails = { crashPoint: 2.0, gameId: null };

            // Initialize Game
            this.init();
        }

        init() {
            this.setupEventListeners();
            this.fetchGameHistory(); // Fetch real history
            this.displayGameHistory();
            this.updateBalance();
            
            // Start the offline loop immediately
            this.startWaitingPhase();
        }

        fetchGameHistory() {
             $.get("{{ route('user.games.aviator.all_bets') }}", (data) => {
                 if(data.rounds) {
                     this.gameHistory = data.rounds;
                     this.displayGameHistory();
                 }
             });
        }
        
        // =================================
        // GAME PHASES
        // =================================

        startWaitingPhase() {
            this.gameState = 'waiting';
            this.currentMultiplier = 1.00;
            this.crashPoint = this.generateRandomCrashPoint(); // JS Decides Crash
            
            // SILENTLY CREATE BACKEND ROUND
            // This ensures we have a valid Round ID for real money bets
            $.post("{{ route('user.games.aviator.create_round') }}", { 
                crash_point: this.crashPoint 
            }, (data) => {
                if(data.game_id && window.gameServerDetails) {
                    window.gameServerDetails.gameId = data.game_id;
                    console.log("Backend Round Created: ID " + data.game_id);
                }
            });

            console.log("Next Round Crash Point: " + this.crashPoint + "x");
            
            this.activeBets = []; // Clear bots
            this.playerBetSlots = [];
            
            // Generate some fake bot bets for atmosphere
            this.generateBotBets();

            // UI Reset
            $('.load-txt').hide(); 
            $('.loading-game').show(); 
            $('#auto_increment_number_div').hide(); // Hide old multiplier
            $('.flew_away_section').hide(); // Hide crashed status
            
            $('#bet_button').show();
            $('#cancle_button').hide();
            $('#cashout_button').hide();
            
            this.displayAllBets();

            // Auto Bet Trigger
            if (this.autoBetEnabled) {
                setTimeout(() => {
                    if (this.playerBetSlots.length === 0) this.placeBet();
                }, 500); 
            }

            // Waiting Countdown
            let timeLeft = 5;
            $('.waiting-text').html(`WAITING FOR NEXT ROUND <span style="color:#ffbc00; font-weight:bold; margin-left:10px;">${timeLeft}s</span>`);
            
            // Animation reset
            $('.fill-line').css('animation', 'none');
            void $('.fill-line').get(0).offsetWidth;
            $('.fill-line').css('animation', `loadLine 5s linear forwards`);

            const countdownInterval = setInterval(() => {
                timeLeft--;
                $('.waiting-text').html(`WAITING FOR NEXT ROUND <span style="color:#ffbc00; font-weight:bold; margin-left:10px;">${timeLeft}s</span>`);
                if (timeLeft <= 0) {
                    clearInterval(countdownInterval);
                    this.startFlyingPhase();
                }
            }, 1000);
        }

        startFlyingPhase() {
            this.gameState = 'flying';
            
            // UI Changes
            $('.load-txt').hide(); 
            $('.loading-game').hide(); 
            $('#auto_increment_number_div').show();
            $('.flew_away_section').hide();
            
            // Toggle Buttons
            const hasActiveBets = this.playerBetSlots.filter(b => !b.cashedOut).length > 0;
            if (hasActiveBets) {
                $('#bet_button').hide(); 
                $('#cancle_button').hide();
                $('#cashout_button').show();
            } else {
                // If no active bets (cashed out or didn't bet), Show NOTHING during flight
                $('#bet_button').hide(); 
                $('#cancle_button').hide();
                $('#cashout_button').hide();
            }

            this.playSound('takeoff');
            if (typeof setVariable === 'function') setVariable('plan');
            
            // Flying Animation Loop (60 FPS)
            const startTime = Date.now();
            this.gameInterval = setInterval(() => {
                const elapsed = (Date.now() - startTime) / 1000;
                
                // Exponential growth formula: 1.00 + (elapsed^2 * 0.1)
                // This starts slow and speeds up just like the real game
                this.currentMultiplier = 1.00 + (elapsed * 0.15) + (elapsed * elapsed * 0.05);

                // Check Crash
                if (this.currentMultiplier >= this.crashPoint) {
                    this.currentMultiplier = this.crashPoint;
                    this.updateMultiplier();
                    this.crash();
                    return;
                }

                this.updateMultiplier();
                this.updateCashoutButton();
                this.updateBotCashouts(); // Make bots cashout randomly

                // Auto Cashout
                if (this.autoCashOutEnabled && this.currentMultiplier >= this.autoCashOutAt) {
                    const active = this.playerBetSlots.filter(b => !b.cashedOut);
                    if (active.length > 0) this.cashOut();
                }
            }, 16);
        }

        crash() {
            clearInterval(this.gameInterval);
            this.gameState = 'crashed';
            
            $('.flew_away_section').show();
            $('#auto_increment_number').html(`${this.crashPoint.toFixed(2)}<span>X</span>`);
            $('#cashout_button').hide(); // Hide immediately on crash
            
            if (typeof stopPlane === 'function') stopPlane();
            this.playSound('crash');

            // END ROUND ON BACKEND (So it shows in history)
            if(window.gameServerDetails && window.gameServerDetails.gameId) {
                 $.post("{{ route('user.games.aviator.end_round') }}", { game_id: window.gameServerDetails.gameId });
            }

            // Handle Losses
            this.playerBetSlots.forEach(bet => {
                if (!bet.cashedOut) {
                    this.myBets.unshift({
                        time: new Date().toLocaleTimeString(),
                        amount: bet.amount,
                        multiplier: 0,
                        cashout: 0
                    });

                    // Sync loss to backend using TicketID
                    if (bet.ticketId) {
                         $.post("{{ route('user.games.aviator.lost') }}", { game_id: bet.ticketId });
                    }
                    
                    // Show notification
                    this.showLossNotification();
                }
            });
            this.displayMyBets();
            this.gameHistory.unshift(this.crashPoint);
            this.gameHistory = this.gameHistory.slice(0, 50);
            this.displayGameHistory();
            this.displayAllBets();
            
            // Wait 3 seconds then restart
            setTimeout(() => this.startWaitingPhase(), 3000);
        }

        // =================================
        // ACTIONS
        // =================================

        placeBet() {
            if (this.gameState !== 'waiting') return;
            const betAmount = parseFloat($('#bet_amount').val());
            if (!betAmount || betAmount < min_bet_amount) { this.showError('Min bet ' + min_bet_amount); return; }
            if (betAmount > max_bet_amount) { this.showError('Max bet ' + max_bet_amount); return; }
            if (betAmount > this.balance) { this.showError('Insufficient balance', "{{ route('user.deposit.index') }}"); return; }
            if (this.playerBetSlots.length >= this.maxPlayerBets) return;

             // Update Local Balance Immediately (Optimistic UI)
            this.balance -= betAmount;
            this.updateBalance();

            const betSlot = this.playerBetSlots.length;
            const tempId = Date.now();
            const bet = {
                id: tempId,
                user: 'You',
                amount: betAmount,
                multiplier: 0,
                cashedOut: false,
                isBot: false,
                slot: betSlot
            };
    
            this.activeBets.unshift(bet);
            this.playerBetSlots.push(bet);
            
            $('#bet_button').hide();
            $('#cancle_button').show();
            this.displayAllBets();
            this.playSound('bet');

            // Call API
            $.ajax({
                url: "{{ route('user.games.aviator.bet') }}",
                method: "POST",
                data: { 
                    bet: betAmount,
                    game_id: window.gameServerDetails ? window.gameServerDetails.gameId : null // Send ROUND ID
                },
                success: (data) => {
                    if(data.error) {
                        this.showError(data.error, data.redirect);
                        // Rollback on error
                        this.balance += betAmount;
                        this.activeBets = this.activeBets.filter(b => b.id !== tempId);
                        this.playerBetSlots = this.playerBetSlots.filter(b => b.id !== tempId);
                        this.updateBalance();
                        this.displayAllBets();
                        return;
                    }
                    // Bet Confirmed
                    console.log("Bet Confirmed. Ticket ID: " + data.game_id);
                    this.balance = parseFloat(data.balance);
                    this.updateBalance();

                    // UPDATE THE BET OBJECT WITH TICKET ID
                    const localBet = this.playerBetSlots.find(b => b.id === tempId);
                    if(localBet) {
                        localBet.ticketId = data.game_id; // Store real DB ID
                    }
                },
                error: (e) => {
                     // Rollback on network error
                     this.balance += betAmount;
                     this.updateBalance();
                     this.showError("Connection Error");
                }
            });
        }

        cancelBet() {
            if (this.gameState !== 'waiting') return;
            const bet = this.playerBetSlots.pop();
            this.activeBets = this.activeBets.filter(b => b.id !== bet.id);
            
            this.balance += bet.amount;
            this.updateBalance();
            
            $('#bet_button').show();
            $('#cancle_button').hide();
            this.displayAllBets();
        }

        cashOut() {
            if (this.gameState !== 'flying' || this.isCashingOut) return;
            const activeBet = this.playerBetSlots.find(b => !b.cashedOut);
            if (!activeBet) return;
            
            // Check if we have the Ticket ID yet (API might be slow)
            if (!activeBet.ticketId) {
                console.log("Wait! Ticket ID not ready yet.");
                return; 
            }

            this.isCashingOut = true;
            const mult = this.currentMultiplier;
            const ticketID = activeBet.ticketId; // Use specific bet ID
            
            // Sync Win to Backend
            $.ajax({
                url: "{{ route('user.games.aviator.cashout') }}",
                method: "POST",
                data: { game_id: ticketID, multiplier: mult },
                success: (data) => {
                    if(data.status === 'won') {
                         activeBet.cashedOut = true;
                         activeBet.multiplier = mult;
                         const winAmount = data.win_amount || (activeBet.amount * mult);
                         
                         this.balance = parseFloat(data.balance); 
                         this.updateBalance();
                         
                         this.myBets.unshift({
                            time: new Date().toLocaleTimeString(),
                            amount: activeBet.amount,
                            multiplier: mult,
                            cashout: winAmount
                        });
                        this.displayMyBets();
                        
                        $('#cashout_button').hide();
                        this.showCashoutSuccess(winAmount, mult, 1);
                        this.playSound('cashout');
                        this.displayAllBets();
                    }
                    this.isCashingOut = false;
                },
                error: (e) => {
                     console.error("Cashout Error", e);
                     this.isCashingOut = false;
                     // Only show error if meaningful
                     if(e.responseJSON?.error) this.showError(e.responseJSON.error);
                }
            });
        }

        // =================================
        // HELPERS
        // =================================

        generateRandomCrashPoint() {
            // Algorithm to simulate realistic crash curve
            // 1% chance of instant crash at 1.00
            if (Math.random() < 0.01) return 1.00;
            
            // Weighted random: simpler floats are more common
            // E = 0.99
            // Multiplier = E / (E - Math.random()) -> Standard Crash Algo
            const E = 0.96; // House Edge
            const r = Math.random();
            let crash = Math.floor(100 * E / (1 - r)) / 100;
            if (crash < 1.00) crash = 1.00;
            if (crash > max_multiplier) crash = max_multiplier; // Cap at defined max multiplier
            return crash;
        }

        generateBotBets() {
            // Create 15-20 fake players
            const names = ['Alex', 'John', 'Sarah', 'Emma', 'David', 'James', 'Robert', 'Lisa', 'Maria', 'Chris', 'Pat', 'Sam', 'Jordan', 'Casey', 'Taylor', 'Morgan', 'Jamie', 'Drew', 'Blake', 'Dakota'];
            const count = 15 + Math.floor(Math.random() * 10);
            for(let i=0; i<count; i++) {
                this.activeBets.push({
                    id: Math.random(),
                    user: names[Math.floor(Math.random() * names.length)] + Math.floor(Math.random()*99),
                    amount: (10 + Math.random() * 500),
                    multiplier: 0,
                    cashedOut: false,
                    isBot: true,
                    targetMult: (1.1 + Math.random() * 5) // When will they cashout?
                });
            }
        }
        
        updateBotCashouts() {
            this.activeBets.forEach(bet => {
                if(bet.isBot && !bet.cashedOut && this.currentMultiplier >= bet.targetMult) {
                    bet.cashedOut = true;
                    bet.multiplier = this.currentMultiplier;
                }
            });
            // Don't redraw every frame, only occasionally or it lags
            if(Math.random() > 0.8) this.displayAllBets();
        }

        updateMultiplier() {
            $('#auto_increment_number').html(`${this.currentMultiplier.toFixed(2)}<span>X</span>`);
        }

        updateCashoutButton() {
            const activeBets = this.playerBetSlots.filter(b => !b.cashedOut);
            if (activeBets.length > 0) {
                const totalWin = activeBets.reduce((sum, bet) => sum + (bet.amount * this.currentMultiplier), 0);
                $('#cash_out_amount').text(`${this.currency}${totalWin.toFixed(2)}`);
            }
        }
        
        updateBalance() {
            $('#wallet_balance').text(this.balance.toFixed(2));
            $('#header_wallet_balance').text(`${this.currency}${this.balance.toFixed(2)}`);
        }

        displayAllBets() {
            const betsHtml = this.activeBets.map(bet => {
                const statusClass = bet.cashedOut ? 'text-success' : '';
                const multiplier = (bet.cashedOut && bet.multiplier > 0) ? `${bet.multiplier.toFixed(2)}x` : '-';
                const amount = parseFloat(bet.amount || 0);
                const cashout = (bet.cashedOut && bet.multiplier > 0) ? `${this.currency}${(amount * bet.multiplier).toFixed(2)}` : '-';
                
                // Mask Username
                const userDisplay = bet.user === 'You' ? 'You' : this.maskUser(bet.user);
                
                return `
                    <div class="list-items ${statusClass}">
                        <div class="column-1 users fw-normal">${userDisplay}</div>
                        <div class="column-2">
                             ${this.currency}${amount.toFixed(2)}
                        </div>
                        <div class="column-3">
                            <div class="bg3 custom-badge mx-auto">${multiplier}</div>
                        </div>
                        <div class="column-4 fw-normal">${cashout}</div>
                    </div>
                `;
            }).join('');
            $('#all_bets').html(betsHtml);
            $('#total_bets').text(this.activeBets.length);
        }
        
        maskUser(name) {
            if(!name || name.length < 3) return name;
            return name.substring(0, 2) + "***" + name.substring(name.length - 1);
        }

        displayMyBets() {
            const myBetsHtml = this.myBets.slice(0, 20).map(bet => {
                const isWin = bet.multiplier > 0;
                const statusClass = isWin ? 'text-success' : 'text-danger';
                return `
                    <div class="list-items ${statusClass}">
                        <div class="column-1 users fw-normal">${bet.time}</div>
                        <div class="column-2">
                            <button class="btn btn-transparent previous-history d-flex align-items-center mx-auto fw-normal">
                                ${this.currency}${bet.amount.toFixed(2)}
                            </button>
                        </div>
                        <div class="column-3">
                            <div class="bg3 custom-badge mx-auto">${bet.multiplier > 0 ? bet.multiplier.toFixed(2) + 'x' : 'Lost'}</div>
                        </div>
                        <div class="column-4 fw-normal">${this.currency}${bet.cashout.toFixed(2)}</div>
                    </div>
                `;
            }).join('');
            $('#my_bet_list').html(myBetsHtml);
        }

        displayGameHistory() {
            // Top Bar History (Last 20)
            const historyHtml = this.gameHistory.slice(0, 20).map(mult => { // Limit to 20
                const val = parseFloat(mult);
                let colorClass = 'bg1'; // Blue default
                if(val >= 10) colorClass = 'bg3'; // Magenta
                else if(val >= 2) colorClass = 'bg2'; // Purple
                
                return `<div class="${colorClass} custom-badge">${val.toFixed(2)}x</div>`;
            }).join('');
            
            $('.payouts-block').html(historyHtml);
            
            // Dropdown List
            $('.round-history-list').html(this.gameHistory.slice(0, 40).map(mult => {
                const val = parseFloat(mult);
                let colorClass = 'bg1';
                if(val >= 10) colorClass = 'bg3';
                else if(val >= 2) colorClass = 'bg2';
                return `<div class="${colorClass} custom-badge">${val.toFixed(2)}x</div>`;
            }).join(''));
        }
         
        showCashoutSuccess(amount, multiplier, betCount) {
             $('#win_mult').text(`${multiplier.toFixed(2)}x`);
             $('#win_amount').text(`${this.currency}${amount.toFixed(2)}`);
             
             $('#toast_win').addClass('show');
             setTimeout(() => { $('#toast_win').removeClass('show'); }, 4000);
        }
        
        showLossNotification() {
             $('#toast_loss').addClass('show');
             setTimeout(() => { $('#toast_loss').removeClass('show'); }, 4000);
        }

        showError(message, redirect = null) {
            $('#error_msg').text(message);
            $('#toast_error').addClass('show');
            setTimeout(() => { 
                $('#toast_error').removeClass('show'); 
                if(redirect) window.location.href = redirect;
            }, 3000);
        }

        playSound(type) {
             if (!this.soundEnabled) return;
             const map = {
                'takeoff': 'fly_plane_audio',
                'crash': 'sound_Audio',
                'cashout': 'cash_out_audio',
                'bet': 'cash_out_audio'
             };
             if(map[type]) {
                 try { document.getElementById(map[type])?.play().catch(e => {}); } catch(e){}
             }
        }
        
        playBackgroundMusic() {
            if (!this.musicEnabled) return;
            try {
                const audio = document.getElementById('background_Audio');
                if (audio) { audio.loop = true; audio.volume = 0.3; audio.play(); }
            } catch (e) {}
        }
        stopBackgroundMusic() {
            try { document.getElementById('background_Audio')?.pause(); } catch(e){}
        }

        setupEventListeners() {
            $('#main_auto_bet').on('change', (e) => { this.autoBetEnabled = e.target.checked; });
            $('#main_checkout').on('change', (e) => { 
                this.autoCashOutEnabled = e.target.checked; 
                if(this.autoCashOutEnabled) {
                    $('#main_incrementor_parent').removeClass('disabled');
                    $('#main_incrementor').prop('disabled', false);
                    this.autoCashOutAt = parseFloat($('#main_incrementor').val()) || 2.00;
                } else {
                    $('#main_incrementor_parent').addClass('disabled');
                    $('#main_incrementor').prop('disabled', true);
                }
            });
            $('#main_incrementor').on('input change', (e) => {
                const value = parseFloat(e.target.value);
                if (value >= 1.01) this.autoCashOutAt = value;
            });
            $('#sound').on('change', (e) => this.soundEnabled = e.target.checked);
            $('#music').on('change', (e) => {
                this.musicEnabled = e.target.checked;
                if (this.musicEnabled) this.playBackgroundMusic();
                else this.stopBackgroundMusic();
            });
            
            // Tab switchers
            $('.navigation-switcher .bet-btn').on('click', () => {
                $('.navigation-switcher .auto-btn').removeClass('active');
                $('.navigation-switcher .bet-btn').addClass('active');
                $('.first-row').removeClass('disabled');
                $('.second-row').removeClass('show'); 
                $('#bet_type').val(0); 
                $('.navigation-switcher .active-line').css('left', '0');
            });
            $('.navigation-switcher .auto-btn').on('click', () => {
                $('.navigation-switcher .bet-btn').removeClass('active');
                $('.navigation-switcher .auto-btn').addClass('active');
                $('.second-row').addClass('show');
                $('#bet_type').val(1);
                $('.navigation-switcher .active-line').css('left', '50%');
            });
            $('.navigation-switcher .bet-btn').addClass('active');
            $('.second-row').removeClass('show');

            // Sidebar Tab Underline Logic
            $('#pills-allbets-tab').on('click', function() {
                $('.tabs-navs .active-line').css('left', '0');
            });
            $('#pills-mybets-tab').on('click', function() {
                $('.tabs-navs .active-line').css('left', '50%');
            });
        }
    }

    // ============================================
    // 3. INIT & GLOBAL WRAPPERS
    // ============================================

    $(document).ready(function () {
        $.ajaxSetup({
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
        });
        console.log('🚀 Initializing Aviator Game JS from Blade...');
        window.crashGame = new CrashGameSimulator();
    });

    // START GLOBAL WRAPPERS (Required for onclick="" usage in HTML)
    window.bet_now = function(element, slot) {
        if(window.crashGame) window.crashGame.placeBet();
    };

    window.cancle_now = function(element, slot) {
         if(window.crashGame) window.crashGame.cancelBet();
    };

    window.cash_out_now = function(element, slot) {
        console.log("Global Click Wrapper: CashOut");
        if(window.crashGame) window.crashGame.cashOut(slot);
        else console.error("window.crashGame not ready");
    };

    window.bet_amount_incremental = function(element) {
        const current = parseFloat($('#bet_amount').val()) || 0;
        $('#bet_amount').val((current + 10).toFixed(2));
    };

    window.bet_amount_decremental = function(element) {
        const current = parseFloat($('#bet_amount').val()) || 0;
        if (current > 10) $('#bet_amount').val((current - 10).toFixed(2));
    };

    window.select_direct_bet_amount = function(element) {
        const amount = $(element).find('.amt').text();
        $('#bet_amount').val(amount + '.00');
    };

    window.main_incrementor_change = function(value) {
        if(window.crashGame) {
             const val = parseFloat(value);
             if (val >= 1.01) window.crashGame.autoCashOutAt = val;
        }
    };
</script>
