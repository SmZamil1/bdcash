@extends($activeTemplate . 'viser.game_master')
@section('content')
    <div id="mainLoader">
        <img src="{{ asset('assets/games/extreme_plinko/assets/loader.png') }}">
        <br><span>0%</span>
    </div>
    <div id="mainHolder">
        <div id="notSupportHolder">
            <div class="notSupport">Your browser isn't supported.<br>Please update your browser in order to run the game.</div>
        </div>
        <div id="canvasHolder">
            <canvas id="gameCanvas" width="1280" height="768"></canvas>
        </div>
    </div>
@endsection

@push('style')
<link rel="icon" type="image/png" href="{{ asset('assets/games/extreme_plinko/icons/favicon-96x96.png') }}" sizes="96x96">
<link rel="apple-touch-icon" sizes="180x180" href="{{ asset('assets/games/extreme_plinko/icons/apple-touch-icon.png') }}">

<link rel="stylesheet" href="{{ asset('assets/games/extreme_plinko/css/normalize.css') }}">
<link rel="stylesheet" href="{{ asset('assets/games/extreme_plinko/css/main.css') }}">
<style>
    body { background-color: #420539; margin: 0; padding: 0; overflow: hidden; height: 100vh; width: 100vw; }
    #canvasHolder {
        display: block !important;
        position: absolute !important;
        width: 100% !important;
        height: 100% !important;
        top: 0 !important;
        left: 0 !important;
        z-index: 1;
    }
    canvas {
        max-width: 100% !important;
        height: auto !important;
        display: block !important;
        margin: 0 auto !important;
    }
    #mainHolder {
        position: absolute !important;
        width: 100% !important;
        height: 100% !important;
        top: 0 !important;
        left: 0 !important;
        z-index: 2;
    }
    #mainLoader {
        background: #420539;
        color: #fff;
        z-index: 999;
        position: fixed;
        width: 100%;
        height: 100%;
        text-align: center;
        font-family: azonixregular, sans-serif;
        top: 0;
        left: 0;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }
    #mainLoader img {
        margin-bottom: 20px;
        max-width: 200px;
    }
    #mainLoader span {
        font-size: 24px;
        margin-top: 10px;
    }
</style>
@endpush

@push('script')
<script src="{{ asset('assets/games/extreme_plinko/js/vendor/mobile-detect.js') }}"></script>
<script src="{{ asset('assets/games/extreme_plinko/js/vendor/createjs.min.js') }}"></script>
<script src="{{ asset('assets/games/extreme_plinko/js/vendor/TweenMax.min.js') }}"></script>
<script src="{{ asset('assets/games/extreme_plinko/js/vendor/p2.min.js') }}"></script>

<script>
    "use strict";

    // Global Config
    const CSRF_TOKEN = "{{ csrf_token() }}";
    const BASE_ASSET_URL = "{{ asset('assets/games/extreme_plinko/') }}/";

    // Membership Stubs
    window.memberSettings = { 'enableMembership': true };
    window.memberData = { 
        'enable': true,
        'points': parseFloat("{{ auth()->user()->interest_wallet }}"),
        'point': parseFloat("{{ auth()->user()->interest_wallet }}"),
        'score': parseFloat("{{ auth()->user()->interest_wallet }}"),
        'chance': 1000 
    };

    // Prevent cross.js/redirect issues by pre-emptively setting trust
    window.details = { trust: true, banner: false };

    // Placeholder functions to avoid ReferenceErrors
    window.addMemberRewardAssets = function() {};
    window.resizeMemberReward = function() {};
    window.buildMemberRewardCanvas = function() {};
    window.addScoreboardAssets = function() {};
    window.buildScoreBoardCanvas = function() {};
    window.resizeScore = function() {};
    window.toggleScoreboardSave = function() {};
    window.matchUserResult = function() {};
    window.saveGame = function(score) { console.log("Progress:", score); };

    // Intercept CreateJS Manifest to fix paths dynamically
    (function() {
        var oldLoadManifest = createjs.LoadQueue.prototype.loadManifest;
        createjs.LoadQueue.prototype.loadManifest = function(manifest, loadNow, basePath) {
            if (Array.isArray(manifest)) {
                manifest.forEach(function(item) {
                    if (item && item.src && !item.src.startsWith('http') && !item.src.startsWith('/')) {
                        item.src = BASE_ASSET_URL + item.src;
                    }
                });
            }
            return oldLoadManifest.call(this, manifest, loadNow, basePath);
        };
    })();

    // Game Interface
    window.getUserResult = function(type, data) {
        if (type === 'drop_start') {
            $.ajax({
                url: "{{ route('user.games.plinko_drop') }}",
                method: "POST",
                data: {
                    _token: CSRF_TOKEN,
                    bet: gameData.totalBet,
                    rows: gameData.totalRows,
                    balls: gameData.totalBalls,
                    risk: gameData.riskLevel
                },
                success: function(resp) {
                    playerData.point = resp.balance;
                    playerData.score = resp.balance;
                    
                    if (typeof gameData !== 'undefined') gameData.fixedResult = resp.results;
                    if (typeof getResult === 'function') getResult(resp.results);
                    if (typeof proceedDropBalls === 'function') proceedDropBalls();
                    if (typeof updateStats === 'function') updateStats();
                },
                error: function(xhr) {
                    let error = xhr.responseJSON ? xhr.responseJSON.error : "Server error";
                    notify('error', error);
                    if (typeof gameData !== 'undefined') gameData.dropCon = false;
                    if (typeof buttonBlankTxt !== 'undefined') buttonBlankTxt.text = textStrings.buttonPlay;
                    if (typeof updateStats === 'function') updateStats();
                    if (typeof displayGameStatusAmount === 'function') displayGameStatusAmount();
                }
            });
        }
    };

    window.updateUserPoint = function() {};
    window.onerror = function(msg, url, line) {
        console.error("Game Error:", msg, "at", url, ":", line);
    };
</script>

<script src="{{ asset('assets/games/extreme_plinko/js/init.js') }}"></script>

<script>
    $(function() {
        // Initialization sync
        if (typeof gameSettings !== 'undefined') {
            gameSettings.enableFixedResult = true; 
            gameSettings.minBet = parseFloat("{{ $setting->min_bet ?? 1 }}");
            gameSettings.maxBet = parseFloat("{{ $setting->max_bet ?? 1000 }}");
            gameSettings.credit = parseFloat("{{ auth()->user()->interest_wallet }}");
            if (typeof gameData !== 'undefined') gameData.physicsEngine = false;
        }

        // Loader updates
        if (typeof loader !== 'undefined') {
            loader.on('progress', function() {
                $('#mainLoader span').text(Math.round(loader.progress * 100) + '%');
            });
            loader.on('complete', function() {
                $('#mainLoader').fadeOut();
            });
            loader.on('error', function(e) {
                console.error("Loader failed:", e);
                notify('error', "Failed to load game assets.");
            });
        }
    });

    window.addEventListener('resize', function() {
        if (typeof resizeCanvas === 'function') resizeCanvas();
    });
</script>
@endpush
