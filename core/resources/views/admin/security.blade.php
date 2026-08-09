@extends('admin.layouts.app')
@section('panel')

    <div class="row mb-none-30">
        <div class="col-lg-12 col-md-12 mb-30">


            @php
                $gateways = App\Models\Gateway::manual()->where('status', 1)->get();
            @endphp



            <div class="card mt-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-2">
                        <h5 class="card-title mb-0">@lang('Security Setting')</h5>
                        <a href="{{ route('admin.security.login.history') }}" class="btn btn-sm btn--dark">
                            <i class="la la-history"></i> @lang('View Admin Login History')
                        </a>
                    </div>

                    <form action="{{ route('admin.security.store') }}" method="POST">
                        @csrf

                        <div class="form-group">
                            <label>@lang('Enable 2-Step Verification')</label>
                            <input type="checkbox" data-width="100%" data-onstyle="-success" data-offstyle="-danger"
                                   data-bs-toggle="toggle" data-on="@lang('Enabled')" data-off="@lang('Disabled')" name="is_secondary_auth_enabled"
                                   @if($admin->is_secondary_auth_enabled) checked @endif>
                        </div>

                        <div class="form-group">
                            <label>@lang('Current Password')</label>
                            <input class="form-control" type="password" name="current_password" required placeholder="@lang('Verify your current password')">
                        </div>

                        <div class="form-group">
                            <label>@lang('New Security Password')</label>
                            <input class="form-control" type="password" name="secondary_password" placeholder="@lang('Enter new security password')">
                            <small class="text-muted">@lang('Leave empty if you don\'t want to change it (unless enabling for first time)')</small>
                        </div>

                        <div class="form-group">
                            <label>@lang('Confirm Security Password')</label>
                            <input class="form-control" type="password" name="secondary_password_confirmation">
                        </div>

                        <button type="submit" class="btn btn--primary w-100 btn-lg h-45">@lang('Update Security Settings')</button>
                    </form>
                </div>
            </div>

            <div class="card mt-4">
                 <div class="card-header bg--primary text-white">@lang('Admin Login Access Control')</div>
                 <div class="card-body">
                    <form action="{{ route('admin.security.route.update') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label>@lang('Current Admin Password')</label>
                            <input class="form-control" type="password" name="current_password" required placeholder="@lang('Verify your current password to change route')">
                        </div>

                         <div class="form-group">
                            <label>@lang('Admin Login Route')</label>
                             <div class="input-group">
                                <span class="input-group-text">{{ url('/') }}/</span>
                                <input class="form-control" type="text" name="admin_route" id="admin_route_input" value="{{ trim(config('app.admin_route', 'admin'), '/') }}" placeholder="@lang('admin')">
                            </div>
                            <small class="text-danger">@lang('Warning: Changing this will modify your admin login URL.')</small>
                            <div class="mt-2">
                                <strong>@lang('New Login URL preview:'): </strong> 
                                <span class="text--primary" id="admin_route_preview">{{ url('/') }}/{{ trim(config('app.admin_route', 'admin'), '/') }}</span>
                            </div>
                        </div>
                        
                        <button type="submit" class="btn btn--primary w-100 btn-lg h-45">@lang('Update Admin Route')</button>
                    </form>
                 </div>
            </div>
        </div>
    </div>
    
    @push('script')
    <script>
        (function($){
            "use strict";
            $('#admin_route_input').on('keyup', function(){
                var val = $(this).val();
                $('#admin_route_preview').text('{{ url('/') }}/' + val);
            });
        })(jQuery);
    </script>
    @endpush

@endsection
