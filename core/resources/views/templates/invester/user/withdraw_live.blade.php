@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="dashboard-inner">
        <div class="row justify-content-center gy-4">
            <div class="col-lg-8">
                <div class="mb-4">
                    <h3 class="mb-2">{{ __($pageTitle) }}</h3>
                    <p class="text-muted text-sm">Real-time withdrawal transactions across the platform.</p>
                </div>

                <div class="card custom--card">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table--responsive--md">
                                <thead>
                                    <tr>
                                        <th>@lang('User')</th>
                                        <th>@lang('Method')</th>
                                        <th>@lang('Amount')</th>
                                        <th>@lang('Time')</th>
                                        <th>@lang('Status')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($withdrawals as $withdraw)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center gap-2">
                                                    <span class="fw-bold text-pink-500">{{ $withdraw->user->username[0] }}***{{ substr($withdraw->user->username, -1) }}</span>
                                                </div>
                                            </td>
                                            <td>{{ $withdraw->method->name }}</td>
                                            <td>
                                                <span class="fw-bold text--success">
                                                    {{ showAmount($withdraw->amount) }}
                                                </span>
                                            </td>
                                            <td>{{ diffForHumans($withdraw->updated_at) }}</td>
                                            <td>
                                                <span class="badge badge--success">@lang('Paid')</span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="100%" class="text-center">{{ __($emptyMessage ?? 'No live withdrawals yet.') }}</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="ai-advisor-container sticky-top" style="top: 20px;">
                    <div class="card custom--card bg-gradient-to-br from-white/90 to-pink-600/10 backdrop-blur border--primary">
                        <div class="card-header bg--primary text-white d-flex align-items-center justify-content-between">
                            <h5 class="m-0 text-white"><i class="las la-robot"></i> AI Advisor</h5>
                            <button onclick="clearWithdrawAi()" class="btn btn-sm text-white opacity-70"><i class="las la-sync"></i></button>
                        </div>
                        <div class="card-body p-3">
                            <div id="aiChatBody" class="ai-chat-body mb-3" style="height: 350px; overflow-y: auto; display: flex; flex-direction: column; gap: 10px;">
                                <div class="message ai-msg p-2 rounded bg-light text-sm">
                                    Hello <strong>{{ auth()->user()->username }}</strong>! I am your <strong>Withdrawal Specialist</strong>. 
                                    I am currently monitoring the live feed. Ask me anything about these transactions or how to process your own payout!
                                </div>
                                <div id="aiTyping" class="text-muted text-xs ms-2" style="display: none;">AI is thinking...</div>
                            </div>
                            <div class="input-group">
                                <input type="text" id="aiInput" class="form-control form--control" placeholder="Ask about withdrawals...">
                                <button onclick="sendWithdrawAi()" class="btn btn--primary"><i class="las la-paper-plane"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .message {
            max-width: 90%;
            padding: 8px 12px;
            border-radius: 10px;
            font-size: 13px;
        }
        .user-msg {
            align-self: flex-end;
            background: #e91e63;
            color: white;
        }
        .ai-msg {
            align-self: flex-start;
            background: #f8f9fa;
            border: 1px solid #eee;
            color: #333;
        }
        .ai-chat-body::-webkit-scrollbar {
            width: 4px;
        }
        .ai-chat-body::-webkit-scrollbar-thumb {
            background: #e91e63;
            border-radius: 10px;
        }
    </style>
@endsection

@push('script')
<script>
    function sendWithdrawAi() {
        const input = $('#aiInput');
        const body = $('#aiChatBody');
        const typing = $('#aiTyping');
        const message = input.val().trim();

        if (!message) return;

        input.val('');
        body.append(`<div class="message user-msg">${message}</div>`);
        body.scrollTop(body[0].scrollHeight);
        
        typing.show();
        body.scrollTop(body[0].scrollHeight);

        $.post("{{ route('user.withdraw.ai.chat') }}", {
            _token: "{{ csrf_token() }}",
            message: message
        }, function(data) {
            typing.hide();
            if(data.success) {
                body.append(`<div class="message ai-msg">${data.message.replace(/\n/g, '<br>')}</div>`);
            } else {
                body.append(`<div class="message ai-msg text-danger">${data.message}</div>`);
            }
            body.scrollTop(body[0].scrollHeight);
        }).fail(function() {
            typing.hide();
            body.append(`<div class="message ai-msg text-danger">Connection lost. AI is offline.</div>`);
        });
    }

    function clearWithdrawAi() {
        const body = $('#aiChatBody');
        body.find('.message:not(:first)').remove();
    }

    $('#aiInput').on('keypress', function(e) {
        if(e.which == 13) sendWithdrawAi();
    });
</script>
@endpush
