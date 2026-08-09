@extends($activeTemplate . 'layouts.master')
@section('content')
<script>
    "use strict";

    function createCountDown(elementId, sec) {
        var tms = sec;
        var x = setInterval(function() {
            var distance = tms * 1000;
            var days = Math.floor(distance / (1000 * 60 * 60 * 24));
            var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            var seconds = Math.floor((distance % (1000 * 60)) / 1000);
            document.getElementById(elementId).innerHTML = `${days}d: ${hours}h ${minutes}m ${seconds}s`;
            if (distance < 0) {
                clearInterval(x);
                document.getElementById(elementId).innerHTML = "COMPLETE";
            }
            tms--;
        }, 1000);
    }
</script>

<div id="appCapsule">
    <div class="_mb-[20px]_ce3u2_567">
        <h3 class="text-pink-600 _py-[5px]_ce3u2_219 _shadow-md_ce3u2_498 _text-[20px]_ce3u2_468 _text-center_ce3u2_102 _font-bold_ce3u2_110">My Plans</h3>
        <div class="_py-2_ce3u2_422 _px-[10px]_ce3u2_175">
            @forelse($invests as $invest)
                @php
                    $plan = $invest->plan;
                    $nextTime = \Carbon\Carbon::parse($invest->next_time);
                @endphp
                <div class="_flex_ce3u2_19 _gap-3_ce3u2_180 _items-center_ce3u2_27 _bg-white_ce3u2_114 _shadow-md_ce3u2_498 _p-2_ce3u2_81 _rounded-[10px]_ce3u2_56 my-3">
                    <img class="_w-[50px]_ce3u2_64 _h-[50px]_ce3u2_60" src="https://cdn-icons-png.flaticon.com/128/6828/6828665.png" alt="Plan Icon" />
                    <div class="flex-auto">
                        <div class="_flex_ce3u2_19 _gap-2_ce3u2_43 item-center">
                            <h1 class="_text-[14px]_ce3u2_407 text-pink-600 _font-semibold_ce3u2_73">Plan:</h1>
                            <h1 class="_text-[16px]_ce3u2_201 text-pink-500 _font-bold_ce3u2_110">{{ __($invest->plan->name) }}</h1>
                        </div>
                        <div class="_flex_ce3u2_19 _gap-2_ce3u2_43 item-center">
                            <h1 class="_text-[14px]_ce3u2_407 text-pink-600 _font-semibold_ce3u2_73">Price:</h1>
                            <h1 class="_text-[16px]_ce3u2_201 text-pink-500 _font-bold_ce3u2_110">{{ showAmount($invest->amount) }}</h1>
                        </div>
                        <div class="_flex_ce3u2_19 _gap-2_ce3u2_43 item-center">
                            <h1 class="_text-[14px]_ce3u2_407 text-pink-600 _font-semibold_ce3u2_73">Daily Profit:</h1>
                            <h1 class="_text-[12px]_ce3u2_77 text-pink-500 _font-bold_ce3u2_110"><span class="_text-[16px]_ce3u2_201 _font-bold_ce3u2_110">{{ showAmount($invest->interest) }}</span></h1>
                        </div>
                        <div class="_flex_ce3u2_19 _gap-2_ce3u2_43 item-center">
                            <h1 class="_text-[14px]_ce3u2_407 text-pink-600 _font-semibold_ce3u2_73">Remaining Times:</h1>
                            <h1 class="_text-[16px]_ce3u2_201 text-pink-500 _font-bold_ce3u2_110">
                                @if ($plan->repeat_time)
                                    {{ $plan->repeat_time }} @lang('times')
                                @else
                                    @lang('Lifetime')
                                @endif
                            </h1>
                        </div>
                        <div class="_flex_ce3u2_19 _gap-2_ce3u2_43 item-center">
                            <h1 class="_text-[14px]_ce3u2_407 text-pink-600 _font-semibold_ce3u2_73">Date:</h1>
                            <h1 class="_text-[16px]_ce3u2_201 text-pink-500 _font-bold_ce3u2_110">{{ showDateTime($invest->created_at, 'M d, Y h:i A') }}</h1>
                        </div>
                        <div class="_flex_ce3u2_19 _gap-2_ce3u2_43 item-center">
                            <h1 class="_text-[14px]_ce3u2_407 text-pink-600 _font-semibold_ce3u2_73">Status:</h1>
                            <h1 class="_text-[16px]_ce3u2_201 text-pink-500 _font-bold_ce3u2_110">
                                @if ($invest->status == 1)
                                    @lang('Running')
                                @elseif($invest->status == 2)
                                    @lang('Canceled')
                                @else
                                    @lang('Completed')
                                @endif
                            </h1>
                        </div>
                        <div class="_flex_ce3u2_19 _gap-2_ce3u2_43 item-center">
                            <h1 class="_text-[14px]_ce3u2_407 text-pink-600 _font-semibold_ce3u2_73">Timer:</h1>
                            <div class="flex-auto">
                                <h1 class="_text-[16px]_ce3u2_201 text-pink-500 _font-bold_ce3u2_110">
                                    <span id="counter{{ $invest->id }}">
                                        @if ($nextTime > now())
                                            <script>
                                                createCountDown('counter{{ $invest->id }}', {{ abs($nextTime->diffInSeconds()) }});
                                            </script>
                                        @endif
                                    </span>
                                </h1> @if ($invest->status == 1)
    @php
        $start = $invest->last_time ? $invest->last_time : $invest->created_at;
    @endphp
    <div id="counter{{ $invest->id }}" class="w-[80%] rounded-full bg-gray-300">
        <div class="bg-pink-500 text-xs font-medium text-white text-center p-0.5 leading-none rounded-full" style="width: {{ diffDatePercent($start, $invest->next_time) }}%;">
            {{ diffDatePercent($start, $invest->next_time) }}%
        </div>
    </div>
@elseif($invest->status == 2)
    @lang('Canceled')
@else
    @lang('Completed')
@endif

                            </div>
                        </div>
                    </div>
                </div>
            @empty
                        <div class="_grid_ce3u2_157 _content-center_ce3u2_580 _justify-items-center_ce3u2_578 _absolute_ce3u2_346 _top-[50%]_ce3u2_585 _left-[50%]_ce3u2_391 _translate-x-[-50%]_ce3u2_386 _translate-y-[-50%]_ce3u2_582">
                            <img width="60px" src="https://cdn-icons-png.flaticon.com/128/14031/14031919.png" alt="" />
                            <h1 class="_text-gray-400_ce3u2_587 _text-sm_ce3u2_214 _mt-2_ce3u2_135">No Logs Found</h1>
                        </div>
            @endforelse
        </div>
    </div>
</div>

@endsection
