@php
    $tutorialContent = getContent('tutorial.content', true);
@endphp
<div
    class="mb-6 bg-gradient-to-br from-blue-400/30 via-blue-600/30 to-blue-800/30 backdrop-blur-xl border-2 border-blue-400/30 rounded-3xl p-2 shadow-2xl">
    <h2 class="text-center text-white font-black text-xl mb-4 border-b-2 border-white/20">
        {{ __(@$tutorialContent->data_values->heading ?? 'YouTube Team Earning 2026 🌟 Earn great investment profits with an investment of just 165! 💰 So what are you waiting for?') }}
    </h2>
    <div class="max-w-4xl mx-auto rounded-2xl overflow-hidden shadow-2xl shadow-black/50 border-2 border-white/20">
        <div class="relative" style="padding-bottom: 56.25%; height: 0;">
            <iframe class="absolute top-0 left-0 w-full h-full"
                src="{{ @$tutorialContent->data_values->youtube_link ?? 'https://www.youtube.com/embed/wM-SvtYcyZo?autoplay=1&mute=1&rel=0&showinfo=0' }}"
                frameborder="0" allow="autoplay; encrypted-media; fullscreen; picture-in-picture" allowfullscreen>
            </iframe>
        </div>
    </div>
</div>
