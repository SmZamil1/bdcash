@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <form action="{{ route('admin.games.update', $game->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <div class="image-upload">
                                        <div class="thumb">
                                            <div class="avatar-preview">
                                                <div class="profilePicPreview" style="background-image: url({{ getImage('assets/games/'.$game->image, '300x300') }})">
                                                    <button type="button" class="remove-image"><i class="fa fa-times"></i></button>
                                                </div>
                                            </div>
                                            <div class="avatar-edit">
                                                <input type="file" class="profilePicUpload" name="image" id="profilePicUpload1" accept=".png, .jpg, .jpeg">
                                                <label for="profilePicUpload1" class="bg--primary">@lang('Upload Image')</label>
                                                <small class="mt-2 text-facebook">@lang('Supported files'): <b>@lang('jpeg'), @lang('jpg'), @lang('png')</b>. @lang('Image will be resized into 300x300px') </small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>@lang('Game Name')</label>
                                            <input type="text" name="name" class="form-control" value="{{ $game->name }}" required>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>@lang('Win Chance') (%)</label>
                                            <div class="input-group">
                                                <input type="number" step="0.01" name="win_chance" class="form-control" value="{{ $game->win_chance }}" required>
                                                <div class="input-group-append">
                                                    <span class="input-group-text">%</span>
                                                </div>
                                            </div>
                                            <small class="text-muted">@lang('Set the winning probability (0-100%)')</small>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>@lang('Invest Limit')</label>
                                            <div class="input-group">
                                                 <input type="number" step="any" name="min_bet" class="form-control" value="{{ $game->min_bet }}" required>
                                                 <input type="number" step="any" name="max_bet" class="form-control" value="{{ $game->max_bet }}" required>
                                            </div>
                                            <small class="text-muted">@lang('Min Bet - Max Bet')</small>
                                        </div>
                                    </div>
                                    
                                    @if($game->game_key == 'aviator')
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>@lang('Max Multiplier')</label>
                                            <input type="number" step="0.01" name="max_multiplier" class="form-control" value="{{ $game->max_multiplier }}">
                                            <small class="text-muted">@lang('The crash plane will not exceed this multiplier')</small>
                                        </div>
                                    </div>
                                    @endif

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>@lang('Bet Options')</label>
                                            <textarea name="bet_options" class="form-control" rows="3">{{ $game->bet_options }}</textarea>
                                            <small class="text-muted">@lang('JSON options for additional settings if needed')</small>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                         <div class="form-group p-3 border rounded">
                                            <label>@lang('Status')</label>
                                            <input type="checkbox" data-width="100%" data-onstyle="-success" data-offstyle="-danger"
                                                   data-toggle="toggle" data-on="@lang('Enable')" data-off="@lang('Disable')" name="status"
                                                   @if($game->status) checked @endif>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn--primary btn-block">@lang('Submit')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
