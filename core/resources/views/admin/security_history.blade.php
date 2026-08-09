@extends('admin.layouts.app')

@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10 ">
                <div class="card-body p-0">
                    <div class="table-responsive--sm table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                            <tr>
                                <th>@lang('Admin')</th>
                                <th>@lang('Login at')</th>
                                <th>@lang('IP')</th>
                                <th>@lang('Location')</th>
                                <th>@lang('Browser | OS')</th>
                                <th>@lang('Device ID')</th>
                                <th>@lang('Action')</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($loginLogs as $log)
                                <tr>
                                    <td>
                                        <span class="fw-bold">{{ @$log->admin->name }}</span>
                                        <br>
                                        <small>{{ @$log->admin->username }}</small>
                                    </td>
                                    <td>
                                        {{ showDateTime($log->created_at) }} <br> {{ diffForHumans($log->created_at) }}
                                    </td>
                                    <td>
                                        <span class="fw-bold">
                                            <a href="{{ route('admin.report.login.ipHistory', $log->admin_ip) }}">{{ $log->admin_ip }}</a>
                                        </span>
                                    </td>
                                    <td>
                                        {{ __($log->city) }} <br> {{ __($log->country) }}
                                    </td>
                                    <td>
                                        {{ __($log->browser) }} <br> {{ __($log->os) }}
                                    </td>
                                    <td>
                                        @if($log->device_fingerprint)
                                            <div class="d-flex align-items-center justify-content-center gap-2">
                                                <span class="badge badge--primary">
                                                    {{ strtoupper($log->device_fingerprint) }}
                                                </span>
                                                <button type="button" class="btn btn-sm btn--info deviceDetailsBtn" 
                                                        data-details="{{ $log->device_details }}"
                                                        data-ip="{{ $log->admin_ip }}"
                                                        data-id="{{ strtoupper($log->device_fingerprint) }}">
                                                    <i class="la la-info-circle"></i>
                                                </button>
                                            </div>
                                        @else
                                            <span class="text--muted">@lang('N/A')</span>
                                        @endif
                                    </td>
                                    <td>
                                        @php
                                            $isBanned = \App\Models\AdminIpBan::where('ip', $log->admin_ip)->exists();
                                        @endphp
                                        <form action="{{ route('admin.security.ip.ban') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="ip" value="{{ $log->admin_ip }}">
                                            @if($isBanned)
                                                <button type="submit" class="btn btn-sm btn--success">
                                                    <i class="la la-unlock"></i> @lang('Unban IP')
                                                </button>
                                            @else
                                                <button type="submit" class="btn btn-sm btn--danger">
                                                    <i class="la la-ban"></i> @lang('Ban IP')
                                                </button>
                                            @endif
                                        </form>
                                    </td>
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
                @if ($loginLogs->hasPages())
                <div class="card-footer py-4">
                    {{ paginateLinks($loginLogs) }}
                </div>
                @endif
            </div>
        </div>
    </div>
@endsection

{{-- Device Details Modal --}}
<div id="deviceDetailsModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">@lang('Device Details') - <span id="modalDeviceId"></span></h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <i class="las la-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <ul class="list-group list-group-flush" id="deviceDetailsList">
                    {{-- Details will be appended here --}}
                </ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn--dark" data-bs-dismiss="modal">@lang('Close')</button>
            </div>
        </div>
    </div>
</div>

@push('breadcrumb-plugins')
    @if(request()->routeIs('admin.security.login.history'))
        <a href="{{ route('admin.security') }}" class="btn btn-sm btn--primary"><i class="la la-undo"></i> @lang('Back to Security')</a>
    @endif
@endpush

@push('script')
<script>
    (function($){
        "use strict";

        $('.deviceDetailsBtn').on('click', function() {
            var modal = $('#deviceDetailsModal');
            var details = $(this).data('details');
            var deviceId = $(this).data('id');
            var ip = $(this).data('ip');
            
            modal.find('#modalDeviceId').text(deviceId);
            var list = modal.find('#deviceDetailsList');
            list.empty();
            
            list.append(`<li class="list-group-item d-flex justify-content-between align-items-center"><strong>@lang('IP Address')</strong> <span>${ip}</span></li>`);

            if (details) {
                $.each(details, function(key, value) {
                    var title = key.charAt(0).toUpperCase() + key.slice(1).replace(/([A-Z])/g, ' $1');
                    list.append(`<li class="list-group-item d-flex justify-content-between align-items-center"><strong>${title}</strong> <span>${value}</span></li>`);
                });
            } else {
                list.append(`<li class="list-group-item text-center">@lang('No detailed information available')</li>`);
            }
            
            modal.modal('show');
        });
    })(jQuery);
</script>
@endpush
