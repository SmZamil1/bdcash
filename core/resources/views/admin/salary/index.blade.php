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
                                    <th>@lang('Min Team Invest')</th>
                                    <th>@lang('Max Team Invest')</th>
                                    <th>@lang('Reward Rate (%)')</th>
                                    <th>@lang('Frequency')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($salaries as $salary)
                                    <tr>
                                        <td>{{ __($salary->name) }}</td>
                                        <td>{{ showAmount($salary->min_turnover) }}</td>
                                        <td>{{ showAmount($salary->max_turnover) }}</td>
                                        <td>{{ showAmount($salary->amount, currencyFormat: false) }}%</td>
                                        <td><span class="badge badge--success">@lang('Weekly')</span></td>
                                        <td>
                                            <button class="btn btn-sm btn-outline--primary editBtn me-1" 
                                                data-salary="{{ $salary }}"
                                                data-action="{{ route('admin.salary.update', $salary->id) }}">
                                                <i class="las la-pen"></i> @lang('Edit')
                                            </button>
                                            
                                            <button class="btn btn-sm btn-outline--danger confirmationBtn" 
                                                data-question="@lang('Are you sure to delete this salary level?')" 
                                                data-action="{{ route('admin.salary.remove', $salary->id) }}">
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
            </div>
        </div>
    </div>

    <div class="modal fade" id="salaryModal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Add New Salary Level')</h5>
                    <button type="button" class="close" data-bs-dismiss="modal">
                        <i class="las la-times"></i>
                    </button>
                </div>
                <form action="{{ route('admin.salary.store') }}" method="post">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label>@lang('Name')</label>
                            <input type="text" class="form-control" name="name" required>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('Minimum Team Investment')</label>
                                    <div class="input-group">
                                        <input type="number" step="any" name="min_turnover" min="0" class="form-control" required>
                                        <span class="input-group-text">{{ gs('cur_text') }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('Maximum Team Investment')</label>
                                    <div class="input-group">
                                        <input type="number" step="any" name="max_turnover" min="0" class="form-control" required>
                                        <span class="input-group-text">{{ gs('cur_text') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>@lang('Reward Rate (Weekly %)')</label>
                            <div class="input-group">
                                <input type="number" step="any" name="amount" min="0" class="form-control" required>
                                <span class="input-group-text">%</span>
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
        (function($) {
            "use strict"
            let modal = $('#salaryModal');
            let action = `{{ route('admin.salary.store') }}`;

            $('.addBtn').on('click', function() {
                modal.find('form').attr('action', action);
                modal.find('.modal-title').text("@lang('Add New Salary Level')");
                modal.modal('show');
                modal.find('form')[0].reset();
            });

            $('.editBtn').on('click', function() {
                let salary = $(this).data('salary');
                modal.find('[name=name]').val(salary.name);
                modal.find('[name=min_turnover]').val(parseFloat(salary.min_turnover).toFixed(2));
                modal.find('[name=max_turnover]').val(parseFloat(salary.max_turnover).toFixed(2));
                modal.find('[name=amount]').val(parseFloat(salary.amount).toFixed(2));
                
                modal.find('form').attr('action', $(this).data('action'));
                modal.find('.modal-title').text("@lang('Update Salary Level')");
                modal.modal('show');
            });

        })(jQuery);
    </script>
@endpush
