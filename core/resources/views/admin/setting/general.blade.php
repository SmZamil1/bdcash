@extends('admin.layouts.app')
@section('panel')
    <div class="row mb-none-30">
        <div class="col-lg-12 col-md-12 mb-30">
            <div class="card">
                <div class="card-body">
                    <form method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-xl-3 col-sm-6">
                                <div class="form-group ">
                                    <label> @lang('Site Title')</label>
                                    <input class="form-control" type="text" name="site_name" required value="{{ gs('site_name') }}">
                                </div>
                            </div>
                            <div class="col-xl-3 col-sm-6">
                                <div class="form-group ">
                                    <label>@lang('Currency')</label>
                                    <input class="form-control" type="text" name="cur_text" required value="{{ gs('cur_text') }}">
                                </div>
                            </div>
                            <div class="col-xl-3 col-sm-6">
                                <div class="form-group ">
                                    <label>@lang('Currency Symbol')</label>
                                    <input class="form-control" type="text" name="cur_sym" required value="{{ gs('cur_sym') }}">
                                </div>
                            </div>
                            <div class="form-group col-xl-3 col-sm-6">
                                <label class="required"> @lang('Timezone')</label>
                                <select class="select2 form-control" name="timezone">
                                    @foreach ($timezones as $key => $timezone)
                                        <option value="{{ @$key }}" @selected(@$key == $currentTimezone)>{{ __($timezone) }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-xl-3 col-sm-6">
                                <div class="form-group">
                                    <label>@lang('Referral Link Parameter (e.g ?ref=)')</label>
                                    <input class="form-control" type="text" name="referral_parameter" required value="{{ gs('referral_parameter') ?? 'reference' }}">
                                </div>
                            </div>

                           
                          
                            <div class="form-group col-xl-3 col-sm-6">
                                <label>@lang('Registration Bonus')</label>
                                <div class="input-group">
                                    <input class="form-control bal-charge" type="text" name="signup_bonus_amount" required value="{{ getAmount(gs('signup_bonus_amount')) }}" min="0" @if (!gs('signup_bonus_control')) readonly @endif>
                                    <div class="input-group-text">{{ gs('cur_text') }}</div>
                                    @if (!gs('signup_bonus_control'))
                                        <small class="text--small text-muted"><i><i class="las la-info-circle"></i> @lang('To give the registration bonus, please enable the module from the') <a href="{{ route('admin.setting.system.configuration') }}" class="text--small">@lang('System Configuration')</a></i></small>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group col-xl-3 col-sm-6">
<label>@lang('Withdrawal Off Days (Click to select days)')</label>
                                <select class="select2-auto-tokenize form-control" name="withdraw_off_days[]" id="withdraw_off_days" multiple>
                                    <option value="sun" @selected(@gs('withdraw_off_days') && in_array('sun', (array)gs('withdraw_off_days')))>@lang('Sunday')</option>
                                    <option value="mon" @selected(@gs('withdraw_off_days') && in_array('mon', (array)gs('withdraw_off_days')))>@lang('Monday')</option>
                                    <option value="tue" @selected(@gs('withdraw_off_days') && in_array('tue', (array)gs('withdraw_off_days')))>@lang('Tuesday')</option>
                                    <option value="wed" @selected(@gs('withdraw_off_days') && in_array('wed', (array)gs('withdraw_off_days')))>@lang('Wednesday')</option>
                                    <option value="thu" @selected(@gs('withdraw_off_days') && in_array('thu', (array)gs('withdraw_off_days')))>@lang('Thursday')</option>
                                    <option value="fri" @selected(@gs('withdraw_off_days') && in_array('fri', (array)gs('withdraw_off_days')))>@lang('Friday')</option>
                                    <option value="sat" @selected(@gs('withdraw_off_days') && in_array('sat', (array)gs('withdraw_off_days')))>@lang('Saturday')</option>
                                </select>
                            </div>
                            <div class="col-xl-3 col-sm-6">
                                <div class="form-group ">
                                    <label>@lang('Balance Transfer System')</label>
                                    <select class="form-control" name="b_transfer">
                                        <option value="1" @selected(gs('b_transfer') == 1)>@lang('Enable')</option>
                                        <option value="0" @selected(gs('b_transfer') == 0)>@lang('Disable')</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-xl-3 col-sm-6">
                                <div class="form-group">
                                    <label>@lang('Balance Transfer Fixed Charge')</label>
                                    <div class="input-group">
                                        <input class="form-control" type="text" name="f_charge" required value="{{ getAmount(gs('f_charge')) }}">
                                        <div class="input-group-text">{{ gs('cur_text') }}</div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-3 col-sm-6">
                                <div class="form-group">
                                    <label>@lang('Balance Transfer Percent Charge')</label>
                                    <div class="input-group">
                                        <input class="form-control" type="text" name="p_charge" required value="{{ getAmount(gs('p_charge')) }}">
                                        <div class="input-group-text">%</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn--primary w-100 h-45">@lang('Submit')</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    <div class="row mb-none-30 mt-4">
        <div class="col-lg-12 col-md-12">
            <div class="card border border--danger">
                 <div class="card-header bg--danger text-white">
                    <h5 class="card-title text-white mb-0">@lang('DANGER ZONE: System Data Reset')</h5>
                </div>
                <div class="card-body">
                    <p class="text-muted">@lang('Clicking the button below will permanently DELETE all Users, Deposits, Withdrawals, Transactions, and Activity Logs. The Database IDs will reset back to 1. This action is irreversible.')</p>
                    <div class="mt-3">
                        <button class="btn btn--danger btn-lg w-100" data-bs-toggle="modal" data-bs-target="#resetModal">
                            <i class="las la-trash-alt"></i> @lang('Reset All Site Data (WIPE ALL)')
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- RESET DATA MODAL --}}
    <div id="resetModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text--danger">@lang('CRITICAL: System Data Wipe Confirmation')</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="las la-times"></i>
                    </button>
                </div>
                <form action="{{ route('admin.system.reset') }}" method="POST">
                    @csrf
                    <div class="modal-body text-center">
                        <div class="text--danger mb-3">
                            <i class="las la-exclamation-triangle" style="font-size: 50px;"></i>
                        </div>
                        <p class="fw-bold">@lang('Are you absolutely SURE you want to DELETE everything?')</p>
                        <p class="text-muted">@lang('All users, transactions, and site activity will be permanently removed. The IDs will reset to 1.')</p>
                        
                        <div class="form-group mt-4 text-start">
                            <label class="required">@lang('Admin Password')</label>
                            <input type="password" name="admin_password" class="form-control" placeholder="@lang('Identify yourself by entering password')" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn--dark" data-bs-dismiss="modal">@lang('Cancel')</button>
                        <button type="submit" class="btn btn--danger">@lang('Yes, WIPE ALL DATA')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <x-confirmation-modal />
@endsection

@push('breadcrumb-plugins')

@endpush


@push('script-lib')
    <script src="{{ asset('assets/admin/js/spectrum.js') }}"></script>
@endpush

@push('style-lib')
    <link rel="stylesheet" href="{{ asset('assets/admin/css/spectrum.css') }}">
@endpush

@push('script')
    <script>
        (function($) {
            "use strict";


            $('.colorPicker').spectrum({
                color: $(this).data('color'),
                change: function(color) {
                    $(this).parent().siblings('.colorCode').val(color.toHexString().replace(/^#?/, ''));
                }
            });

            $('.colorCode').on('input', function() {
                var clr = $(this).val();
                $(this).parents('.input-group').find('.colorPicker').spectrum({
                    color: clr,
                });
            });
        })(jQuery);
    </script>
@endpush
