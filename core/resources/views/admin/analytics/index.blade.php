@extends('admin.layouts.app')

@section('panel')
    <div class="row gy-4">
        <div class="col-xxl-3 col-sm-6">
            <x-widget
                link="javascript:void(0)"
                image="https://cdn-icons-png.flaticon.com/128/2910/2910243.png"
                title="Total Page Visits"
                value="{{ $totalVisits }}"
                bg="primary"
            />
        </div>
        <div class="col-xxl-3 col-sm-6">
            <x-widget
                link="javascript:void(0)"
                image="https://cdn-icons-png.flaticon.com/128/1017/1017498.png"
                title="Unique Visitors"
                value="{{ $uniqueVisitors }}"
                bg="success"
            />
        </div>
        <div class="col-xxl-3 col-sm-6">
            <x-widget
                style="2"
                link="javascript:void(0)"
                image="https://cdn-icons-png.flaticon.com/128/33/33308.png"
                title="Visits Today"
                value="{{ $todayVisits }}"
                color="info"
            />
        </div>
        <div class="col-xxl-3 col-sm-6">
            <x-widget
                style="2"
                link="javascript:void(0)"
                image="https://cdn-icons-png.flaticon.com/128/9720/9720918.png"
                title="Online Users (5m)"
                value="{{ $onlineUsers }}"
                color="warning"
            />
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-lg-6 mb-30">
            <div class="card b-radius--10 overflow-hidden box--shadow1">
                <div class="card-header">
                    <h5 class="card-title">@lang('Top 10 Visited Pages')</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive--sm table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                            <tr>
                                <th>@lang('URL')</th>
                                <th>@lang('Views')</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($topPages as $page)
                                <tr>
                                    <td class="text-break">{{ $page->url }}</td>
                                    <td><span class="badge badge--success">{{ $page->count }}</span></td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6 mb-30">
            <div class="card b-radius--10 overflow-hidden box--shadow1">
                <div class="card-header">
                    <h5 class="card-title">@lang('Browser Statistics')</h5>
                </div>
                <div class="card-body">
                    <canvas id="browserChart" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
         <div class="col-lg-12">
            <div class="card b-radius--10 overflow-hidden box--shadow1">
                <div class="card-header">
                    <h5 class="card-title">@lang('Recent Activity Log')</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive--sm table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                            <tr>
                                <th>@lang('User')</th>
                                <th>@lang('IP Address')</th>
                                <th>@lang('Page')</th>
                                <th>@lang('Browser')</th>
                                <th>@lang('Time')</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($recentLogs as $log)
                                <tr>
                                    <td>
                                        @if($log->user)
                                            <span class="fw-bold">{{ $log->user->fullname }}</span>
                                            <br>
                                            <a href="{{ route('admin.users.detail', $log->user_id) }}"><span>@</span>{{ $log->user->username }}</a>
                                        @else
                                            <span class="fw-bold">Guest</span>
                                        @endif
                                    </td>
                                    <td>{{ $log->ip_address }}</td>
                                    <td class="text-break">{{ Str::limit($log->url, 50) }}</td>
                                    <td>{{ Str::limit($log->user_agent, 30) }}</td>
                                    <td>{{ showDateTime($log->created_at) }} <br> {{ diffForHumans($log->created_at) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script src="{{ asset('assets/admin/js/vendor/chart.js.2.8.0.js') }}"></script>
    <script>
        "use strict";
        var ctx = document.getElementById('browserChart').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: @json($browsers->keys()),
                datasets: [{
                    data: @json($browsers->values()),
                    backgroundColor: [
                        '#ff7675', '#6c5ce7', '#ffa62b', '#ffeaa7', '#00b894'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                legend: {
                    position: 'bottom'
                }
            }
        });
    </script>
@endpush
