@extends('admin.layouts.app')

@section('panel')
@php
    $email = request()->getHost();
@endphp
    <div class="row">
        <div class="col-12">
            <div class="row gy-4">

                <div class="col-xxl-3 col-sm-6">
                    <div class="widget-two style--two box--shadow2 b-radius--5 bg--warning">
                        <div class="widget-two__icon b-radius--5 bg--warning">
                            <i class="las la-money-bill-wave-alt"></i>
                        </div>
                        <div class="widget-two__content">
                            <h3 class="text-white">{{ showAmount($user->interest_wallet) }}</h3>
                            <p class="text-white">@lang('Balance')</p>
                        </div>
                        <a href="{{ route('admin.report.transaction') }}?search={{ $user->username }}" class="widget-two__btn">@lang('View All')</a>
                    </div>
                </div>
                <!-- dashboard-w1 end -->

                <div class="col-xxl-3 col-sm-6">
                    <div class="widget-two style--two box--shadow2 b-radius--5 bg--primary">
                        <div class="widget-two__icon b-radius--5 bg--primary">
                            <i class="las la-wallet"></i>
                        </div>
                        <div class="widget-two__content">
                            <h3 class="text-white">{{ showAmount($totalDeposit) }}</h3>
                            <p class="text-white">@lang('Deposits')</p>
                        </div>
                        <a href="{{ route('admin.deposit.list') }}?search={{ $user->username }}" class="widget-two__btn">@lang('View All')</a>
                    </div>
                </div>
                <!-- dashboard-w1 end -->

                <div class="col-xxl-3 col-sm-6">
                    <div class="widget-two style--two box--shadow2 b-radius--5 bg--1">
                        <div class="widget-two__icon b-radius--5 bg--primary">
                            <i class="fas fa-wallet"></i>
                        </div>
                        <div class="widget-two__content">
                            <h3 class="text-white">{{ showAmount($totalWithdrawals) }}</h3>
                            <p class="text-white">@lang('Withdrawals')</p>
                        </div>
                        <a href="{{ route('admin.withdraw.data.all') }}?search={{ $user->username }}" class="widget-two__btn">@lang('View All')</a>
                    </div>
                </div>
                <!-- dashboard-w1 end -->

                <div class="col-xxl-3 col-sm-6">
                    <div class="widget-two style--two box--shadow2 b-radius--5 bg--17">
                        <div class="widget-two__icon b-radius--5 bg--primary">
                            <i class="las la-exchange-alt"></i>
                        </div>
                        <div class="widget-two__content">
                            <h3 class="text-white">{{ $totalTransaction }}</h3>
                            <p class="text-white">@lang('Transactions')</p>
                        </div>
                        <a href="{{ route('admin.report.transaction') }}?search={{ $user->username }}" class="widget-two__btn">@lang('View All')</a>
                    </div>
                </div>
                <!-- dashboard-w1 end -->

            </div>

            <div class="d-flex flex-wrap gap-3 mt-4">
                <div class="flex-fill">
                    <button data-bs-toggle="modal" data-bs-target="#addSubModal" class="btn btn--success btn--shadow w-100 btn-lg bal-btn" data-act="add">
                        <i class="las la-plus-circle"></i> @lang('Balance')
                    </button>
                </div>

                <div class="flex-fill">
                    <button data-bs-toggle="modal" data-bs-target="#addSubModal" class="btn btn--danger btn--shadow w-100 btn-lg bal-btn" data-act="sub">
                        <i class="las la-minus-circle"></i> @lang('Balance')
                    </button>
                </div>

                {{-- <div class="flex-fill">
                    <a href="{{route('admin.report.login.history')}}?search={{ $user->username }}" class="btn btn--primary btn--shadow w-100 btn-lg">
                        <i class="las la-list-alt"></i>@lang('Logins')
                    </a>
                </div>

                <div class="flex-fill">
                    <a href="{{ route('admin.users.notification.log',$user->id) }}" class="btn btn--secondary btn--shadow w-100 btn-lg">
                        <i class="las la-bell"></i>@lang('Notifications')
                    </a>
                </div> --}}

               
                <div class="flex-fill">
                    <a href="{{route('admin.users.login',$user->id)}}" target="_blank" class="btn btn--primary btn--gradi btn--shadow w-100 btn-lg">
                        <i class="las la-sign-in-alt"></i>@lang('Login as User')
                    </a>
                </div>

                @php
                    $lastLogin = $user->loginLogs()->orderBy('id', 'desc')->first();
                @endphp
                @if($lastLogin)
                <div class="flex-fill">
                    <a href="{{ route('admin.users.duplicate', ['search' => $lastLogin->user_ip]) }}" class="btn btn--warning btn--gradi btn--shadow w-100 btn-lg">
                        <i class="las la-users-cog"></i> @lang('Shared IP Accounts')
                    </a>
                </div>
                @endif

                <div class="flex-fill">
                    <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#passChangeModal" class="btn btn--info btn--gradi btn--shadow w-100 btn-lg">
                        <i class="las la-sign-in-alt"></i>@lang('Change Password')
                    </a>
                </div>

                @if($user->kyc_data)
                <div class="flex-fill">
                    <a href="{{ route('admin.users.kyc.details', $user->id) }}" target="_blank" class="btn btn--dark btn--shadow w-100 btn-lg">
                        <i class="las la-user-check"></i>@lang('KYC Data')
                    </a>
                </div>
                @endif

                <div class="flex-fill">
                    @if($user->status == Status::USER_ACTIVE )
                    <button type="button" class="btn btn--warning btn--gradi btn--shadow w-100 btn-lg userStatus" data-bs-toggle="modal" data-bs-target="#userStatusModal">
                        <i class="las la-ban"></i>@lang('Ban User')
                    </button>
                    @else
                    <button type="button" class="btn btn--success btn--gradi btn--shadow w-100 btn-lg userStatus" data-bs-toggle="modal" data-bs-target="#userStatusModal">
                        <i class="las la-undo"></i>@lang('Unban User')
                    </button>
                    @endif
                </div>

                <div class="flex-fill">
                     <button type="button" class="btn btn--secondary btn--gradi btn--shadow w-100 btn-lg" data-bs-toggle="modal" data-bs-target="#activatePlanModal">
                        <i class="las la-gift"></i>@lang('Activate Plan')
                    </button>
                </div>
            </div>

    {{-- Activate Plan MODAL --}}
    <div id="activatePlanModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Activate Plan for User')</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="las la-times"></i>
                    </button>
                </div>
                <form action="{{ route('admin.users.activate.plan', $user->id) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label>@lang('Select Plan')</label>
                            <select name="plan_id" class="form-control" required>
                                <option value="">@lang('Select One')</option>
                                @foreach($plans as $plan)
                                    <option value="{{ $plan->id }}" data-fixed_amount="{{ $plan->fixed_amount }}" data-minimum="{{ $plan->minimum }}" data-maximum="{{ $plan->maximum }}">
                                        {{ $plan->name }} ({{ $plan->fixed_amount > 0 ? showAmount($plan->fixed_amount) : showAmount($plan->minimum) . ' - ' . showAmount($plan->maximum) }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>@lang('Amount')</label>
                            <div class="input-group">
                                <input type="number" step="any" name="amount" class="form-control" placeholder="@lang('Enter Amount')" required>
                                <div class="input-group-text">{{ __($general->cur_text) }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn--primary h-45 w-100">@lang('Activate Plan')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('script')
    <script>
        (function($){
            "use strict";
            $('select[name=plan_id]').on('change', function(){
                var selected = $(this).find('option:selected');
                var fixedAmount = parseFloat(selected.data('fixed_amount'));
                var minimum = parseFloat(selected.data('minimum'));
                var maximum = parseFloat(selected.data('maximum'));
                var amountInput = $('input[name=amount]');

                if(fixedAmount > 0){
                    amountInput.val(fixedAmount).attr('readonly', true);
                } else {
                    amountInput.val(minimum).attr('readonly', false);
                }
            });
        })(jQuery);
    </script>
    @endpush

<div class="card mt-30">
    <div class="card-header">
        <h5 class="card-title mb-0">@lang('Information of') {{$user->fullname}}</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.users.update', [$user->id]) }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>@lang('Email')</label>
                        <input class="form-control" type="email" name="email" value="{{ $user->username . '@' . $email }}" required>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label>@lang('Mobile Number')</label>
                        <div class="input-group">
                            <span class="input-group-text mobile-code"></span>
                            <input type="number" name="mobile" value="{{ old('mobile', $user->mobile) }}" id="mobile" class="form-control checkUser" required>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>@lang('Username')</label>
                        <input type="text" name="username" value="{{ $user->username }}" id="username" class="form-control checkUser" required>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label>@lang('Country')</label>
                        <select name="country" class="form-control">
                            @foreach($countries as $key => $country)
                                <option data-mobile_code="{{ $country->dial_code }}" value="{{ $key }}" @if($user->country_code == $key) selected @endif>{{ __($country->country) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>@lang('City')</label>
                        <input type="text" name="city" class="form-control" value="{{ @$user->city }}">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>@lang('State')</label>
                        <input type="text" name="state" class="form-control" value="{{ @$user->state }}">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>@lang('Zip/Postal')</label>
                        <input type="text" name="zip" class="form-control" value="{{ @$user->zip }}">
                    </div>
                </div>
            </div>

            
<div class="row mt-4">
    <div class="form-group col-xl-3 col-md-6 col-12">
        <label>Deposit Access</label>
        <input type="hidden" name="deposit_access" value="0">
        <input type="checkbox" data-width="100%" data-onstyle="-success" data-offstyle="-danger"
               data-bs-toggle="toggle" data-on="Enabled" data-off="Disabled" name="deposit_access"
               value="1" {{ $user->deposit_access ? 'checked' : '' }}>
    </div>

    <div class="form-group col-xl-3 col-md-6 col-12">
        <label>Withdraw Access</label>
        <input type="hidden" name="withdraw_access" value="0">
        <input type="checkbox" data-width="100%" data-onstyle="-success" data-offstyle="-danger"
               data-bs-toggle="toggle" data-on="Enabled" data-off="Disabled" name="withdraw_access"
               value="1" {{ $user->withdraw_access ? 'checked' : '' }}>
    </div>

    <div class="form-group col-xl-3 col-md-6 col-12">
        <label>Required Referrals for Withdraw</label>
        <div class="input-group">
            <input type="number" name="required_referrals" class="form-control" value="{{ $user->required_referrals ?? 0 }}">
            <span class="input-group-text">@lang('Users')</span>
        </div>
    </div>

    <div class="form-group col-xl-3 col-md-6 col-12">
        <label>Ban User from Creating New Accounts</label>
        <input type="hidden" name="ban_new_accounts" value="0">
        <input type="checkbox" data-width="100%" data-onstyle="-success" data-offstyle="-danger"
               data-bs-toggle="toggle" data-on="Enabled" data-off="Disabled" name="ban_new_accounts"
               value="1" {{ $user->ban_new_accounts == Status::ENABLE ? 'checked' : '' }}>
    </div>

    <div class="form-group col-xl-3 col-md-6 col-12">
        <label>Account Withdrawal Limit (Total)</label>
        <div class="input-group">
            <input type="number" step="any" name="withdrawal_limit" class="form-control" value="{{ getAmount($user->withdrawal_limit) }}" placeholder="Enter Limit (0 = No Limit)">
            <span class="input-group-text">{{ __($general->cur_text) }}</span>
        </div>
        <small class="text--small text-muted">@lang('Once total withdrawals reach this amount, user cannot withdraw more.')</small>
    </div>
</div>



            <div class="row mt-4">
                <div class="col-md-12">
                    <div class="form-group">
                        <button type="submit" class="btn btn--primary w-100 h-45">@lang('Submit')</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>


            </div>

        </div>
    </div>

    

    {{-- change Pass MODAL --}}
    <div id="passChangeModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><span class="type"></span> <span>@lang('Password Change')</span></h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="las la-times"></i>
                    </button>
                </div>
                <form action="{{route('admin.users.change.pass',$user->id)}}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label>@lang('New Password')</label>
                            <div class="input-group">
                                <input type="text" name="password" class="form-control" placeholder="@lang('Enter Password')" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn--info h-45 w-100">@lang('Change Password')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Add Sub Balance MODAL --}}
    <div id="addSubModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><span class="type"></span> <span>@lang('Balance')</span></h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="las la-times"></i>
                    </button>
                </div>
                <form action="{{ route('admin.users.add.sub.balance', $user->id) }}" class="balanceAddSub disableSubmission" method="POST">
                    @csrf
                    <input type="hidden" name="act">
                    <div class="modal-body">
                        <div class="form-group">
                            <label>@lang('Amount')</label>
                            <div class="input-group">
                                <input type="number" step="any" name="amount" class="form-control" placeholder="@lang('Please provide positive amount')" required>
                                <div class="input-group-text">{{ __(gs('cur_text')) }}</div>
                            </div>
                        </div>
                   <div class="form-group" style="display: none;">
    <label>@lang('Wallet Type')</label>
    <select name="wallet_type" required>
        <option value="interest_wallet" selected>@lang('Interest Wallet')</option>
    </select>
</div>

                        <div class="form-group">
                            <label>@lang('Remark')</label>
                            <textarea class="form-control" placeholder="@lang('Remark')" name="remark" rows="4" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn--primary h-45 w-100">@lang('Submit')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <div id="userStatusModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        @if($user->status == Status::USER_ACTIVE)
                        <span>@lang('Ban User')</span>
                        @else
                        <span>@lang('Unban User')</span>
                        @endif
                    </h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="las la-times"></i>
                    </button>
                </div>
                <form action="{{route('admin.users.status',$user->id)}}" method="POST">
                    @csrf
                    <div class="modal-body">
                        @if($user->status == Status::USER_ACTIVE)
                        <h6 class="mb-2">@lang('If you ban this user he/she won\'t able to access his/her dashboard.')</h6>
                        <div class="form-group">
                            <label>@lang('Reason')</label>
                            <textarea class="form-control" name="reason" rows="4" required></textarea>
                        </div>
                        @else
                        <p><span>@lang('Ban reason was'):</span></p>
                        <p>{{ $user->ban_reason }}</p>
                        <h4 class="text-center mt-3">@lang('Are you sure to unban this user?')</h4>
                        @endif
                    </div>
                    <div class="modal-footer">
                        @if($user->status == Status::USER_ACTIVE)
                        <button type="submit" class="btn btn--primary h-45 w-100">@lang('Submit')</button>
                        @else
                        <button type="button" class="btn btn--dark" data-bs-dismiss="modal">@lang('No')</button>
                        <button type="submit" class="btn btn--primary">@lang('Yes')</button>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection


@push('script')
 <script>
        (function($) {
            "use strict"


            $('.bal-btn').on('click', function() {

                $('.balanceAddSub')[0].reset();

                var act = $(this).data('act');
                $('#addSubModal').find('input[name=act]').val(act);
                if (act == 'add') {
                    $('.type').text('Add');
                } else {
                    $('.type').text('Subtract');
                }
            });

            let mobileElement = $('.mobile-code');
            $('select[name=country]').on('change', function() {
                mobileElement.text(`+${$('select[name=country] :selected').data('mobile_code')}`);
            }).change();

        })(jQuery);
    </script>
@endpush
