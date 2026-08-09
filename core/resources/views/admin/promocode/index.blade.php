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
                                    <th>@lang('Code')</th>
                                    <th>@lang('Amount')</th>
                                    <th>@lang('Wallet')</th>
                                    <th>@lang('Limit / Used')</th>
                                    <th>@lang('Frequency')</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($promocodes as $promo)
                                    <tr>
                                        <td>
                                            <span class="fw-bold">{{ $promo->code }}</span>
                                        </td>
                                        <td>
                                            {{ showAmount($promo->amount) }}
                                        </td>
                                        <td>
                                            @if($promo->wallet_type == 'interest_wallet')
                                                <span class="badge badge--primary">@lang('Interest Wallet')</span>
                                            @else
                                                <span class="badge badge--info">@lang('Deposit Wallet')</span>
                                            @endif
                                        </td>
                                        <td>
                                            {{ $promo->usage_limit == 0 ? __('Unlimited') : $promo->usage_limit }} / {{ $promo->used_count }}
                                        </td>
                                        <td>
                                            <span class="badge badge--dark">{{ ucfirst(str_replace('_', ' ', $promo->frequency)) }}</span>
                                        </td>
                                        <td>
                                            @if($promo->status)
                                                <span class="badge badge--success">@lang('Active')</span>
                                            @else
                                                <span class="badge badge--warning">@lang('Disabled')</span>
                                            @endif
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-outline--primary editBtn me-1" 
                                                data-promo="{{ $promo }}"
                                                data-action="{{ route('admin.promocode.update', $promo->id) }}">
                                                <i class="las la-pen"></i> @lang('Edit')
                                            </button>
                                            
                                            <button class="btn btn-sm btn-outline--{{ $promo->status ? 'warning' : 'success' }} confirmationBtn" 
                                                data-question="@lang('Are you sure to change status?')" 
                                                data-action="{{ route('admin.promocode.status', $promo->id) }}">
                                                <i class="las la-sync"></i> {{ $promo->status ? __('Disable') : __('Enable') }}
                                            </button>

                                            <button class="btn btn-sm btn-outline--danger confirmationBtn" 
                                                data-question="@lang('Are you sure to delete this promo code?')" 
                                                data-action="{{ route('admin.promocode.remove', $promo->id) }}">
                                                <i class="las la-trash"></i> @lang('Delete')
                                            </button>
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
                @if($promocodes->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($promocodes) }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Create/Edit Modal --}}
    <div class="modal fade" id="promoModal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Create Promo Code')</h5>
                    <button type="button" class="close" data-bs-dismiss="modal">
                        <i class="las la-times"></i>
                    </button>
                </div>
                <form action="{{ route('admin.promocode.store') }}" method="post">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label>@lang('Promo Code')</label>
                            <div class="input-group">
                                <input type="text" class="form-control" name="code" placeholder="@lang('Leave empty to auto-generate')">
                                <span class="input-group-text cursor-pointer" onclick="generateCode()">
                                    <i class="las la-random"></i> @lang('Generate')
                                </span>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('Reward Amount')</label>
                                    <div class="input-group">
                                        <input type="number" step="any" name="amount" min="0" class="form-control" required>
                                        <span class="input-group-text">{{ gs('cur_text') }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('Credit To Wallet')</label>
                                    <select name="wallet_type" class="form-control" required>
                                        <option value="interest_wallet">@lang('Interest Wallet')</option>
                                        <option value="interest_wallet">@lang('Deposit Wallet')</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('Global Usage Limit') <small class="text-muted">(@lang('0 for Unlimited'))</small></label>
                                    <input type="number" name="usage_limit" min="0" class="form-control" value="0" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('User Frequency')</label>
                                    <select name="frequency" class="form-control" required>
                                        <option value="one_time">@lang('One Time Per User')</option>
                                        <option value="daily">@lang('Daily')</option>
                                        <option value="weekly">@lang('Weekly')</option>
                                        <option value="monthly">@lang('Monthly')</option>
                                    </select>
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
    <button class="btn btn-outline--primary btn-sm addBtn"><i class="las la-plus"></i> @lang('Add New')</button>
@endpush

@push('script')
    <script>
        function generateCode() {
            let result = '';
            let characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
            for (let i = 0; i < 8; i++) {
                result += characters.charAt(Math.floor(Math.random() * characters.length));
            }
            $('input[name=code]').val(result);
        }

        (function($) {
            "use strict"
            let modal = $('#promoModal');
            let action = `{{ route('admin.promocode.store') }}`;

            $('.addBtn').on('click', function() {
                modal.find('form').attr('action', action);
                modal.find('.modal-title').text("@lang('Create Promo Code')");
                $('input[name=code]').attr('readonly', false);
                modal.modal('show');
                modal.find('form')[0].reset();
            });

            $('.editBtn').on('click', function() {
                let promo = $(this).data('promo');
                modal.find('[name=code]').val(promo.code).attr('readonly', true);
                modal.find('[name=amount]').val(parseFloat(promo.amount).toFixed(2));
                modal.find('[name=wallet_type]').val(promo.wallet_type);
                modal.find('[name=usage_limit]').val(promo.usage_limit);
                modal.find('[name=frequency]').val(promo.frequency);
                
                modal.find('form').attr('action', $(this).data('action'));
                modal.find('.modal-title').text("@lang('Update Promo Code')");
                modal.modal('show');
            });

        })(jQuery);
    </script>
@endpush
