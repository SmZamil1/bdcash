@php
    use App\Models\User;

    // Get current user IP
    $ipAddress = request()->ip();

    // Check if this IP is banned
    $isBanned = User::where('ban_new_accounts', 1)
                    ->where('ip_address', $ipAddress)
                    ->exists();
@endphp

@if($isBanned)
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, viewport-fit=cover" />
        <meta name="theme-color" content="#000000">
        <title>Access Denied - {{ gs('site_name') }}</title>
        
        <!-- Tailwind CSS -->
        <script src="{{ asset('asset/theme3/assets/tailwind.js') }}"></script>
        <script>
            tailwind.config = {
                theme: {
                    extend: {
                        backgroundImage: {
                            'gradient-radial': 'radial-gradient(var(--tw-gradient-stops))',
                        },
                    },
                },
            };
        </script>
        
        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
        
        <link rel="shortcut icon" type="image/png" href="{{ siteFavicon() }}">
    </head>

    <body class="bg-black text-white min-h-screen overflow-hidden">
        <!-- Background Blur Effects -->
        <div class="fixed top-0 right-[5rem] w-[600px] h-[300px] -translate-y-1/3 translate-x-1/3 rounded-full bg-red-600 blur-[120px] pointer-events-none"></div>
        <div class="fixed top-1/2 left-1/2 w-[500px] h-[500px] -translate-x-1/2 -translate-y-1/2 rounded-full bg-red-600/20 blur-[120px] pointer-events-none"></div>
        <div class="fixed bottom-0 left-[10rem] w-[700px] h-[200px] translate-y-1/2 -translate-x-1/2 rounded-full bg-red-600 blur-[120px] pointer-events-none"></div>

        <!-- Main Content -->
        <div class="relative z-10 min-h-screen flex items-center justify-center px-4">
            <div class="text-center max-w-2xl mx-auto">
                <!-- Logo -->
                <div class="mb-8">
                    <img src="{{ siteLogo() }}" alt="Logo" class="w-32 h-32 mx-auto object-contain">
                </div>

                <!-- Warning Icon -->
                <div class="mb-8">
                    <div class="relative inline-block">
                        <div class="absolute inset-0 bg-gradient-to-r from-red-500 to-orange-600 blur-3xl opacity-30"></div>
                        <div class="relative w-32 h-32 mx-auto bg-gradient-to-br from-red-500/20 to-orange-600/20 rounded-full flex items-center justify-center border-4 border-red-500/50">
                            <i class="fas fa-ban text-6xl text-red-500"></i>
                        </div>
                    </div>
                </div>

                <!-- Error Message -->
                <div class="mb-8">
                    <h1 class="text-4xl md:text-5xl font-bold text-white mb-4">
                        Access Denied
                    </h1>
                    <p class="text-gray-400 text-lg max-w-md mx-auto leading-relaxed mb-4">
                        Your IP address has been blocked from creating new accounts on this platform.
                    </p>
                    <div class="inline-block px-6 py-3 bg-red-500/10 backdrop-blur-md border border-red-500/30 rounded-full">
                        <p class="text-red-400 font-mono text-sm">
                            <i class="fas fa-network-wired mr-2"></i>
                            IP: {{ $ipAddress }}
                        </p>
                    </div>
                </div>

                <!-- Info Box -->
                <div class="mb-8 max-w-md mx-auto">
                    <div class="bg-white/5 backdrop-blur-md border border-white/10 rounded-2xl p-6">
                        <h3 class="text-white font-semibold mb-3 flex items-center justify-center gap-2">
                            <i class="fas fa-info-circle text-blue-400"></i>
                            Why is this happening?
                        </h3>
                        <ul class="text-gray-400 text-sm space-y-2 text-left">
                            <li class="flex items-start gap-2">
                                <i class="fas fa-check text-red-400 mt-1"></i>
                                <span>Your IP address has been flagged for suspicious activity</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <i class="fas fa-check text-red-400 mt-1"></i>
                                <span>Multiple account creation attempts detected</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <i class="fas fa-check text-red-400 mt-1"></i>
                                <span>Security measures to protect our platform</span>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                    <a href="{{ route('home') }}" class="w-full sm:w-auto px-8 py-4 rounded-full bg-gradient-to-r from-blue-500 to-purple-600 text-white font-semibold hover:shadow-lg hover:shadow-blue-500/50 hover:scale-105 transition-all duration-300 flex items-center justify-center gap-2">
                        <i class="fas fa-home"></i>
                        <span>Go to Homepage</span>
                    </a>
                    <a href="{{ route('contact') }}" class="w-full sm:w-auto px-8 py-4 rounded-full bg-white/10 backdrop-blur-md border border-white/20 text-white font-semibold hover:bg-white/20 hover:scale-105 transition-all duration-300 flex items-center justify-center gap-2">
                        <i class="fas fa-envelope"></i>
                        <span>Contact Support</span>
                    </a>
                </div>
            </div>
        </div>
    </body>
    </html>
@else
    @yield('content')
@endif
