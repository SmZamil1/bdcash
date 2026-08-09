@extends('admin.layouts.master')
@section('content')
<div class="login-main">
    <div class="container custom-container">
        <div class="row justify-content-center">
            <div class="col-xxl-5 col-xl-5 col-lg-6 col-md-8 col-sm-11">
                <div class="login-area">
                    <div class="login-wrapper">
                        <div class="login-wrapper__body">
                            <h3 class="title text-white text-center">@lang('Security Verification')</h3>
                            <p class="text-white text-center mb-4">@lang('Please enter your secondary security password.')</p>
                            <form action="{{ route('admin.security.verify.post') }}" method="POST" class="cmn-form mt-30 login-form">
                                @csrf
                                <div class="form-group">
                                    <label>@lang('Security Password')</label>
                                    <input type="password" name="code" class="form-control" required placeholder="@lang('Enter Security Password')">
                                </div>
                                <button type="submit" class="btn btn-lg btn--primary border-0 w-100 mt-3">@lang('Verify')</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
