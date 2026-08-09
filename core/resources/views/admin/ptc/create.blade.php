@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <form action="{{ route('admin.ptc.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label>@lang('Title')</label>
                                <input type="text" name="title" class="form-control" value="{{ old('title') }}" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label>@lang('Amount')</label>
                                <div class="input-group">
                                    <input type="number" step="any" name="amount" class="form-control" value="{{ old('amount') }}" required>
                                    <span class="input-group-text">{{ gs('cur_text') }}</span>
                                </div>
                            </div>
                            <div class="form-group col-md-4">
                                <label>@lang('Duration (Seconds)')</label>
                                <div class="input-group">
                                    <input type="number" name="duration" class="form-control" value="{{ old('duration') }}" required>
                                    <span class="input-group-text">@lang('Seconds')</span>
                                </div>
                            </div>
                            <div class="form-group col-md-4">
                                <label>@lang('Maximum Show')</label>
                                <input type="number" name="max_show" class="form-control" value="{{ old('max_show') }}" required>
                            </div>
                            <div class="form-group col-md-4">
                                <label>@lang('Ad Type')</label>
                                <select name="ad_type" class="form-control" required>
                                    <option value="1">@lang('Link / URL')</option>
                                    <option value="2">@lang('Youtube Video')</option>
                                    <option value="3">@lang('Image')</option>
                                </select>
                            </div>
                            
                            <div class="form-group col-md-12" id="url_field">
                                <label>@lang('URL / Link')</label>
                                <input type="text" name="url" class="form-control" value="{{ old('url') }}">
                                <small class="text--info" id="yt_hint" style="display:none;">@lang('Enter Youtube Video Code Only (e.g. XXXXXX)')</small>
                            </div>

                            <div class="form-group col-md-12" id="image_field" style="display:none;">
                                <label>@lang('Ad Image')</label>
                                <input type="file" name="image" class="form-control" accept="image/*">
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-end">
                        <button type="submit" class="btn btn--primary h-45 w-100">@lang('Submit')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('script')
<script>
    (function($){
        "use strict";
        $('select[name="ad_type"]').on('change', function(){
            var type = $(this).val();
            if(type == 1){
                $('#url_field').show();
                $('#image_field').hide();
                $('#yt_hint').hide();
                $('label[for="url"]').text('URL / Link');
            } else if(type == 2){
                $('#url_field').show();
                $('#image_field').hide();
                $('#yt_hint').show();
                $('label[for="url"]').text('Youtube Embed Link');
            } else if(type == 3){
                $('#url_field').hide();
                $('#image_field').show();
            }
        }).change();

        $('form').on('submit', function(e){
            var type = $('select[name="ad_type"]').val();
            if(type == 2){
                var url = $('input[name="url"]').val();
                var regExp = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|\&v=)([^#\&\?]*).*/;
                var match = url.match(regExp);
                
                if (match && match[2].length == 11) {
                    $('input[name="url"]').val('https://www.youtube.com/embed/' + match[2]);
                } else if(url.indexOf('https://www.youtube.com/embed/') === -1){
                     $('input[name="url"]').val('https://www.youtube.com/embed/' + url);
                }
            }
        });
    })(jQuery);
</script>
@endpush
