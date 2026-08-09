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
    
    /* Table Enhancements */
    .glass-table thead tr th {
        background-color: rgba(30, 58, 138, 0.4);
        color: #bfdbfe;
        border-bottom: 1px solid rgba(96, 165, 250, 0.2);
    }
    .glass-table tbody tr {
        border-bottom: 1px solid rgba(96, 165, 250, 0.1);
        transition: background-color 0.2s;
    }
    .glass-table tbody tr:last-child {
        border-bottom: none;
    }
    .glass-table tbody tr:hover {
        background-color: rgba(30, 58, 138, 0.2);
    }
    .glass-table td {
        color: #e0f2fe;
    }
</style>

    <!-- TradingView Widget BEGIN -->
    <div class="mb-5 -mx-3 md:mx-0 rounded-none md:rounded-2xl overflow-hidden border-y md:border border-blue-400/30 shadow-lg shadow-blue-500/10">
        <div class="h-[300px] md:h-[400px] w-full bg-blue-900/20 backdrop-blur-sm">
            <div class="tradingview-widget-container" style="height: 100%; width: 100%;">
                <div id="tradingview_chart" style="height: 100%; width: 100%;"></div>
                <script type="text/javascript" src="https://s3.tradingview.com/tv.js"></script>
            </div>
        </div>
    </div>
    <!-- TradingView Widget END -->

    <div class="mb-5 px-1">
        <div class="bg-gradient-to-br from-blue-400/20 via-blue-600/20 to-blue-800/20 border border-blue-400/30 shadow-xl rounded-2xl backdrop-blur-lg p-5 relative overflow-hidden">
            
            <!-- Decorative elements -->
            <div class="absolute top-0 right-0 w-32 h-32 bg-blue-500/10 rounded-full blur-3xl -mr-10 -mt-10 pointer-events-none"></div>
            <div class="absolute bottom-0 left-0 w-32 h-32 bg-blue-500/10 rounded-full blur-3xl -ml-10 -mb-10 pointer-events-none"></div>

            <h3 class="text-white text-xl font-bold font-orbitron text-center mb-6 drop-shadow-md">@lang('Stock Trading')</h3>
            
            <form id="tradeForm" class="relative z-10">
                @csrf
                <div class="mb-5">
                     <label class="font-semibold text-blue-200 mb-2 block text-sm ml-1">@lang('Select Stock')</label>
                     <div class="relative group">
                        <select name="stock_id" id="stockSelect" class="w-full bg-blue-900/40 border border-blue-500/30 text-white text-sm rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 block p-3 outline-none transition-all shadow-inner">
                            @foreach($stocks as $stock)
                                <option value="{{ $stock->id }}" data-symbol="{{ $stock->symbol }}" class="text-gray-900 bg-white">{{ __($stock->name) }} ({{ $stock->symbol }})</option>
                            @endforeach
                        </select>
                     </div>
                </div>

                <div class="grid grid-cols-2 gap-4 mb-5">
                    <div>
                         <label class="font-semibold text-blue-200 mb-2 block text-sm ml-1">@lang('Amount')</label>
                         <div class="flex">
                             <input type="number" step="any" name="amount" class="rounded-none rounded-l-xl bg-blue-900/40 border border-blue-500/30 text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm p-3 outline-none shadow-inner placeholder-blue-300/30" placeholder="0.00" required>
                             <span class="inline-flex items-center px-4 text-sm text-blue-100 bg-blue-800/40 border border-l-0 border-blue-500/30 rounded-r-xl font-bold">
                                {{ gs('cur_text') }}
                            </span>
                        </div>
                    </div>
                    <div>
                        <label class="font-semibold text-blue-200 mb-2 block text-sm ml-1">@lang('Time')</label>
                        <select name="duration" class="w-full bg-blue-900/40 border border-blue-500/30 text-white text-sm rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 block p-3 outline-none shadow-inner">
                            @foreach($durations as $time)
                                <option value="{{ trim($time) }}" class="text-gray-900 bg-white">{{ trim($time) }} @lang('Minute'){{ trim($time) > 1 ? 's' : '' }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <button type="button" class="trade-btn w-full bg-gradient-to-r from-green-600 to-green-500 hover:from-green-500 hover:to-green-400 shadow-lg shadow-green-500/20 text-white font-bold font-orbitron rounded-xl text-sm px-5 py-3.5 text-center transform active:scale-95 transition-all duration-200 border border-green-400/20" data-value="up">
                        <i class="las la-arrow-up text-xl mr-1"></i> @lang('CALL (UP)')
                    </button>
                    <button type="button" class="trade-btn w-full bg-gradient-to-r from-red-600 to-red-500 hover:from-red-500 hover:to-red-400 shadow-lg shadow-red-500/20 text-white font-bold font-orbitron rounded-xl text-sm px-5 py-3.5 text-center transform active:scale-95 transition-all duration-200 border border-red-400/20" data-value="down">
                        <i class="las la-arrow-down text-xl mr-1"></i> @lang('PUT (DOWN)')
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Active Trades -->
    <div class="mb-20 px-1">
        <div class="flex items-center justify-between mb-3">
            <h3 class="text-white text-lg font-bold font-orbitron flex items-center gap-2">
                <i class="las la-history text-blue-400"></i> @lang('Recent Activity')
            </h3>
            <a href="{{ route('user.all.trade.history') }}" class="text-xs text-blue-300 hover:text-white font-orbitron transition-colors">@lang('View All')</a>
        </div>
        <div id="tradeHistoryBody" class="space-y-3">
            <!-- Loaded via AJAX -->
            <div class="text-center py-10 glass-card rounded-2xl border border-blue-500/20 shadow-lg">
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
             let tvSymbol = symbol;
             // Naive mapping - if it doesn't have a prefix, assume NASDAQ
             if(!tvSymbol.includes(':')) {
                 tvSymbol = "NASDAQ:" + tvSymbol;
             }

             new TradingView.widget(
                {
                "autosize": true,
                "symbol": tvSymbol,
                "interval": "1",
                "timezone": "Etc/UTC",
                "theme": "dark",
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
        let initialSymbol = $('#stockSelect option:selected').data('symbol');
        if(initialSymbol) loadTradingView(initialSymbol);

        // On Stock Change
        $('#stockSelect').on('change', function() {
            let symbol = $(this).find(':selected').data('symbol');
            loadTradingView(symbol);
        });

        // Place Trade
        $('.trade-btn').on('click', function() {
            let type = $(this).data('value');
            let amount = $('input[name="amount"]').val();
            let duration = $('select[name="duration"]').val();
            let stock_id = $('#stockSelect').val();
            let btn = $(this);
            
            if(!amount || amount <= 0) {
                notify('error', 'Please enter a valid amount');
                return;
            }

            btn.prop('disabled', true).addClass('opacity-50');

            $.post('{{ route('user.stock.trading.place') }}', {
                _token: '{{ csrf_token() }}',
                type: type,
                amount: amount,
                duration: duration,
                stock_id: stock_id
            }, function(response) {
                btn.prop('disabled', false).removeClass('opacity-50');
                if(response.error){
                    notify('error', response.error);
                    if(response.redirect) {
                        setTimeout(() => {
                            window.location.href = response.redirect;
                        }, 2000);
                    }
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
            $.get('{{ route('user.trading.history') }}?category=Stocks', function(response) {
                if(response.server_time) {
                     let clientTime = new Date().getTime();
                     timeOffset = response.server_time - clientTime;
                }
                if(response.html) {
                    $('#tradeHistoryBody').html(response.html);
                    initCountdowns();
                }
                if(response.trades) {
                    response.trades.forEach(function(trade) {
                        if(trade.status == 1 || trade.status == 2) {
                            if(!notifiedTrades.includes(trade.id)) {
                                if(!isFirstLoad) {
                                    if(trade.status == 1) {
                                        notify('success', 'Trade Won! Profit: ' + parseFloat(trade.profit).toFixed(2));
                                    } else {
                                        notify('error', 'Trade Lost. Amount: ' + parseFloat(trade.amount).toFixed(2));
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
                    loadTradeHistory();
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
                        $this.text("Processing...");
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

        loadTradeHistory();
        setInterval(function() {
            checkResults(); 
        }, 5000);

    })(jQuery);
</script>
@endpush
