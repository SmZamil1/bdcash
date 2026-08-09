@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <form action="{{ route('admin.ptc.update', $ad->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label>@lang('Title')</label>
                                <input type="text" name="title" class="form-control" value="{{ $ad->title }}" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label>@lang('Amount')</label>
                                <div class="input-group">
                                    <input type="number" step="any" name="amount" class="form-control" value="{{ getAmount($ad->amount) }}" required>
                                    <span class="input-group-text">{{ gs('cur_text') }}</span>
                                </div>
                            </div>
                            <div class="form-group col-md-4">
                                <label>@lang('Duration (Seconds)')</label>
                                <div class="input-group">
                                    <input type="number" name="duration" class="form-control" value="{{ $ad->duration }}" required>
                                    <span class="input-group-text">@lang('Seconds')</span>
                                </div>
                            </div>
                            <div class="form-group col-md-4">
                                <label>@lang('Maximum Show')</label>
                                <input type="number" name="max_show" class="form-control" value="{{ $ad->max_show }}" required>
                            </div>
                            <div class="form-group col-md-4">
                                <label>@lang('Ad Type')</label>
                                <select name="ad_type" class="form-control" required>
                                    <option value="1" @selected(old('ad_type', $ad->ad_type) == 1)>@lang('Link / URL')</option>
                                    <option value="2" @selected(old('ad_type', $ad->ad_type) == 2)>@lang('Youtube Video')</option>
                                    <option value="3" @selected(old('ad_type', $ad->ad_type) == 3)>@lang('Image')</option>
                                </select>
                            </div>
                            
                            <div class="form-group col-md-12" id="url_field">
                                <label>@lang('URL / Link')</label>
                                <div class="input-group">
                                    <span class="input-group-text" id="yt_prefix" style="display:none;">https://www.youtube.com/embed/</span>
                                    <input type="text" name="url" class="form-control" value="{{ $ad->url }}">
                                </div>
                                <small class="text--info" id="yt_hint" style="display:none;">@lang('Enter Youtube Video Code Only (e.g. XXXXXX)')</small>
                            </div>

                            <div class="form-group col-md-12" id="image_field" style="display:none;">
                                <label>@lang('Ad Image')</label>
                                <input type="file" name="image" class="form-control" accept="image/*">
                                @if($ad->image)
                                    <div class="mt-2">
                                        <img src="{{ getImage(getFilePath('ptc') . '/' . $ad->image) }}" alt="Ad Image" style="height: 100px;">
                                    </div>
                                @endif
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
        
        // Strip prefix if present for display on load
        var currentType = $('select[name="ad_type"]').val();
        if(currentType == 2){
             var urlVal = $('input[name="url"]').val();
             if(urlVal.indexOf('https://www.youtube.com/embed/') !== -1){
                 $('input[name="url"]').val(urlVal.replace('https://www.youtube.com/embed/', ''));
             }
        }

        $('select[name="ad_type"]').on('change', function(){
            var type = $(this).val();
            if(type == 1){
                $('#url_field').show();
                $('#yt_prefix').hide();
                $('#image_field').hide();
                $('#yt_hint').hide();
            } else if(type == 2){
                $('#url_field').show();
                $('#yt_prefix').show();
                $('#image_field').hide();
                $('#yt_hint').show();
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
