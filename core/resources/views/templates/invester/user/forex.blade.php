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
        background: linear-gradient(135deg, rgba(59, 130, 246, 0.1), rgba(147, 51, 234, 0.1), rgba(236, 72, 153, 0.1));
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.37);
    }
    /* Custom Scrollbar for dropdowns */
    select option {
        background-color: #000;
        color: white;
    }
</style>
<!-- Add Line Awesome for Icons -->
<link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">

        <div class="px-[5px] pb-[8px] mt-4 mb-4">
            <h1 class="text-3xl font-bold text-white font-orbitron">@lang('Forex Trading')</h1>
            <p class="text-blue-200/80 text-sm">@lang('Global Currency Market')</p>
        </div>

        <div class="mb-4 -mx-3">
            <div class="glass-card rounded-none md:rounded-2xl overflow-hidden shadow-2xl shadow-blue-500/10 border-y border-white/5 md:border">
                <div class="h-[300px] md:h-[450px]">
                    <div class="tradingview-widget-container" style="height: 100%; width: 100%;">
                        <div id="tradingview_chart" style="height: 100%; width: 100%;"></div>
                        <script type="text/javascript" src="https://s3.tradingview.com/tv.js"></script>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Trading Controls -->
        <div class="px-2 mb-6">
            <div class="glass-card rounded-2xl p-4 relative overflow-hidden">
                <div class="absolute -right-10 -top-10 w-32 h-32 bg-blue-500/20 rounded-full blur-3xl"></div>

                <div class="flex items-center justify-between mb-4 relative z-10">
                    <h3 class="text-lg font-bold text-white font-orbitron flex items-center gap-2">
                        <i class="las la-globe text-pink-400"></i> @lang('Place Order')
                    </h3>
                    <div class="bg-white/10 px-3 py-1 rounded-full text-xs font-bold text-blue-300">
                        @lang('Balance'): {{ showAmount(auth()->user()->balance) }}
                    </div>
                </div>
                
                <form id="tradeForm" class="space-y-4 relative z-10">
                    <!-- Asset Selection -->
                    <div>
                         <label class="text-xs font-bold text-blue-200 ml-1 mb-1 block uppercase">@lang('Pair')</label>
                         <div class="relative">
                             <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <i class="las la-exchange-alt text-lg text-blue-400"></i>
                             </div>
                             <select name="coin" id="coinSelect" class="w-full bg-black/40 border border-white/10 rounded-xl py-3 pl-10 pr-4 text-white text-sm focus:outline-none focus:border-blue-500 appearance-none font-orbitron">
                                @foreach($pairs as $pair)
                                    <option value="{{ $pair->symbol }}" class="bg-black text-white">{{ __($pair->name) }} ({{ $pair->symbol }})</option>
                                @endforeach
                             </select>
                             <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                 <i class="las la-angle-down text-gray-400"></i>
                             </div>
                         </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <!-- Amount -->
                        <div>
                             <label class="text-xs font-bold text-blue-200 ml-1 mb-1 block uppercase">@lang('Invest')</label>
                             <div class="relative">
                                 <input type="number" step="any" name="amount" class="w-full bg-black/40 border border-white/10 rounded-xl py-3 px-3 text-white text-sm focus:outline-none focus:border-blue-500 font-orbitron placeholder-gray-600" placeholder="0.00" required>
                                 <span class="absolute right-3 top-1/2 -translate-y-1/2 text-xs text-gray-400 font-bold">{{ gs('cur_text') }}</span>
                            </div>
                        </div>
                        <!-- Time -->
                        <div>
                            <label class="text-xs font-bold text-blue-200 ml-1 mb-1 block uppercase">@lang('Duration')</label>
                            <div class="relative">
                                <select name="duration" class="w-full bg-black/40 border border-white/10 rounded-xl py-3 px-3 text-white text-sm focus:outline-none focus:border-blue-500 appearance-none font-orbitron">
                                    @foreach($durations as $time)
                                        <option value="{{ trim($time) }}" class="bg-black">{{ trim($time) }} @lang('Min')</option>
                                    @endforeach
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
                                    <i class="las la-angle-down text-gray-400"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3 mt-2">
                        <button type="button" class="trade-btn relative w-full group overflow-hidden rounded-xl p-[1px] transform active:scale-95 transition-all" data-value="up">
                            <div class="absolute inset-0 bg-gradient-to-br from-green-400 to-green-600"></div>
                            <div class="relative bg-black/20 hover:bg-transparent backdrop-blur-sm rounded-xl h-full flex flex-col items-center justify-center py-3 transition-all">
                                <i class="las la-arrow-up text-2xl text-white drop-shadow-md mb-1 group-hover:-translate-y-1 transition-transform"></i>
                                <span class="font-bold text-white text-sm font-orbitron tracking-wider">@lang('BUY')</span>
                                <span class="text-[9px] text-white/80">@lang('Up')</span>
                            </div>
                        </button>
                        
                        <button type="button" class="trade-btn relative w-full group overflow-hidden rounded-xl p-[1px] transform active:scale-95 transition-all" data-value="down">
                            <div class="absolute inset-0 bg-gradient-to-br from-red-400 to-red-600"></div>
                            <div class="relative bg-black/20 hover:bg-transparent backdrop-blur-sm rounded-xl h-full flex flex-col items-center justify-center py-3 transition-all">
                                <i class="las la-arrow-down text-2xl text-white drop-shadow-md mb-1 group-hover:translate-y-1 transition-transform"></i>
                                <span class="font-bold text-white text-sm font-orbitron tracking-wider">@lang('SELL')</span>
                                <span class="text-[9px] text-white/80">@lang('Down')</span>
                            </div>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Recent Trades -->
        <div class="px-1 mb-20">
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-white text-lg font-bold font-orbitron flex items-center gap-2">
                    <i class="las la-history text-blue-400"></i> @lang('Recent Activity')
                </h3>
                <a href="{{ route('user.all.trade.history') }}" class="text-xs text-blue-300 hover:text-white transition-colors">@lang('View All')</a>
            </div>
            <div id="tradeHistoryBody" class="space-y-3">
                <!-- Loaded via AJAX -->
                <div class="text-center py-10 glass-card rounded-2xl">
                    <i class="las la-spinner la-spin text-3xl mb-2 text-blue-400"></i>
                    <p class="text-sm text-gray-400 font-orbitron">@lang('Loading trades...')</p>
                </div>
            </div>
        </div>


@endsection

@push('script')
<script>
    (function($){
        "use strict";

        // Initialize TradingView
        function loadTradingView(symbol) {
             new TradingView.widget(
                {
                "autosize": true,
                "symbol": "FX_IDC:" + symbol,
                "interval": "1",
                "timezone": "Etc/UTC",
                "theme": "dark", // Dark theme for consistency
                "style": "1",
                "locale": "en",
                "toolbar_bg": "#f1f3f6",
                "enable_publishing": false,
                "hide_top_toolbar": true,
                "save_image": false,
                "container_id": "tradingview_chart"
                }
            );
        }

        // Initial Load
        let initialCoin = $('#coinSelect').val();
         // If no pair selected, default to EURUSD
        if(!initialCoin && $('#coinSelect option').length > 0) {
             $('#coinSelect option:first').prop('selected', true);
             initialCoin = $('#coinSelect').val();
        }
        if(initialCoin) loadTradingView(initialCoin);
        else loadTradingView('EURUSD');

        // On Coin Change
        $('#coinSelect').on('change', function() {
            let symbol = $(this).val();
            loadTradingView(symbol);
        });

        // Place Trade
        $('.trade-btn').on('click', function() {
            let type = $(this).data('value');
            let amount = $('input[name="amount"]').val();
            let duration = $('select[name="duration"]').val();
            let coin = $('#coinSelect').val();
            let btn = $(this);
            
            if(!amount || amount <= 0) {
                notify('error', 'Please enter a valid amount');
                return;
            }

            btn.prop('disabled', true).addClass('opacity-50');

            $.post('{{ route('user.trading.place') }}', {
                _token: '{{ csrf_token() }}',
                type: type,
                amount: amount,
                duration: duration,
                coin: coin
            }, function(response) {
                btn.prop('disabled', false).removeClass('opacity-50');
                if(response.error){
                    notify('error', response.error);
                }else{
                    notify('success', response.success);
                    $('input[name="amount"]').val('');
                    loadTradeHistory(); // Refresh immediately
                }
            }).fail(function() {
                btn.prop('disabled', false).removeClass('opacity-50');
                notify('error', 'Something went wrong');
            });
        });

        let notifiedTrades = [];
        let isFirstLoad = true;
        let timeOffset = 0;

        // Load History
        function loadTradeHistory() {
             // Added category=Forex to filter correct history
            $.get('{{ route('user.trading.history') }}?category=Forex', function(response) {
                if(response.server_time) {
                     let clientTime = new Date().getTime();
                     timeOffset = response.server_time - clientTime;
                }
                if(response.html) {
                    $('#tradeHistoryBody').html(response.html);
                    initCountdowns();
                } else if (!response.trades || response.trades.length === 0) {
                     $('#tradeHistoryBody').html('<div class="text-center py-8 bg-blue-900/10 rounded-2xl border border-white/5 border-dashed"><div class="w-12 h-12 rounded-full bg-white/5 flex items-center justify-center mx-auto mb-3"><i class="las la-history text-2xl text-gray-500"></i></div><p class="text-gray-400 text-xs">No recent trades found</p></div>');
                }

                if(response.trades) {
                    response.trades.forEach(function(trade) {
                        if(trade.status == 1 || trade.status == 2) {
                            if(!notifiedTrades.includes(trade.id)) {
                                if(!isFirstLoad) {
                                    if(trade.status == 1) {
                                        notify('success', 'Trade Won!');
                                    } else {
                                        notify('error', 'Trade Lost.');
                                    }
                                }
                                notifiedTrades.push(trade.id);
                            }
                        }
                    });
                }
                isFirstLoad = false;
            });
        }

        // Check Results (polling)
        function checkResults() {
            $.get('{{ route('user.trading.result') }}', function(response) {
                if(response.processed > 0) {
                    loadTradeHistory(); // Reload if any trade finished
                }
            });
        }

        // Countdown Timer Logic
        function initCountdowns() {
            $('.countdown').each(function() {
                let $this = $(this);
                let finishTime = parseInt($this.data('finish'));
                
                if($this.data('interval')) clearInterval($this.data('interval'));

                let interval = setInterval(function() {
                    let now = new Date().getTime() + timeOffset;
                    let distance = finishTime - now;

                    if (distance < 0) {
                        clearInterval(interval);
                        $this.text("Calculating...");
                        setTimeout(checkResults, 1000); 
                        return;
                    }

                    let minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                    let seconds = Math.floor((distance % (1000 * 60)) / 1000);

                    $this.text(minutes + "m " + seconds + "s ");
                }, 1000);
                
                $this.data('interval', interval);
            });
        }

        // Initial Load
        loadTradeHistory();
        setInterval(checkResults, 5000);

    })(jQuery);
</script>
@endpush
