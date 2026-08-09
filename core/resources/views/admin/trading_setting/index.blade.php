@extends('admin.layouts.app')
@section('panel')
    <div class="row mb-none-30">
        <div class="col-lg-12 col-md-12 mb-30">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.trading.setting.update') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-3 col-sm-6">
                                <div class="form-group">
                                    <label>@lang('Profit Percentage')</label>
                                    <div class="input-group">
                                        <input class="form-control" type="number" name="profit" required value="{{ @gs('trading_setting')->profit }}" min="0" max="100" step="any">
                                        <div class="input-group-text">%</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6">
                                <div class="form-group">
                                    <label>@lang('Minimum Trade Amount')</label>
                                    <div class="input-group">
                                        <input class="form-control" type="number" name="min_amount" required value="{{ @gs('trading_setting')->min_amount }}" min="0" step="any">
                                        <div class="input-group-text">{{ gs('cur_text') }}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6">
                                <div class="form-group">
                                    <label>@lang('Maximum Trade Amount')</label>
                                    <div class="input-group">
                                        <input class="form-control" type="number" name="max_amount" required value="{{ @gs('trading_setting')->max_amount }}" min="0" step="any">
                                        <div class="input-group-text">{{ gs('cur_text') }}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6">
                                <div class="form-group">
                                    <label>@lang('Daily Trade Limit')</label>
                                    <div class="input-group">
                                        <input class="form-control" type="number" name="daily_limit" required value="{{ @gs('trading_setting')->daily_limit }}" min="0" step="any">
                                        <div class="input-group-text">{{ gs('cur_text') }}</div>
                                    </div>
                                    <small class="text-muted"><i>@lang('User can trade maximum this amount per day. 0 for unlimited.')</i></small>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>@lang('Trade Duration Options (Minutes)')</label>
                                    <small class="text-muted"><i>@lang('Comma separated values, e.g. 1,3,5,15')</i></small>
                                    <input class="form-control" type="text" name="time_setting" required value="{{ @gs('trading_setting')->time_setting }}" placeholder="1,3,5,15">
                                </div>
                            </div>
                        </div>

                        <div class="form-group mt-3">
                            <button type="submit" class="btn btn--primary w-100 h-45">@lang('Submit')</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('breadcrumb-plugins')
    <a href="{{ route('admin.time.index') }}" class="btn btn-sm btn-outline--info"><i class="las la-clock"></i> @lang('Time Settings')</a>
@endpush
