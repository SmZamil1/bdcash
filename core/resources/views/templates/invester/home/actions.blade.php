
<div class="grid grid-cols-2 gap-3 mb-6">
    <!-- Login Button -->
    <a href="{{ route('user.login') }}"
        class="relative group overflow-hidden rounded-2xl py-4 shadow-2xl transition-all duration-300 hover:scale-[1.02]">
        <!-- Animated Background -->
        <div class="absolute inset-0 bg-gradient-to-r from-blue-500 via-blue-600 to-purple-600"></div>
        <div
            class="absolute inset-0 bg-gradient-to-r from-transparent via-white/10 to-transparent transform -skew-x-12 translate-x-full group-hover:translate-x-[-200%] transition-transform duration-1000">
        </div>

        <!-- Glow Effect -->
        <div
            class="absolute inset-0 rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-300 shadow-[0_0_30px_rgba(59,130,246,0.5)]">
        </div>

        <!-- Content -->
        <div class="relative z-10 flex items-center justify-between px-6">
            <div class="flex items-center gap-4">
                <div class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center shadow-lg">
                    <svg class="w-7 h-7 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M3 3a1 1 0 011 1v12a1 1 0 11-2 0V4a1 1 0 011-1zm7.707 3.293a1 1 0 010 1.414L9.414 9H17a1 1 0 110 2H9.414l1.293 1.293a1 1 0 01-1.414 1.414l-3-3a1 1 0 010-1.414l3-3a1 1 0 011.414 0z"
                            clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="text-left">
                    <span class="text-white font-black text-lg block">@lang('Login')</span>
                </div>
            </div>
        </div>

        <!-- Border Glow -->
        <div class="absolute inset-0 rounded-2xl border-2 border-white/20"></div>
    </a>

    <!-- Sign Up Button -->
    <a href="{{ route('user.register') }}"
        class="relative group overflow-hidden rounded-2xl py-4 shadow-2xl transition-all duration-300 hover:scale-[1.02]">
        <!-- Animated Background -->
        <div class="absolute inset-0 bg-gradient-to-r from-cyan-500 via-teal-500 to-emerald-600"></div>
        <div
            class="absolute inset-0 bg-gradient-to-r from-transparent via-white/10 to-transparent transform -skew-x-12 translate-x-full group-hover:translate-x-[-200%] transition-transform duration-1000">
        </div>

        <!-- Glow Effect -->
        <div
            class="absolute inset-0 rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-300 shadow-[0_0_30px_rgba(20,184,166,0.5)]">
        </div>

        <!-- Content -->
        <div class="relative z-10 flex items-center justify-between px-6">
            <div class="flex items-center gap-4">
                <div class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center shadow-lg">
                    <svg class="w-7 h-7 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path
                            d="M8 9a3 3 0 100-6 3 3 0 000 6zM8 11a6 6 0 016 6H2a6 6 0 016-6zM16 7a1 1 0 10-2 0v1h-1a1 1 0 100 2h1v1a1 1 0 102 0v-1h1a1 1 0 100-2h-1V7z" />
                    </svg>
                </div>
                <div class="text-left">
                    <span class="text-white font-black text-lg block">@lang('Sign Up')</span>
                </div>
            </div>
        </div>

        <!-- Border Glow -->
        <div class="absolute inset-0 rounded-2xl border-2 border-white/20"></div>
    </a>
</div>
