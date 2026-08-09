@extends($activeTemplate . 'layouts.master')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card custom--card glass-card border-0 shadow-lg">
            <div class="card-header bg-gradient-to-r from-blue-600 to-blue-800 text-white p-4">
                <h3 class="card-title text-white mb-0 text-center">
                    <i class="las la-database me-2"></i>@lang('AinPay Payment - Database Details')
                </h3>
            </div>
            <div class="card-body p-4 text-center">
                <div class="mb-4">
                    <img src="{{ getImage('assets/images/gateway/ainpay.png') }}" alt="AinPay" class="img-fluid rounded shadow-sm" style="max-width: 150px;">
                </div>

                <div class="payment-details mb-4">
                    <ul class="list-group list-group-flush bg-transparent">
                        <li class="list-group-item d-flex justify-content-between align-items-center border-0 px-0 bg-transparent text-white">
                            @lang('Amount')
                            <span class="fw-bold">{{ showAmount($deposit->amount) }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center border-0 px-0 bg-transparent text-white">
                            @lang('Charge')
                            <span class="text-danger">+ {{ showAmount($deposit->charge) }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center border-top border-secondary px-0 bg-transparent text-white mt-2 pt-3">
                            <span class="h4 mb-0">@lang('Total Payable')</span>
                            <span class="h4 mb-0 text-blue-400">{{ showAmount($deposit->final_amount) }} {{ $deposit->method_currency }}</span>
                        </li>
                    </ul>
                </div>

                <form action="{{ route('ipn.AinPay') }}" method="POST">
                    @csrf
                    <input type="hidden" name="track" value="{{ $deposit->trx }}">
                    <button type="submit" class="btn btn-primary btn-lg w-100 rounded-pill shadow-lg transition-all hover:scale-105">
                        <i class="las la-wallet me-2"></i>@lang('Pay Now')
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    .glass-card {
        background: rgba(255, 255, 255, 0.05) !important;
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.1) !important;
    }
    .custom--table {
        background: transparent !important;
    }
    .custom--table td, .custom--table th {
        border-color: rgba(255, 255, 255, 0.1) !important;
        padding: 1rem !important;
    }
    .text-blue-400 {
        color: #60a5fa !important;
    }
</style>
@endsection
