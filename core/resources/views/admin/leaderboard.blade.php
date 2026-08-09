@extends('admin.layouts.app')

@section('panel')
<div class="row pt-50">
    {{-- Top Investors --}}
    <div class="col-xl-4 col-md-6 mb-30">
        <div class="card b-radius--10 box--shadow1">
            <div class="card-header border-0">
                <h4 class="card-title text-center text--primary">🏆 Top Investors</h4>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive--sm table-responsive">
                    <table class="table table--light style--two">
                        <thead>
                            <tr>
                                <th>@lang('User')</th>
                                <th>@lang('Invested')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($topInvestors as $invest)
                            <tr>
                                <td>
                                    <span class="user">
                                        <div class="thumb">
                                            <img src="{{ @$invest->user->image ? getImage(getFilePath('userProfile') . '/' . $invest->user->image, getFileSize('userProfile')) : 'https://i.imgur.com/rAPve5a.png' }}" alt="@lang('image')">
                                        </div>
                                        <span class="name">{{ @$invest->user->fullname }}</span>
                                    </span>
                                    <br>
                                    <span class="text--small"><a href="{{ route('admin.users.detail', $invest->user_id) }}"><span>@</span>{{ @$invest->user->username }}</a></span>
                                </td>
                                <td>
                                    <span class="fw-bold text--warning">{{ is_numeric($invest->total_amount) ? showAmount($invest->total_amount) : $invest->total_amount }}</span>
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

    {{-- Top Depositors --}}
    <div class="col-xl-4 col-md-6 mb-30">
        <div class="card b-radius--10 box--shadow1">
            <div class="card-header border-0">
                <h4 class="card-title text-center text--success">💰 Top Depositors</h4>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive--sm table-responsive">
                    <table class="table table--light style--two">
                        <thead>
                            <tr>
                                <th>@lang('User')</th>
                                <th>@lang('Deposited')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($topDepositors as $deposit)
                            <tr>
                                <td>
                                    <span class="user">
                                        <div class="thumb">
                                            <img src="{{ @$deposit->user->image ? getImage(getFilePath('userProfile') . '/' . $deposit->user->image, getFileSize('userProfile')) : 'https://i.imgur.com/rAPve5a.png' }}" alt="@lang('image')">
                                        </div>
                                        <span class="name">{{ @$deposit->user->fullname }}</span>
                                    </span>
                                    <br>
                                    <span class="text--small"><a href="{{ route('admin.users.detail', $deposit->user_id) }}"><span>@</span>{{ @$deposit->user->username }}</a></span>
                                </td>
                                <td>
                                    <span class="fw-bold text--success">{{ is_numeric($deposit->total_amount) ? showAmount($deposit->total_amount) : $deposit->total_amount }}</span>
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

    {{-- Top Withdrawers --}}
    <div class="col-xl-4 col-md-6 mb-30">
        <div class="card b-radius--10 box--shadow1">
            <div class="card-header border-0">
                <h4 class="card-title text-center text--danger">💸 Top Withdrawers</h4>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive--sm table-responsive">
                    <table class="table table--light style--two">
                        <thead>
                            <tr>
                                <th>@lang('User')</th>
                                <th>@lang('Withdrawn')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($topWithdrawers as $withdraw)
                            <tr>
                                <td>
                                    <span class="user">
                                        <div class="thumb">
                                            <img src="{{ @$withdraw->user->image ? getImage(getFilePath('userProfile') . '/' . $withdraw->user->image, getFileSize('userProfile')) : 'https://i.imgur.com/rAPve5a.png' }}" alt="@lang('image')">
                                        </div>
                                        <span class="name">{{ @$withdraw->user->fullname }}</span>
                                    </span>
                                    <br>
                                    <span class="text--small"><a href="{{ route('admin.users.detail', $withdraw->user_id) }}"><span>@</span>{{ @$withdraw->user->username }}</a></span>
                                </td>
                                <td>
                                    <span class="fw-bold text--danger">{{ is_numeric($withdraw->total_amount) ? showAmount($withdraw->total_amount) : $withdraw->total_amount }}</span>
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
@endsection

@push('breadcrumb-plugins')
    <a href="{{ route('admin.dashboard') }}" class="btn btn-sm btn--primary box--shadow1 text--small"><i class="la la-fw la-backward"></i> @lang('Go Back')</a>
@endpush
