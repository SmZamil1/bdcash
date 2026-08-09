@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <form action="{{ route('admin.staff.role.store', @$role->id ?? 0) }}" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="form-group">
                            <label>@lang('Role Name')</label>
                            <input type="text" class="form-control" name="name" value="{{ @$role->name }}" required>
                        </div>

                        <div class="form-group">
                            <label>@lang('Permissions')</label>
                            <div class="row">
                                @php
                                    $permissions = [
                                        'Manage Users' => 'admin.users.*',
                                        'Manage Deposits' => 'admin.deposit.*',
                                        'Manage Withdrawals' => 'admin.withdraw.*',
                                        'Manage Plans' => 'admin.plan.*',
                                        'General Setting' => 'admin.setting.*',
                                        'Manage Frontend' => 'admin.frontend.*',
                                        'Manage Support Tickets' => 'admin.ticket.*',
                                        'System Info' => 'admin.system.*',
                                        'Referrals & Levels' => 'admin.referrals.*',
                                        'Leaderboard' => 'admin.leaderboard.*',
                                        'Salary System' => 'admin.salary.*',
                                        'Promo Codes' => 'admin.promocode.*',
                                        'Earning Modules' => 'admin.earning.methods.*',
                                        'Games & Casino' => 'admin.games.*',
                                        'AI Bots' => 'admin.aibots.*',
                                        'Cloud Mining' => 'admin.mining.*',
                                        'Options Trading' => 'admin.trading.setting.*',
                                        'Stock Manager' => 'admin.stock.*',
                                        'Forex Manager' => 'admin.forex.*',
                                        'PTC Module' => 'admin.ptc.*',
                                        'VIP Plans' => 'admin.vip.*',
                                        'Daily Rewards' => 'admin.daily_reward.*',
                                        'Reports & Analytics' => 'admin.report.*',
                                        'Payment Gateways' => 'admin.gateway.*',
                                        'App Settings' => 'admin.app.settings.*',
                                        'Subscribers' => 'admin.subscriber.*',
                                        'Staff Management' => 'admin.staff.*',
                                        'Security Manager' => 'admin.security',
                                        'Profitable Users' => 'admin.users.profitable',
                                        'Duplicate Users' => 'admin.users.duplicate',
                                        'Sliding Banner' => 'admin.frontend.banner',
                                        'Important Links' => 'admin.frontend.links',
                                    ];
                                @endphp
                                @foreach($permissions as $key => $val)
                                    <div class="col-md-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="permissions[]" value="{{ $val }}" id="permission_{{ $loop->index }}" 
                                            @if(@$role->permissions && in_array($val, $role->permissions)) checked @endif>
                                            <label class="form-check-label text--black" for="permission_{{ $loop->index }}">
                                                {{ $key }}
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-end">
                        <button type="submit" class="btn btn--primary h-45 w-100">@lang('Submit')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('breadcrumb-plugins')
    <a href="{{ route('admin.staff.roles') }}" class="btn btn-sm btn-outline--primary"><i class="la la-undo"></i> @lang('Back')</a>
@endpush
