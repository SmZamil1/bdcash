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
                                    <th>@lang('Day')</th>
                                    <th>@lang('Reward Amount')</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($rewards as $reward)
                                    <tr>
                                        <td><span class="fw-bold">{{ $reward->day }}</span></td>
                                        <td>{{ showAmount($reward->amount) }}</td>
                                        <td>
                                            @if($reward->status)
                                                <span class="badge badge--success">@lang('Active')</span>
                                            @else
                                                <span class="badge badge--warning">@lang('Disabled')</span>
                                            @endif
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-outline--primary editBtn" 
                                                data-reward="{{ $reward }}"
                                                data-action="{{ route('admin.daily_reward.update', $reward->id) }}">
                                                <i class="las la-pen"></i> @lang('Edit')
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Edit Modal --}}
    <div class="modal fade" id="editModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Update Daily Reward')</h5>
                    <button type="button" class="close" data-bs-dismiss="modal">
                        <i class="las la-times"></i>
                    </button>
                </div>
                <form action="" method="post">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label>@lang('Day')</label>
                            <input type="text" class="form-control" name="day" readonly>
                        </div>
                        <div class="form-group">
                            <label>@lang('Reward Amount')</label>
                            <div class="input-group">
                                <input type="number" step="any" name="amount" min="0" class="form-control" required>
                                <span class="input-group-text">{{ gs('cur_text') }}</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>@lang('Status')</label>
                            <select name="status" class="form-control">
                                <option value="1">@lang('Active')</option>
                                <option value="0">@lang('Disabled')</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn--primary w-100 h-45">@lang('Submit')</button>
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
            let modal = $('#editModal');

            $('.editBtn').on('click', function() {
                let reward = $(this).data('reward');
                modal.find('[name=day]').val(reward.day);
                modal.find('[name=amount]').val(parseFloat(reward.amount).toFixed(2));
                modal.find('[name=status]').val(reward.status);
                
                modal.find('form').attr('action', $(this).data('action'));
                modal.modal('show');
            });

        })(jQuery);
    </script>
@endpush
