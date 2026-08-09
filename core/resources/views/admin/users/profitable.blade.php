@extends('admin.layouts.app')
@section('panel')
    <div class="row mb-4">
        <div class="col-lg-12">
            <div class="alert alert-info d-flex align-items-center" role="alert">
                <i class="las la-info-circle me-2 text-2xl"></i>
                <div>
                    <h5 class="alert-heading font-bold mb-1">@lang('About This List')</h5>
                    <p class="mb-0">
                        @lang('This list shows users who are in <strong>Profit</strong>. They have earned more money than they deposited.')
                    </p>
                    <hr>
                    <p class="mb-0 text-sm">
                        &bull; <strong>@lang('Total Deposit'):</strong> @lang('Total money they put in.') <br>
                        &bull; <strong>@lang('Total Profit'):</strong> @lang('Total money they earned from plans.') <br>
                        &bull; <strong>@lang('Ref Commission'):</strong> @lang('Earnings from inviting friends.')
                    </p>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10 ">
                <div class="card-body p-0">
                    <div class="table-responsive--md  table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                                <tr>
                                    <th>@lang('User')</th>
                                    <th>@lang('Email-Phone')</th>
                                    <th>@lang('Total Deposit')</th>
                                    <th>@lang('Total Profit')</th>
                                    <th>@lang('Ref Commission')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($users as $user)
                                    <tr>
                                        <td>
                                            <span class="fw-bold">{{ $user->fullname }}</span>
                                            <br>
                                            <span class="small">
                                                <a href="{{ route('admin.users.detail', $user->id) }}"><span>@</span>{{ $user->username }}</a>
                                            </span>
                                        </td>
                                        <td>
                                            {{ $user->email }}<br>
                                            <a href="https://wa.me/{{ $user->mobile }}" target="_blank" class="text--primary" title="@lang('Chat on WhatsApp')">
                                                <i class="fab fa-whatsapp"></i> {{ $user->mobile }}
                                            </a>
                                        </td>
                                        <td>
                                            <span class="fw-bold text--success">{{ showAmount($user->deposits_sum_amount, 2, true, false, false) }} {{ __(gs()->cur_text) }}</span>
                                        </td>
                                        <td>
                                            <span class="fw-bold text--primary">{{ showAmount($user->total_profit, 2, true, false, false) }} {{ __(gs()->cur_text) }}</span>
                                        </td>
                                        <td>
                                            <span class="fw-bold text--info">{{ showAmount($user->total_commission, 2, true, false, false) }} {{ __(gs()->cur_text) }}</span>
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.users.detail', $user->id) }}" class="btn btn-sm btn-outline--primary">
                                                <i class="las la-desktop"></i> @lang('Details')
                                            </a>
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
                @if ($users->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($users) }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('breadcrumb-plugins')
    <x-search-form placeholder="Username / Email" />
@endpush
