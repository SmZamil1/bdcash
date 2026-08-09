<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>404 - Page Not Found</title>
    
    <!-- Scripts & Config -->
    <script src="{{ asset('assets/global/js/firebase/firebase-configs.js') }}"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@900&family=Share+Tech+Mono&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <style>
        body {
            background-color: #000;
            color: #fff;
            font-family: 'Share Tech Mono', monospace;
            overflow: hidden;
        }
        
        .glitch-wrapper {
            position: relative;
        }

        .glitch {
            font-family: 'Montserrat', sans-serif;
            font-size: 15rem;
            font-weight: 900;
            line-height: 1;
            position: relative;
            color: #fff;
            letter-spacing: -10px;
            text-shadow: 2px 2px 0px #0b64f4, -2px -2px 0px #ab296a;
        }

        .glitch::before,
        .glitch::after {
            content: attr(data-text);
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: #000;
        }

        .glitch::before {
            left: 2px;
            text-shadow: -1px 0 #ab296a;
            clip: rect(24px, 550px, 90px, 0);
            animation: glitch-anim-2 3s infinite linear alternate-reverse;
        }

        .glitch::after {
            left: -2px;
            text-shadow: -1px 0 #0b64f4;
            clip: rect(85px, 550px, 140px, 0);
            animation: glitch-anim 2.5s infinite linear alternate-reverse;
        }

        @keyframes glitch-anim {
            0% { clip: rect(10px, 9999px, 30px, 0); transform: skew(0.55deg); }
            10% { clip: rect(50px, 9999px, 70px, 0); transform: skew(0.35deg); }
            20% { clip: rect(20px, 9999px, 90px, 0); transform: skew(0.15deg); }
            30% { clip: rect(80px, 9999px, 100px, 0); transform: skew(0.45deg); }
            40% { clip: rect(10px, 9999px, 50px, 0); transform: skew(0.25deg); }
            50% { clip: rect(30px, 9999px, 60px, 0); transform: skew(0.65deg); }
            60% { clip: rect(70px, 9999px, 110px, 0); transform: skew(0.05deg); }
            70% { clip: rect(10px, 9999px, 40px, 0); transform: skew(0.35deg); }
            80% { clip: rect(50px, 9999px, 80px, 0); transform: skew(0.6deg); }
            90% { clip: rect(20px, 9999px, 140px, 0); transform: skew(0.85deg); }
            100% { clip: rect(40px, 9999px, 90px, 0); transform: skew(0.05deg); }
        }

        @keyframes glitch-anim-2 {
            0% { clip: rect(60px, 9999px, 10px, 0); transform: skew(0.15deg); }
            5% { clip: rect(90px, 9999px, 140px, 0); transform: skew(0.55deg); }
            10% { clip: rect(20px, 9999px, 50px, 0); transform: skew(0.75deg); }
            15% { clip: rect(10px, 9999px, 80px, 0); transform: skew(0.35deg); }
            20% { clip: rect(50px, 9999px, 20px, 0); transform: skew(0.05deg); }
            25% { clip: rect(80px, 9999px, 120px, 0); transform: skew(0.25deg); }
            30% { clip: rect(30px, 9999px, 70px, 0); transform: skew(0.65deg); }
            35% { clip: rect(70px, 9999px, 30px, 0); transform: skew(0.45deg); }
            40% { clip: rect(10px, 9999px, 90px, 0); transform: skew(0.85deg); }
            45% { clip: rect(40px, 9999px, 60px, 0); transform: skew(0.15deg); }
            50% { clip: rect(80px, 9999px, 10px, 0); transform: skew(0.55deg); }
            55% { clip: rect(20px, 9999px, 130px, 0); transform: skew(0.9deg); }
            60% { clip: rect(60px, 9999px, 40px, 0); transform: skew(0.35deg); }
            65% { clip: rect(10px, 9999px, 80px, 0); transform: skew(0.55deg); }
            70% { clip: rect(90px, 9999px, 20px, 0); transform: skew(0.75deg); }
            75% { clip: rect(30px, 9999px, 110px, 0); transform: skew(0.25deg); }
            80% { clip: rect(70px, 9999px, 50px, 0); transform: skew(0.15deg); }
            85% { clip: rect(10px, 9999px, 140px, 0); transform: skew(0.45deg); }
            90% { clip: rect(40px, 9999px, 80px, 0); transform: skew(0.65deg); }
            95% { clip: rect(50px, 9999px, 30px, 0); transform: skew(0.35deg); }
            100% { clip: rect(20px, 9999px, 70px, 0); transform: skew(0.85deg); }
        }

        .cyber-btn {
            background: transparent;
            border: 1px solid #0b64f4;
            color: #0b64f4;
            position: relative;
            overflow: hidden;
            transition: 0.3s;
        }
        .cyber-btn:hover {
            background: #0b64f4;
            color: #fff;
            box-shadow: 0 0 20px #0b64f4;
        }
        
    </style>
</head>

<body class="flex flex-col items-center justify-center min-h-screen relative">

    <!-- Scan Lines -->
    <div class="fixed inset-0 pointer-events-none z-0 opacity-10" style="background: linear-gradient(rgba(18, 16, 16, 0) 50%, rgba(0, 0, 0, 0.25) 50%), linear-gradient(90deg, rgba(255, 0, 0, 0.06), rgba(0, 255, 0, 0.02), rgba(0, 0, 255, 0.06)); background-size: 100% 2px, 3px 100%;"></div>

    <div class="z-10 text-center px-4">
        <div class="glitch-wrapper mb-8">
            <h1 class="glitch md:text-[200px] text-[120px]" data-text="404">404</h1>
        </div>
        
        <h2 class="text-2xl md:text-3xl text-blue-400 mb-6 uppercase tracking-[0.2em] font-bold">System Failure // Page Not Found</h2>
        
        <p class="max-w-md mx-auto text-gray-400 mb-10 text-sm md:text-base leading-relaxed border-l-2 border-blue-500 pl-4 text-left">
            > Error Code: 404_NOT_FOUND<br>
            > Analysis: The requested URL was not found on this server.<br>
            > Suggestion: Return to base immediately.
        </p>
        
        <div class="flex flex-col sm:flex-row gap-5 justify-center">
            <a href="{{ url('/') }}" class="cyber-btn px-8 py-3 uppercase tracking-widest font-bold text-sm">
                <i class="fas fa-home mr-2"></i> Dashboard
            </a>
            <a href="javascript:history.back()" class="cyber-btn px-8 py-3 uppercase tracking-widest font-bold text-sm !border-pink-600 !text-pink-600 hover:!bg-pink-600 hover:!text-white hover:!shadow-[0_0_20px_#db2777]">
                <i class="fas fa-reply mr-2"></i> Go Back
            </a>
        </div>
    </div>

    <!-- Random code decoration -->
    <div class="fixed bottom-4 right-4 text-[10px] text-gray-700 font-mono hidden md:block text-right">
        ID: 7712-XG<br>
        MEM: 64TB<br>
        STATUS: ERR
    </div>

</body>
</html>
