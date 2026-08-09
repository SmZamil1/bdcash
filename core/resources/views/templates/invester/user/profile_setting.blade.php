@extends($activeTemplate . 'layouts.master')
@section('content')

<style>
    /* Normal CSS wiggle animation */
    /* ... existing animation styles ... */
    @keyframes wiggle {
        0%,
        100% {
            transform: rotate(-3deg);
        }

        50% {
            transform: rotate(3deg);
        }
    }

    .wiggle-animation {
        animation: wiggle 1s ease-in-out infinite;
    }

    .profile-thumb {
        position: relative;
        width: 7.25rem;
        height: 7.25rem;
        border-radius: 50%;
        display: inline-flex;
        overflow: visible;
    }

    .profile-thumb .profilePicPreview {
        width: 7.25rem;
        height: 7.25rem;
        border-radius: 50%;
        display: block;
        border: 4px solid rgba(255, 255, 255, 0.3);
        box-shadow: 0 0 10px 0 rgba(0, 0, 0, 0.25);
        background-size: cover;
        background-position: center;
    }

    .profile-thumb .profilePicUpload {
        font-size: 0;
        opacity: 0;
        position: absolute;
        left: 0;
        bottom: 0;
        width: 100%;
        height: 100%;
        cursor: pointer;
    }

    .profile-thumb .avatar-edit {
        position: absolute;
        bottom: 0;
        right: 0;
        z-index: 10;
    }

    .profile-thumb .avatar-edit label {
        width: 40px;
        height: 40px;
        background-color: #3b82f6; /* Blue-500 */
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 2px solid #ffffff;
        cursor: pointer;
        color: #ffffff;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        transition: background-color 0.2s;
    }

    .profile-thumb .avatar-edit label:hover {
        background-color: #2563eb; /* Blue-600 */
    }

    .imgEdit {
        width: 35px !important;
        height: 35px !important;
    }
    
    .glass-input {
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
        color: white !important;
    }
    .glass-input:focus {
        border-color: rgba(255, 255, 255, 0.4);
        background: rgba(255, 255, 255, 0.15);
        color: white !important;
    }
</style>

<div class="px-4 pb-20">
    <!-- Profile Header Card -->
    <div class="bg-[linear-gradient(135deg,#0b64f4,#516dfb)] rounded-3xl p-6 shadow-xl relative overflow-hidden my-3">
        <div class="absolute top-1 right-1 w-40 h-40 rounded-full bg-white/20"></div>
        <div class="absolute bottom-0 left-0 w-32 h-32 rounded-full bg-white/15"></div>
        
        <div class="relative z-10 flex items-center gap-4">
            <div class="profile-thumb-wrapper">
                <div class="profile-thumb">
                    <div class="avatar-preview">
                        <div style="background-image: url('https://i.imgur.com/rAPve5a.png');" class="profilePicPreview"></div>
                    </div>
                </div>
            </div>
            
            <div class="text-left flex-1 pl-4">
                <h3 class="mb-1 text-white font-bold text-xl">{{ __($user->fullname) }}</h3>
                <span class="text-white/80 text-sm">@lang('User ID'): {{ $user->username }}</span>
            </div>
        </div>
    </div>

    <div class="hidden" id="currentPath">Account</div>
    <!-- Account Navigation -->
    <div class="!bg-gradient-to-br !from-blue-400 !via-blue-600 !to-blue-800 !backdrop-blur-lg rounded-2xl shadow-xl mb-4 border border-blue-400/30 overflow-hidden">
        <div class="p-3">
            <div class="grid grid-cols-2 gap-2">
                <a class="Nav profileSettingBtn py-2.5 rounded-full font-semibold text-sm text-white bg-white/30 transition-all duration-300 text-center border border-white/20 shadow-lg" id="NavAccount" href="{{ route('user.profile.setting') }}">
                    @lang('Account')
                </a>
                <a class="Nav passwordChangeBtn py-2.5 rounded-full font-semibold text-sm text-white bg-white/10 hover:bg-white/20 transition-all duration-300 text-center border border-white/5" id="NavPassword" href="{{ route('user.change.password') }}">
                    @lang('Password')
                </a>
            </div>
        </div>
    </div>

    <!-- Profile Form Card -->
    <form id="profileSettingForm" action="" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="border border-blue-400/40 shadow-xl shadow-blue-500/30 rounded-2xl backdrop-blur-lg mb-4 overflow-hidden">
            <div class="bg-gradient-to-r from-blue-500 via-blue-600 to-blue-700 p-4">
                <h4 class="subtitle mb-0 text-white font-semibold text-lg">
                    @lang('User Informations')
                </h4>
            </div>

            <div class="bg-gradient-to-br from-blue-900/60 via-blue-900/40 to-blue-800/40 backdrop-blur-xl p-4">
                <div class="form-group boxed mb-4">
                    <div class="input-wrapper">
                        <label class="label text-white/80 text-sm mb-2 block">@lang('Mobile Number')</label>
                        <input type="tel" class="w-full form-control glass-input rounded-lg px-4 py-2" name="mobile" id="phone" value="{{ $user->mobile }}" required>
                    </div>
                </div>

                <div class="form-group boxed mb-4">
                    <div class="input-wrapper">
                        <label class="label text-white/80 text-sm mb-2 block">@lang('E-Mail')</label>
                        <input type="email" class="w-full form-control glass-input rounded-lg px-4 py-2" name="email" id="email" value="{{ $user->email }}" required>
                    </div>
                </div>
            </div>
            
            <div class="bg-gradient-to-br from-blue-900/60 via-blue-900/40 to-blue-800/40 p-4 border-t border-white/10">
                <button type="submit" class="btn text-white !border-0 bg-gradient-to-r from-blue-500 to-blue-700 hover:from-blue-600 hover:to-blue-800 rounded-full w-full ProfileSubmitBtn font-semibold py-3 shadow-lg flex items-center justify-center gap-2">
                    <span class="btn-text">@lang('Update Information')</span>
                    <div class="btn-spinner hidden">
                        <div class="w-5 h-5 border-2 border-white/30 border-t-white rounded-full animate-spin"></div>
                    </div>
                </button>
            </div>
        </div>
    </form>
</div>

@endsection

@push('script')
<script>
    (function($) {
        "use strict";
        $('#profileSettingForm').on('submit', function(e) {
            e.preventDefault();
            const form = $(this);
            const btn = form.find('.ProfileSubmitBtn');
            const btnText = btn.find('.btn-text');
            const btnSpinner = btn.find('.btn-spinner');

            // Pre-validation or UI changes
            btn.prop('disabled', true);
            btnText.text('Updating...');
            btnSpinner.removeClass('hidden');

            const formData = new FormData(this);

            $.ajax({
                url: form.attr('action'),
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.success) {
                        notify('success', response.message);
                    } else {
                        notify('error', response.message);
                    }
                },
                error: function(response) {
                    const errors = response.responseJSON?.errors;
                    if (errors) {
                        Object.keys(errors).forEach(key => {
                            notify('error', errors[key]);
                        });
                    } else {
                        notify('error', 'Something went wrong. Please try again.');
                    }
                },
                complete: function() {
                    btn.prop('disabled', false);
                    btnText.text('Update Information');
                    btnSpinner.addClass('hidden');
                }
            });
        });

        function proPicURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    var preview = $(input).parents('.profile-thumb').find('.profilePicPreview');
                    $(preview).css('background-image', 'url(' + e.target.result + ')');
                    $(preview).addClass('has-image');
                    $(preview).hide();
                    $(preview).fadeIn(650);
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
        $(".profilePicUpload").on('change', function() {
            proPicURL(this);
        });
    })(jQuery);
</script>
@endpush

