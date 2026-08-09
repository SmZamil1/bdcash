@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10">
                <div class="card-header">
                    <h5 class="card-title">@lang('Aviator Game - Live Dashboard')</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="card border--success">
                                <div class="card-body text-center">
                                    <h6 class="text--success">@lang('Live Active Players')</h6>
                                    <h2 id="active_bets_count">0</h2>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card border--primary">
                                <div class="card-body text-center">
                                    <h6 class="text--primary">@lang('Live Running Bets')</h6>
                                    <h2 id="total_bet_amount">0.00</h2>
                                </div>
                            </div>
                        </div>
                    </div>

                    <h5 class="mb-3">@lang('Overall Statistics')</h5>
                    <div class="row mb-4">
                         <div class="col-xl-4 col-md-6 mb-30">
                            <div class="card b-radius--10 bg--primary">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div class="details">
                                            <div class="desciption">
                                                <span class="text--small text-white">@lang('Total Bets')</span>
                                            </div>
                                            <div class="numbers">
                                                <span class="amount text-white fw-bold" id="stats_total_bets">0</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-md-6 mb-30">
                            <div class="card b-radius--10 bg--info">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div class="details">
                                            <div class="desciption">
                                                <span class="text--small text-white">@lang('Total Wagered')</span>
                                            </div>
                                            <div class="numbers">
                                                <span class="amount text-white fw-bold" id="stats_total_wagered">0.00</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-md-6 mb-30">
                            <div class="card b-radius--10 bg--danger">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div class="details">
                                            <div class="desciption">
                                                <span class="text--small text-white">@lang('User Profit (Admin Loss)')</span>
                                            </div>
                                            <div class="numbers">
                                                <span class="amount text-white fw-bold" id="stats_admin_loss">0.00</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <h5 class="mb-3">@lang('Live Active Bets')</h5>
                            <div class="table-responsive--md table-responsive">
                                <table class="table table--light style--two">
                                    <thead>
                                        <tr>
                                            <th>@lang('User')</th>
                                            <th>@lang('Bet Amount')</th>
                                            <th>@lang('Current Mult.')</th>
                                            <th>@lang('Est. Payout')</th>
                                            <th>@lang('Time Started')</th>
                                        </tr>
                                    </thead>
                                    <tbody id="live_results_body">
                                        <tr>
                                            <td colspan="5" class="text-center text-muted">
                                                <i class="las la-spinner la-spin"></i> @lang('Waiting for live bets...')
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-5">
                        <div class="col-md-12">
                            <h5 class="mb-3 text--info">@lang('Upcoming Results (Ready for Next Games)')</h5>
                            <div class="table-responsive--md table-responsive">
                                <table class="table table--light style--two">
                                    <thead>
                                        <tr>
                                            <th>@lang('Queue ID')</th>
                                            <th>@lang('Scheduled Crash Point')</th>
                                            <th>@lang('Created At')</th>
                                            <th>@lang('Status')</th>
                                        </tr>
                                    </thead>
                                    <tbody id="upcoming_results_body">
                                        <!-- Populated via JS -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-5">
                        <div class="col-md-12">
                            <h5 class="mb-3">@lang('Recent Results')</h5>
                            <div class="table-responsive--md table-responsive">
                                <table class="table table--light style--two">
                                    <thead>
                                        <tr>
                                            <th>@lang('User')</th>
                                            <th>@lang('Bet Amount')</th>
                                            <th>@lang('Cashout/Crash')</th>
                                            <th>@lang('Final Payout')</th>
                                            <th>@lang('Status')</th>
                                            <th>@lang('Time')</th>
                                        </tr>
                                    </thead>
                                    <tbody id="history_body">
                                        <!-- Populated via JS -->
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

@push('script')
<script>
    'use strict';
    
    $(document).ready(function() {
        startPolling();
    });
    
    function startPolling() {
        poll();
        setInterval(poll, 1000); // Poll every second
    }
    
    async function poll() {
        try {
            const response = await fetch("{{ route('admin.games.aviator.poll') }}");
            const data = await response.json();
            
            $('#active_bets_count').text(data.active_bets_count);
            $('#total_bet_amount').text('{{ gs('cur_sym') }}' + parseFloat(data.total_bet_amount).toFixed(2));
            
            // Update Overall Stats
            if(data.stats) {
                $('#stats_total_bets').text(data.stats.total_bets);
                $('#stats_total_wagered').text('{{ gs('cur_sym') }}' + parseFloat(data.stats.total_wagered).toFixed(2));
                // $('#stats_admin_profit').text('{{ gs('cur_sym') }}' + parseFloat(data.stats.admin_profit).toFixed(2)); // Removed
                $('#stats_admin_loss').text('{{ gs('cur_sym') }}' + parseFloat(data.stats.admin_loss).toFixed(2));
            }
            
            // Update Active Table
            let activeHtml = '';
            if(data.bets.length > 0){
                data.bets.forEach(bet => {
                    activeHtml += `<tr>
                        <td>${bet.username}</td>
                        <td>{{ gs('cur_sym') }}${parseFloat(bet.bet_amount).toFixed(2)}</td>
                        <td class="text--success fw-bold">${bet.multiplier}x</td>
                        <td>{{ gs('cur_sym') }}${bet.payout}</td>
                        <td>${bet.created_at}</td>
                    </tr>`;
                });
            } else {
                activeHtml = '<tr><td colspan="5" class="text-center text-muted">No active games</td></tr>';
            }
            $('#live_results_body').html(activeHtml);

            // Update Upcoming Table
            let upcomingHtml = '';
            if(data.upcoming && data.upcoming.length > 0){
                data.upcoming.forEach(u => {
                    let multClass = u.crash_point >= 5 ? 'text--success' : (u.crash_point <= 1.2 ? 'text--danger' : '');
                    upcomingHtml += `<tr>
                        <td>#${u.id}</td>
                        <td class="${multClass} fw-bold">${parseFloat(u.crash_point).toFixed(2)}x</td>
                        <td>${new Date(u.created_at).toLocaleTimeString()}</td>
                        <td><span class="badge badge--info">Queued</span></td>
                    </tr>`;
                });
            } else {
                upcomingHtml = '<tr><td colspan="4" class="text-center">Generating next batch...</td></tr>';
            }
            $('#upcoming_results_body').html(upcomingHtml);

            // Update History Table
            let historyHtml = '';
            if(data.history.length > 0){
                data.history.forEach(h => {
                    let multDisp = h.status === 'Won' ? h.multiplier.toFixed(2) + 'x' : h.crash_point.toFixed(2) + 'x';
                    let statusClass = h.status === 'Won' ? 'badge--success' : 'badge--danger';
                    
                    historyHtml += `<tr>
                        <td>${h.username}</td>
                        <td>{{ gs('cur_sym') }}${parseFloat(h.bet_amount).toFixed(2)}</td>
                        <td>${multDisp}</td>
                        <td>{{ gs('cur_sym') }}${parseFloat(h.payout).toFixed(2)}</td>
                        <td><span class="badge ${statusClass}">${h.status}</span></td>
                        <td>${h.created_at}</td>
                    </tr>`;
                });
            }
            $('#history_body').html(historyHtml);

        } catch (e) {
            console.error("Poll failed", e);
        }
    }
</script>
@endpush
