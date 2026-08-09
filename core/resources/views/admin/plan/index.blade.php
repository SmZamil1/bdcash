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
                                    <th>@lang('Image')</th>
                                    <th>@lang('Name')</th>
                                    <th>@lang('Invest Limit')</th>
                                    <th>@lang('Interest')</th>
                                    <th>@lang('Time')</th>
                                    <th>@lang('Featured')</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($plans as $plan)
                                    <tr>
                                        <td>
                                            @if($plan->image)
                                                <img src="{{ getImage('assets/images/plans/' . $plan->image) }}" style="width: 50px; height: 50px; object-fit: cover; border-radius: 5px;">
                                            @else
                                                <span class="text-muted"><i class="las la-image f-24"></i></span>
                                            @endif
                                        </td>
                                        <td>{{ __($plan->name) }}</td>
                                        <td>
                                            @if ($plan->fixed_amount == 0)
                                                <span>{{ showAmount($plan->minimum) }} - {{ showAmount($plan->maximum) }}</span>
                                            @else
                                                <span>{{ showAmount($plan->fixed_amount) }}</span>
                                            @endif
                                        </td>
                                        <td>{{ showAmount($plan->interest, currencyFormat: false) }} 
                                            @if ($plan->interest_type == 1) % @else {{ gs('cur_text') }} @endif
                                        </td>
                                        <td>{{ @$plan->timeSetting->time }} @lang('Hours')</td>
                                        <td>
                                            @if ($plan->featured == 1)
                                                <span class="badge badge--success">@lang('Yes')</span>
                                            @else
                                                <span class="badge badge--warning">@lang('No')</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($plan->status == 1)
                                                <span class="badge badge--success">@lang('Active')</span>
                                            @else
                                                <span class="badge badge--warning">@lang('Inactive')</span>
                                            @endif
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-outline--primary modalShow me-2" data-type="edit" data-bs-toggle="modal" data-bs-target="#editModal" data-resource="{{ $plan }}" data-action="{{ route('admin.plan.update', $plan->id) }}">
                                                <i class="las la-pen"></i>@lang('Edit')
                                            </button>
                                            @if ($plan->status)
                                                <button class="btn btn-sm btn-outline--danger confirmationBtn" data-question="@lang('Are you sure to disable this plan?')" data-action="{{ route('admin.plan.status', $plan->id) }}">
                                                    <i class="las la-eye-slash"></i>@lang('Disable')
                                                </button>
                                            @else
                                                <button class="btn btn-sm btn-outline--success confirmationBtn" data-question="@lang('Are you sure to enable this plan?')" data-action="{{ route('admin.plan.status', $plan->id) }}">
                                                    <i class="las la-eye"></i>@lang('Enable')
                                                </button>
                                            @endif
                                            <button class="btn btn-sm btn-outline--danger confirmationBtn" data-question="@lang('Are you sure to delete this plan?')" data-action="{{ route('admin.plan.delete', $plan->id) }}">
                                                <i class="las la-trash"></i>@lang('Delete')
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
            </div>
        </div>
    </div>

    {{-- Add Modal --}}
    <div class="modal fade" id="addModal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Add New Plan')</h5>
                    <button type="button" class="close" data-bs-dismiss="modal">
                        <i class="las la-times"></i>
                    </button>
                </div>
                <form action="{{ route('admin.plan.store') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="invest_type" value="2"> {{-- Always Fixed --}}

                    <div class="modal-body">
                        {{-- Name & Amount inline --}}
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('Name')</label>
                                    <input type="text" class="form-control" name="name" required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('Amount')</label>
                                    <div class="input-group">
                                        <input type="number" step="any" class="form-control" name="amount" value="" required>
                                        <span class="input-group-text">{{ gs('cur_text') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        {{-- Image Input --}}
                        <div class="row">
                             <div class="col-md-12">
                                <div class="form-group">
                                    <label>@lang('Plan Image')</label>
                                    <input type="file" class="form-control" name="image" accept="image/*">
                                    <small class="text-muted">Supported: jpeg, jpg, png. Resized to fit card.</small>
                                </div>
                            </div>
                        </div>

                        {{-- Interest & Time --}}
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('Interest type')</label>
                                    <select name="interest_type" class="form-control" required>
                                        <option value="1">@lang('Percent')</option>
                                        <option value="2">@lang('Fixed')</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('Interest')</label>
                                    <div class="input-group">
                                        <input type="number" step="any" class="form-control" name="interest" required>
                                        <span class="input-group-text interest-type"></span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('Time')</label>
                                    <select name="time" class="form-control" required>
                                        <option value="">@lang('Select One')</option>
                                        @foreach ($times as $time)
                                            <option value="{{ $time->id }}">{{ __($time->name) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('Return type')</label>
                                    <select name="return_type" class="form-control" required>
                                        <option value="1">@lang('Lifetime')</option>
                                        <option value="0">@lang('Repeat')</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="repeat-time row"></div>

                        {{-- Compound Interest, Hold Capital, Featured --}}
                        <div class="row">
                            <div class="col-md-6 col-lg-6">
                                <div class="form-group">
                                    <label>@lang('Compound Interest')</label>
                                    <input type="checkbox" data-width="100%" data-onstyle="-success" data-offstyle="-danger" data-bs-toggle="toggle" data-on="@lang('Yes')" data-off="@lang('No')" name="compound_interest">
                                </div>
                            </div>

                            <div class="col-md-6 col-lg-4 holdCapitalGroup">
                                <div class="form-group">
                                    <label>@lang('Hold Capital')</label>
                                    <input type="checkbox" data-width="100%" data-onstyle="-success" data-offstyle="-danger" data-bs-toggle="toggle" data-on="@lang('Yes')" data-off="@lang('No')" name="hold_capital">
                                </div>
                            </div>

                            <div class="col-md-6 col-lg-6">
                                <div class="form-group">
                                    <label>@lang('Plan Opened / Plan Locked')</label>
                                    <input type="checkbox" data-width="100%" data-onstyle="-success" data-offstyle="-danger" data-bs-toggle="toggle" data-on="@lang('Plan Opened')" data-off="@lang('Plan Locked')" name="featured">
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
                    <h5 class="modal-title">@lang('Edit Plan')</h5>
                    <button type="button" class="close" data-bs-dismiss="modal">
                        <i class="las la-times"></i>
                    </button>
                </div>
                <form method="post" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="invest_type" value="2"> {{-- Always Fixed --}}

                                        <div class="modal-body">
                        {{-- Name & Amount inline --}}
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('Name')</label>
                                    <input type="text" class="form-control" name="name" required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('Amount')</label>
                                    <div class="input-group">
                                        <input type="number" step="any" class="form-control" name="amount" value="" required>
                                        <span class="input-group-text">{{ gs('cur_text') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Image Input --}}
                        <div class="row">
                             <div class="col-md-12">
                                <div class="form-group">
                                    <label>@lang('Change Plan Image')</label>
                                    <input type="file" class="form-control" name="image" accept="image/*">
                                    <small class="text-muted">Leave empty to keep current image</small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('Interest type')</label>
                                    <select name="interest_type" class="form-control" required>
                                        <option value="1">@lang('Percent')</option>
                                        <option value="2">@lang('Fixed')</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('Interest')</label>
                                    <div class="input-group">
                                        <input type="number" step="any" class="form-control" name="interest" required>
                                        <span class="input-group-text interest-type"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('Time')</label>
                                    <select name="time" class="form-control" required>
                                        @foreach ($times as $time)
                                            <option value="{{ $time->id }}">{{ __($time->name) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('Return type')</label>
                                    <select name="return_type" class="form-control" required>
                                        <option value="1">@lang('Lifetime')</option>
                                        <option value="0">@lang('Repeat')</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="repeat-time row"></div>

                        <div class="row">
                            <div class="col-md-6 col-lg-6">
                                <div class="form-group">
                                    <label>@lang('Compound Interest')</label>
                                    <input type="checkbox" data-width="100%" data-onstyle="-success" data-offstyle="-danger" data-bs-toggle="toggle" data-on="@lang('Yes')" data-off="@lang('No')" name="compound_interest">
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-4 holdCapitalGroup">
                                <div class="form-group">
                                    <label>@lang('Hold Capital')</label>
                                    <input type="checkbox" data-width="100%" data-onstyle="-success" data-offstyle="-danger" data-bs-toggle="toggle" data-on="@lang('Yes')" data-off="@lang('No')" name="hold_capital">
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-6">
                                <div class="form-group">
                                    <label>@lang('Plan Opened / Plan Locked')</label>
                                    <input type="checkbox" data-width="100%" data-onstyle="-success" data-offstyle="-danger" data-bs-toggle="toggle" data-on="@lang('Plan Opened')" data-off="@lang('Plan Locked')" name="featured">
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
        let plan = new HyipPlan(modal, $(this));

        // Always Fixed
        plan.getInvestType(2);

        modal.find('[name=interest_type]').change(function(){
            plan.getInterestType($(this).val());
        }).change();

        plan.setupEditModal();

        modal.find('[name=return_type]').change(function(){
            plan.getReturnType($(this).val());
        }).change();

        $(modal).on('change', '[name=capital_back]', function(){
            plan.holdCapitalView();
        }).change();
    });

    class HyipPlan {
        constructor(modal, btn){
            this.modal = modal;
            this.btn = btn;
            this.resource = btn.data('resource');
            this.action = btn.data('action');
            this.fixedAmount = '';
            this.minimumAmount = '';
            this.maximumAmount = '';

            if(this.resource){
                if(this.resource.fixed_amount <= 0){
                    this.minimumAmount = parseFloat(this.resource.minimum).toFixed(2);
                    this.maximumAmount = parseFloat(this.resource.maximum).toFixed(2);
                } else {
                    this.fixedAmount = parseFloat(this.resource.fixed_amount).toFixed(2);
                }

                if(this.resource.interest_type == 1){
                    this.modal.find('[name=interest_type]').val(1);
                } else {
                    this.modal.find('[name=interest_type]').val(2);
                }

                if(this.resource.lifetime == 1){
                    this.modal.find('[name=return_type]').val(1);
                } else {
                    this.modal.find('[name=return_type]').val(0);
                }

                if(this.resource.compound_interest == 1){
                    this.modal.find('[name=compound_interest]').bootstrapToggle('on');
                } else {
                    this.modal.find('[name=compound_interest]').bootstrapToggle('off');
                }

                if(this.resource.hold_capital == 1){
                    this.modal.find('[name=hold_capital]').bootstrapToggle('on');
                } else {
                    this.modal.find('[name=hold_capital]').bootstrapToggle('off');
                }

                if(this.resource.featured == 1){
                    this.modal.find('[name=featured]').bootstrapToggle('on');
                } else {
                    this.modal.find('[name=featured]').bootstrapToggle('off');
                }
            }
        }

        getInvestType(type){
            let html = `
                <div class="col-md-12">
                    <div class="form-group">
                        <label class="required">@lang('Amount')</label>
                        <div class="input-group">
                            <input type="number" step="any" class="form-control" name="amount" value="${this.fixedAmount}" required>
                            <span class="input-group-text">{{ gs('cur_text') }}</span>
                        </div>
                    </div>
                </div>
            `;
            this.modal.find('.amount-fields').html(html);
        }

        getInterestType(type){
            this.modal.find('.interest-type').text(type == 1 ? '%' : '{{ gs('cur_text') }}');
        }

        getReturnType(type){
            let html = '';
            if(type == 0){
                html = `
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="required">@lang('Repeat Times')</label>
                            <input type="number" class="form-control" name="repeat_time" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>@lang('Capital back')</label>
                            <select name="capital_back" class="form-control" required>
                                <option value="1">@lang('Yes')</option>
                                <option value="0">@lang('No')</option>
                            </select>
                        </div>
                    </div>
                `;
            }
            this.modal.find('.repeat-time').html(html);
            if(this.resource){
                this.modal.find('[name=repeat_time]').val(this.resource.repeat_time);
                this.modal.find('[name=capital_back]').val(this.resource.capital_back);
            }
            this.holdCapitalView();
        }

        setupEditModal(){
            if(this.resource){
                let modal = this.modal;
                modal.find('[name=name]').val(this.resource.name);
                modal.find('[name=amount]').val(parseFloat(this.resource.fixed_amount).toFixed(2));
                modal.find('[name=interest]').val(parseFloat(this.resource.interest).toFixed(2));
                modal.find('[name=time]').val(this.resource.time_setting_id);
                modal.find('[name=repeat_time]').val(this.resource.repeat_time);
                modal.find('[name=capital_back]').val(this.resource.capital_back);
                modal.find('[name=return_type]').val(this.resource.lifetime);
                modal.find('form').attr('action', this.btn.data('action'));
            }
        }

        holdCapitalView(){
            let modal = this.modal;
            let capitalBack = modal.find('[name=capital_back]').val();
            if(capitalBack == '1'){
                modal.find('[name=compound_interest]').closest('.col-md-6').removeClass('col-lg-6').addClass('col-lg-4');
                modal.find('[name=featured]').closest('.col-md-6').removeClass('col-lg-6').addClass('col-lg-4');
                modal.find('.holdCapitalGroup').show();
            } else {
                modal.find('[name=compound_interest]').closest('.col-md-6').removeClass('col-lg-4').addClass('col-lg-6');
                modal.find('[name=featured]').closest('.col-md-6').removeClass('col-lg-4').addClass('col-lg-6');
                modal.find('.holdCapitalGroup').hide();
                modal.find('[name=hold_capital]').bootstrapToggle('off');
            }
        }
    }

})(jQuery);
</script>
@endpush
