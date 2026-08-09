@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10">
                <div class="card-header">
                    <h5 class="card-title">@lang('Aviator Game - Live & Upcoming Results')</h5>
                </div>
                <div class="card-body">
                    <!-- Live Game Status - All Users See Same Game -->
                    <div class="alert alert-info mb-3">
                        <i class="las la-broadcast-tower"></i> <strong>@lang('LIVE GAME:')</strong> @lang('All users are playing the same game simultaneously. The multiplier and crash point are identical for everyone.')
                    </div>
                    
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card border--primary">
                                <div class="card-body text-center">
                                    <h6 class="text--primary">@lang('Game Status')</h6>
                                    <h3 id="game_status" class="badge badge--success">@lang('WAITING')</h3>
                                    <p class="mt-2 mb-0">
                                        <span class="text-muted">@lang('Time:')</span>
                                        <strong id="time_remaining">5s</strong>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card border--info">
                                <div class="card-body text-center">
                                    <h6 class="text--info">@lang('Betting Window')</h6>
                                    <h3 id="betting_status" class="badge badge--success">@lang('OPEN')</h3>
                                    <p class="mt-2 mb-0">
                                        <span class="text-muted">@lang('Round:')</span>
                                        <strong id="round_number">#1</strong>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card border--success">
                                <div class="card-body text-center">
                                    <h6 class="text--success">@lang('Live Multiplier')</h6>
                                    <h2 id="current_multiplier" class="text--success">1.00x</h2>
                                    <p class="mt-2 mb-0">
                                        <span class="text-muted">@lang('Bets:')</span>
                                        <strong id="active_bets_count">0</strong>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card border--warning">
                                <div class="card-body text-center">
                                    <h6 class="text--warning">@lang('Round Total')</h6>
                                    <h3 id="total_bet_amount">{{ showAmount(0) }}</h3>
                                    <p class="mt-2 mb-0">
                                        <span class="text-muted">@lang('Payout:')</span>
                                        <strong id="total_payout">{{ showAmount(0) }}</strong>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tabs for Live and Upcoming Results -->
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-bs-toggle="tab" href="#live_results" role="tab">
                                <i class="las la-broadcast-tower"></i> @lang('Live Results')
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#upcoming_results" role="tab">
                                <i class="las la-clock"></i> @lang('Upcoming Results')
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#history" role="tab">
                                <i class="las la-history"></i> @lang('Game History')
                            </a>
                        </li>
                    </ul>

                    <div class="tab-content mt-3">
                        <!-- Live Results Tab -->
                        <div class="tab-pane fade show active" id="live_results" role="tabpanel">
                            <div class="alert alert-success">
                                <i class="las la-users"></i> <strong>@lang('Current Round - All Users\' Bets')</strong>
                                <span id="betting_window_msg" class="ms-2"></span>
                            </div>
                            <div class="table-responsive--md table-responsive">
                                <table class="table table--light style--two">
                                    <thead>
                                        <tr>
                                            <th>@lang('Round ID')</th>
                                            <th>@lang('Username')</th>
                                            <th>@lang('Bet Amount')</th>
                                            <th>@lang('Multiplier')</th>
                                            <th>@lang('Payout')</th>
                                            <th>@lang('Status')</th>
                                            <th>@lang('Time')</th>
                                        </tr>
                                    </thead>
                                    <tbody id="live_results_body">
                                        <tr>
                                            <td colspan="7" class="text-center text-muted">
                                                <i class="las la-spinner la-spin"></i> @lang('Waiting for live bets...')
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Upcoming Results Tab -->
                        <div class="tab-pane fade" id="upcoming_results" role="tabpanel">
                            <div class="alert alert-info">
                                <i class="las la-info-circle"></i> @lang('Next 10 scheduled game results (Pre-generated crash points)')
                            </div>
                            <div class="table-responsive--md table-responsive">
                                <table class="table table--light style--two">
                                    <thead>
                                        <tr>
                                            <th>@lang('Game #')</th>
                                            <th>@lang('Scheduled Crash Point')</th>
                                            <th>@lang('Estimated Start Time')</th>
                                            <th>@lang('Status')</th>
                                        </tr>
                                    </thead>
                                    <tbody id="upcoming_results_body">
                                        <!-- Will be populated by JavaScript -->
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- History Tab -->
                        <div class="tab-pane fade" id="history" role="tabpanel">
                            <div class="table-responsive--md table-responsive">
                                <table class="table table--light style--two">
                                    <thead>
                                        <tr>
                                            <th>@lang('Game ID')</th>
                                            <th>@lang('Crash Point')</th>
                                            <th>@lang('Total Bets')</th>
                                            <th>@lang('Total Bet Amount')</th>
                                            <th>@lang('Total Payout')</th>
                                            <th>@lang('Profit/Loss')</th>
                                            <th>@lang('Time')</th>
                                        </tr>
                                    </thead>
                                    <tbody id="history_body">
                                        <!-- Will be populated by JavaScript -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('style')
<style>
    .card {
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }
    
    #current_multiplier {
        font-size: 2.5rem;
        font-weight: bold;
        animation: pulse 1s infinite;
    }
    
    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.7; }
    }
    
    .badge--flying {
        background-color: #28a745;
        color: white;
        padding: 5px 15px;
        border-radius: 20px;
    }
    
    .badge--crashed {
        background-color: #dc3545;
        color: white;
        padding: 5px 15px;
        border-radius: 20px;
    }
    
    .badge--waiting {
        background-color: #ffc107;
        color: #000;
        padding: 5px 15px;
        border-radius: 20px;
    }
    
    .multiplier-high {
        color: #28a745;
        font-weight: bold;
    }
    
    .multiplier-medium {
        color: #ffc107;
        font-weight: bold;
    }
    
    .multiplier-low {
        color: #dc3545;
        font-weight: bold;
    }
    
    .live-indicator {
        display: inline-block;
        width: 10px;
        height: 10px;
        background-color: #28a745;
        border-radius: 50%;
        animation: blink 1s infinite;
        margin-right: 5px;
    }
    
    @keyframes blink {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.3; }
    }
</style>
@endpush

@push('script')
<script>
    'use strict';
    
    let pollInterval;
    
    $(document).ready(function() {
        startPolling();
    });
    
    function startPolling() {
        poll();
        pollInterval = setInterval(poll, 200);
    }
    
    async function poll() {
        try {
            const response = await fetch("{{ route('admin.games.aviator.poll') }}");
            const data = await response.json();
            updateDashboard(data);
        } catch (e) {
            console.error("Poll failed", e);
        }
    }
    
    function updateDashboard(data) {
        const status = data.game_state;
        const multiplier = parseFloat(data.multiplier).toFixed(2);
        const crashPoint = parseFloat(data.crash_point).toFixed(2);
        
        // 1. Game Status Logic
        const $statusBadge = $('#game_status');
        const $betStatusBadge = $('#betting_status');
        const $msgDiv = $('#betting_window_msg');
        
        if (status === 'waiting') {
            $statusBadge.removeClass('badge--flying badge--crashed').addClass('badge--waiting').text('WAITING');
            $betStatusBadge.removeClass('badge--danger').addClass('badge--success').text('OPEN');
            $msgDiv.html('<span class="badge badge--success"><i class="las la-check-circle"></i> Betting is OPEN</span>');
            $('#time_remaining').text(Math.ceil(data.countdown) + 's');
            
            // Show 1.00x during waiting
            $('#current_multiplier').text('1.00x');
            
        } else if (status === 'flying') {
            $statusBadge.removeClass('badge--waiting badge--crashed').addClass('badge--flying').text('FLYING');
            $betStatusBadge.removeClass('badge--success').addClass('badge--danger').text('CLOSED');
            $msgDiv.html('<span class="badge badge--danger"><i class="las la-times-circle"></i> Betting is CLOSED</span>');
            $('#time_remaining').text('--');
            
            // Show live multiplier
            $('#current_multiplier').text(multiplier + 'x');
            
        } else if (status === 'crashed') {
            $statusBadge.removeClass('badge--waiting badge--flying').addClass('badge--crashed').text('CRASHED');
            $betStatusBadge.removeClass('badge--success').addClass('badge--danger').text('CLOSED');
            $('#time_remaining').text('Next Round..');
            
            // Show crash point
            $('#current_multiplier').text(crashPoint + 'x');
        }
        
        // 2. Round Info and Stats
        if(data.round_id) $('#round_number').text('#' + data.round_id);
        $('#active_bets_count').text(data.active_bets_count);
        $('#total_bet_amount').text('{{ gs('cur_sym') }}' + parseFloat(data.total_bet_amount).toFixed(2));
        $('#total_payout').text('{{ gs('cur_sym') }}' + parseFloat(data.total_payout).toFixed(2));
        
        // 3. Live Results Table (Active Bets)
        updateLiveTable(data.bets, status, crashPoint);
        
        // 4. Upcoming Games Table
        updateUpcomingTable(data.upcoming);
        
        // 5. History Table
        updateHistoryTable(data.history);
    }
    
    function updateLiveTable(bets, status, crashPoint) {
        let html = '';
        if (!bets || bets.length === 0) {
            html = `<tr><td colspan="7" class="text-center text-muted">@lang('No active bets')</td></tr>`;
        } else {
            bets.forEach(bet => {
                let statusBadge = 'badge--warning';
                let statusText = 'Active';
                let multiplierDisp = '--';
                let payoutDisp = '0.00';
                
                if (bet.status === 'cashed_out') {
                    statusBadge = 'badge--success';
                    statusText = 'Cashed Out';
                    multiplierDisp = parseFloat(bet.cashout_multiplier).toFixed(2) + 'x';
                    payoutDisp = parseFloat(bet.payout).toFixed(2);
                } else if (status === 'crashed' && bet.status !== 'cashed_out') {
                    statusBadge = 'badge--danger';
                    statusText = 'Lost';
                    multiplierDisp = '0.00x'; // Or showing crash point? usually 0 for loss
                }
                
                html += `
                <tr>
                    <td><strong>#${bet.round_id || '-'}</strong></td>
                    <td>${bet.username}</td>
                    <td>{{ gs('cur_sym') }}${parseFloat(bet.bet_amount).toFixed(2)}</td>
                    <td>${multiplierDisp}</td>
                    <td>{{ gs('cur_sym') }}${payoutDisp}</td>
                    <td><span class="badge ${statusBadge}">${statusText}</span></td>
                    <td>--</td> 
                </tr>`;
            });
        }
        $('#live_results_body').html(html);
    }
    
    function updateUpcomingTable(upcoming) {
        let html = '';
        if (!upcoming || upcoming.length === 0) {
            html = `<tr><td colspan="4" class="text-center">@lang('No upcoming rounds scheduled')</td></tr>`;
        } else {
             upcoming.forEach(game => {
                 let multClass = 'text-muted';
                 if (game.crash_point >= 2.0) multClass = 'text-info';
                 if (game.crash_point >= 10.0) multClass = 'text-warning';
                 
                 html += `
                 <tr>
                    <td>#${game.round_number}</td>
                    <td class="${multClass}"><strong>${parseFloat(game.crash_point).toFixed(2)}x</strong></td>
                    <td>Next</td>
                    <td><span class="badge badge--primary">Pending</span></td>
                 </tr>
                 `;
             });
        }
        $('#upcoming_results_body').html(html);
    }
    
    function updateHistoryTable(history) {
        let html = '';
        if (!history || history.length === 0) {
             html = `<tr><td colspan="7" class="text-center">@lang('No history')</td></tr>`;
        } else {
             history.forEach(game => {
                  let profit = (parseFloat(game.total_bet_amount || 0) - parseFloat(game.total_payout || 0)).toFixed(2);
                  let profitClass = profit >= 0 ? 'text-success' : 'text-danger';
                  
                  html += `
                  <tr>
                    <td>#${game.round_number}</td>
                    <td><strong>${parseFloat(game.crash_point).toFixed(2)}x</strong></td>
                    <td>${game.total_bets || 0}</td>
                    <td>{{ gs('cur_sym') }}${parseFloat(game.total_bet_amount || 0).toFixed(2)}</td>
                    <td>{{ gs('cur_sym') }}${parseFloat(game.total_payout || 0).toFixed(2)}</td>
                    <td class="${profitClass}">{{ gs('cur_sym') }}${profit}</td>
                    <td>${new Date(game.created_at).toLocaleTimeString()}</td>
                  </tr>
                  `;
             });
        }
        $('#history_body').html(html);
    }
    
    function formatTime(date) {
        // Helper if needed
    }
</script>
@endpush
