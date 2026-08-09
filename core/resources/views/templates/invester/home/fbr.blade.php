@php
    $fbrContent = getContent('fbr.content', true);
@endphp
<div
    class="mb-6 bg-gradient-to-br from-blue-400/30 via-blue-600/30 to-blue-800/30 backdrop-blur-xl border-2 border-blue-400/40 rounded-3xl p-5 shadow-2xl">
    <h2 class="text-white font-black text-xl mb-3 pb-2 border-b-2 border-white/20">
        {{ __(@$fbrContent->data_values->heading ?? 'Package Plan') }}
    </h2>
    <p class="text-white/90 text-sm leading-relaxed mb-3">
        {{ __(@$fbrContent->data_values->description ?? "You can watch ads by investing, which will result in higher earnings. If you don't have an investment, you can watch ads by building your team. As your team grows, your earnings will also scale up.") }}
    </p>
    <div class="relative group">
        <div
            class="absolute inset-0 bg-gradient-to-r from-blue-400 to-blue-600 rounded-2xl blur-xl opacity-50 group-hover:opacity-75 transition-opacity">
        </div>
        <img class="relative w-full rounded-2xl border-2 border-white/30 shadow-xl"
            src="{{ getImage('assets/images/frontend/fbr/' . @$fbrContent->data_values->image, '800x400') }}"
            alt="FBR Image">
    </div>
</div>
