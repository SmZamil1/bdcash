<header class="flex items-center justify-between pb-2">
    @guest
        <a href="{{ route('user.login') }}" class="w-[50px] h-[50px] rounded-full flex items-center justify-center shadow-lg bg-gradient-to-b from-blue-400 via-blue-500 to-blue-600 transition-all active:scale-90">
            <i class="fi fi-rr-login-lock font-bold text-2xl text-white pt-1"></i>
        </a>
    @else
        <a href="{{ route('user.logout') }}" class="bg-gradient-to-b from-blue-400 via-blue-500 to-blue-600 rounded-full mb-1 transition-all active:scale-95 hover:shadow-blue-500/50 shadow-lg">
            <img src="https://imgur.com/Int8D8M.png" class="w-[50px] h-[50px] rounded-full aspect-square" alt="Logout">
        </a>
    @endguest
    
    <div class="flex items-center gap-3">
        <h1 class="font-extrabold text-2xl font-[Orbitron] text-white tracking-tighter">{{ gs('site_name') }}</h1>
    </div>

    @guest
        <div class="bg-gradient-to-bl from-blue-400 via-blue-500 to-blue-600 rounded-full mb-1 shadow-lg">
            <img src="{{ siteLogo() }}" class="w-[50px] h-[50px] rounded-full aspect-square object-contain p-2" alt="Logo">
        </div>
    @else
        <a href="{{ route('user.home') }}" class="bg-gradient-to-bl from-blue-400 via-blue-500 to-blue-600 rounded-full mb-1 transition-all active:scale-95 hover:shadow-blue-500/50 shadow-lg">
            <img src="https://i.imgur.com/rAPve5a.png" class="w-[50px] h-[50px] rounded-full aspect-square" alt="Profile">
        </a>
    @endguest
</header>
