@extends('admin.layouts.app')

@section('panel')
    <style>
        .widget-two__btn {
            z-index: 1;
        }
    </style>


@php
    \Wise\Service\WiseService::getProductInfo();
    \Wise\Service\WiseService::getBroadcastMessages();
    $general = gs();
    $systemInfo = json_decode($general->system_info);
@endphp

@if ($systemInfo && version_compare(@$systemInfo->version, systemDetails()['version'], '>'))
    <div class="row">
        <div class="col-md-12">
            <div class="card text-white bg-warning mb-3">
                <div class="card-header">
                    <h3 class="card-title"> @lang('New Version Available') <button
                            class="btn btn--dark float-end">@lang('Version')
                            {{ @$systemInfo->version }}</button> </h3>
                </div>
                <div class="card-body">
                    <h5 class="card-title text-dark">@lang('What is the Update ?')</h5>
                    <p>
                        <pre class="f-size--24">{{ @$systemInfo->details }}</pre>
                    </p>
                </div>
            </div>
        </div>
    </div>
@endif




@php
    $universalMessages = json_decode($general->broadcast_messages);
@endphp

@if ($universalMessages)
    <div class="row">
        @foreach ($universalMessages as $msg)
            <div class="col-md-12">
                <div class="alert border border--primary" role="alert">
                    <div class="alert__icon bg--primary"><i class="far fa-bell"></i></div>
                    <p class="alert__message">@php echo $msg; @endphp</p>
                    <button type="button" class="close" data-bs-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
            </div>
        @endforeach
    </div>
@endif



    {{-- Cron URL --}}
    <div class="mb-4">
        <code class="mb-2 d-inline-block text-dark">
            {{ __('Please Set Cron Url To Your Server') }}
        </code>
        <div class="input-group">
            <input type="text" class="form-control copy-text" value="curl -s {{ route('cron') }} ">
            <button class="input-group-text btn--primary text-white copy" type="button" id="button-addon2">
                {{ __('Copy Cron Url') }}
            </button>
        </div>
    </div>

    {{-- Pending Deposits / Withdrawals --}}
  <div class="card bg--white  mb-3">
            <div class="card-boldy p-2 d-flex justify-content-end gap-2 flex-column flex-sm-row">
                @if ($deposit['total_deposit_pending'] > 0)
                    <a class="btn--primary py-1 px-3 rounded-pill text-white text-nowrap text-center h-auto w-auto"
                        href="{{ route('admin.deposit.pending') }}">
                        <i class="fa-solid fa-arrows-spin fa-spin"></i>
                        Pending Deposits ({{ $deposit['total_deposit_pending'] }})
                    </a>
                @endif
                @if ($withdrawals['total_withdraw_pending'] > 0)
                    <a class="btn--success py-1 px-3 rounded-pill text-white text-nowrap text-center h-auto w-auto"
                        href="{{ route('admin.withdraw.data.pending') }}">
                        <i class="fa-solid fa-arrows-spin fa-spin"></i>
                        Pending Withdrawals ({{ $withdrawals['total_withdraw_pending'] }})
                    </a>
                @endif
            </div>
        </div>

    {{-- User Widgets --}}
    <div class="row gy-4">
        <div class="col-xxl-6 col-sm-6">
            <x-widget link="{{ route('admin.users.all') }}" 
                image="https://cdn-icons-png.flaticon.com/128/33/33887.png" title="Total Users"
                value="{{ $widget['total_users'] }}" bg="warning" />
        </div>
        <div class="col-xxl-6 col-sm-6">
            <x-widget link="{{ route('admin.users.active') }}"
                image="https://cdn-icons-png.flaticon.com/128/33/33308.png" title="Plan Active Users"
                value="{{ $widget['verified_users'] }}" bg="success" />
        </div>
    </div>

    {{-- Today Overview --}}
    <div class="row gy-4 mt-3">
        <div class="col-12">
            <h5 class="mb-2 text-dark">Today Overview</h5>
        </div>

        <div class="col-xxl-4 col-sm-6">
            <x-widget style="2" link="{{ route('admin.users.all') }}"
                image="https://cdn-icons-png.flaticon.com/128/681/681494.png"
                title="Users Joined Today" value="{{ $todayData['users_joined'] }}" color="primary" />
        </div>

        <div class="col-xxl-4 col-sm-6">
            <x-widget style="2" link="{{ route('admin.deposit.list') }}"
                image="https://cdn-icons-png.flaticon.com/128/12147/12147151.png"
                title="Today Deposits" value="{{ showAmount($todayData['deposit_amount']) }}" color="success" />
        </div>

        <div class="col-xxl-4 col-sm-6">
            <x-widget style="2" link="{{ route('admin.withdraw.data.all') }}"
                image="https://cdn-icons-png.flaticon.com/128/7939/7939990.png"
                title="Today Withdrawals" value="{{ showAmount($todayData['withdraw_amount']) }}" color="danger" />
        </div>

        <div class="col-xxl-4 col-sm-6">
            <x-widget style="2" link="{{ route('admin.report.transaction') }}"
                image="https://cdn-icons-png.flaticon.com/128/9720/9720918.png"
                title="Today Plan Activations" value="{{ $todayData['plan_activations'] }}" color="info" />
        </div>
        <div class="col-xxl-4 col-sm-6">
             <x-widget style="2" link="{{ route('admin.report.transaction') }}"
                image="https://cdn-icons-png.flaticon.com/128/4305/4305518.png"
                title="Today Commission" value="{{ showAmount($todayData['commission']) }}" color="primary" />
        </div>
    </div>

    {{-- Investment Plans Section --}}
    @if(@gs('earning_methods')['investment'] ?? 1)
    <div class="row gy-4 mt-3">
        <div class="col-12">
            <h5 class="mb-2 text-dark">Investment Plans Statistics</h5>
        </div>
        <div class="col-xxl-3 col-sm-6">
            <x-widget style="2" link="{{ route('admin.plan.index') }}"
                image="https://cdn-icons-png.flaticon.com/128/2910/2910243.png"
                title="Total Invested" value="{{ showAmount($invest['invests']) }}" color="primary" />
        </div>
        <div class="col-xxl-3 col-sm-6">
            <x-widget style="2" link="{{ route('admin.report.transaction') }}"
                image="https://cdn-icons-png.flaticon.com/128/11135/11135127.png"
                title="Active Investments" value="{{ showAmount($invest['active_invests']) }}" color="success" />
        </div>
        <div class="col-xxl-3 col-sm-6">
            <x-widget style="2" link="{{ route('admin.report.transaction') }}"
                image="https://cdn-icons-png.flaticon.com/128/1017/1017498.png"
                title="Interests Paid" value="{{ showAmount($invest['interests']) }}" color="warning" />
        </div>
        <div class="col-xxl-3 col-sm-6">
            <x-widget style="2" link="{{ route('admin.plan.index') }}"
                image="https://cdn-icons-png.flaticon.com/128/33/33308.png"
                title="Today Activations" value="{{ $todayData['plan_activations'] }}" color="info" />
        </div>
    </div>
    @endif

    {{-- AI Trading Bots Section --}}
    @if(@gs('earning_methods')['ai_bots'] ?? 1)
    <div class="row gy-4 mt-3">
        <div class="col-12">
            <h5 class="mb-2 text-dark">AI Trading Bots Statistics</h5>
        </div>
        <div class="col-xxl-4 col-sm-6">
            <x-widget style="2" link="{{ route('admin.aibots.index') }}"
                image="https://cdn-icons-png.flaticon.com/128/12637/12637629.png"
                title="Active Bots" value="{{ $widget['active_bots_count'] }}" color="primary" />
        </div>
        <div class="col-xxl-4 col-sm-6">
            <x-widget style="2" link="{{ route('admin.aibots.index') }}"
                image="https://cdn-icons-png.flaticon.com/128/2910/2910243.png"
                title="Total Bot Invest" value="{{ showAmount($widget['total_bots_invest']) }}" color="success" />
        </div>
        <div class="col-xxl-4 col-sm-6">
            <x-widget style="2" link="{{ route('admin.report.transaction') }}"
                image="https://cdn-icons-png.flaticon.com/128/1017/1017498.png"
                title="Total Bot Returns" value="{{ showAmount($widget['total_bots_return'] ?? 0) }}" color="info" />
        </div>
    </div>
    @endif

    {{-- Cloud Mining Section --}}
    @if(@gs('earning_methods')['mining'] ?? 1)
    <div class="row gy-4 mt-3">
        <div class="col-12">
            <h5 class="mb-2 text-dark">Cloud Mining Statistics</h5>
        </div>
        <div class="col-xxl-4 col-sm-6">
            <x-widget style="2" link="{{ route('admin.mining.index') }}"
                image="https://cdn-icons-png.flaticon.com/512/2586/2586032.png"
                title="Active Rigs" value="{{ $widget['active_miners_count'] }}" color="danger" />
        </div>
        <div class="col-xxl-4 col-sm-6">
            <x-widget style="2" link="{{ route('admin.mining.index') }}"
                image="https://cdn-icons-png.flaticon.com/128/2910/2910243.png"
                title="Total Mining Invest" value="{{ showAmount($widget['total_mining_invest']) }}" color="warning" />
        </div>
        <div class="col-xxl-4 col-sm-6">
            <x-widget style="2" link="{{ route('admin.report.transaction') }}"
                image="https://cdn-icons-png.flaticon.com/128/1017/1017498.png"
                title="Total Mining Returns" value="{{ showAmount($widget['total_mining_return'] ?? 0) }}" color="success" />
        </div>
    </div>
    @endif

    {{-- Options Trading Section --}}
    @if(@gs('earning_methods')['trading'] ?? 1)
    <div class="row gy-4 mt-3">
        <div class="col-12">
            <h5 class="mb-2 text-dark">Options Trading Statistics</h5>
        </div>
        <div class="col-xxl-3 col-sm-6">
            <x-widget style="2" link="{{ route('admin.trading.setting.index') }}"
                image="https://cdn-icons-png.flaticon.com/512/10799/10799666.png"
                title="Total Trades" value="{{ $widget['total_trades_count'] }}" color="info" />
        </div>
        <div class="col-xxl-3 col-sm-6">
            <x-widget style="2" link="{{ route('admin.trading.setting.index') }}"
                image="   https://cdn-icons-png.flaticon.com/512/1642/1642120.png"
                title="Total Wins" value="{{ $widget['trading_wins'] }}" color="success" />
        </div>
        <div class="col-xxl-3 col-sm-6">
            <x-widget style="2" link="{{ route('admin.trading.setting.index') }}"
                image="https://cdn-icons-png.flaticon.com/512/9267/9267564.png"
                title="Total Losses" value="{{ $widget['trading_losses'] }}" color="danger" />
        </div>
        <div class="col-xxl-3 col-sm-6">
            <x-widget style="2" link="{{ route('admin.trading.setting.index') }}"
                image="https://cdn-icons-png.flaticon.com/128/2910/2910243.png"
                title="Total Wagered" value="{{ showAmount($widget['total_trade_invest'] ?? 0) }}" color="primary" />
        </div>
    </div>
    @endif

    {{-- Forex Trading Section --}}
    @if(gs('forex_module'))
    <div class="row gy-4 mt-3">
        <div class="col-12">
            <h5 class="mb-2 text-dark">Forex Trading Statistics</h5>
        </div>
        <div class="col-xxl-3 col-sm-6">
            <x-widget style="2" link="{{ route('admin.forex.index') }}"
                image="https://cdn-icons-png.flaticon.com/512/2285/2285516.png"
                title="Total Forex Trades" value="{{ $widget['total_forex_trades'] }}" color="primary" />
        </div>
        <div class="col-xxl-3 col-sm-6">
             <x-widget style="2" link="{{ route('admin.forex.index') }}"
                image="https://cdn-icons-png.flaticon.com/512/1642/1642120.png"
                title="Forex Wins" value="{{ $widget['forex_wins'] }}" color="success" />
        </div>
        <div class="col-xxl-3 col-sm-6">
            <x-widget style="2" link="{{ route('admin.forex.index') }}"
                image="https://cdn-icons-png.flaticon.com/512/9267/9267564.png"
                title="Forex Losses" value="{{ $widget['forex_losses'] }}" color="danger" />
        </div>
        <div class="col-xxl-3 col-sm-6">
             <x-widget style="2" link="{{ route('admin.report.transaction') }}"
                image="https://cdn-icons-png.flaticon.com/128/2910/2910243.png"
                title="Total Forex Invest" value="{{ showAmount($widget['total_forex_invest']) }}" color="info" />
        </div>
    </div>
    @endif

    {{-- Stock Trading Section --}}
    @if(gs('stock_module'))
    <div class="row gy-4 mt-3">
        <div class="col-12">
            <h5 class="mb-2 text-dark">Stock Trading Statistics</h5>
        </div>
        <div class="col-xxl-3 col-sm-6">
            <x-widget style="2" link="{{ route('admin.stock.index') }}"
                image="https://cdn-icons-png.flaticon.com/512/2435/2435227.png"
                title="Total Stock Trades" value="{{ $widget['total_stock_trades'] }}" color="primary" />
        </div>
        <div class="col-xxl-3 col-sm-6">
            <x-widget style="2" link="{{ route('admin.stock.index') }}"
                 image="https://cdn-icons-png.flaticon.com/512/1642/1642120.png"
                title="Stock Wins" value="{{ $widget['stock_wins'] }}" color="success" />
        </div>
        <div class="col-xxl-3 col-sm-6">
            <x-widget style="2" link="{{ route('admin.stock.index') }}"
                 image="https://cdn-icons-png.flaticon.com/512/9267/9267564.png"
                title="Stock Losses" value="{{ $widget['stock_losses'] }}" color="danger" />
        </div>
        <div class="col-xxl-3 col-sm-6">
             <x-widget style="2" link="{{ route('admin.report.transaction') }}"
                image="https://cdn-icons-png.flaticon.com/128/2910/2910243.png"
                title="Total Stock Invest" value="{{ showAmount($widget['total_stock_invest']) }}" color="info" />
        </div>
    </div>
    @endif

    {{-- Games & Aviator Section --}}
    @if(@gs('earning_methods')['game'] ?? 1)
    <div class="row gy-4 mt-3">
        <div class="col-12">
            <h5 class="mb-2 text-dark">Games & Aviator Statistics</h5>
        </div>
        <div class="col-xxl-3 col-sm-6">
            <x-widget style="2" link="{{ route('admin.games.index') }}"
                image="https://cdn-icons-png.flaticon.com/128/13/13973.png" 
                title="Total Game Plays" value="{{ $widget['total_games_played'] }}" color="success" />
        </div>
        <div class="col-xxl-3 col-sm-6">
            <x-widget style="2" link="{{ route('admin.games.index') }}"
                image="https://cdn-icons-png.flaticon.com/512/8941/8941098.png "
                title="Total Game Wins" value="{{ $widget['total_game_wins'] }}" color="primary" />
        </div>
        <div class="col-xxl-3 col-sm-6">
            <x-widget style="2" link="{{ route('admin.games.index') }}"
                image="https://cdn-icons-png.flaticon.com/128/1017/1017498.png"
                title="Aviator Payouts" value="{{ showAmount($widget['aviator_total_payout'] ?? 0) }}" color="warning" />
        </div>
        <div class="col-xxl-3 col-sm-6">
            <x-widget style="2" link="{{ route('admin.games.index') }}"
                image="https://cdn-icons-png.flaticon.com/128/2460/2460481.png"
                title="Aviator Net Profit" value="{{ showAmount($widget['aviator_total_profit'] ?? 0) }}" color="danger" />
        </div>
    </div>
    @endif

    {{-- PTC Ads Section --}}
    @if(@gs('earning_methods')['ptc'] ?? 1)
    <div class="row gy-4 mt-3">
        <div class="col-12">
            <h5 class="mb-2 text-dark">PTC Ads Statistics</h5>
        </div>
        <div class="col-xxl-4 col-sm-6">
            <x-widget style="2" link="{{ route('admin.ptc.index') }}"
                image="https://cdn-icons-png.flaticon.com/512/7398/7398957.png"
                title="Total Ads" value="{{ $widget['total_ptc_ads'] ?? 0 }}" color="primary" />
        </div>
        <div class="col-xxl-4 col-sm-6">
            <x-widget style="2" link="{{ route('admin.ptc.index') }}"
                image="https://cdn-icons-png.flaticon.com/128/2645/2645897.png"
                title="Total Clicks/Views" value="{{ $widget['total_ptc_views'] ?? 0 }}" color="info" />
        </div>
        <div class="col-xxl-4 col-sm-6">
            <x-widget style="2" link="{{ route('admin.report.transaction') }}"
                image="https://cdn-icons-png.flaticon.com/128/1017/1017498.png"
                title="Total PTC Earned" value="{{ showAmount($widget['total_ptc_earned'] ?? 0) }}" color="success" />
        </div>
    </div>
    @endif

    {{-- VIP & Staking Section --}}
    <div class="row gy-4 mt-3">
        <div class="col-12">
            <h5 class="mb-2 text-dark">VIP & Staking Statistics</h5>
        </div>
        <div class="col-xxl-3 col-sm-6">
            <x-widget style="2" link="{{ route('admin.vip.index') }}"
                image="https://cdn-icons-png.flaticon.com/128/9720/9720918.png"
                title="Active VIP Users" value="{{ $widget['total_vip_active'] ?? 0 }}" color="warning" />
        </div>
        <div class="col-xxl-3 col-sm-6">
            <x-widget style="2" link="{{ route('admin.vip.index') }}"
                image="https://cdn-icons-png.flaticon.com/128/2460/2460481.png"
                title="VIP Revenue" value="{{ showAmount($widget['total_vip_revenue'] ?? 0) }}" color="primary" />
        </div>
        <div class="col-xxl-3 col-sm-6">
            <x-widget style="2" link="{{ route('admin.report.transaction') }}"
                image="https://cdn-icons-png.flaticon.com/128/2910/2910243.png"
                title="Total Staked" value="{{ showAmount($widget['total_staking_invest'] ?? 0) }}" color="info" />
        </div>
        <div class="col-xxl-3 col-sm-6">
            <x-widget style="2" link="{{ route('admin.report.transaction') }}"
                image="https://cdn-icons-png.flaticon.com/128/1017/1017498.png"
                title="Staking Returns" value="{{ showAmount($widget['total_staking_return'] ?? 0) }}" color="success" />
        </div>
    </div>


    {{-- Commission & Profit Overview --}}
    <div class="row gy-4 mt-3">
        <div class="col-12">
            <h5 class="mb-2 text-dark">Commission & Profit Overview</h5>
        </div>
        <div class="col-xxl-4 col-sm-6">
            <x-widget style="2" link="{{ route('admin.report.transaction') }}"
                image="https://cdn-icons-png.flaticon.com/128/637/637126.png"
                title="Total Commission Given" value="{{ showAmount($widget['total_commission']) }}" color="success" />
        </div>
         <div class="col-xxl-4 col-sm-6">
            <x-widget style="2" link="{{ route('admin.report.transaction') }}"
                image="https://cdn-icons-png.flaticon.com/128/6020/6020362.png"
                title="Total User Invested" value="{{ showAmount($invest['invests']) }}" color="info" />
        </div>
        <div class="col-xxl-4 col-sm-6">
            <x-widget style="2" link="{{ route('admin.report.transaction') }}"
                image="https://cdn-icons-png.flaticon.com/128/1017/1017498.png"
                title="Total User Profit (Interest)" value="{{ showAmount($invest['interests']) }}" color="warning" />
        </div>
    </div>

    {{-- Admin Profit Section --}}
    <div class="row gy-4 mt-3">
        <div class="col-12">
            <h5 class="mb-2 text-dark">Admin Profit & Financial Overview</h5>
        </div>
        <div class="col-xxl-6 col-sm-6">
            <x-widget style="2" link="{{ route('admin.deposit.list') }}"
                image="https://cdn-icons-png.flaticon.com/128/2460/2460481.png"
                title="Platform Net Profit (Charges)" value="{{ showAmount($widget['total_profit']) }}" color="primary" />
        </div>
        <div class="col-xxl-6 col-sm-6">
            <x-widget style="2" link="{{ route('admin.withdraw.data.all') }}"
                image="https://cdn-icons-png.flaticon.com/128/2910/2910243.png"
                title="Net Liquidity (Dep - With)" value="{{ showAmount($widget['net_liquidity']) }}" color="{{ $widget['net_liquidity'] >= 0 ? 'success' : 'danger' }}" />
        </div>
    </div>

    {{-- Deposit Overview --}}
    <div class="row gy-4 mt-3">
        <div class="col-12">
            <h5 class="mb-2 text-dark">Deposit Overview</h5>
        </div>

        <div class="col-xxl-6 col-sm-6">
            <x-widget style="2" link="{{ route('admin.deposit.list') }}"
                image="https://cdn-icons-png.flaticon.com/128/12023/12023518.png" icon_style="false"
                title="Total Deposited" value="{{ showAmount($deposit['total_deposit_amount']) }}"
                color="success" />
        </div>
        <div class="col-xxl-6 col-sm-6" id="pending-deposits-overview">
            <x-widget style="2" link="{{ route('admin.deposit.pending') }}"
                image="https://cdn-icons-png.flaticon.com/128/12682/12682974.png" icon_style="false"
                title="Pending Deposits" value="{{ $deposit['total_deposit_pending'] }}" color="warning" />
        </div>
        <div class="col-xxl-6 col-sm-6">
            <x-widget style="2" link="{{ route('admin.deposit.rejected') }}"
                image="https://cdn-icons-png.flaticon.com/128/10953/10953092.png" icon_style="false"
                title="Rejected Deposits" value="{{ $deposit['total_deposit_rejected'] }}" color="warning" />
        </div>
        <div class="col-xxl-6 col-sm-6">
            <x-widget style="2" link="{{ route('admin.deposit.list') }}"
                image="https://cdn-icons-png.flaticon.com/128/11748/11748686.png" icon_style="false"
                title="Deposited Charge"
                value="{{ showAmount($deposit['total_deposit_charge']) }}" color="primary" />
        </div>
    </div>

    {{-- Withdrawal Overview --}}
    <div class="row gy-4 mt-3">
        <div class="col-12">
            <h5 class="mb-2 text-dark">Withdrawal Overview</h5>
        </div>

        <div class="col-xxl-6 col-sm-6">
            <x-widget style="2" link="{{ route('admin.withdraw.data.all') }}"
                image="https://cdn-icons-png.flaticon.com/128/10953/10953112.png" title="Total Withdrawn"
                value="{{ showAmount($withdrawals['total_withdraw_amount']) }}" color="success" />
        </div>
        <div class="col-xxl-6 col-sm-6" id="pending-withdrawals-overview">
            <x-widget style="2" link="{{ route('admin.withdraw.data.pending') }}"
                image="https://cdn-icons-png.flaticon.com/128/10270/10270594.png" title="Pending Withdrawals"
                value="{{ $withdrawals['total_withdraw_pending'] }}" color="warning" />
        </div>
        <div class="col-xxl-6 col-sm-6">
            <x-widget style="2" link="{{ route('admin.withdraw.data.rejected') }}"
                image="https://cdn-icons-png.flaticon.com/128/11243/11243817.png" title="Rejected Withdrawals"
                value="{{ $withdrawals['total_withdraw_rejected'] }}" color="danger" />
        </div>
        <div class="col-xxl-6 col-sm-6">
            <x-widget style="2" link="{{ route('admin.withdraw.data.all') }}"
                image="https://cdn-icons-png.flaticon.com/128/11749/11749461.png" title="Withdrawal Charge"
                value="{{ showAmount($withdrawals['total_withdraw_charge']) }}" color="primary" />
        </div>
    </div>
@endsection


@push('script')
    <script src="{{ asset('assets/admin/js/vendor/apexcharts.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/vendor/chart.js.2.8.0.js') }}"></script>

    <script>
        "use strict";

        var copyButton = document.querySelector('.copy');
        var copyInput = document.querySelector('.copy-text');
        copyButton.addEventListener('click', function(e) {
            e.preventDefault();
            copyInput.select();
            document.execCommand('copy');
            notify('success', 'Copied Successfully!')
        });
        copyInput.addEventListener('click', function() {
            this.select();
        });

        var chart = new ApexCharts(document.querySelector("#apex-bar-chart"), options);
        chart.render();

        var ctx = document.getElementById('userBrowserChart');
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: @json($chart['user_browser_counter']->keys()),
                datasets: [{
                    data: {{ $chart['user_browser_counter']->flatten() }},
                    backgroundColor: [
                        '#ff7675','#6c5ce7','#ffa62b','#ffeaa7','#D980FA','#fccbcb','#45aaf2','#05dfd7',
                        '#FF00F6','#1e90ff','#2ed573','#eccc68','#ff5200','#cd84f1','#7efff5','#7158e2',
                        '#fff200','#ff9ff3','#08ffc8','#3742fa','#1089ff','#70FF61','#bf9fee','#574b90'
                    ],
                    borderColor: ['rgba(231, 80, 90, 0.75)'],
                    borderWidth: 0,
                }]
            },
            options: { aspectRatio: 1, responsive: true, maintainAspectRatio: true, legend: { display: false } }
        });

        var ctx = document.getElementById('userOsChart');
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: @json($chart['user_os_counter']->keys()),
                datasets: [{
                    data: {{ $chart['user_os_counter']->flatten() }},
                    backgroundColor: [
                        '#ff7675','#6c5ce7','#ffa62b','#ffeaa7','#D980FA','#fccbcb','#45aaf2','#05dfd7',
                        '#FF00F6','#1e90ff','#2ed573','#eccc68','#ff5200','#cd84f1','#7efff5','#7158e2',
                        '#fff200','#ff9ff3','#08ffc8','#3742fa','#1089ff','#70FF61','#bf9fee','#574b90'
                    ],
                    borderColor: ['rgba(0, 0, 0, 0.05)'],
                    borderWidth: 0,
                }]
            },
            options: { aspectRatio: 1, responsive: true, legend: { display: false } }
        });

        var ctx = document.getElementById('userCountryChart');
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: @json($chart['user_country_counter']->keys()),
                datasets: [{
                    data: {{ $chart['user_country_counter']->flatten() }},
                    backgroundColor: [
                        '#ff7675','#6c5ce7','#ffa62b','#ffeaa7','#D980FA','#fccbcb','#45aaf2','#05dfd7',
                        '#FF00F6','#1e90ff','#2ed573','#eccc68','#ff5200','#cd84f1','#7efff5','#7158e2',
                        '#fff200','#ff9ff3','#08ffc8','#3742fa','#1089ff','#70FF61','#bf9fee','#574b90'
                    ],
                    borderColor: ['rgba(231, 80, 90, 0.75)'],
                    borderWidth: 0,
                }]
            },
            options: { aspectRatio: 1, responsive: true, legend: { display: false } }
        });

        var options = {
            chart: { height: 450, type: "area", toolbar: { show: false }, dropShadow: { enabled: true, enabledSeries: [0], top: -2, left: 0, blur: 10, opacity: 0.08 }, animations: { enabled: true, easing: 'linear', dynamicAnimation: { speed: 1000 } } },
            dataLabels: { enabled: false },
            grid: { padding: { left: 5, right: 5 }, xaxis: { lines: { show: false } }, yaxis: { lines: { show: false } } }
        };
        new ApexCharts(document.querySelector("#apex-line"), options).render();

        function updatePendingCounts() {
            $.get("{{ route('admin.pending.counts') }}", function(data) {
                // Update Overview Widgets
                $('#pending-deposits-overview h3').text(data.pending_deposits);
                $('#pending-withdrawals-overview h3').text(data.pending_withdrawals);

                // Update Notifications Bar
                if (data.pending_deposits > 0 || data.pending_withdrawals > 0) {
                    $('#pending-notifications').show();
                    
                    // Deposits Button
                    if (data.pending_deposits > 0) {
                        if ($('#pending-deposits-btn').length == 0) {
                            $('#pending-notifications-content').prepend(`
                                <a class="btn--primary py-1 px-3 rounded-pill text-white text-nowrap text-center h-auto w-auto"
                                    id="pending-deposits-btn"
                                    href="{{ route('admin.deposit.pending') }}">
                                    <i class="fa-solid fa-arrows-spin fa-spin"></i>
                                    Pending Deposits (<span class="count">${data.pending_deposits}</span>)
                                </a>
                            `);
                        } else {
                            $('#pending-deposits-btn .count').text(data.pending_deposits);
                        }
                    } else {
                        $('#pending-deposits-btn').remove();
                    }

                    // Withdrawals Button
                    if (data.pending_withdrawals > 0) {
                        if ($('#pending-withdrawals-btn').length == 0) {
                            $('#pending-notifications-content').append(`
                                <a class="btn--success py-1 px-3 rounded-pill text-white text-nowrap text-center h-auto w-auto"
                                    id="pending-withdrawals-btn"
                                    href="{{ route('admin.withdraw.data.pending') }}">
                                    <i class="fa-solid fa-arrows-spin fa-spin"></i>
                                    Pending Withdrawals (<span class="count">${data.pending_withdrawals}</span>)
                                </a>
                            `);
                        } else {
                            $('#pending-withdrawals-btn .count').text(data.pending_withdrawals);
                        }
                    } else {
                        $('#pending-withdrawals-btn').remove();
                    }
                } else {
                    $('#pending-notifications').hide();
                }
            });
        }

        setInterval(updatePendingCounts, 5000); // Update every 5 seconds
    </script>
@endpush
