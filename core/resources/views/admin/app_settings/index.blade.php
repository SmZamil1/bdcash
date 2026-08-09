@extends('admin.layouts.app')
@section('panel')
    <div class="row mb-none-30">
        <div class="col-lg-12 col-md-12 mb-30">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.app.settings.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label class="font-weight-bold">@lang('App Name') <span class="text-danger">*</span></label>
                                    <input class="form-control" type="text" name="app_name" value="{{ $setting->app_name ?? 'My Earning App' }}" required>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label class="font-weight-bold">@lang('Developer Name') <span class="text-danger">*</span></label>
                                    <input class="form-control" type="text" name="developer" value="{{ $setting->developer ?? 'ViserLab Corp' }}" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 col-sm-12">
                                <div class="form-group">
                                    <label class="font-weight-bold">@lang('Version') <span class="text-danger">*</span></label>
                                    <input class="form-control" type="text" name="version" value="{{ $setting->version ?? '1.0.0' }}" required>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-12">
                                <div class="form-group">
                                    <label class="font-weight-bold">@lang('Size') <span class="text-danger">*</span></label>
                                    <input class="form-control" type="text" name="size" value="{{ $setting->size ?? '15 MB' }}" required>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-12">
                                <div class="form-group">
                                    <label class="font-weight-bold">@lang('Downloads Count') <span class="text-danger">*</span></label>
                                    <input class="form-control" type="text" name="downloads" value="{{ $setting->downloads ?? '1M+' }}" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label class="font-weight-bold">@lang('Rating (e.g 4.5)') <span class="text-danger">*</span></label>
                                    <input class="form-control" type="text" name="rating" value="{{ $setting->rating ?? '4.5' }}" required>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label class="font-weight-bold">@lang('Reviews Count (e.g 85K)') <span class="text-danger">*</span></label>
                                    <input class="form-control" type="text" name="reviews_count" value="{{ $setting->reviews_count ?? '85K' }}" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                             <div class="col-md-12">
                                <div class="form-group">
                                    <label class="font-weight-bold">@lang('Download Link') <span class="text-danger">*</span></label>
                                    <input class="form-control" type="url" name="download_link" value="{{ $setting->download_link ?? '#' }}" required>
                                </div>
                            </div>
                        </div>

                         <div class="row">
                             <div class="col-md-12">
                                <div class="form-group">
                                    <label class="font-weight-bold">@lang('Tags (Comma Separated)')</label>
                                    <input class="form-control bg--light" type="text" name="tags" value="{{ isset($setting->tags) ? implode(',', $setting->tags) : 'Finance,Earning,Productivity' }}">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold">@lang('Description')</label>
                            <textarea class="form-control" name="description" rows="5">{{ $setting->description ?? '' }}</textarea>
                        </div>

                        <hr>
                        <h5 class="mb-3">@lang('Visual Assets')</h5>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold">@lang('App Logo')</label>
                                    <div class="image-upload">
                                        <div class="thumb">
                                            <div class="avatar-preview">
                                                <div class="profilePicPreview" style="background-image: url({{ getImage(getFilePath('app_logo').'/'.@$setting->app_logo, getFileSize('app_logo')) }})">
                                                    <button type="button" class="remove-image"><i class="fa fa-times"></i></button>
                                                </div>
                                            </div>
                                            <div class="avatar-edit">
                                                <input type="file" class="profilePicUpload" name="app_logo" id="profilePicUpload1" accept=".png, .jpg, .jpeg">
                                                <label for="profilePicUpload1" class="bg--primary">@lang('Upload Image')</label>
                                                <small class="mt-2 text-facebook">@lang('Supported files'): <b>jpeg, jpg, png</b>. @lang('Image will be resized into') <b>144x144px</b></small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold">@lang('Screenshots')</label>
                                    <input type="file" name="screenshots[]" multiple class="form-control mb-2" accept=".png, .jpg, .jpeg">
                                    <small class="text-muted">@lang('Select multiple files to upload new screenshots.')</small>
                                    
                                    @if(isset($setting->screenshots) && count($setting->screenshots) > 0)
                                        <div class="d-flex flex-wrap mt-3 gap-2">
                                            @foreach($setting->screenshots as $screenshot)
                                                <div class="position-relative m-1">
                                                    <img src="{{ getImage(getFilePath('app_screenshots').'/'.$screenshot) }}" width="80" class="rounded">
                                                    <div class="form-check position-absolute" style="top: 0; right: 0;">
                                                        <input class="form-check-input bg-danger" type="checkbox" name="delete_screenshots[]" value="{{ $screenshot }}">
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                        <small class="text-danger">@lang('Check box to delete screenshot on save.')</small>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn--primary w-100 h-45">@lang('Submit')</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
