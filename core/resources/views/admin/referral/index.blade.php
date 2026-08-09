@extends('admin.layouts.app')

@section('panel')
<div class="row">
@foreach($commissionTypes as $key => $type)
<div class="col-lg-4 mb-4">
<div class="card border--primary parent">

    <div class="card-header bg--primary d-flex justify-content-between align-items-center">
        <h5 class="text-white mb-0">{{ __($type) }}</h5>

        @if(gs($key) == 0)
        <a href="{{ route('admin.referrals.status',$key) }}"
           class="btn btn--success btn-sm rounded-pill">
            <i class="las la-toggle-on"></i> @lang('Enable Now')
        </a>
        @else
        <a href="{{ route('admin.referrals.status',$key) }}"
           class="btn btn--danger btn-sm rounded-pill">
            <i class="las la-toggle-off"></i> @lang('Disable Now')
        </a>
        @endif
    </div>

    <div class="card-body">

        <ul class="list-group list-group-flush mb-3">
        @foreach($referrals->where('commission_type',$key) as $referral)
            <li class="list-group-item d-flex justify-content-between">
                <span class="fw-bold">@lang('Level') {{ $referral->level }}</span>
                <span class="fw-bold">{{ getAmount($referral->percent) }}%</span>
            </li>
        @endforeach
        </ul>

        <div class="border-line-area mt-3">
            <h6 class="border-line-title">@lang('Update Setting')</h6>
        </div>

        <div class="form-group">
            <label>@lang('Number of Level')</label>
            <div class="input-group">
                <input type="number" name="level" min="1"
                       placeholder="Type a number & hit ENTER ↵"
                       class="form-control rounded-start-pill">

                <button type="button"
                        class="btn btn--primary generate rounded-end-pill">
                    @lang('Generate')
                </button>
            </div>
            <span class="text--danger required-message d-none">
                @lang('Please enter a number')
            </span>
        </div>

        <form action="{{ route('admin.referrals.update') }}"
              method="post"
              class="d-none levelForm mt-3">
            @csrf
            <input type="hidden" name="commission_type" value="{{ $key }}">

            <h6 class="text--danger mb-3">
                @lang('The Old setting will be removed after generating new')
            </h6>

            <div class="referralLevels"></div>

            <button type="submit"
                    class="btn btn--primary w-100 rounded-pill">
                @lang('Submit')
            </button>
        </form>

    </div>
</div>
</div>
@endforeach
</div>
@endsection

{{-- ================= STYLES ================= --}}
@push('style')
<style>
/* ---------- Button Curve ---------- */
.btn {
    border-radius: 30px !important;
}

.btn-sm {
    border-radius: 20px !important;
    padding: 5px 14px;
}

.generate {
    border-radius: 0 30px 30px 0 !important;
}

.rounded-start-pill {
    border-radius: 30px 0 0 30px !important;
}

.deleteBtn {
    border-radius: 0 30px 30px 0 !important;
}

/* Circle icon button */
.btn-circle {
    width: 36px;
    height: 36px;
    border-radius: 50% !important;
    padding: 0;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* ---------- Divider ---------- */
.border-line-area {
    position: relative;
    text-align: center;
}
.border-line-area::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 0;
    width: 100%;
    height: 1px;
    background: #e5e5e5;
}
.border-line-title {
    background: #fff;
    padding: 3px 12px;
    position: relative;
    z-index: 1;
}
</style>
@endpush

{{-- ================= SCRIPT ================= --}}
@push('script')
<script>
(function ($) {
"use strict";

$('[name="level"]').on('keyup', function (e) {
    if (e.which === 13) {
        generateLevels($(this));
    }
});

$('.generate').on('click', function () {
    generateLevels($(this).closest('.card-body').find('[name="level"]'));
});

$(document).on('click', '.deleteBtn', function () {
    $(this).closest('.input-group').remove();
});

function generateLevels(input) {
    let level = input.val();
    let parent = input.closest('.card-body');
    let html = '';

    if (level && level > 0) {
        parent.find('.levelForm').removeClass('d-none');
        parent.find('.required-message').addClass('d-none');

        for (let i = 1; i <= level; i++) {
            html += `
            <div class="input-group mb-2">
                <span class="input-group-text">@lang('Level') ${i}</span>
                <input type="hidden" name="level[]" value="${i}">
                <input name="percent[]" class="form-control"
                       placeholder="@lang('Commission Percentage')" required>
                <button type="button"
                        class="btn btn--danger deleteBtn">
                    <i class="la la-times"></i>
                </button>
            </div>`;
        }

        parent.find('.referralLevels').html(html);
    } else {
        parent.find('.levelForm').addClass('d-none');
        parent.find('.required-message').removeClass('d-none');
    }
}
})(jQuery);
</script>
@endpush

