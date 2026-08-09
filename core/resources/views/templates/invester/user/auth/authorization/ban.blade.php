<html lang="en" data-headlessui-focus-visible="">
    <div id="in-page-channel-node-id" data-channel-name="in_page_channel_hH2IxD"></div>
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        <meta name="description" content="{{ gs()->siteName($pageTitle ?? 'Access Denied') }}" />
        <meta name="keywords" content="{{ gs()->siteName($pageTitle ?? 'Access Denied') }}" />
        <meta name="robots" content="noindex, nofollow" />
        <script src="{{ asset('assets/js/base.js') }}"></script>
        <title>{{ gs()->siteName($pageTitle ?? 'Access Denied') }}</title>
    </head>
    <body class="relative bg-gray-100 !min-h-[100vh] text-white max-w-[480px] mx-auto">
        <div id="root">
            <div id="appCapsule">
                <div class="grid content-center justify-items-center h-[75dvh]">
                    <div class="grid content-center justify-items-center text-center">
                        <img class="w-[80px] mx-auto" src="/assets/global/images/ban-xxl.png" alt="Banned" />
                        <h1 class="text-sm mt-2 text-red-600 font-bold">Access Denied</h1>
                        <p class="mt-1 text-red-500">
                            Your account has been banned by the admin.
                        </p>

                        @if(isset($user->ban_reason) && $user->ban_reason)
                            <p class="mt-2 text-yellow-400">
                                <strong>Reason:</strong> {{ $user->ban_reason }}
                            </p>
                        @endif

                        {{-- Contact Admin Section --}}
                        @php
                            $dashboardLinks = getContent('links.content', true);
                            $links = [
                                ['url' => $dashboardLinks->data_values->whatsapp ?? '#', 'label' => 'Whatsapp', 'img' => '/assets/global/images/kzvakN0uL3PGPGwT1iLk.png'],
                                ['url' => $dashboardLinks->data_values->telegram ?? '#', 'label' => 'Telegram', 'img' => '/assets/global/images/iwXatOzZYDs1Sa2ogQNg.png'],
                                ['url' => $dashboardLinks->data_values->youtube ?? '#', 'label' => 'Youtube', 'img' => '/assets/global/images/eGImZjCXj5mLGz3tmjnE.png'],
                                ['url' => $dashboardLinks->data_values->app ?? '#', 'label' => 'App Download', 'img' => '/assets/global/images/JonUhD9Wjpst2WumLoHk.png'],
                            ];
                        @endphp

                        <div class="my-4">
                            <h2 class="text-black font-semibold mb-2">Contact Admin</h2>
                            <div class="grid grid-cols-4 gap-4 justify-items-center">
                                @foreach($links as $link)
                                    @if($link['url'] != '#')
                                        <a href="{{ $link['url'] }}" target="_blank" class="grid items-center">
                                            <div class="justify-self-center bg-white border-2 border-gray-400 rounded-[20px] w-[65px] h-[65px] p-1 hover:shadow-[0px_0px_8px_5px_gray]">
                                                <img class="w-full h-full" src="{{ $link['img'] }}" alt="{{ $link['label'] }}">
                                            </div>
                                            <h1 class="text-[13px] font-semibold truncate text-black mt-1 text-center">
                                                {{ $link['label'] }}
                                            </h1>
                                        </a>
                                    @endif
                                @endforeach
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <div style="position: fixed; z-index: 9999; inset: 16px; pointer-events: none;"></div>
        </div>
    </body>
</html>
