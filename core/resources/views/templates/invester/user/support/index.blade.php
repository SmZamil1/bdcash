<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>{{ $pageTitle }}</title>

    <!-- Google Fonts: Roboto -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">

    <!-- Material Symbols Rounded -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,1,0" />

    <style>
        :root {
            --bg-color: #0B141A;
            --header-bg: #0B141A; /* Revert to Black for OLED look */
            --item-hover: #202C33;
            --text-primary: #E9EDEF;
            --text-secondary: #8696A0;
            --accent-green: #25D366;
            --fab-green: #25D366;
            --search-bg: #202C33;

            /* Chips */
            --chip-bg: #202C33;
            --chip-text: #8696A0;
            --chip-active-bg: #0F3529; /* Darker Green Pill */
            --chip-active-text: #00A884; /* Brighter Green Text */

            --nav-bg: #0B141A;
            --nav-indicator-bg: #0F3529;
            --separator: #1f2c34;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            -webkit-tap-highlight-color: transparent;
        }

        body {
            background-color: var(--bg-color);
            color: var(--text-primary);
            font-family: 'Roboto', sans-serif;
            height: 100vh;
            overflow: hidden;
        }

        /* Material Symbols */
        .material-symbols-rounded {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
            font-size: 24px;
        }

        .material-symbols-rounded.filled-icon {
            font-variation-settings: 'FILL' 1;
        }

        .app-container {
            display: flex;
            flex-direction: column;
            height: 100%;
            max-width: 480px;
            margin: 0 auto;
            background-color: var(--bg-color);
            position: relative;
        }

        /* Header */
        .header {
            background-color: var(--header-bg);
            padding: 12px 14px 6px;
            flex-shrink: 0;
        }

        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 12px;
            height: 30px;
        }

        .logo {
            font-size: 23px;
            font-weight: 700;
            color: #fff;
            letter-spacing: 0.5px;
        }

        .header-icons {
            display: flex;
            gap: 18px;
            align-items: center;
        }

        .header-icons .icon-btn {
            color: #fff;
            font-size: 26px;
            cursor: pointer;
        }

        /* Search Bar */
        .search-container {
            margin-bottom: 12px;
        }

        .search-bar {
            background-color: var(--search-bg);
            border-radius: 24px;
            display: flex;
            align-items: center;
            padding: 0 16px;
            height: 46px;
        }

        .meta-ring {
            width: 18px;
            height: 18px;
            border-radius: 50%;
            border: 3px solid transparent;
            background: linear-gradient(135deg, #2dbdff, #25D366, #cf23cf) border-box;
            -webkit-mask: linear-gradient(#fff 0 0) padding-box, linear-gradient(#fff 0 0);
            mask-composite: exclude;
            margin-right: 12px;
        }

        .search-bar input {
            background: transparent;
            border: none;
            color: var(--text-primary);
            font-size: 16px;
            width: 100%;
            outline: none;
        }

        .search-bar input::placeholder {
            color: var(--text-secondary);
        }

        /* Chips */
        .filter-chips {
            display: flex;
            gap: 8px;
            overflow-x: auto;
            scrollbar-width: none;
            padding-bottom: 8px;
        }

        .filter-chips::-webkit-scrollbar {
            display: none;
        }

        .chip {
            background-color: var(--chip-bg);
            color: var(--chip-text);
            padding: 7px 14px;
            border-radius: 18px;
            font-size: 14px;
            font-weight: 500;
            white-space: nowrap;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .chip.active {
            background-color: var(--chip-active-bg);
            color: var(--chip-active-text);
        }

        /* List Container */
        .chat-list-container {
            flex: 1;
            overflow-y: auto;
            padding-bottom: 90px;
        }

        /* Archived */
        .archived-row {
            display: flex;
            align-items: center;
            padding: 14px 16px;
            cursor: pointer;
        }

        .archived-icon {
            margin-right: 16px;
            width: 48px;
            display: flex;
            justify-content: center;
            color: var(--text-secondary);
        }

        .archived-icon span {
            font-size: 22px;
        }

        .archived-text {
            flex: 1;
            font-size: 16px;
            font-weight: 500;
            color: var(--text-primary);
        }

        .archived-count {
            color: var(--accent-green);
            font-size: 13px;
            font-weight: 500;
        }

        /* Chat Item */
        .chat-item {
            display: flex;
            padding: 10px 16px;
            cursor: pointer;
            position: relative;
            height: 72px;
        }

        .chat-item:active {
            background-color: var(--item-hover);
        }

        .avatar-container {
            margin-right: 14px;
        }

        .avatar {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            object-fit: cover;
        }

        .chat-info {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            min-width: 0;
        }

        .chat-header-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 4px;
        }

        .chat-name {
            font-size: 17px;
            font-weight: 500;
            color: var(--text-primary);
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .chat-time {
            font-size: 12px;
            color: var(--text-secondary);
            white-space: nowrap;
        }

        .chat-time.active {
            color: var(--accent-green);
        }

        .chat-preview {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .last-message {
            color: var(--text-secondary);
            font-size: 14px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            display: flex;
            align-items: center;
        }

        .unread-badge {
            background-color: var(--accent-green);
            color: #0B141A;
            font-size: 11px;
            font-weight: 700;
            border-radius: 50%;
            min-width: 18px;
            height: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0 4px;
        }

        /* FAB */
        .fab-container {
            position: absolute;
            bottom: 96px;
            right: 16px;
            z-index: 20;
        }

        .fab {
            width: 56px;
            height: 56px;
            background-color: var(--fab-green);
            border: none;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.5);
            color: #0B141A;
            cursor: pointer;
            text-decoration: none;
        }

        .fab span {
            font-size: 26px;
            font-variation-settings: 'FILL' 0, 'wght' 600;
        }

        /* Bottom Nav */
        .bottom-nav {
            display: flex;
            justify-content: space-around;
            align-items: center;
            height: 80px;
            background-color: var(--nav-bg);
            border-top: 1px solid var(--separator);
            position: absolute;
            bottom: 0;
            width: 100%;
            z-index: 10;
        }

        .nav-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            width: 25%;
            cursor: pointer;
            padding-bottom: 12px;
        }

        .icon-indicator {
            padding: 4px 20px;
            border-radius: 20px;
            margin-bottom: 4px;
            position: relative;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .nav-item.active .icon-indicator {
            background-color: var(--nav-indicator-bg);
        }

        .nav-item.active span.material-symbols-rounded {
            color: #D1E4DE;
        }

        .nav-item.active .nav-label {
            font-weight: 700;
            color: #D1E4DE;
        }

        .nav-label {
            font-size: 12px;
            font-weight: 500;
            color: var(--text-primary);
        }

        .badge {
            position: absolute;
            top: -6px;
            right: 50%;
            transform: translateX(18px);
            background: var(--accent-green);
            color: #0B141A;
            font-size: 10px;
            font-weight: 800;
            border-radius: 10px;
            padding: 1px 5px;
        }
    </style>
</head>

<body>

    <div class="app-container">
        <!-- Header -->
        <header class="header">
            <div class="top-bar">
                <h1 class="logo">WhatsApp</h1>
                <div class="header-icons">
                     <a href="{{ route('ticket.open') }}" style="color:white; text-decoration:none;"><span class="material-symbols-rounded icon-btn">camera_alt</span></a>
                    <span class="material-symbols-rounded icon-btn">more_vert</span>
                </div>
            </div>

            <div class="search-container">
                <div class="search-bar">
                    <!-- Meta AI Ring / Search Icon -->
                    <div class="meta-ring"></div>
                    <input type="text" id="searchInput" placeholder="Ask Meta AI or Search">
                </div>
            </div>

            <div class="filter-chips">
                <div class="chip active">All</div>
                <div class="chip">Unread <span class="chip-count"></span></div>
                <div class="chip">Favourites</div>
                <div class="chip">Groups </div>
                <div class="chip" style="padding: 7px 10px;"><span class="material-symbols-rounded" style="font-size: 18px;">add</span></div>
            </div>
        </header>

        <!-- Chat List Area -->
        <div class="chat-list-container">


            <!-- Chat List -->
            <div class="chat-list" id="chatList">
                 @forelse($supports as $support)
                    <a href="{{ route('ticket.view', $support->ticket) }}" class="chat-item-link" style="text-decoration: none;">
                        <div class="chat-item">
                            <div class="avatar-container">
                                <!-- Fixed Admin/Support Avatar -->
                                <img src="https://ui-avatars.com/api/?name=Support&background=00a884&color=fff&size=128&bold=true" alt="Avatar" class="avatar">
                            </div>
                            <div class="chat-info">
                                <div class="chat-header-row">
                                    <span class="chat-name">
                                        Admin Support
                                    </span>
                                    <span class="chat-time {{ $support->status == 1 ? 'active' : '' }}">
                                        {{ $support->last_reply ? diffForHumans($support->last_reply) : $support->created_at->format('d/m/y') }}
                                    </span>
                                </div>
                                <div class="chat-preview">
                                    <span class="last-message" style="{{ $support->status == 1 ? 'color: var(--text-primary); font-weight: 600;' : '' }}">
                                        @php
                                            $lastMessage = $support->supportMessage->last();
                                            $lastMsgText = 'No messages yet';
                                            $hasAttachment = false;
                                            
                                            if($lastMessage) {
                                                if($lastMessage->message) {
                                                    $lastMsgText = strLimit($lastMessage->message, 30);
                                                } elseif($lastMessage->attachments->count() > 0) {
                                                    $firstAttachment = $lastMessage->attachments->first();
                                                    $ext = pathinfo($firstAttachment->attachment, PATHINFO_EXTENSION);
                                                    if(in_array(strtolower($ext), ['webm','mp3','wav','ogg'])) {
                                                        $lastMsgText = '<span class="material-symbols-rounded" style="font-size: 16px; vertical-align: middle; margin-right: 2px;">mic</span> Voice Message';
                                                    } elseif(in_array(strtolower($ext), ['jpg','jpeg','png'])) {
                                                        $lastMsgText = '<span class="material-symbols-rounded" style="font-size: 16px; vertical-align: middle; margin-right: 2px;">photo_camera</span> Photo';
                                                    } else {
                                                        $lastMsgText = '<span class="material-symbols-rounded" style="font-size: 16px; vertical-align: middle; margin-right: 2px;">attach_file</span> File';
                                                    }
                                                    $hasAttachment = true;
                                                }
                                            }
                                        @endphp

                                        @if($support->status == 0)
                                            <span class="material-symbols-rounded" style="font-size: 16px; margin-right: 2px;">done</span>
                                        @elseif($support->status == 1)
                                            <!-- Admin replied -->
                                        @elseif($support->status == 2)
                                            <span class="material-symbols-rounded" style="font-size: 16px; margin-right: 2px; color: var(--accent-green);">done_all</span>
                                        @elseif($support->status == 3)
                                            <!-- Closed -->
                                        @endif
                                        
                                        {!! $lastMsgText !!}
                                    </span>
                                    @if($support->status == 1)
                                        <div style="display: flex; flex-direction: column; align-items: flex-end;">
                                            <span class="unread-badge" style="margin-bottom: 2px;">1</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </a>
                @empty
                    <!-- No tickets found? Show default 'Admin Support' contact to start new chat -->
                     <a href="{{ route('ticket.open') }}" class="chat-item-link" style="text-decoration: none;">
                        <div class="chat-item">
                            <div class="avatar-container">
                                <img src="https://ui-avatars.com/api/?name=Support&background=00a884&color=fff&size=128&bold=true" alt="Avatar" class="avatar">
                            </div>
                            <div class="chat-info">
                                <div class="chat-header-row">
                                    <span class="chat-name">
                                        Admin Support
                                    </span>
                                    <span class="chat-time">
                                        Now
                                    </span>
                                </div>
                                <div class="chat-preview">
                                    <span class="last-message">
                                        Start a new conversation
                                    </span>
                                </div>
                            </div>
                        </div>
                    </a>
                @endforelse
            </div>
             @if ($supports->hasPages())
                <div style="padding: 10px;">
                    {{ paginateLinks($supports) }}
                </div>
            @endif
        </div>

        <!-- Coming Soon Container -->
        <div class="coming-soon-container" style="display: none; flex: 1; justify-content: center; align-items: center; flex-direction: column; text-align: center; height: 100%; color: var(--text-secondary);">
            <span class="material-symbols-rounded" style="font-size: 64px; margin-bottom: 20px;">pending</span>
            <h2 style="color: var(--text-primary); margin-bottom: 10px;">Coming Soon</h2>
            <p>This feature is currently under development.</p>
        </div>

        <!-- Floating Action Button (FAB) -->
        <div class="fab-container">
            <a href="{{ route('ticket.open') }}" class="fab">
                <span class="material-symbols-rounded filled-icon">add_comment</span>
            </a>
        </div>

        <!-- Bottom Navigation -->
        <nav class="bottom-nav">
            <div class="nav-item active">
                <div class="icon-indicator">
                    <span class="material-symbols-rounded filled-icon">chat</span>
                    <span class="badge">{{ $supports->count() }}</span>
                </div>
                <span class="nav-label">Chats</span>
            </div>
            <div class="nav-item">
                <div class="icon-indicator">
                    <span class="material-symbols-rounded">update</span>
                </div>
                <span class="nav-label">Updates</span>
            </div>
            <div class="nav-item">
                <div class="icon-indicator">
                    <span class="material-symbols-rounded">groups</span>
                </div>
                <span class="nav-label">Communities</span>
            </div>
             <a href="{{ route('user.home') }}" class="nav-item" style="text-decoration:none;">
                <div class="icon-indicator">
                    <span class="material-symbols-rounded">arrow_back</span>
                </div>
                <span class="nav-label">Back</span>
            </a>
        </nav>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        (function ($) {
            "use strict";
            // Simple filter for chips or search
            $('#searchInput').on('keyup', function() {
                var value = $(this).val().toLowerCase();
                $("#chatList .chat-item-link").filter(function() {
                    $(this).toggle($(this).find('.chat-name').text().toLowerCase().indexOf(value) > -1)
                });
            });
            
            $('.chip').on('click', function() {
                $('.chip').removeClass('active');
                $(this).addClass('active');
            });

            // Nav handling
            $('.nav-item').on('click', function() {
                 // If it's the back button (which is an anchor), let it behave normally (or handled by route)
                if ($(this).is('a')) return;

                // Remove active class from all
                $('.nav-item').removeClass('active');
                $(this).addClass('active');

                var label = $(this).find('.nav-label').text().trim();

                if (label === 'Chats') {
                    $('.chat-list-container').show();
                    $('.coming-soon-container').hide();
                    $('.fab-container').show();
                    $('.filter-chips').css('display', 'flex');
                } else if (label === 'Updates' || label === 'Communities') {
                    $('.chat-list-container').hide();
                    $('.fab-container').hide();
                    $('.filter-chips').hide();
                    $('.coming-soon-container').css('display', 'flex');
                }
            });
        })(jQuery);
    </script>
</body>

</html>
