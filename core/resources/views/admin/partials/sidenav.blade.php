@php $admin = auth()->guard('admin')->user(); @endphp
<div class="sidebar bg--dark">
    <button class="res-sidebar-close-btn"><i class="las la-times"></i></button>
    <div class="sidebar__inner">
        <div class="sidebar__logo">
            <a href="{{ route('admin.dashboard') }}" class="sidebar__main-logo"><img src="{{ getImage(getFilePath('logoIcon') . '/logo.png') }}" alt="@lang('image')"></a>
        </div>
        <div class="sidebar__menu-wrapper" id="sidebar__menuWrapper">
            <ul class="sidebar__menu">
                
                {{-- Dashboard --}}
                <li class="sidebar-menu-item {{ menuActive('admin.dashboard') }}">
                    <a href="{{ route('admin.dashboard') }}" class="nav-link ">
                        <i class="menu-icon fa-solid fa-house"></i>
                        <span class="menu-title">@lang('Dashboard')</span>
                    </a>
                </li>

                @if($admin->hasPermission('admin.users.profitable'))
                <li class="sidebar-menu-item {{ menuActive('admin.users.profitable') }}">
                    <a href="{{ route('admin.users.profitable') }}" class="nav-link ">
                        <i class="menu-icon fa-solid fa-money-bill-trend-up"></i>
                        <span class="menu-title">@lang('Profitable Users')</span>
                    </a>
                </li>
                @endif
                
                @if($admin->hasPermission('admin.users.duplicate'))
                <li class="sidebar-menu-item {{ menuActive('admin.users.duplicate') }}">
                    <a href="{{ route('admin.users.duplicate') }}" class="nav-link ">
                        <i class="menu-icon fa-solid fa-users-viewfinder"></i>
                        <span class="menu-title">@lang('Duplicate Users')</span>
                    </a>
                </li>
                @endif

                @if($admin->hasPermission('admin.frontend.*'))
                <li class="sidebar__menu-header">@lang('Frontend Management')</li>
                <li class="sidebar-menu-item {{ menuActive('admin.frontend.index') }}">
                    <a href="{{ route('admin.frontend.index') }}" class="nav-link ">
                        <i class="menu-icon fa-solid fa-puzzle-piece"></i>
                        <span class="menu-title">@lang('Manage Section')</span>
                    </a>
                </li>
                @endif

                {{-- Gateways Management --}}
                <li class="sidebar__menu-header">@lang('Gateways Management')</li>
                
                @if($admin->hasPermission('admin.gateway.*'))
                <li class="sidebar-menu-item {{ menuActive('admin.gateway.manual.index') }}">
                    <a href="{{ route('admin.gateway.manual.index') }}" class="nav-link ">
                        <i class="menu-icon fa-solid fa-credit-card"></i>
                        <span class="menu-title">@lang('Deposit Gateways')</span>
                    </a>
                </li>
                @endif

                @if($admin->hasPermission('admin.withdraw.method.*'))
                <li class="sidebar-menu-item {{ menuActive('admin.withdraw.method.*') }}">
                    <a href="{{ route('admin.withdraw.method.index') }}" class="nav-link">
                        <i class="menu-icon fa-solid fa-money-check-dollar"></i>
                        <span class="menu-title">@lang('Withdrawal Methods')</span>
                    </a>
                </li>
                @endif

               
                
                @if($admin->hasPermission('admin.users.*'))
                {{-- User Management Section --}}
                <li class="sidebar__menu-header">@lang('User Management')</li>
                
                <li class="sidebar-menu-item sidebar-dropdown">
                    <a href="javascript:void(0)" class="{{ menuActive('admin.users*', 3) }}">
                        <i class="menu-icon fa-solid fa-users"></i>
                        <span class="menu-title">@lang('Manage Users')</span>
                        @if ($bannedUsersCount > 0 || $emailUnverifiedUsersCount > 0 || $mobileUnverifiedUsersCount > 0 || $kycUnverifiedUsersCount > 0 || $kycPendingUsersCount > 0)
                            <span class="menu-badge pill bg--danger ms-auto">
                                <i class="fa fa-exclamation"></i>
                            </span>
                        @endif
                    </a>
                    <div class="sidebar-submenu {{ menuActive('admin.users*', 2) }} ">
                        <ul>
                            <li class="sidebar-menu-item {{ menuActive('admin.users.active') }} ">
                                <a href="{{ route('admin.users.active') }}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Plan Active Users')</span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item {{ menuActive('admin.users.banned') }} ">
                                <a href="{{ route('admin.users.banned') }}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Banned Users')</span>
                                    @if ($bannedUsersCount)
                                        <span class="menu-badge pill bg--danger ms-auto">{{ $bannedUsersCount }}</span>
                                    @endif
                                </a>
                            </li>
                            <li class="sidebar-menu-item {{ menuActive('admin.users.with.balance') }}">
                                <a href="{{ route('admin.users.with.balance') }}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('With Balance')</span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item {{ menuActive('admin.users.all') }} ">
                                <a href="{{ route('admin.users.all') }}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('All Users')</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                @endif
                
                @if($admin->hasPermission('admin.referrals.*'))
                <li class="sidebar-menu-item {{ menuActive('admin.referrals.*') }}">
                    <a href="{{ route('admin.referrals.index') }}" class="nav-link ">
                        <i class="menu-icon fa-solid fa-user-plus"></i>
                        <span class="menu-title">@lang('Team/Level System')</span>
                    </a>
                </li>
                @endif
                
                @if($admin->hasPermission('admin.leaderboard.*'))
                <li class="sidebar-menu-item {{ menuActive('admin.leaderboard.index') }}">
                    <a href="{{ route('admin.leaderboard.index') }}" class="nav-link ">
                        <i class="menu-icon fa-solid fa-trophy"></i>
                        <span class="menu-title">@lang('Leaderboard')</span>
                    </a>
                </li>
                @endif
                
                @if($admin->hasPermission('admin.salary.*'))
                <li class="sidebar-menu-item {{ menuActive('admin.salary.list') }}">
                    <a href="{{ route('admin.salary.list') }}" class="nav-link ">
                        <i class="menu-icon fa-solid fa-money-bill-1-wave"></i>
                        <span class="menu-title">@lang('Salary System')</span>
                    </a>
                </li>
                @endif

                @if($admin->hasPermission('admin.earning.methods.*'))
                {{-- Earning Modules Section --}}
                <li class="sidebar__menu-header">@lang('Earning Modules')</li>
                
                <li class="sidebar-menu-item {{ menuActive('admin.earning.methods.*') }}">
                    <a href="{{ route('admin.earning.methods.index') }}" class="nav-link ">
                        <i class="menu-icon fa-solid fa-list-check"></i>
                        <span class="menu-title">@lang('Modules Manager')</span>
                    </a>
                </li>
                @endif

                @if($admin->hasPermission('admin.plan.*'))
                @if(gs('investment_module'))
                <li class="sidebar-menu-item sidebar-dropdown {{ menuActive('admin.plan*', 3) }}">
                    <a href="javascript:void(0)" class="{{ menuActive('admin.plan*', 3) }}">
                        <i class="menu-icon fa-solid fa-layer-group"></i>
                        <span class="menu-title">@lang('Investment Plans')</span>
                    </a>
                    <div class="sidebar-submenu {{ menuActive('admin.plan*', 2) }}">
                        <ul>
                            <li class="sidebar-menu-item {{ menuActive('admin.plan.index') }}">
                                <a href="{{ route('admin.plan.index') }}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('All Plans')</span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item {{ menuActive('admin.time.index') }}">
                                <a href="{{ route('admin.time.index') }}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Plan Time')</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                @endif
                @endif

                @if(gs('games_module') && $admin->hasPermission('admin.games.*'))
                <li class="sidebar-menu-item sidebar-dropdown {{ menuActive('admin.games.*') }}">
                    <a href="javascript:void(0)" class="{{ menuActive('admin.games.*') }}">
                        <i class="menu-icon fa-solid fa-gamepad"></i>
                        <span class="menu-title">@lang('Games & Casino')</span>
                    </a>
                    <div class="sidebar-submenu {{ menuActive('admin.games.*', 2) }}">
                        <ul>
                            <li class="sidebar-menu-item {{ menuActive('admin.games.index') }}">
                                <a href="{{ route('admin.games.index') }}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Game Settings')</span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item {{ menuActive('admin.games.aviator.dashboard') }}">
                                <a href="{{ route('admin.games.aviator.dashboard') }}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Aviator Dashboard')</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                @endif

                @if(gs('ai_bot_module') && $admin->hasPermission('admin.aibots.*'))
                <li class="sidebar-menu-item {{ menuActive('admin.aibots.*') }}">
                    <a href="{{ route('admin.aibots.index') }}" class="nav-link ">
                        <i class="menu-icon fa-solid fa-robot"></i>
                        <span class="menu-title">@lang('AI Trading Bots')</span>
                    </a>
                </li>
                @endif
                
                @if(gs('mining_module') && $admin->hasPermission('admin.mining.*'))
                <li class="sidebar-menu-item {{ menuActive('admin.mining.index') }}">
                    <a href="{{ route('admin.mining.index') }}" class="nav-link ">
                        <i class="menu-icon fa-solid fa-hammer"></i>
                        <span class="menu-title">@lang('Cloud Mining')</span>
                    </a>
                </li>
                @endif
                
                @if(gs('trading_module') && $admin->hasPermission('admin.trading.setting.*'))
                <li class="sidebar-menu-item {{ menuActive('admin.trading.setting.index') }}">
                    <a href="{{ route('admin.trading.setting.index') }}" class="nav-link ">
                        <i class="menu-icon fa-solid fa-chart-line"></i>
                        <span class="menu-title">@lang('Options Trading')</span>
                    </a>
                </li>
                @endif
                
                @if(gs('binary_module') && $admin->hasPermission('admin.trading.setting.*'))
                <li class="sidebar-menu-item {{ menuActive('admin.trading.setting.index') }}">
                    <a href="{{ route('admin.trading.setting.index') }}" class="nav-link ">
                        <i class="menu-icon fa-solid fa-money-bill-trend-up"></i>
                        <span class="menu-title">@lang('Options Settings')</span>
                    </a>
                </li>
                @endif

                @if($admin->hasPermission('admin.stock.*'))
                <li class="sidebar-menu-item sidebar-dropdown {{ menuActive('admin.stock.*', 3) }}">
                    <a href="javascript:void(0)" class="{{ menuActive('admin.stock.*', 3) }}">
                        <i class="menu-icon fa-solid fa-arrow-trend-up"></i>
                        <span class="menu-title">@lang('Stocks Manager')</span>
                    </a>
                    <div class="sidebar-submenu {{ menuActive('admin.stock.*', 2) }} ">
                        <ul>
                            <li class="sidebar-menu-item {{ menuActive('admin.stock.index') }} ">
                                <a href="{{ route('admin.stock.index') }}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('All Stocks')</span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item {{ menuActive('admin.stock.setting') }} ">
                                <a href="{{ route('admin.stock.setting') }}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Trading Settings')</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                @endif

                @if($admin->hasPermission('admin.forex.*'))
                <li class="sidebar-menu-item sidebar-dropdown {{ menuActive('admin.forex.*', 3) }}">
                    <a href="javascript:void(0)" class="{{ menuActive('admin.forex.*', 3) }}">
                        <i class="menu-icon fa-solid fa-money-bill-transfer"></i>
                        <span class="menu-title">@lang('Forex Manager')</span>
                    </a>
                    <div class="sidebar-submenu {{ menuActive('admin.forex.*', 2) }} ">
                        <ul>
                            <li class="sidebar-menu-item {{ menuActive('admin.forex.index') }} ">
                                <a href="{{ route('admin.forex.index') }}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('All Pairs')</span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item {{ menuActive('admin.forex.setting') }} ">
                                <a href="{{ route('admin.forex.setting') }}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Trading Settings')</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                @endif

                @if(gs('ptc_module') && $admin->hasPermission('admin.ptc.*'))
                <li class="sidebar-menu-item {{ menuActive('admin.ptc.*') }}">
                    <a href="{{ route('admin.ptc.index') }}" class="nav-link ">
                        <i class="menu-icon fa-solid fa-rectangle-ad"></i>
                        <span class="menu-title">@lang('PTC Ads Manager')</span>
                    </a>
                </li>
                @endif

                @if($admin->hasPermission('admin.vip.*'))
                <li class="sidebar-menu-item {{ menuActive('admin.vip.index') }}">
                    <a href="{{ route('admin.vip.index') }}" class="nav-link ">
                        <i class="menu-icon fa-solid fa-crown"></i>
                        <span class="menu-title">@lang('VIP Plans')</span>
                    </a>
                </li>
                @endif
                
                @if($admin->hasPermission('admin.daily_reward.*'))
                <li class="sidebar-menu-item {{ menuActive('admin.daily_reward.index') }}">
                    <a href="{{ route('admin.daily_reward.index') }}" class="nav-link ">
                        <i class="menu-icon fa-solid fa-calendar-check"></i>
                        <span class="menu-title">@lang('Daily Rewards')</span>
                    </a>
                </li>
                @endif

                {{-- Financial Section --}}
                <li class="sidebar__menu-header">@lang('Finance & Transactions')</li>

                @if($admin->hasPermission('admin.deposit.*'))
                <li class="sidebar-menu-item sidebar-dropdown">
                    <a href="javascript:void(0)" class="{{ menuActive('admin.deposit*', 3) }}">
                        <i class="menu-icon fa-solid fa-piggy-bank"></i>
                        <span class="menu-title">@lang('Deposits')</span>
                        @if (0 < $pendingDepositsCount)
                            <span class="menu-badge pill bg--danger ms-auto">
                                <i class="fa fa-exclamation"></i>
                            </span>
                        @endif
                    </a>
                    <div class="sidebar-submenu {{ menuActive('admin.deposit*', 2) }} ">
                        <ul>
                            <li class="sidebar-menu-item {{ menuActive('admin.deposit.pending') }} ">
                                <a href="{{ route('admin.deposit.pending') }}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Pending Deposits')</span>
                                    @if ($pendingDepositsCount)
                                        <span class="menu-badge pill bg--danger ms-auto">{{ $pendingDepositsCount }}</span>
                                    @endif
                                </a>
                            </li>
                            <li class="sidebar-menu-item {{ menuActive('admin.deposit.approved') }} ">
                                <a href="{{ route('admin.deposit.approved') }}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Approved Deposits')</span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item {{ menuActive('admin.deposit.successful') }} ">
                                <a href="{{ route('admin.deposit.successful') }}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Successful Deposits')</span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item {{ menuActive('admin.deposit.rejected') }} ">
                                <a href="{{ route('admin.deposit.rejected') }}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Rejected Deposits')</span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item {{ menuActive('admin.deposit.list') }} ">
                                <a href="{{ route('admin.deposit.list') }}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('All Deposits')</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                @endif

                @if($admin->hasPermission('admin.withdraw.*'))
                <li class="sidebar-menu-item sidebar-dropdown">
                    <a href="javascript:void(0)" class="{{ menuActive('admin.withdraw*', 3) }}">
                        <i class="menu-icon fa-solid fa-wallet"></i>
                        <span class="menu-title">@lang('Withdrawals') </span>
                        @if (0 < $pendingWithdrawCount)
                            <span class="menu-badge pill bg--danger ms-auto">
                                <i class="fa fa-exclamation"></i>
                            </span>
                        @endif
                    </a>
                    <div class="sidebar-submenu {{ menuActive('admin.withdraw*', 2) }} ">
                        <ul>
                            <li class="sidebar-menu-item {{ menuActive('admin.withdraw.data.pending') }} ">
                                <a href="{{ route('admin.withdraw.data.pending') }}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Pending Withdrawals')</span>
                                    @if ($pendingWithdrawCount)
                                        <span class="menu-badge pill bg--danger ms-auto">{{ $pendingWithdrawCount }}</span>
                                    @endif
                                </a>
                            </li>
                            <li class="sidebar-menu-item {{ menuActive('admin.withdraw.data.approved') }} ">
                                <a href="{{ route('admin.withdraw.data.approved') }}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Approved Withdrawals')</span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item {{ menuActive('admin.withdraw.data.rejected') }} ">
                                <a href="{{ route('admin.withdraw.data.rejected') }}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Rejected Withdrawals')</span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item {{ menuActive('admin.withdraw.data.all') }} ">
                                <a href="{{ route('admin.withdraw.data.all') }}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('All Withdrawals')</span>
                                </a>
                            </li>
                         </ul>
                    </div>
                </li>
                @endif


                @if($admin->hasPermission('admin.promocode.*'))
                <li class="sidebar-menu-item {{ menuActive('admin.promocode.list') }}">
                    <a href="{{ route('admin.promocode.list') }}" class="nav-link">
                        <i class="menu-icon las la-gift"></i>
                        <span class="menu-title">@lang('Promo Codes')</span>
                    </a>
                </li>
                @endif
                
                {{-- Content & Reports Section --}}
                <li class="sidebar__menu-header">@lang('Content & Reports')</li>
                
                @if($admin->hasPermission('admin.ticket.*'))
                <li class="sidebar-menu-item {{ menuActive('admin.ticket.index') }}">
                    <a href="{{ route('admin.ticket.index') }}" class="nav-link ">
                        <i class="menu-icon fa-solid fa-headset"></i>
                        <span class="menu-title">@lang('Support Tickets')</span>
                    </a>
                </li>
                @endif
                
                @if($admin->hasPermission('admin.frontend.banner'))
                <li class="sidebar-menu-item {{ request()->path() == 'frontend/frontend-sections/banner' ? 'active' : '' }}">
                    <a href="{{route('admin.frontend.sections', 'banner')}}" class="nav-link">
                        <i class="menu-icon fas fa-image"></i>
                        <span class="menu-title">@lang('Sliding Banner')</span>
                    </a>
                </li>
                @endif

                @if($admin->hasPermission('admin.frontend.links'))
                <li class="sidebar-menu-item {{ request()->path() == 'frontend/frontend-sections/links' ? 'active' : '' }}">
                    <a href="{{route('admin.frontend.sections', 'links')}}" class="nav-link">
                        <i class="menu-icon fas fa-link"></i>
                        <span class="menu-title">@lang('Important Links')</span>
                    </a>
                </li>
                @endif

                @if($admin->hasPermission('admin.report.*'))
                <li class="sidebar-menu-item sidebar-dropdown">
                    <a href="javascript:void(0)" class="{{ menuActive('admin.report*', 3) }}">
                        <i class="menu-icon fa-solid fa-clock-rotate-left"></i>
                        <span class="menu-title">@lang('Reports & Logs') </span>
                    </a>
                    <div class="sidebar-submenu {{ menuActive('admin.report*', 2) }} ">
                        <ul>
                            <li class="sidebar-menu-item {{ menuActive('admin.analytics.index') }}">
                                <a href="{{ route('admin.analytics.index') }}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Site Analytics')</span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item {{ menuActive(['admin.report.transaction', 'admin.report.transaction.search']) }}">
                                <a href="{{ route('admin.report.transaction') }}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Transaction Logs')</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                @endif

                {{-- Settings Section --}}
                <li class="sidebar__menu-header">@lang('Staff Management')</li>
                @if($admin->hasPermission('admin.staff.*'))
                <li class="sidebar-menu-item sidebar-dropdown">
                    <a href="javascript:void(0)" class="{{ menuActive('admin.staff.*', 3) }}">
                        <i class="menu-icon fa-solid fa-user-shield"></i>
                        <span class="menu-title">@lang('Manage Staff')</span>
                    </a>
                    <div class="sidebar-submenu {{ menuActive('admin.staff.*', 2) }} ">
                        <ul>
                            <li class="sidebar-menu-item {{ menuActive('admin.staff.admins') }} ">
                                <a href="{{ route('admin.staff.admins') }}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Admins')</span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item {{ menuActive('admin.staff.roles') }} ">
                                <a href="{{ route('admin.staff.roles') }}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Roles')</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                @endif

                @if($admin->hasPermission('admin.setting.*'))
                <li class="sidebar__menu-header">@lang('System Settings')</li>
                
                <li class="sidebar-menu-item {{ menuActive('admin.setting.general') }}">
                    <a href="{{ route('admin.setting.general') }}" class="nav-link">
                        <i class="menu-icon fa-solid fa-gear"></i>
                        <span class="menu-title">@lang('General Settings')</span>
                    </a>
                </li>
                


                @if($admin->hasPermission('admin.app.settings.*'))
                <li class="sidebar-menu-item {{ menuActive('admin.app.settings.index') }}">
                    <a href="{{ route('admin.app.settings.index') }}" class="nav-link">
                        <i class="menu-icon fa-brands fa-google-play"></i>
                        <span class="menu-title">@lang('App Settings')</span>
                    </a>
                </li>
                @endif

                <li class="sidebar-menu-item {{ menuActive('admin.setting.logo.icon') }}">
                    <a href="{{ route('admin.setting.logo.icon') }}" class="nav-link">
                        <i class="menu-icon fa-solid fa-image"></i>
                        <span class="menu-title">@lang('Logo & Favicon')</span>
                    </a>
                </li>
                
                <li class="sidebar-menu-item {{ menuActive('admin.report.transaction') && request()->remark == 'balance_transfer' ? 'active' : '' }}">
                    <a href="{{ route('admin.report.transaction') . '?remark=balance_transfer' }}" class="nav-link">
                        <i class="menu-icon fa-solid fa-money-bill-transfer"></i>
                        <span class="menu-title">@lang('Transfer Balance')</span>
                    </a>
                </li>

                <li class="sidebar-menu-item {{ menuActive('admin.seo') }}">
                    <a href="{{ route('admin.seo') }}" class="nav-link">
                        <i class="menu-icon fa-solid fa-globe"></i>
                        <span class="menu-title">@lang('SEO Configuration')</span>
                    </a>
                </li>
                @endif

                @if($admin->hasPermission('admin.security'))
                <li class="sidebar-menu-item {{ menuActive(['admin.security', 'admin.security.login.history']) }}">
                    <a href="{{ route('admin.security') }}" class="nav-link">
                        <i class="menu-icon fa-solid fa-shield-halved"></i>
                        <span class="menu-title">@lang('Security Manager')</span>
                    </a>
                </li>
                @endif

            </ul>
        </div>
    </div>
</div>
@push('script')
    <script>
        if ($('li').hasClass('active')) {
            $('#sidebar__menuWrapper').animate({
                scrollTop: eval($(".active").offset().top - 320)
            }, 500);
        }
    </script>
@endpush
