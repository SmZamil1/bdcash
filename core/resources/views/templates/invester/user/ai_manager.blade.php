@extends($activeTemplate . 'layouts.master')
@section('content')
<div class="dashboard-body">
    <div class="row gy-4 justify-content-center">
        <div class="col-lg-12">
            <div class="card custom--card">
                <div class="card-header d-flex flex-wrap justify-content-between align-items-center">
                    <h4 class="card-title mb-0">@lang('AI Account Manager')</h4>
                    <div class="text-end">
                        <button class="btn btn--base btn-sm me-2" data-bs-toggle="modal" data-bs-target="#depositModal">
                            <i class="las la-plus-circle"></i> @lang('Add Funds')
                        </button>
                        <button class="btn btn--danger btn-sm" data-bs-toggle="modal" data-bs-target="#withdrawModal">
                            <i class="las la-minus-circle"></i> @lang('Withdraw Funds')
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        <!-- Balance Card -->
                        <div class="col-xl-4 col-md-6">
                            <div class="d-widget d-flex flex-wrap align-items-center rounded-3 bg--primary p-4 h-100">
                                <div class="d-widget__content">
                                    <h3 class="d-widget__amount text-white">{{ showAmount($manager->balance) }} {{ $general->cur_text }}</h3>
                                    <p class="d-widget__caption text-white">@lang('Managed Balance')</p>
                                </div>
                                <div class="d-widget__icon ms-auto text-white">
                                    <i class="las la-robot fs-1"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Profit Card -->
                        <div class="col-xl-4 col-md-6">
                            <div class="d-widget d-flex flex-wrap align-items-center rounded-3 bg--success p-4 h-100">
                                <div class="d-widget__content">
                                    <h3 class="d-widget__amount text-white">{{ showAmount($manager->last_profit) }} {{ $general->cur_text }}</h3>
                                    <p class="d-widget__caption text-white">@lang('Total AI Profit')</p>
                                </div>
                                <div class="d-widget__icon ms-auto text-white">
                                    <i class="las la-chart-line fs-1"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Settings Card -->
                        <div class="col-xl-4 col-md-12">
                            <div class="card h-100 border-0 shadow-none bg--body">
                                <div class="card-body">
                                    <h5 class="mb-3">@lang('AI Configuration')</h5>
                                    <form action="{{ route('user.ai.manager.update') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="action" value="update_settings">
                                        
                                        <div class="form-group mb-3">
                                            <label class="form-label">@lang('Risk Level')</label>
                                            <select name="risk_level" class="form-control form--control">
                                                <option value="low" @selected($manager->risk_level == 'low')>@lang('Low Risk (Conservative)')</option>
                                                <option value="medium" @selected($manager->risk_level == 'medium')>@lang('Medium Risk (Balanced)')</option>
                                                <option value="high" @selected($manager->risk_level == 'high')>@lang('High Risk (Aggressive)')</option>
                                            </select>
                                        </div>

                                        <div class="form-group mb-3">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" name="auto_compound" id="autoCompound" @checked($manager->auto_compound)>
                                                <label class="form-check-label" for="autoCompound">@lang('Auto-Compound Earnings')</label>
                                            </div>
                                        </div>

                                        <div class="form-group mb-3">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" name="status" id="aiStatus" @checked($manager->status)>
                                                <label class="form-check-label" for="aiStatus">@lang('Enable AI Manager')</label>
                                            </div>
                                        </div>

                                        <hr>
                                        <h5 class="mb-3 mt-3">@lang('Auto Withdraw Settings')</h5>
                                        
                                        <div class="form-group mb-3">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" name="auto_withdraw" id="autoWithdraw" @checked($manager->auto_withdraw)>
                                                <label class="form-check-label" for="autoWithdraw">@lang('Enable Auto Withdraw')</label>
                                            </div>
                                            <small class="text-muted">@lang('Automatically withdraw profits when amount is reached at scheduled time.')</small>
                                        </div>

                                        <div class="form-group mb-3">
                                            <label class="form-label">@lang('Withdraw Amount')</label>
                                            <div class="input-group">
                                                <input type="number" step="any" name="withdraw_amount" class="form-control" value="{{ getAmount($manager->withdraw_amount) }}" placeholder="0.00">
                                                <span class="input-group-text">{{ $general->cur_text }}</span>
                                            </div>
                                        </div>

                                        <div class="form-group mb-3">
                                            <label class="form-label">@lang('Withdraw Frequency')</label>
                                            <select name="withdraw_frequency" class="form-control form--control">
                                                <option value="daily" @selected($manager->withdraw_frequency == 'daily')>@lang('Daily (24 Hours)')</option>
                                                <option value="weekly" @selected($manager->withdraw_frequency == 'weekly')>@lang('Weekly (7 Days)')</option>
                                                <option value="monthly" @selected($manager->withdraw_frequency == 'monthly')>@lang('Monthly (30 Days)')</option>
                                            </select>
                                        </div>
                                        
                                        @if($manager->next_withdraw_at)
                                            <div class="alert alert-info p-2 mt-2">
                                                <small>@lang('Next auto-withdraw scheduled for:') <strong>{{ showDateTime($manager->next_withdraw_at) }}</strong></small>
                                            </div>
                                        @endif
                                        <hr>

                                        <button type="submit" class="btn btn--base w-100">@lang('Save Configuration')</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Chart Section -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card border-0 shadow-none bg--body">
                                <div class="card-body">
                                    <h5 class="card-title">@lang('Performance Analytics')</h5>
                                    <div id="aiPerformanceChart"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Deposit Modal -->
<div class="modal fade" id="depositModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">@lang('Add Funds to AI Manager')</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('user.ai.manager.update') }}" method="POST">
                @csrf
                <input type="hidden" name="action" value="deposit">
                <div class="modal-body">
                    <div class="form-group">
                        <label>@lang('Amount')</label>
                        <div class="input-group">
                            <input type="number" step="any" name="amount" class="form-control" placeholder="0.00" required>
                            <span class="input-group-text">{{ $general->cur_text }}</span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn--dark" data-bs-dismiss="modal">@lang('Close')</button>
                    <button type="submit" class="btn btn--base">@lang('Confirm Deposit')</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Withdraw Modal -->
<div class="modal fade" id="withdrawModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">@lang('Withdraw Funds from AI Manager')</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('user.ai.manager.update') }}" method="POST">
                @csrf
                <input type="hidden" name="action" value="withdraw">
                <div class="modal-body">
                    <div class="form-group">
                        <label>@lang('Amount')</label>
                        <div class="input-group">
                            <input type="number" step="any" name="amount" class="form-control" placeholder="0.00" required>
                            <span class="input-group-text">{{ $general->cur_text }}</span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn--dark" data-bs-dismiss="modal">@lang('Close')</button>
                    <button type="submit" class="btn btn--danger">@lang('Confirm Withdraw')</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('script')
<script src="{{ asset('assets/global/js/apexcharts.min.js') }}"></script>
<script>
    (function ($) {
        "use strict";
        var options = {
            series: [{
                name: 'Profit',
                data: [31, 40, 28, 51, 42, 109, 100]
            }],
            chart: {
                height: 350,
                type: 'area',
                toolbar: {
                    show: false
                }
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                curve: 'smooth'
            },
            xaxis: {
                type: 'datetime',
                categories: ["2023-09-19T00:00:00.000Z", "2023-09-19T01:30:00.000Z", "2023-09-19T02:30:00.000Z", "2023-09-19T03:30:00.000Z", "2023-09-19T04:30:00.000Z", "2023-09-19T05:30:00.000Z", "2023-09-19T06:30:00.000Z"]
            },
            tooltip: {
                x: {
                    format: 'dd/MM/yy HH:mm'
                },
            },
            colors: ['#00e396']
        };

        var chart = new ApexCharts(document.querySelector("#aiPerformanceChart"), options);
        chart.render();
    })(jQuery);
</script>
@endpush
