<div id="drawer-navigation" class="fixed top-0 left-0 z-[60] w-[230px] h-screen p-4 overflow-y-auto bg-gradient-to-br from-white/90 to-pink-600/40 backdrop-blur">
    <button onclick="toggleSidebar()" type="button" class="text-gray-50 bg-red-500/70 rounded-lg text-sm p-1.5 absolute top-2.5 end-2.5 inline-flex items-center hover:bg-gray-600 hover:text-white">
        <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
            <path
                fill-rule="evenodd"
                d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                clip-rule="evenodd"
            ></path>
        </svg>
        <span class="sr-only">Close menu</span>
    </button>
                <div class="py-4 overflow-y-auto mt-5">
                    <ul class="space-y-2 font-medium">
                        <li>
                            <a class="flex items-center px-3 py-3 shadow-md rounded-r-full bg-white/90 hover:bg-pink-800/60 group my-3" href="{{ route('home') }}">
                                <img class="w-[20px]" src="https://cdn-icons-png.flaticon.com/128/2569/2569464.png" alt="" /><span class="ms-3 text-pink-500 group-hover:text-white">Home</span>
                            </a>
                            <a class="flex items-center px-3 py-3 shadow-md rounded-r-full bg-white/90 hover:bg-pink-800/60 group my-3" href="{{ route('user.home') }}">
                                <img class="w-[20px]" src="https://cdn-icons-png.flaticon.com/128/2489/2489014.png" alt="" /><span class="ms-3 text-pink-500 group-hover:text-white">Dashboard</span>
                            </a>
                            @if(gs('investment_module'))
                            <a class="flex items-center px-3 py-3 shadow-md rounded-r-full bg-white/90 hover:bg-pink-800/60 group my-3" href="{{ route('user.invest.statistics') }}">
                                <img class="w-[20px]" src="https://cdn-icons-png.flaticon.com/128/3107/3107249.png" alt="" /><span class="ms-3 text-pink-500 group-hover:text-white">My Plans</span>
                            </a>
                            @endif
                            <a class="flex items-center px-3 py-3 shadow-md rounded-r-full bg-white/90 hover:bg-pink-800/60 group my-3" href="{{ route('plan') }}">
                                <img class="w-[20px]" src="https://cdn-icons-png.flaticon.com/128/2953/2953536.png" alt="" /><span class="ms-3 text-pink-500 group-hover:text-white">Deposit</span>
                            </a>
                             <a class="flex items-center px-3 py-3 shadow-md rounded-r-full bg-white/90 hover:bg-pink-800/60 group my-3" href="{{ route('user.deposit.history') }}">
                                <img class="w-[20px]" src="https://cdn-icons-png.flaticon.com/128/7627/7627568.png" alt="" /><span class="ms-3 text-pink-500 group-hover:text-white">Deposit Logs</span>
                            </a>
                            <a class="flex items-center px-3 py-3 shadow-md rounded-r-full bg-white/90 hover:bg-pink-800/60 group my-3" href="{{ route('user.withdraw') }}">
                                <img class="w-[20px]" src="https://cdn-icons-png.flaticon.com/128/2751/2751615.png" alt="" /><span class="ms-3 text-pink-500 group-hover:text-white">Withdraw</span>
                            </a>
                            <a class="flex items-center px-3 py-3 shadow-md rounded-r-full bg-white/90 hover:bg-pink-800/60 group my-3" href="{{ route('user.withdraw.history') }}">
                                <img class="w-[20px]" src="https://cdn-icons-png.flaticon.com/128/2228/2228787.png" alt="" /><span class="ms-3 text-pink-500 group-hover:text-white">Withdraw Logs</span>
                            </a>
                            <a class="flex items-center px-3 py-3 shadow-md rounded-r-full bg-white/90 hover:bg-pink-800/60 group my-3" href="{{ route('user.commissions') }}">
                                <img class="w-[20px]" src="https://cdn-icons-png.flaticon.com/128/6043/6043325.png" alt="" /><span class="ms-3 text-pink-500 group-hover:text-white">Commissions</span>
                            </a>
                            <a class="flex items-center px-3 py-3 shadow-md rounded-r-full bg-white/90 hover:bg-pink-800/60 group my-3" href="{{ route('user.referrals') }}">
                                <img class="w-[20px]" src="https://cdn-icons-png.flaticon.com/128/6543/6543820.png" alt="" /><span class="ms-3 text-pink-500 group-hover:text-white">Referral</span>
                            </a>
                            <a class="flex items-center px-3 py-3 shadow-md rounded-r-full bg-white/90 hover:bg-pink-800/60 group my-3" href="{{ route('user.transactions') }}">
                                <img class="w-[20px]" src="https://cdn-icons-png.flaticon.com/128/6828/6828649.png" alt="" /><span class="ms-3 text-pink-500 group-hover:text-white">Transactions</span>
                            </a>
                            <a class="flex items-center px-3 py-3 shadow-md rounded-r-full bg-white/90 hover:bg-pink-800/60 group my-3" href="{{ route('user.salary') }}">
                                <img class="w-[20px]" src="https://cdn-icons-png.flaticon.com/128/3135/3135706.png" alt="" /><span class="ms-3 text-pink-500 group-hover:text-white">Collect Salary</span>
                            </a>
                            @if(gs('b_transfer'))
                                <a class="flex items-center px-3 py-3 shadow-md rounded-r-full bg-white/90 hover:bg-pink-800/60 group my-3" href="{{ route('user.transfer.balance') }}">
                                    <img class="w-[20px]" src="https://cdn-icons-png.flaticon.com/128/2534/2534183.png" alt="" /><span class="ms-3 text-pink-500 group-hover:text-white">@lang('Transfer Balance')</span>
                                </a>
                            @endif
                            <a class="flex items-center px-3 py-3 shadow-md rounded-r-full bg-white/90 hover:bg-pink-800/60 group my-3" href="{{ route('user.daily.reward') }}">
                                <img class="w-[20px]" src="https://cdn-icons-png.flaticon.com/128/3281/3281223.png" alt="" /><span class="ms-3 text-pink-500 group-hover:text-white">Daily Reward</span>
                            </a>
                            <a class="flex items-center px-3 py-3 shadow-md rounded-r-full bg-white/90 hover:bg-pink-800/60 group my-3" href="{{ route('user.promocode') }}">
                                <img class="w-[20px]" src="https://cdn-icons-png.flaticon.com/128/726/726476.png" alt="" /><span class="ms-3 text-pink-500 group-hover:text-white">Promo Code</span>
                            </a>

                            @if(gs('mining_module'))
                            <a class="flex items-center px-3 py-3 shadow-md rounded-r-full bg-white/90 hover:bg-pink-800/60 group my-3" href="{{ route('user.miners') }}">
                                <img class="w-[20px]" src="https://cdn-icons-png.flaticon.com/128/2108/2108625.png" alt="" /><span class="ms-3 text-pink-500 group-hover:text-white">Cloud Mining</span>
                            </a>
                            <a class="flex items-center px-3 py-3 shadow-md rounded-r-full bg-white/90 hover:bg-pink-800/60 group my-3" href="{{ route('user.mining.log') }}">
                                <img class="w-[20px]" src="https://cdn-icons-png.flaticon.com/128/9850/9850812.png" alt="" /><span class="ms-3 text-pink-500 group-hover:text-white">My Rigs</span>
                            </a>
                            @endif

                            @if(gs('games_module'))
                            <a class="flex items-center px-3 py-3 shadow-md rounded-r-full bg-white/90 hover:bg-pink-800/60 group my-3" href="{{ route('user.games.index') }}">
                                <img class="w-[20px]" src="https://cdn-icons-png.flaticon.com/128/686/686589.png" alt="" /><span class="ms-3 text-pink-500 group-hover:text-white">Games</span>
                            </a>
                            @endif
                            
                            @if(gs('trading_module'))
                            <a class="flex items-center px-3 py-3 shadow-md rounded-r-full bg-white/90 hover:bg-pink-800/60 group my-3" href="{{ route('user.trading') }}">
                                <img class="w-[20px]" src="https://cdn-icons-png.flaticon.com/128/7791/7791781.png" alt="" /><span class="ms-3 text-pink-500 group-hover:text-white">@lang('Options Trading')</span>
                            </a>
                            @endif

                            @if(gs('forex_module'))
                            <a class="flex items-center px-3 py-3 shadow-md rounded-r-full bg-white/90 hover:bg-pink-800/60 group my-3" href="{{ route('user.forex') }}">
                                <img class="w-[20px]" src="https://cdn-icons-png.flaticon.com/128/2285/2285516.png" alt="" /><span class="ms-3 text-pink-500 group-hover:text-white">@lang('Forex Trading')</span>
                            </a>
                            @endif

                            @if(gs('stock_module'))
                            <a class="flex items-center px-3 py-3 shadow-md rounded-r-full bg-white/90 hover:bg-pink-800/60 group my-3" href="{{ route('user.stock.trading') }}">
                                <img class="w-[20px]" src="https://cdn-icons-png.flaticon.com/128/2435/2435227.png" alt="" /><span class="ms-3 text-pink-500 group-hover:text-white">@lang('Stock Trading')</span>
                            </a>
                            @endif
                            @if(gs('ai_bot_module'))
                            <a class="flex items-center px-3 py-3 shadow-md rounded-r-full bg-white/90 hover:bg-pink-800/60 group my-3" href="{{ route('user.ai.bots') }}">
                                <img class="w-[20px]" src="https://cdn-icons-png.flaticon.com/128/2040/2040946.png" alt="" /><span class="ms-3 text-pink-500 group-hover:text-white">@lang('AI Bot Trade')</span>
                            </a>
                            @endif

                            @if(gs('ptc_module'))
                            <a class="flex items-center px-3 py-3 shadow-md rounded-r-full bg-white/90 hover:bg-pink-800/60 group my-3" href="{{ route('user.ptc.index') }}">
                                <img class="w-[20px]" src="https://cdn-icons-png.flaticon.com/128/3133/3133604.png" alt="" /><span class="ms-3 text-pink-500 group-hover:text-white">@lang('PTC - Watch Ads')</span>
                            </a>
                            @endif

                            <a class="flex items-center px-3 py-3 shadow-md rounded-r-full bg-white/90 hover:bg-pink-800/60 group my-3" href="{{ route('user.vip.index') }}">
                                <img class="w-[20px]" src="https://cdn-icons-png.flaticon.com/128/9210/9210217.png" alt="" /><span class="ms-3 text-pink-500 group-hover:text-white">@lang('VIP Plans')</span>
                            </a>

                            <a class="flex items-center px-3 py-3 shadow-md rounded-r-full bg-white/90 hover:bg-pink-800/60 group my-3" href="{{ route('ticket.index') }}">
                                <img class="w-[20px]" src="https://cdn-icons-png.flaticon.com/128/4233/4233830.png" alt="" /><span class="ms-3 text-pink-500 group-hover:text-white">Support</span>
                            </a>
                            <a class="flex items-center px-3 py-3 shadow-md rounded-r-full bg-white/90 hover:bg-pink-800/60 group my-3" href="{{ route('user.app.download') }}">
                                <img class="w-[20px]" src="https://cdn-icons-png.flaticon.com/128/300/300218.png" alt="" /><span class="ms-3 text-pink-500 group-hover:text-white">@lang('App Download')</span>
                            </a>
                            <a class="flex items-center px-3 py-3 shadow-md rounded-r-full bg-white/90 hover:bg-pink-800/60 group my-3" href="{{ route('user.profile.setting') }}">
                                <img class="w-[20px]" src="https://cdn-icons-png.flaticon.com/128/561/561772.png" alt="" /><span class="ms-3 text-pink-500 group-hover:text-white">Settings</span>
                            </a>
                            <a class="flex items-center px-3 py-3 shadow-md rounded-r-full bg-white/90 hover:bg-pink-800/60 group my-3 cursor-pointer" href="{{ route('user.logout') }}">
                                <img class="w-[20px]" src="https://cdn-icons-png.flaticon.com/128/3292/3292455.png" alt="" /><span class="ms-3 text-pink-500 group-hover:text-white">Logout</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>