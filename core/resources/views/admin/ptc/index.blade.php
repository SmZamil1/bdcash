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
                                <th>@lang('Title')</th>
                                <th>@lang('Type')</th>
                                <th>@lang('Duration')</th>
                                <th>@lang('Amount')</th>
                                <th>@lang('Views')</th>
                                <th>@lang('Status')</th>
                                <th>@lang('Action')</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($ads as $ad)
                                <tr>
                                    <td>{{ __($ad->title) }}</td>
                                    <td>
                                        @if($ad->ad_type == 1)
                                            <span class="badge badge--primary">@lang('Link/URL')</span>
                                        @elseif($ad->ad_type == 2)
                                            <span class="badge badge--info">@lang('Youtube')</span>
                                        @elseif($ad->ad_type == 3)
                                            <span class="badge badge--success">@lang('Image')</span>
                                        @endif
                                    </td>
                                    <td>{{ $ad->duration }} @lang('sec')</td>
                                    <td>{{ showAmount($ad->amount) }}</td>
                                    <td>{{ $ad->showed }} / {{ $ad->max_show }}</td>
                                    <td>
                                        @php echo $ad->status == 1 ? '<span class="badge badge--success">'.trans('Active').'</span>' : '<span class="badge badge--danger">'.trans('Inactive').'</span>'; @endphp
                                    </td>
                                    <td>
                                        <div class="button--group">
                                            <a href="{{ route('admin.ptc.edit', $ad->id) }}" class="btn btn-sm btn-outline--primary">
                                                <i class="la la-pencil"></i> @lang('Edit')
                                            </a>
                                            @if($ad->status == 0)
                                                <button class="btn btn-sm btn-outline--success confirmationBtn" data-question="@lang('Are you sure to enable this ad?')" data-action="{{ route('admin.ptc.status', $ad->id) }}">
                                                    <i class="la la-eye"></i> @lang('Enable')
                                                </button>
                                            @else
                                                <button class="btn btn-sm btn-outline--danger confirmationBtn" data-question="@lang('Are you sure to disable this ad?')" data-action="{{ route('admin.ptc.status', $ad->id) }}">
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
                @if ($ads->hasPages())
                <div class="card-footer py-4">
                    {{ paginateLinks($ads) }}
                </div>
                @endif
            </div>
        </div>
    </div>
    <x-confirmation-modal />
@endsection

@push('breadcrumb-plugins')
    <a href="{{ route('admin.ptc.create') }}" class="btn btn-sm btn-outline--primary"><i class="las la-plus"></i>@lang('Add New Ad')</a>
@endpush
