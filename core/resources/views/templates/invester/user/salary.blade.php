@extends($activeTemplate . 'layouts.master')
@section('content')

<style>
    @import url('https://fonts.googleapis.com/css2?family=Orbitron:wght@400..900&display=swap');
    :root { --font-orbitron: 'Orbitron', sans-serif; }
    .font-orbitron { font-family: var(--font-orbitron); }

    .glass-card {
        background: linear-gradient(135deg, rgba(17,24,39,0.7), rgba(31,41,55,0.7));
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border: 1px solid rgba(255,255,255,0.08);
        box-shadow: 0 8px 32px 0 rgba(0,0,0,0.3);
    }

    .salary-card {
        background: rgba(255,255,255,0.03);
        border: 1px solid rgba(255,255,255,0.07);
        border-radius: 20px;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    .salary-card:hover {
        transform: translateY(-3px);
        background: rgba(255,255,255,0.055);
        border-color: rgba(59,130,246,0.3);
        box-shadow: 0 8px 30px rgba(59,130,246,0.12);
    }
    .salary-card.is-active {
        background: linear-gradient(135deg, rgba(59,130,246,0.12), rgba(99,102,241,0.08));
        border-color: rgba(99,102,241,0.45);
        box-shadow: 0 0 24px rgba(99,102,241,0.18);
    }
    .salary-card.is-passed {
        background: linear-gradient(135deg, rgba(34,197,94,0.1), rgba(16,185,129,0.06));
        border-color: rgba(34,197,94,0.3);
    }
    .salary-card.is-locked {
        opacity: 0.65;
        border-color: rgba(255,255,255,0.05);
    }

    .status-badge {
        font-size: 10px;
        font-weight: 700;
        letter-spacing: 0.05em;
        text-transform: uppercase;
        padding: 3px 10px;
        border-radius: 999px;
    }

    .progress-bar-bg {
        background: rgba(255,255,255,0.06);
        border-radius: 999px;
        height: 6px;
        overflow: hidden;
    }
    .progress-bar-fill {
        height: 100%;
        border-radius: 999px;
        transition: width 0.6s ease;
    }

    .glow-blue  { box-shadow: 0 0 18px rgba(59,130,246,0.5);  }
    .glow-green { box-shadow: 0 0 18px rgba(34,197,94,0.5);  }
    .glow-gray  { box-shadow: 0 0 18px rgba(100,116,139,0.3);}

    .btn-action {
        width: 100%;
        padding: 8px 0;
        border-radius: 12px;
        font-size: 13px;
        font-weight: 700;
        text-align: center;
        transition: all 0.25s ease;
        letter-spacing: 0.03em;
        border: none;
        cursor: pointer;
    }
    .btn-action:active { transform: scale(0.97); }

    .btn-activate {
        background: linear-gradient(135deg, #3b82f6, #6366f1);
        color: #fff;
        box-shadow: 0 4px 16px rgba(99,102,241,0.35);
    }
    .btn-activate:hover { box-shadow: 0 6px 24px rgba(99,102,241,0.55); }

    .btn-collect {
        background: linear-gradient(135deg, #22c55e, #16a34a);
        color: #fff;
        box-shadow: 0 4px 16px rgba(34,197,94,0.35);
    }
    .btn-collect:hover { box-shadow: 0 6px 24px rgba(34,197,94,0.55); }

    .btn-disabled {
        background: rgba(255,255,255,0.06);
        color: rgba(255,255,255,0.35);
        cursor: not-allowed;
        border: 1px solid rgba(255,255,255,0.08);
    }
    .btn-completed {
        background: rgba(34,197,94,0.15);
        color: #4ade80;
        cursor: not-allowed;
        border: 1px solid rgba(34,197,94,0.2);
    }
    .btn-locked {
        background: rgba(239,68,68,0.12);
        color: #f87171;
        cursor: not-allowed;
        border: 1px solid rgba(239,68,68,0.2);
    }

    .icon-ring {
        width: 56px; height: 56px;
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
    }
</style>

<link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">

<div id="appCapsule" class="pb-24">
    <div class="px-3 py-4">

        {{-- ── Header ──────────────────────────────────── --}}
        <div class="mb-5 ps-1">
            <h1 class="text-3xl font-bold text-white font-orbitron mb-1">@lang('Salary')</h1>
            <p class="text-blue-200/60 text-sm">@lang('Earn weekly salary rewards based on team turnover')</p>
        </div>

        {{-- ── Team Turnover Card ───────────────────────── --}}
        <div class="glass-card rounded-[22px] p-4 mb-5 relative overflow-hidden">
            <div class="absolute -top-8 -right-8 w-36 h-36 bg-blue-500/10 rounded-full blur-2xl"></div>
            <div class="absolute -bottom-6 -left-6 w-28 h-28 bg-indigo-500/10 rounded-full blur-2xl"></div>
            <div class="relative z-10 flex items-center gap-4">
                <div class="icon-ring bg-blue-500/20 border border-blue-400/30 glow-blue">
                    <img src="https://cdn-icons-png.flaticon.com/128/10112/10112502.png" class="w-7 h-7 object-contain" alt="turnover">
                </div>
                <div>
                    <p class="text-blue-200/60 text-xs mb-0.5 uppercase tracking-widest font-semibold">@lang('Team Turnover')</p>
                    <p class="text-2xl font-bold text-white font-orbitron">
                        {{ showAmount($teamTurnover) }}
                    </p>
                </div>
            </div>
        </div>

        {{-- ── Section Title ────────────────────────────── --}}
        <div class="flex items-center gap-3 mb-4 px-1">
            <div class="h-px flex-1 bg-white/10"></div>
            <span class="text-xs font-bold text-blue-300 uppercase tracking-widest">@lang('Salary Levels')</span>
            <div class="h-px flex-1 bg-white/10"></div>
        </div>

        {{-- ── Salary Level Cards ───────────────────────── --}}
        <div class="space-y-4">
            @forelse($salaries as $salary)
                @php
                    $isLocked = $teamTurnover < $salary->min_turnover;
                    $isPassed = $salary->max_turnover > 0 && $teamTurnover > $salary->max_turnover;
                    $isActive = !$isLocked && !$isPassed;

                    $lastClaim = \App\Models\UserSalaryLog::where('user_id', auth()->id())
                        ->where('user_salary_id', $salary->id)
                        ->latest()->first();

                    $isActivated = $lastClaim ? true : false;

                    // Fix: use copy() to avoid Carbon mutation bug
                    $nextClaimAt   = $isActivated ? $lastClaim->created_at->copy()->addDays(7) : null;
                    $canCollect    = $isActivated && now()->gte($nextClaimAt);
                    // diffInSeconds($nextClaimAt) from now → positive = future seconds remaining
                    $secsRemaining = ($isActivated && !$canCollect && $nextClaimAt)
                        ? (int) $nextClaimAt->diffInSeconds(now())
                        : 0;

                    // Progress toward this level (capped 0–100)
                    $progress = 0;
                    if ($salary->max_turnover > 0 && $salary->min_turnover >= 0) {
                        $range    = $salary->max_turnover - $salary->min_turnover;
                        $progress = $range > 0
                            ? min(100, max(0, (($teamTurnover - $salary->min_turnover) / $range) * 100))
                            : ($teamTurnover >= $salary->min_turnover ? 100 : 0);
                    } elseif ($salary->max_turnover == 0 && $salary->min_turnover > 0) {
                        $progress = $teamTurnover >= $salary->min_turnover ? 100 : min(99, ($teamTurnover / $salary->min_turnover) * 100);
                    }

                    // Status label & colour
                    if ($isPassed) {
                        $statusLabel = 'Completed'; $statusClass = 'bg-green-500/20 text-green-400';
                        $iconSrc     = 'https://cdn-icons-png.flaticon.com/128/5511/5511414.png';
                        $iconBg      = 'bg-green-500/20 border-green-400/30'; $iconGlow = 'glow-green';
                    } elseif ($isLocked) {
                        $statusLabel = 'Locked'; $statusClass = 'bg-red-500/15 text-red-400';
                        $iconSrc     = 'https://cdn-icons-png.flaticon.com/128/615/615075.png';
                        $iconBg      = 'bg-slate-700/60 border-white/10'; $iconGlow = 'glow-gray';
                    } else {
                        $statusLabel = $isActivated ? ($canCollect ? 'Collect Now' : 'Active') : 'Unlocked';
                        $statusClass = $canCollect ? 'bg-yellow-500/25 text-yellow-300' : 'bg-blue-500/20 text-blue-300';
                        $iconSrc     = 'https://cdn-icons-png.flaticon.com/128/2583/2583344.png';
                        $iconBg      = 'bg-blue-500/20 border-blue-400/30'; $iconGlow = 'glow-blue';
                    }

                    $cardClass = $isPassed ? 'is-passed' : ($isLocked ? 'is-locked' : 'is-active');
                @endphp

                <div class="salary-card {{ $cardClass }} p-4">

                    {{-- Faint glow blob --}}
                    @if($isActive)
                        <div class="absolute -top-6 -right-6 w-24 h-24 bg-indigo-500/10 rounded-full blur-2xl pointer-events-none"></div>
                    @endif

                    <div class="relative z-10 flex items-start gap-4">

                        {{-- Icon --}}
                        <div class="icon-ring {{ $iconBg }} {{ $iconGlow }} mt-1">
                            <img src="{{ $iconSrc }}" class="w-7 h-7 object-contain" alt="">
                        </div>

                        {{-- Body --}}
                        <div class="flex-1 min-w-0">

                            {{-- Name + badge --}}
                            <div class="flex items-center justify-between gap-2 mb-1 flex-wrap">
                                <h2 class="text-white font-bold text-[15px] font-orbitron leading-tight">
                                    {{ __($salary->name) }}
                                </h2>
                                <span class="status-badge {{ $statusClass }}">{{ __($statusLabel) }}</span>
                            </div>

                            {{-- Rate + Requirement --}}
                            <p class="text-blue-200/55 text-[12px] mb-1">
                                @lang('Salary:')
                                <span class="text-white font-bold">{{ number_format($salary->amount, 2) }}%</span>
                                &nbsp;·&nbsp;
                                @lang('Turnover Required:')
                                <span class="text-white font-bold">
                                    {{ gs('cur_sym') }}{{ showAmount($salary->min_turnover) }}
                                    @if($salary->max_turnover > 0)
                                        – {{ gs('cur_sym') }}{{ showAmount($salary->max_turnover) }}
                                    @else
                                        +
                                    @endif
                                </span>
                            </p>

                            {{-- Your turnover vs requirement --}}
                            <p class="text-[11px] mb-2 {{ $isLocked ? 'text-red-400/70' : 'text-green-400/80' }}">
                                <i class="las la-chart-bar me-1"></i>
                                @lang('Your Turnover:')
                                <span class="font-bold">{{ gs('cur_sym') }}{{ showAmount($teamTurnover) }}</span>
                                @if($isLocked)
                                    &nbsp;·&nbsp;
                                    <span class="text-red-400">@lang('Need') {{ gs('cur_sym') }}{{ showAmount($salary->min_turnover - $teamTurnover) }} @lang('more')</span>
                                @endif
                            </p>

                            {{-- Progress Bar --}}
                            <div class="progress-bar-bg mb-2">
                                <div class="progress-bar-fill {{ $isPassed ? 'bg-green-500' : ($isLocked ? 'bg-red-600/60' : 'bg-gradient-to-r from-blue-500 to-indigo-500') }}"
                                     style="width: {{ $progress }}%;">
                                </div>
                            </div>

                            {{-- Last Claim + Next Claim Info --}}
                            @if($isActivated)
                                <div class="bg-white/5 rounded-xl px-3 py-2 mb-3 space-y-1">

                                    {{-- Last Claim Date --}}
                                    <div class="flex items-center gap-2 text-[11px]">
                                        <i class="las la-history text-blue-300"></i>
                                        <span class="text-white/50">@lang('Last Claim:')</span>
                                        <span class="text-white/85 font-semibold ml-auto">{{ $lastClaim->created_at->format('d M Y, h:i A') }}</span>
                                    </div>

                                    {{-- Next Claim Date + Countdown --}}
                                    @if(!$canCollect && $nextClaimAt)
                                        <div class="flex items-center gap-2 text-[11px]">
                                            <i class="las la-calendar-check text-yellow-400"></i>
                                            <span class="text-white/50">@lang('Next Claim:')</span>
                                            <span class="text-yellow-200/80 font-semibold ml-auto">{{ $nextClaimAt->format('d M Y, h:i A') }}</span>
                                        </div>
                                        <div class="flex items-center gap-2 text-[11px] time-left-row">
                                            <i class="las la-hourglass-half text-orange-400"></i>
                                            <span class="text-white/50">@lang('Time Left:')</span>
                                            <span class="text-orange-300 font-bold font-orbitron ml-auto countdown-timer" data-seconds="{{ $secsRemaining }}">{{ gmdate('H:i:s', $secsRemaining) }}</span>
                                        </div>
                                    @elseif($canCollect)
                                        <div class="flex items-center gap-2 text-[11px]">
                                            <i class="las la-check-circle text-green-400"></i>
                                            <span class="text-green-400 font-semibold">@lang('Ready to collect your weekly reward!')</span>
                                        </div>
                                    @endif

                                </div>
                            @endif

                            {{-- Action Button --}}
                            @if($isActive)
                                @if(!$isActivated)
                                    <form action="{{ route('user.salary.claim') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ $salary->id }}">
                                        <button type="submit" class="btn-action btn-activate">
                                            <i class="las la-bolt me-1"></i>@lang('Activate Salary')
                                        </button>
                                    </form>
                                @elseif($canCollect)
                                    <form action="{{ route('user.salary.claim') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ $salary->id }}">
                                        <button type="submit" class="btn-action btn-collect">
                                            <i class="las la-coins me-1"></i>@lang('Collect Reward')
                                        </button>
                                    </form>
                                @else
                                    {{-- Live countdown button --}}
                                    <button class="btn-action btn-disabled countdown-btn" disabled
                                            data-salary-id="{{ $salary->id }}">
                                        <i class="las la-clock me-1"></i>
                                        @lang('Next in:')
                                        <span class="countdown-timer font-orbitron" data-seconds="{{ $secsRemaining }}">
                                            @php
                                                $d = floor($secsRemaining / 86400);
                                                $h = floor(($secsRemaining % 86400) / 3600);
                                                $m = floor(($secsRemaining % 3600) / 60);
                                                $s = $secsRemaining % 60;
                                            @endphp
                                            {{ $d > 0 ? $d.'d ' : '' }}{{ str_pad($h,2,'0',STR_PAD_LEFT) }}h {{ str_pad($m,2,'0',STR_PAD_LEFT) }}m {{ str_pad($s,2,'0',STR_PAD_LEFT) }}s
                                        </span>
                                    </button>
                                @endif
                            @elseif($isPassed)
                                <button class="btn-action btn-completed" disabled>
                                    <i class="las la-check-circle me-1"></i>@lang('Completed')
                                </button>
                            @else
                                <button class="btn-action btn-locked" disabled>
                                    <i class="las la-lock me-1"></i>@lang('Locked')
                                </button>
                            @endif

                        </div>
                    </div>
                </div>

            @empty
                <div class="glass-card rounded-[22px] py-14 text-center">
                    <img src="https://i.imgur.com/D7pxgR7.png" class="w-16 mx-auto mb-3 opacity-50" alt="">
                    <h3 class="text-white font-bold text-sm">@lang('No Salary Levels Defined')</h3>
                    <p class="text-white/30 text-xs mt-1">@lang('Check back later.')</p>
                </div>
            @endforelse
        </div>

        {{-- ── How It Works ─────────────────────────────── --}}
        <div class="mt-6 p-4 bg-white/5 rounded-2xl border border-white/10">
            <h6 class="text-blue-400 font-bold mb-3 flex items-center gap-2">
                <i class="las la-info-circle text-xl"></i> @lang('How It Works')
            </h6>
            <ul class="space-y-2 text-sm text-gray-400">
                <li class="flex items-start gap-2">
                    <span class="w-1.5 h-1.5 rounded-full bg-blue-500 mt-1.5 shrink-0"></span>
                    @lang('Build your team turnover to unlock higher salary levels.')
                </li>
                <li class="flex items-start gap-2">
                    <span class="w-1.5 h-1.5 rounded-full bg-blue-500 mt-1.5 shrink-0"></span>
                    @lang('Once unlocked, activate the salary to start earning.')
                </li>
                <li class="flex items-start gap-2">
                    <span class="w-1.5 h-1.5 rounded-full bg-blue-500 mt-1.5 shrink-0"></span>
                    @lang('Collect your reward every 7 days after activation.')
                </li>
                <li class="flex items-start gap-2">
                    <span class="w-1.5 h-1.5 rounded-full bg-blue-500 mt-1.5 shrink-0"></span>
                    @lang('Rewards are credited directly to your Interest Wallet.')
                </li>
            </ul>
        </div>

    </div>
</div>

@push('script')
<script>
(function() {
    var csrfToken = document.querySelector('meta[name="csrf-token"]') ?
                   document.querySelector('meta[name="csrf-token"]').getAttribute('content') : '';

    var claimRoute = '{{ route("user.salary.claim") }}';

    function swapToCollect(countdownEl) {
        // Walk up to the btn-disabled button
        var btn = countdownEl.closest('.countdown-btn');
        if (!btn) return;
        var salaryId = btn.getAttribute('data-salary-id');
        if (!salaryId) return;

        // Also update the info panel "Time Left" line in the same card
        var card = btn.closest('.salary-card');
        if (card) {
            var timeLeftRow = card.querySelector('.time-left-row');
            if (timeLeftRow) {
                timeLeftRow.innerHTML =
                    '<i class="las la-check-circle text-green-400"></i>' +
                    '<span class="text-green-400 font-semibold">Ready to collect your weekly reward!</span>';
            }
        }

        // Build a real collect form and replace the button
        var form = document.createElement('form');
        form.method = 'POST';
        form.action = claimRoute;
        form.innerHTML =
            '<input type="hidden" name="_token" value="' + csrfToken + '">' +
            '<input type="hidden" name="id" value="' + salaryId + '">' +
            '<button type="submit" class="btn-action btn-collect">' +
            '  <i class="las la-coins me-1"></i> Collect Reward' +
            '</button>';

        btn.parentNode.replaceChild(form, btn);
    }

    function updateCountdowns() {
        document.querySelectorAll('.countdown-timer[data-seconds]').forEach(function(el) {
            var secs = parseInt(el.getAttribute('data-seconds'));

            if (secs <= 0) {
                // Swap button to collect — no page reload
                swapToCollect(el);
                // Remove the data-seconds so this timer stops ticking
                el.removeAttribute('data-seconds');
                return;
            }

            var days    = Math.floor(secs / 86400);
            var hours   = Math.floor((secs % 86400) / 3600);
            var minutes = Math.floor((secs % 3600) / 60);
            var seconds = secs % 60;

            var parts = [];
            if (days > 0) parts.push(days + 'd');
            parts.push(String(hours).padStart(2,'0') + 'h');
            parts.push(String(minutes).padStart(2,'0') + 'm');
            parts.push(String(seconds).padStart(2,'0') + 's');

            el.textContent = parts.join(' ');
            el.setAttribute('data-seconds', secs - 1);
        });
    }

    // Run immediately & then every second
    updateCountdowns();
    setInterval(updateCountdowns, 1000);
})();
</script>
@endpush

@endsection