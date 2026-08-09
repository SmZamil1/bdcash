@extends('admin.layouts.app')
@section('panel')
    <div class="row mb-none-30">
        <div class="col-lg-12 col-md-12 mb-30">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <!-- Games Module -->
                        <div class="col-xl-4 col-md-6 mb-30">
                            <div class="card border-primary">
                                <div class="card-header bg--primary">
                                    <h4 class="card-title text-white">@lang('Games Module')</h4>
                                </div>
                                <div class="card-body">
                                    <p class="mb-3">Enable or disable the entire Gaming section (Aviator, Plinko, etc.) for users.</p>
                                    <div class="d-flex justify-content-between align-items-center">
                                         @if(gs('games_module', 1))
                                            <span class="badge badge--success">@lang('Enabled')</span>
                                            <div>
                                                <a href="{{ route('admin.games.index') }}" class="btn btn-sm btn-outline--primary me-2">
                                                    <i class="las la-cog"></i> @lang('Config')
                                                </a>
                                                <button class="btn btn-sm btn-outline--danger confirmationBtn" data-question="@lang('Are you sure to disable Games Module?')" data-action="{{ route('admin.earning.methods.status', 'games_module') }}">
                                                    <i class="las la-ban"></i> @lang('Disable')
                                                </button>
                                            </div>
                                         @else
                                            <span class="badge badge--danger">@lang('Disabled')</span>
                                            <div>
                                                <a href="{{ route('admin.games.index') }}" class="btn btn-sm btn-outline--primary me-2">
                                                    <i class="las la-cog"></i> @lang('Config')
                                                </a>
                                                <button class="btn btn-sm btn-outline--success confirmationBtn" data-question="@lang('Are you sure to enable Games Module?')" data-action="{{ route('admin.earning.methods.status', 'games_module') }}">
                                                    <i class="las la-check-circle"></i> @lang('Enable')
                                                </button>
                                            </div>
                                         @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Trading Module -->
                        <div class="col-xl-4 col-md-6 mb-30">
                            <div class="card border-info">
                                <div class="card-header bg--info">
                                    <h4 class="card-title text-white">@lang('Trading Module')</h4>
                                </div>
                                <div class="card-body">
                                    <p class="mb-3">Enable or disable Trading features (Crypto/Forex simulation) on the frontend.</p>
                                    <div class="d-flex justify-content-between align-items-center">
                                         @if(gs('trading_module', 1))
                                            <span class="badge badge--success">@lang('Enabled')</span>
                                            <div>
                                                <a href="{{ route('admin.trading.setting.index') }}" class="btn btn-sm btn-outline--primary me-2">
                                                    <i class="las la-cog"></i> @lang('Config')
                                                </a>
                                                <button class="btn btn-sm btn-outline--danger confirmationBtn" data-question="@lang('Are you sure to disable Trading Module?')" data-action="{{ route('admin.earning.methods.status', 'trading_module') }}">
                                                    <i class="las la-ban"></i> @lang('Disable')
                                                </button>
                                            </div>
                                         @else
                                            <span class="badge badge--danger">@lang('Disabled')</span>
                                            <div>
                                                <a href="{{ route('admin.trading.setting.index') }}" class="btn btn-sm btn-outline--primary me-2">
                                                    <i class="las la-cog"></i> @lang('Config')
                                                </a>
                                                <button class="btn btn-sm btn-outline--success confirmationBtn" data-question="@lang('Are you sure to enable Trading Module?')" data-action="{{ route('admin.earning.methods.status', 'trading_module') }}">
                                                    <i class="las la-check-circle"></i> @lang('Enable')
                                                </button>
                                            </div>
                                         @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- AI Bot Module -->
                        <div class="col-xl-4 col-md-6 mb-30">
                            <div class="card border-warning">
                                <div class="card-header bg--warning">
                                    <h4 class="card-title text-dark">@lang('AI Bot Module')</h4>
                                </div>
                                <div class="card-body">
                                    <p class="mb-3">Enable or disable Automated AI Bot investment plans for users.</p>
                                    <div class="d-flex justify-content-between align-items-center">
                                         @if(gs('ai_bot_module', 1))
                                            <span class="badge badge--success">@lang('Enabled')</span>
                                            <div>
                                                <a href="{{ route('admin.aibots.index') }}" class="btn btn-sm btn-outline--primary me-2">
                                                    <i class="las la-cog"></i> @lang('Config')
                                                </a>
                                                <button class="btn btn-sm btn-outline--danger confirmationBtn" data-question="@lang('Are you sure to disable AI Bots?')" data-action="{{ route('admin.earning.methods.status', 'ai_bot_module') }}">
                                                    <i class="las la-ban"></i> @lang('Disable')
                                                </button>
                                            </div>
                                         @else
                                            <span class="badge badge--danger">@lang('Disabled')</span>
                                            <div>
                                                <a href="{{ route('admin.aibots.index') }}" class="btn btn-sm btn-outline--primary me-2">
                                                    <i class="las la-cog"></i> @lang('Config')
                                                </a>
                                                <button class="btn btn-sm btn-outline--success confirmationBtn" data-question="@lang('Are you sure to enable AI Bots?')" data-action="{{ route('admin.earning.methods.status', 'ai_bot_module') }}">
                                                    <i class="las la-check-circle"></i> @lang('Enable')
                                                </button>
                                            </div>
                                         @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                         <!-- Mining Module -->
                         <div class="col-xl-4 col-md-6 mb-30">
                            <div class="card border-secondary">
                                <div class="card-header bg--secondary">
                                    <h4 class="card-title text-white">@lang('Mining Module')</h4>
                                </div>
                                <div class="card-body">
                                    <p class="mb-3">Enable or disable Cloud Mining investment features.</p>
                                    <div class="d-flex justify-content-between align-items-center">
                                         @if(gs('mining_module', 1))
                                            <span class="badge badge--success">@lang('Enabled')</span>
                                            <div>
                                                <a href="{{ route('admin.mining.index') }}" class="btn btn-sm btn-outline--primary me-2">
                                                    <i class="las la-cog"></i> @lang('Config')
                                                </a>
                                                <button class="btn btn-sm btn-outline--danger confirmationBtn" data-question="@lang('Are you sure to disable Mining Module?')" data-action="{{ route('admin.earning.methods.status', 'mining_module') }}">
                                                    <i class="las la-ban"></i> @lang('Disable')
                                                </button>
                                            </div>
                                         @else
                                            <span class="badge badge--danger">@lang('Disabled')</span>
                                            <div>
                                                <a href="{{ route('admin.mining.index') }}" class="btn btn-sm btn-outline--primary me-2">
                                                    <i class="las la-cog"></i> @lang('Config')
                                                </a>
                                                <button class="btn btn-sm btn-outline--success confirmationBtn" data-question="@lang('Are you sure to enable Mining Module?')" data-action="{{ route('admin.earning.methods.status', 'mining_module') }}">
                                                    <i class="las la-check-circle"></i> @lang('Enable')
                                                </button>
                                            </div>
                                         @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        


                         <!-- Investment Plans -->
                         <div class="col-xl-4 col-md-6 mb-30">
                            <div class="card border-dark">
                                <div class="card-header bg--dark">
                                    <h4 class="card-title text-white">@lang('Investment Plans')</h4>
                                </div>
                                <div class="card-body">
                                    <p class="mb-3">Enable or disable Standard HYIP Investment Plans.</p>
                                    <div class="d-flex justify-content-between align-items-center">
                                         @if(gs('investment_module') || gs('investment_module', 1))
                                            <span class="badge badge--success">@lang('Enabled')</span>
                                            <div>
                                                <a href="{{ route('admin.plan.index') }}" class="btn btn-sm btn-outline--primary me-2">
                                                    <i class="las la-cog"></i> @lang('Config')
                                                </a>
                                                <button class="btn btn-sm btn-outline--danger confirmationBtn" data-question="@lang('Are you sure to disable Investment Plans?')" data-action="{{ route('admin.earning.methods.status', 'investment_module') }}">
                                                    <i class="las la-ban"></i> @lang('Disable')
                                                </button>
                                            </div>
                                         @else
                                            <span class="badge badge--danger">@lang('Disabled')</span>
                                            <div>
                                                <a href="{{ route('admin.plan.index') }}" class="btn btn-sm btn-outline--primary me-2">
                                                    <i class="las la-cog"></i> @lang('Config')
                                                </a>
                                                <button class="btn btn-sm btn-outline--success confirmationBtn" data-question="@lang('Are you sure to enable Investment Plans?')" data-action="{{ route('admin.earning.methods.status', 'investment_module') }}">
                                                    <i class="las la-check-circle"></i> @lang('Enable')
                                                </button>
                                            </div>
                                         @endif
                                    </div>
                                </div>
                            </div>
                        </div>



                        <!-- Forex Trading Module -->
                        <div class="col-xl-4 col-md-6 mb-30">
                            <div class="card border-success">
                                <div class="card-header bg--success">
                                    <h4 class="card-title text-white">@lang('Forex Trading Module')</h4>
                                </div>
                                <div class="card-body">
                                    <p class="mb-3">Enable or disable simulation Forex Trading features for users.</p>
                                    <div class="d-flex justify-content-between align-items-center">
                                         @if(gs('forex_module'))
                                            <span class="badge badge--success">@lang('Enabled')</span>
                                             <div>
                                                <a href="{{ route('admin.forex.setting') }}" class="btn btn-sm btn-outline--primary me-2">
                                                    <i class="las la-cog"></i> @lang('Config')
                                                </a>
                                                <button class="btn btn-sm btn-outline--danger confirmationBtn" data-question="@lang('Are you sure to disable Forex Trading Module?')" data-action="{{ route('admin.earning.methods.status', 'forex_module') }}">
                                                    <i class="las la-ban"></i> @lang('Disable')
                                                </button>
                                            </div>
                                         @else
                                            <span class="badge badge--danger">@lang('Disabled')</span>
                                             <div>
                                                <a href="{{ route('admin.forex.setting') }}" class="btn btn-sm btn-outline--primary me-2">
                                                    <i class="las la-cog"></i> @lang('Config')
                                                </a>
                                                <button class="btn btn-sm btn-outline--success confirmationBtn" data-question="@lang('Are you sure to enable Forex Trading Module?')" data-action="{{ route('admin.earning.methods.status', 'forex_module') }}">
                                                    <i class="las la-check-circle"></i> @lang('Enable')
                                                </button>
                                            </div>
                                         @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- PTC Ads Module -->
                        <div class="col-xl-4 col-md-6 mb-30">
                            <div class="card border-info">
                                <div class="card-header bg--info">
                                    <h4 class="card-title text-white">@lang('PTC Ads Module')</h4>
                                </div>
                                <div class="card-body">
                                    <p class="mb-3">Enable or disable Pay-Per-Click ads earning system for users.</p>
                                    <div class="d-flex justify-content-between align-items-center">
                                         @if(gs('ptc_module'))
                                            <span class="badge badge--success">@lang('Enabled')</span>
                                            <div>
                                                <a href="{{ route('admin.ptc.index') }}" class="btn btn-sm btn-outline--primary me-2">
                                                    <i class="las la-cog"></i> @lang('Config')
                                                </a>
                                                <button class="btn btn-sm btn-outline--danger confirmationBtn" data-question="@lang('Are you sure to disable PTC Ads Module?')" data-action="{{ route('admin.earning.methods.status', 'ptc_module') }}">
                                                    <i class="las la-ban"></i> @lang('Disable')
                                                </button>
                                            </div>
                                         @else
                                            <span class="badge badge--danger">@lang('Disabled')</span>
                                            <div>
                                                <a href="{{ route('admin.ptc.index') }}" class="btn btn-sm btn-outline--primary me-2">
                                                    <i class="las la-cog"></i> @lang('Config')
                                                </a>
                                                <button class="btn btn-sm btn-outline--success confirmationBtn" data-question="@lang('Are you sure to enable PTC Ads Module?')" data-action="{{ route('admin.earning.methods.status', 'ptc_module') }}">
                                                    <i class="las la-check-circle"></i> @lang('Enable')
                                                </button>
                                            </div>
                                         @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Stock Trading Module -->
                        <div class="col-xl-4 col-md-6 mb-30">
                            <div class="card border-dark">
                                <div class="card-header bg--dark">
                                    <h4 class="card-title text-white">@lang('Stock Trading Module')</h4>
                                </div>
                                <div class="card-body">
                                    <p class="mb-3">Enable or disable simulation Stock Trading features for users.</p>
                                    <div class="d-flex justify-content-between align-items-center">
                                         @if(gs('stock_module'))
                                            <span class="badge badge--success">@lang('Enabled')</span>
                                            <div>
                                                <a href="{{ route('admin.stock.setting') }}" class="btn btn-sm btn-outline--primary me-2">
                                                    <i class="las la-cog"></i> @lang('Config')
                                                </a>
                                                <button class="btn btn-sm btn-outline--danger confirmationBtn" data-question="@lang('Are you sure to disable Stock Trading Module?')" data-action="{{ route('admin.earning.methods.status', 'stock_module') }}">
                                                    <i class="las la-ban"></i> @lang('Disable')
                                                </button>
                                            </div>
                                         @else
                                            <span class="badge badge--danger">@lang('Disabled')</span>
                                            <div>
                                                <a href="{{ route('admin.stock.setting') }}" class="btn btn-sm btn-outline--primary me-2">
                                                    <i class="las la-cog"></i> @lang('Config')
                                                </a>
                                                <button class="btn btn-sm btn-outline--success confirmationBtn" data-question="@lang('Are you sure to enable Stock Trading Module?')" data-action="{{ route('admin.earning.methods.status', 'stock_module') }}">
                                                    <i class="las la-check-circle"></i> @lang('Enable')
                                                </button>
                                            </div>
                                         @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <x-confirmation-modal />
@endsection
