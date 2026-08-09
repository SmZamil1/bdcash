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
                                    <th>@lang('Coin Code')</th>
                                    <th>@lang('Tag')</th>
                                    <th>@lang('Price')</th>
                                    <th>@lang('Return')</th>
                                    <th>@lang('Duration')</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($plans as $plan)
                                    <tr>
                                        <td>{{ __($plan->name) }}</td>
                                        <td>{{ __($plan->coin_code) }}</td>
                                        <td>{{ __($plan->tag) }}</td>
                                        <td>{{ showAmount($plan->price, 0) }}</td>
                                        <td>{{ showAmount($plan->min_return, currencyFormat: false) }} % @lang('Daily')</td>
                                        <td>{{ $plan->duration }} @lang('Days')</td>
                                        <td>
                                            @if ($plan->status == 1)
                                                <span class="badge badge--success">@lang('Active')</span>
                                            @else
                                                <span class="badge badge--warning">@lang('Inactive')</span>
                                            @endif
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-outline--primary modalShow me-2" data-type="edit" data-bs-toggle="modal" data-bs-target="#editModal" data-resource="{{ $plan }}" data-action="{{ route('admin.mining.update', $plan->id) }}">
                                                <i class="las la-pen"></i>@lang('Edit')
                                            </button>
                                            @if ($plan->status)
                                                <button class="btn btn-sm btn-outline--danger confirmationBtn" data-question="@lang('Are you sure to disable this plan?')" data-action="{{ route('admin.mining.status', $plan->id) }}">
                                                    <i class="las la-eye-slash"></i>@lang('Disable')
                                                </button>
                                            @else
                                                <button class="btn btn-sm btn-outline--success confirmationBtn" data-question="@lang('Are you sure to enable this plan?')" data-action="{{ route('admin.mining.status', $plan->id) }}">
                                                    <i class="las la-eye"></i>@lang('Enable')
                                                </button>
                                            @endif
                                            <button class="btn btn-sm btn-outline--danger confirmationBtn" data-question="@lang('Are you sure to delete this plan?')" data-action="{{ route('admin.mining.delete', $plan->id) }}">
                                                <i class="las la-trash"></i>@lang('Delete')
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage ?? 'No data found') }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if($plans->hasPages())
                <div class="card-footer py-4">
                    {{ paginateLinks($plans) }}
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
                    <h5 class="modal-title">@lang('Add New Mining Plan')</h5>
                    <button type="button" class="close" data-bs-dismiss="modal">
                        <i class="las la-times"></i>
                    </button>
                </div>
                <form action="{{ route('admin.mining.store') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('Name')</label>
                                    <input type="text" class="form-control" name="name" required placeholder="e.g. Bitcoin Miner">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('Coin Code')</label>
                                    <input type="text" class="form-control" name="coin_code" required placeholder="e.g. BTC">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('Tag')</label>
                                    <input type="text" class="form-control" name="tag" required placeholder="e.g. SHA-256">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('Price')</label>
                                    <div class="input-group">
                                        <input type="number" step="any" class="form-control" name="price" required>
                                        <span class="input-group-text">{{ gs('cur_text') }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('Daily Return (%)')</label>
                                    <div class="input-group">
                                        <input type="number" step="any" class="form-control" name="min_return" required>
                                        <span class="input-group-text">%</span>
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
                                    <label>@lang('Icon')</label>
                                    <input type="file" class="form-control" name="icon" required accept=".png, .jpg, .jpeg">
                                </div>
                            </div>
                             <div class="col-md-12">
                                <div class="form-group">
                                    <label>@lang('Status')</label>
                                    <input type="checkbox" data-width="100%" data-onstyle="-success" data-offstyle="-danger" data-bs-toggle="toggle" data-on="@lang('Active')" data-off="@lang('Inactive')" name="status" checked>
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
                    <h5 class="modal-title">@lang('Edit Mining Plan')</h5>
                    <button type="button" class="close" data-bs-dismiss="modal">
                        <i class="las la-times"></i>
                    </button>
                </div>
                <form method="post" enctype="multipart/form-data">
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
                                    <label>@lang('Coin Code')</label>
                                    <input type="text" class="form-control" name="coin_code" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('Tag')</label>
                                    <input type="text" class="form-control" name="tag" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('Price')</label>
                                    <div class="input-group">
                                        <input type="number" step="any" class="form-control" name="price" required>
                                        <span class="input-group-text">{{ gs('cur_text') }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('Daily Return (%)')</label>
                                    <div class="input-group">
                                        <input type="number" step="any" class="form-control" name="min_return" required>
                                        <span class="input-group-text">%</span>
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
                                    <label>@lang('Icon')</label>
                                    <div class="mb-2">
                                        <img src="" id="edit_icon_preview" class="rounded" width="50">
                                    </div>
                                    <input type="file" class="form-control" name="icon" accept=".png, .jpg, .jpeg">
                                    <small class="text-muted mt-2">@lang('Leave empty to keep current icon')</small>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>@lang('Status')</label>
                                    <input type="checkbox" data-width="100%" data-onstyle="-success" data-offstyle="-danger" data-bs-toggle="toggle" data-on="@lang('Active')" data-off="@lang('Inactive')" name="status">
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
    <button class="btn btn-outline--primary btn-sm modalShow" data-type="add" data-bs-toggle="modal" data-bs-target="#addModal"><i class="las la-plus"></i> @lang('Add New')</button>
@endpush

@push('script')
<script>
(function($){
    "use strict";

    $('.modalShow').on('click', function(){
        let type = $(this).data('type');
        let modal = type === 'add' ? $('#addModal') : $('#editModal');
        let resource = $(this).data('resource');
        
        if(type === 'edit'){
            modal.find('form').attr('action', $(this).data('action'));
            modal.find('[name=name]').val(resource.name);
            modal.find('[name=coin_code]').val(resource.coin_code);
            modal.find('[name=tag]').val(resource.tag);
            modal.find('[name=price]').val(parseFloat(resource.price).toFixed(2));
            modal.find('[name=min_return]').val(parseFloat(resource.min_return).toFixed(2));
            modal.find('[name=time_setting_id]').val(resource.time_setting_id);
            modal.find('[name=duration]').val(resource.duration);
            
            // Set image preview
            let imagePath = "{{ asset('assets/images/mining') }}/" + resource.icon;
            modal.find('#edit_icon_preview').attr('src', imagePath);
            
            if(resource.status == 1){
                modal.find('[name=status]').bootstrapToggle('on');
            }else{
                modal.find('[name=status]').bootstrapToggle('off');
            }
        }
    });

})(jQuery);
</script>
@endpush
