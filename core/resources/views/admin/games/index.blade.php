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
                                <th>@lang('Game Name')</th>
                                <th>@lang('Win Chance')</th>
                                <th>@lang('Min Bet')</th>
                                <th>@lang('Max Bet')</th>
                                <th>@lang('Status')</th>
                                <th>@lang('Action')</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($games as $game)
                                <tr>
                                    <td data-label="@lang('Game Name')">
                                        <div class="user">
                                            <div class="thumb">
                                                <img src="{{ getImage('assets/games/'.$game->image, '300x300') }}" alt="@lang('image')">
                                            </div>
                                            <span class="name">{{ __($game->name) }}</span>
                                        </div>
                                    </td>
                                    <td data-label="@lang('Win Chance')">{{ $game->win_chance }}%</td>
                                    <td data-label="@lang('Min Bet')">{{ showAmount($game->min_bet) }} {{ __($general->cur_text) }}</td>
                                    <td data-label="@lang('Max Bet')">{{ showAmount($game->max_bet) }} {{ __($general->cur_text) }}</td>
                                    <td data-label="@lang('Status')">
                                        @if($game->status == 1)
                                            <span class="text--small badge font-weight-normal badge--success">@lang('Active')</span>
                                        @else
                                            <span class="text--small badge font-weight-normal badge--warning">@lang('Inactive')</span>
                                        @endif
                                    </td>
                                    <td data-label="@lang('Action')">
                                        <a href="{{ route('admin.games.edit', $game->id) }}" class="icon-btn ml-1" data-toggle="tooltip" title="@lang('Edit')">
                                            <i class="la la-pen"></i>
                                        </a>
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
