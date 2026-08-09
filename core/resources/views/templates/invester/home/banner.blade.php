@php
    $banners = getContent('banner.element');
@endphp
<div class="relative mt-4">
    <div
        class="swiper swiper-banner rounded-3xl overflow-hidden border-4 border-blue-400/40 shadow-2xl shadow-blue-500/50">
        <div class="swiper-wrapper">
            @if($banners->count())
                @foreach($banners as $banner)
                <div class="swiper-slide relative">
                    <img class="w-full" src="{{ getImage('assets/images/frontend/banner/' . @$banner->data_values->image, '1080x720') }}" alt="Banner">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent"></div>
                </div>
                @endforeach
            @else
                <!-- Fallback Static Banners -->
                <div class="swiper-slide relative">
                    <img class="w-full" src="{{ getImage(null, '1080x720') }}" alt="Banner Placeholder">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent"></div>
                </div>
            @endif
        </div>
        <div class="swiper-pagination"></div>
    </div>
</div>
