@extends('admin.layouts.app')
@section('panel')
    <div class="row mb-4">
        <div class="col-lg-12">
            <div class="alert alert-info d-flex align-items-center" role="alert">
                <i class="las la-info-circle me-2 text-2xl"></i>
                <div>
                     <h5 class="alert-heading font-bold mb-1">@lang('About This List')</h5>
                     <p>@lang('This system finds <strong>IP Addresses</strong> that are used by multiple different accounts. This helps detect multi-account cheating.')</p>
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
                                    <th>@lang('IP Address')</th>
                                    <th>@lang('Account Count')</th>
                                    <th>@lang('Users Using This IP')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($groups as $group)
                                    <tr>
                                        <td>
                                            <span class="fw-bold">{{ $group->user_ip }}</span>
                                        </td>
                                        <td>
                                            <span class="badge badge--danger">{{ $group->user_count }} @lang('Accounts')</span>
                                        </td>
                                        <td>
                                            <div class="d-flex flex-wrap gap-2">
                                            @foreach($group->users as $user)
                                                <a href="{{ route('admin.users.detail', $user->id) }}" class="btn btn-sm btn-outline--primary">
                                                    {{ $user->username }}
                                                </a>
                                            @endforeach
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
                @if ($groups->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($groups) }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
