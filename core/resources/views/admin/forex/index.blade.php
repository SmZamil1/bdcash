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
                                <th>@lang('Pair Name')</th>
                                <th>@lang('Symbol')</th>
                                <th>@lang('Status')</th>
                                <th>@lang('Action')</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($pairs as $pair)
                                <tr>
                                    <td>
                                        <span class="fw-bold">{{ __($pair->name) }}</span>
                                    </td>
                                    <td>
                                        <span class="fw-bold">{{ $pair->symbol }}</span>
                                    </td>
                                    <td>
                                        @php echo $pair->statusBadge; @endphp
                                    </td>
                                    <td>
                                        <div class="button--group">
                                            <button class="btn btn-sm btn-outline--primary editBtn"
                                                data-id="{{ $pair->id }}"
                                                data-name="{{ $pair->name }}"
                                                data-symbol="{{ $pair->symbol }}">
                                                <i class="la la-pencil"></i> @lang('Edit')
                                            </button>
                                            @if($pair->status == 0)
                                                <button class="btn btn-sm btn-outline--success confirmationBtn" data-question="@lang('Are you sure to enable this pair?')" data-action="{{ route('admin.forex.status', $pair->id) }}">
                                                    <i class="la la-eye"></i> @lang('Enable')
                                                </button>
                                            @else
                                                <button class="btn btn-sm btn-outline--danger confirmationBtn" data-question="@lang('Are you sure to disable this pair?')" data-action="{{ route('admin.forex.status', $pair->id) }}">
                                                    <i class="la la-eye-slash"></i> @lang('Disable')
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                                </tr>
                            @endforelse

                            </tbody>
                        </table><!-- table end -->
                    </div>
                </div>
                @if ($pairs->hasPages())
                <div class="card-footer py-4">
                    {{ paginateLinks($pairs) }}
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
                    <h5 class="modal-title">@lang('Add New Forex Pair')</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="las la-times"></i>
                    </button>
                </div>
                <form action="{{ route('admin.forex.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label>@lang('Name')</label>
                            <input type="text" class="form-control" name="name" placeholder="@lang('e.g. Euro / US Dollar')" required>
                        </div>
                        <div class="form-group">
                            <label>@lang('Symbol')</label>
                            <input type="text" class="form-control" name="symbol" placeholder="@lang('e.g. EURUSD')" required>
                            <small class="text--small text--info">@lang('TradingView symbol compatible: EURUSD, GBPUSD, etc.')</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn--primary w-100">@lang('Submit')</button>
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
                    <h5 class="modal-title">@lang('Edit Forex Pair')</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="las la-times"></i>
                    </button>
                </div>
                <form action="" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label>@lang('Name')</label>
                            <input type="text" class="form-control" name="name" required>
                        </div>
                        <div class="form-group">
                            <label>@lang('Symbol')</label>
                            <input type="text" class="form-control" name="symbol" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn--primary w-100">@lang('Submit')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <x-confirmation-modal />
@endsection

@push('breadcrumb-plugins')
    <a href="{{ route('admin.time.index') }}" class="btn btn-sm btn-outline--info"><i class="las la-clock"></i> @lang('Time Settings')</a>
    <a href="{{ route('admin.forex.setting') }}" class="btn btn-sm btn-outline--info"><i class="las la-cog"></i>@lang('Trading Settings')</a>
    <button class="btn btn-sm btn-outline--primary addBtn"><i class="las la-plus"></i>@lang('Add New Pair')</button>
@endpush

@push('script')
    <script>
        (function($) {
            "use strict";
            $('.addBtn').on('click', function() {
                var modal = $('#addModal');
                modal.modal('show');
            });

            $('.editBtn').on('click', function() {
                var modal = $('#editModal');
                var resource = $(this).data();
                modal.find('form').attr('action', `{{ route('admin.forex.update', '') }}/${resource.id}`);
                modal.find('input[name=name]').val(resource.name);
                modal.find('input[name=symbol]').val(resource.symbol);
                modal.modal('show');
            });
        })(jQuery);
    </script>
@endpush
