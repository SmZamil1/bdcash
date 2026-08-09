@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10">
                <div class="card-body p-0">
                    <div class="table-responsive--md table-responsive">
                        <table class="table table--light style--two mb-0">
                            <thead>
                                <tr>
                                    <th>@lang('Name')</th>
                                    <th>@lang('Investment Limit')</th>
                                    <th>@lang('Daily Return')</th>
                                    <th>@lang('Trade Limit')</th>
                                    <th>@lang('Duration')</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($bots as $bot)
                                    <tr>
                                        <td>{{ __($bot->name) }}</td>
                                        <td>{{ showAmount($bot->price) }} - {{ showAmount($bot->max_invest) }}</td>
                                        <td>{{ showAmount($bot->daily_profit) }}%</td>
                                        <td>{{ $bot->daily_trade_limit }}</td>
                                        <td>{{ $bot->duration }} @lang('Days')</td>
                                        <td>
                                            @if ($bot->status == 1)
                                                <span class="badge badge--success">@lang('Active')</span>
                                            @else
                                                <span class="badge badge--warning">@lang('Inactive')</span>
                                            @endif
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-outline--primary modalShow me-2" data-type="edit" data-bs-toggle="modal" data-bs-target="#editModal" data-resource="{{ $bot }}" data-action="{{ route('admin.aibots.update', $bot->id) }}">
                                                <i class="las la-pen"></i>@lang('Edit')
                                            </button>
                                            @if ($bot->status)
                                                <button class="btn btn-sm btn-outline--danger confirmationBtn" data-question="@lang('Are you sure to disable this bot?')" data-action="{{ route('admin.aibots.status', $bot->id) }}">
                                                    <i class="las la-eye-slash"></i>@lang('Disable')
                                                </button>
                                            @else
                                                <button class="btn btn-sm btn-outline--success confirmationBtn" data-question="@lang('Are you sure to enable this bot?')" data-action="{{ route('admin.aibots.status', $bot->id) }}">
                                                     <i class="las la-eye"></i>@lang('Enable')
                                                 </button>
                                             @endif
                                             <button class="btn btn-sm btn-outline--danger confirmationBtn" data-question="@lang('Are you sure to delete this bot?')" data-action="{{ route('admin.aibots.delete', $bot->id) }}">
                                                 <i class="las la-trash"></i>@lang('Delete')
                                             </button>
                                         </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage ?? 'No bots found') }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if($bots->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($bots) }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Add Modal --}}
    <div class="modal fade" id="addModal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Add New AI Bot')</h5>
                    <button type="button" class="close" data-bs-dismiss="modal">
                        <i class="las la-times"></i>
                    </button>
                </div>
                <form action="{{ route('admin.aibots.store') }}" method="post">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('Name')</label>
                                    <input type="text" class="form-control" name="name" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('Min Investment Amount')</label>
                                    <div class="input-group">
                                        <input type="number" step="any" class="form-control" name="price" required>
                                        <span class="input-group-text">{{ gs('cur_text') }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('Max Investment Amount')</label>
                                    <div class="input-group">
                                        <input type="number" step="any" class="form-control" name="max_invest" required>
                                        <span class="input-group-text">{{ gs('cur_text') }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('Return Interval')</label>
                                    <select class="form-control" name="time_setting_id" required>
                                        <option value="">@lang('Select One')</option>
                                        @foreach($timeSettings as $time)
                                            <option value="{{ $time->id }}">{{ __($time->name) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('Duration (Days)')</label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" name="duration" required>
                                        <span class="input-group-text">@lang('Days')</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>@lang('Daily Trade Limit') <small class="text-danger">(@lang('0 = Unlimited'))</small></label>
                                    <input type="number" class="form-control" name="daily_trade_limit" value="0" required>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>@lang('Daily Profit') (%)</label>
                                    <div class="input-group">
                                        <input type="number" step="any" class="form-control" name="daily_profit" required>
                                        <span class="input-group-text">%</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn--primary w-100 h-45">@lang('Submit')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Edit Modal --}}
    <div class="modal fade" id="editModal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Edit AI Bot')</h5>
                    <button type="button" class="close" data-bs-dismiss="modal">
                        <i class="las la-times"></i>
                    </button>
                </div>
                <form method="post">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('Name')</label>
                                    <input type="text" class="form-control" name="name" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('Min Investment Amount')</label>
                                    <div class="input-group">
                                        <input type="number" step="any" class="form-control" name="price" required>
                                        <span class="input-group-text">{{ gs('cur_text') }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('Max Investment Amount')</label>
                                    <div class="input-group">
                                        <input type="number" step="any" class="form-control" name="max_invest" required>
                                        <span class="input-group-text">{{ gs('cur_text') }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('Return Interval')</label>
                                    <select class="form-control" name="time_setting_id" required>
                                        <option value="">@lang('Select One')</option>
                                        @foreach($timeSettings as $time)
                                            <option value="{{ $time->id }}">{{ __($time->name) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('Duration (Days)')</label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" name="duration" required>
                                        <span class="input-group-text">@lang('Days')</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>@lang('Daily Trade Limit') <small class="text-danger">(@lang('0 = Unlimited'))</small></label>
                                    <input type="number" class="form-control" name="daily_trade_limit" value="0" required>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>@lang('Daily Profit') (%)</label>
                                    <div class="input-group">
                                        <input type="number" step="any" class="form-control" name="daily_profit" required>
                                        <span class="input-group-text">%</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn--primary w-100 h-45">@lang('Submit')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <x-confirmation-modal />
@endsection

@push('breadcrumb-plugins')
    <a href="{{ route('admin.time.index') }}" class="btn btn-sm btn-outline--info me-2"><i class="las la-clock"></i> @lang('Time Settings')</a>
    @if($botCount < 1)
        <button class="btn btn-outline--primary btn-sm modalShow" data-type="add" data-bs-toggle="modal" data-bs-target="#addModal"><i class="las la-plus"></i> @lang('Add New')</button>
    @endif
@endpush

@push('script')
<script>
    (function($){
        "use strict";

        $('.modalShow').on('click', function(){
            let type = $(this).data('type');
            let modal = type === 'add' ? $('#addModal') : $('#editModal');
            
            if(type === 'edit'){
                let resource = $(this).data('resource');
                let action = $(this).data('action');

                modal.find('form').attr('action', action);
                modal.find('[name=name]').val(resource.name);
                modal.find('[name=price]').val(parseFloat(resource.price).toFixed(2));
                modal.find('[name=max_invest]').val(parseFloat(resource.max_invest).toFixed(2));
                modal.find('[name=daily_trade_limit]').val(resource.daily_trade_limit);
                modal.find('[name=time_setting_id]').val(resource.time_setting_id);
                modal.find('[name=duration]').val(resource.duration);
                // Profit Type removed from UI, hardcoded in backend
                modal.find('[name=daily_profit]').val(parseFloat(resource.daily_profit).toFixed(2));
            }
        });
    })(jQuery);
</script>
@endpush
