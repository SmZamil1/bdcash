@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10 ">
                <div class="card-body p-0">
                    <div class="table-responsive--md  table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                            <tr>
                                <th>@lang('Name')</th>
                                <th>@lang('Price')</th>
                                <th>@lang('Daily Ad Limit')</th>
                                <th>@lang('Validity')</th>
                                <th>@lang('Status')</th>
                                <th>@lang('Action')</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($plans as $plan)
                                <tr>
                                    <td>{{ __($plan->name) }}</td>
                                    <td>{{ showAmount($plan->price) }}</td>
                                    <td>{{ $plan->daily_ad_limit }} @lang('Ads')</td>
                                    <td>{{ $plan->validity }} @lang('Days')</td>
                                    <td>
                                        @php echo $plan->status == 1 ? '<span class="badge badge--success">'.trans('Active').'</span>' : '<span class="badge badge--danger">'.trans('Inactive').'</span>'; @endphp
                                    </td>
                                    <td>
                                        <div class="button--group">
                                            <button class="btn btn-sm btn-outline--primary editBtn" 
                                                data-id="{{ $plan->id }}"
                                                data-name="{{ $plan->name }}"
                                                data-price="{{ getAmount($plan->price) }}"
                                                data-daily_ad_limit="{{ $plan->daily_ad_limit }}"
                                                data-validity="{{ $plan->validity }}">
                                                <i class="la la-pencil"></i> @lang('Edit')
                                            </button>
                                            @if($plan->status == 0)
                                                <button class="btn btn-sm btn-outline--success confirmationBtn" data-question="@lang('Are you sure to enable this plan?')" data-action="{{ route('admin.vip.status', $plan->id) }}">
                                                    <i class="la la-eye"></i> @lang('Enable')
                                                </button>
                                            @else
                                                <button class="btn btn-sm btn-outline--danger confirmationBtn" data-question="@lang('Are you sure to disable this plan?')" data-action="{{ route('admin.vip.status', $plan->id) }}">
                                                    <i class="la la-eye-slash"></i> @lang('Disable')
                                                </button>
                                            @endif
                                            <button class="btn btn-sm btn-outline--danger confirmationBtn" data-question="@lang('Are you sure to delete this plan?')" data-action="{{ route('admin.vip.delete', $plan->id) }}">
                                                <i class="la la-trash"></i> @lang('Delete')
                                            </button>
                                        </div>
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
                @if ($plans->hasPages())
                <div class="card-footer py-4">
                    {{ paginateLinks($plans) }}
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Add Modal --}}
    <div id="addModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Add New VIP Plan')</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="las la-times"></i>
                    </button>
                </div>
                <form action="{{ route('admin.vip.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label>@lang('Name')</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>@lang('Price')</label>
                            <div class="input-group">
                                <input type="number" step="any" name="price" class="form-control" required>
                                <span class="input-group-text">{{ gs('cur_text') }}</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>@lang('Daily Ad Limit')</label>
                            <input type="number" name="daily_ad_limit" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>@lang('Validity (Days)')</label>
                            <input type="number" name="validity" class="form-control" required>
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
    <div id="editModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Edit VIP Plan')</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="las la-times"></i>
                    </button>
                </div>
                <form action="" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label>@lang('Name')</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>@lang('Price')</label>
                            <div class="input-group">
                                <input type="number" step="any" name="price" class="form-control" required>
                                <span class="input-group-text">{{ gs('cur_text') }}</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>@lang('Daily Ad Limit')</label>
                            <input type="number" name="daily_ad_limit" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>@lang('Validity (Days)')</label>
                            <input type="number" name="validity" class="form-control" required>
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
    <button class="btn btn-sm btn-outline--primary" data-bs-toggle="modal" data-bs-target="#addModal"><i class="las la-plus"></i>@lang('Add New Plan')</button>
@endpush

@push('script')
<script>
    (function($){
        "use strict";
        $('.editBtn').on('click', function(){
            var modal = $('#editModal');
            var data = $(this).data();
            modal.find('form').attr('action', `{{ route('admin.vip.update', '') }}/${data.id}`);
            modal.find('input[name="name"]').val(data.name);
            modal.find('input[name="price"]').val(data.price);
            modal.find('input[name="daily_ad_limit"]').val(data.daily_ad_limit);
            modal.find('input[name="validity"]').val(data.validity);
            modal.modal('show');
        });
    })(jQuery);
</script>
@endpush
