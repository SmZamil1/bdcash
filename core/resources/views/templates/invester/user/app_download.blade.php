<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Download App - {{ gs()->site_name }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f8f9fa;
        }
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }
        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
        .screenshot-img {
            width: auto;
            height: auto;
            max-width: 100%;
            max-height: 400px;
            object-fit: contain;
        }
        .play-store-container {
            background-color: white;
            color: #202124;
            max-width: 480px;
            margin: 0 auto;
            min-height: 100vh;
        }
    </style>
</head>
<body>

<div class="play-store-container">
    {{-- Top Bar (Mobile Play Store Style) --}}
    <div class="flex items-center gap-4 p-4 sticky top-0 bg-white z-50 shadow-sm border-b border-gray-100">
        <a href="javascript:history.back()">
            <i class="fa-solid fa-arrow-left text-[#5f6368] text-xl"></i>
        </a>
        <div class="flex-1 text-lg font-medium text-[#202124] truncate">{{ $setting->app_name ?? gs()->site_name }}</div>
        <i class="fa-solid fa-magnifying-glass text-[#5f6368] text-xl"></i>
        <i class="fa-solid fa-ellipsis-vertical text-[#5f6368] text-xl ml-2 cursor-pointer"></i>
    </div>

    <div class="px-5 pb-10">
        {{-- Header: Icon & Title --}}
        <div class="flex items-start gap-5 mt-4">
            @if(isset($setting) && $setting->app_logo)
                <img src="{{ getImage(getFilePath('app_logo') . '/' . $setting->app_logo) }}" class="w-[72px] h-[72px] rounded-xl shadow-sm object-cover" alt="Icon">
            @else
                <img src="{{ getImage(getFilePath('logoIcon') . '/favicon.png') }}" class="w-[72px] h-[72px] rounded-xl shadow-sm object-contain border border-gray-100" alt="Icon">
            @endif
            <div class="flex-1 overflow-hidden">
                <h1 class="text-2xl font-medium leading-8 text-[#202124] truncate">{{ $setting->app_name ?? gs()->site_name }}</h1>
                <a href="#" class="text-[#01875f] font-medium text-sm block mt-1">{{ $setting->developer ?? 'MyInvest Corp' }}</a>
                <div class="text-[#5f6368] text-xs mt-1">Contains ads • In-app purchases</div>
            </div>
        </div>

        {{-- Stats Row --}}
        <div class="flex items-center justify-between py-6">
            <div class="flex flex-col items-center flex-1 border-r border-gray-200">
                <div class="flex items-center gap-1 font-medium text-[#202124]">
                    <span>{{ $setting->rating ?? '4.8' }}</span> <i class="fa-solid fa-star text-[10px] text-gray-500 mt-0.5"></i>
                </div>
                <div class="text-[10px] text-[#5f6368]">{{ $setting->reviews_count ?? '120K' }} reviews</div>
            </div>
            <div class="flex flex-col items-center flex-1 border-r border-gray-200">
                <div class="font-medium text-[#202124]">{{ $setting->size ?? '18 MB' }}</div>
                <div class="text-[10px] text-[#5f6368]">Size</div>
            </div>
            <div class="flex flex-col items-center flex-1 border-r border-gray-200">
                <div class="font-medium text-[#202124]">Rated 3+</div>
                <div class="text-[10px] text-[#5f6368]"><i class="fa-solid fa-circle-info text-[10px]"></i></div>
            </div>
            <div class="flex flex-col items-center flex-1">
                <div class="font-medium text-[#202124]">{{ $setting->downloads ?? '5M+' }}</div>
                <div class="text-[10px] text-[#5f6368]">Downloads</div>
            </div>
        </div>

        {{-- Install Button --}}
        <div class="w-full mt-2">
             @if(isset($setting) && $setting->download_link)
                <a href="{{ $setting->download_link }}" target="_blank"
                    class="block w-full bg-[#01875f] active:bg-[#017050] text-white text-center font-medium py-2.5 rounded-lg text-sm transition-colors shadow-md">
                    @lang('Install')
                </a>
            @else
                <button disabled class="block w-full bg-[#01875f] opacity-50 text-white text-center font-medium py-2.5 rounded-lg text-sm cursor-not-allowed">
                    @lang('Available Soon')
                </button>
            @endif
        </div>

        {{-- Screenshots Carousel --}}
        <div class="mt-8">
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-lg font-medium text-[#202124]">Screenshots</h3>
                <i class="fa-solid fa-arrow-right text-[#5f6368] text-sm"></i>
            </div>
            <div class="flex gap-4 overflow-x-auto no-scrollbar pb-4">
                 @if(isset($setting) && $setting->screenshots && count($setting->screenshots) > 0)
                    @foreach($setting->screenshots as $screenshot)
                    <div class="flex-shrink-0 bg-white rounded-lg overflow-hidden border border-gray-100 shadow-sm">
                        <img src="{{ getImage(getFilePath('app_screenshots') . '/' . $screenshot) }}" class="h-60 w-auto rounded-lg object-cover" alt="Screenshot">
                    </div>
                    @endforeach
                 @else
                    <img src="https://via.placeholder.com/150x300.png?text=Preview+1" class="h-60 w-auto rounded-lg object-cover border border-gray-200" alt="Preview">
                    <img src="https://via.placeholder.com/150x300.png?text=Preview+2" class="h-60 w-auto rounded-lg object-cover border border-gray-200" alt="Preview">
                    <img src="https://via.placeholder.com/150x300.png?text=Preview+3" class="h-60 w-auto rounded-lg object-cover border border-gray-200" alt="Preview">
                 @endif
            </div>
        </div>

        {{-- About Section --}}
        <div class="mt-6">
            <div class="flex items-center justify-between mb-2">
                <h3 class="text-lg font-medium text-[#202124]">About this app</h3>
                <i class="fa-solid fa-arrow-right text-[#5f6368] text-sm"></i>
            </div>
            <p class="text-[#5f6368] text-sm leading-5 overflow-hidden font-[400]">
                {!! nl2br(e($setting->description ?? 'Secure your financial future with the most advanced investment platform. Real-time tracking and instant withdrawals make this the perfect tool for modern investors.')) !!}
            </p>
        </div>

        {{-- Ratings & Reviews --}}
        <div class="mt-8">
            <h3 class="text-lg font-medium text-[#202124] mb-4">Ratings and reviews</h3>
            <div class="flex items-start gap-4 mb-6">
                <div class="flex flex-col items-center">
                    <div class="text-5xl font-medium text-[#202124]">{{ $setting->rating ?? '4.8' }}</div>
                    <div class="flex text-[#01875f] text-xs gap-0.5 my-1">
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star-half-stroke"></i>
                    </div>
                    <div class="text-xs text-[#5f6368]">{{ $setting->reviews_count ?? '120K' }}</div>
                </div>
                <div class="flex-1 space-y-1 mt-1">
                    {{-- 5 Star --}}
                    <div class="flex items-center gap-3">
                        <span class="text-xs font-bold text-[#202124] w-2">5</span>
                        <div class="flex-1 h-2 bg-gray-100 rounded-full overflow-hidden">
                            <div class="bg-[#01875f] h-full rounded-full" style="width: 85%"></div>
                        </div>
                    </div>
                    {{-- 4 Star --}}
                    <div class="flex items-center gap-3">
                        <span class="text-xs font-bold text-[#202124] w-2">4</span>
                        <div class="flex-1 h-2 bg-gray-100 rounded-full overflow-hidden">
                            <div class="bg-[#01875f] h-full rounded-full" style="width: 10%"></div>
                        </div>
                    </div>
                    {{-- 3 Star --}}
                    <div class="flex items-center gap-3">
                        <span class="text-xs font-bold text-[#202124] w-2">3</span>
                        <div class="flex-1 h-2 bg-gray-100 rounded-full overflow-hidden">
                            <div class="bg-[#01875f] h-full rounded-full" style="width: 3%"></div>
                        </div>
                    </div>
                     {{-- 2 Star --}}
                     <div class="flex items-center gap-3">
                        <span class="text-xs font-bold text-[#202124] w-2">2</span>
                        <div class="flex-1 h-2 bg-gray-100 rounded-full overflow-hidden">
                            <div class="bg-[#01875f] h-full rounded-full" style="width: 1%"></div>
                        </div>
                    </div>
                     {{-- 1 Star --}}
                     <div class="flex items-center gap-3">
                        <span class="text-xs font-bold text-[#202124] w-2">1</span>
                        <div class="flex-1 h-2 bg-gray-100 rounded-full overflow-hidden">
                            <div class="bg-[#01875f] h-full rounded-full" style="width: 1%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                {{-- Review 1 --}}
                <div class="flex gap-4">
                     <img src="https://i.pravatar.cc/150?img=33" class="w-8 h-8 rounded-full" alt="User">
                     <div class="flex-1">
                         <div class="font-medium text-[#202124] text-sm">Sarah Jenkins</div>
                         <div class="flex items-center gap-2 mb-1">
                             <div class="flex text-[#01875f] text-[10px] gap-0.5">
                                 <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                             </div>
                             <span class="text-[10px] text-[#5f6368]">2 days ago</span>
                         </div>
                         <p class="text-[#5f6368] text-xs">This app has completely changed how I manage my portfolio. The real-time updates are a game changer!</p>
                     </div>
                </div>
                 {{-- Review 2 --}}
                 <div class="flex gap-4">
                    <img src="https://i.pravatar.cc/150?img=11" class="w-8 h-8 rounded-full" alt="User">
                    <div class="flex-1">
                        <div class="font-medium text-[#202124] text-sm">Michael Chen</div>
                        <div class="flex items-center gap-2 mb-1">
                            <div class="flex text-[#01875f] text-[10px] gap-0.5">
                                <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                            </div>
                            <span class="text-[10px] text-[#5f6368]">1 week ago</span>
                        </div>
                        <p class="text-[#5f6368] text-xs">Very secure and fast withdrawals. Support team is also very responsive. Highly recommended.</p>
                    </div>
               </div>
            </div>
            <div class="text-center pt-4 pb-8">
                <button class="text-[#01875f] font-medium text-sm">See all reviews</button>
            </div>
        </div>
    </div>
</div>

</body>
</html>
