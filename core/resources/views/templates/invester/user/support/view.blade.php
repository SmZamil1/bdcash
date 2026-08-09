<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Support Chat - {{ $myTicket->ticket }}</title>

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
            --header-bg: #202C33;
            --item-hover: #202C33;
            --text-primary: #E9EDEF;
            --text-secondary: #8696A0;
            --accent-green: #00a884;
            --message-sent: #005c4b;
            --message-received: #202c33;
            --search-bg: #202C33;
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

        .chat-app-container {
            display: flex;
            flex-direction: column;
            height: 100%;
            max-width: 480px;
            margin: 0 auto;
            background-color: var(--bg-color);
            position: relative;
        }

        /* Header */
        .chat-header {
            background-color: var(--header-bg);
            padding: 10px 10px; /* Reducing padding slightly */
            display: flex;
            align-items: center;
            color: var(--text-primary);
            z-index: 20;
            height: 60px;
            border-bottom: 1px solid rgba(255,255,255,0.05);
            user-select: none; /* Prevent text selection artifacts */
        }

        .chat-header-info {
            flex: 1;
            display: flex;
            align-items: center;
            margin-left: 5px; /* Tighter spacing */
            overflow: hidden;
            cursor: pointer; /* Clickable feel */
        }

        .header-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-right: 10px;
            object-fit: cover;
        }

        .header-text {
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .header-name {
            font-size: 16px;
            font-weight: 500;
            color: var(--text-primary);
            line-height: normal;
        }

        .header-status {
            font-size: 13px;
            color: var(--text-secondary);
            line-height: normal;
            margin-top: 1px;
        }
        
        .back-btn {
            cursor: pointer;
            color: var(--text-primary);
            display: flex;
            align-items: center;
            padding: 5px;
            border-radius: 50%;
        }
        
        .back-btn:active {
            background-color: rgba(255,255,255,0.1);
        }

        .chat-header-actions {
            display: flex;
            align-items: center;
        }

        /* Messages Area */
        .messages-area {
            flex: 1;
            overflow-y: auto;
            position: relative;
            display: flex;
            flex-direction: column;
            padding: 20px 16px;
            gap: 4px;
            background-color: #0b141a;
            background-image: linear-gradient(rgba(11, 20, 26, 0.92), rgba(11, 20, 26, 0.92)), url("https://user-images.githubusercontent.com/15075759/28719144-86dc0f70-73b1-11e7-911d-60d70fcded21.png");
            background-size: cover;
            background-attachment: fixed;
        }

        .message-row {
            display: flex;
            width: 100%;
            margin-bottom: 4px;
        }

        .message-row.sent {
            justify-content: flex-end;
        }

        .message-row.received {
            justify-content: flex-start;
        }

        .message-avatar {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            overflow: hidden;
            flex-shrink: 0;
            margin-bottom: 5px; /* Align with bottom of bubble */
        }
        
        .message-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .message-bubble {
            max-width: 70%;
            padding: 6px 9px 8px 9px;
            border-radius: 12px;
            position: relative;
            font-size: 14.2px;
            line-height: 19px;
            box-shadow: 0 1px 0.5px rgba(0, 0, 0, 0.13);
            color: var(--text-primary);
            word-wrap: break-word;
        }

        .sent .message-bubble {
            background-color: var(--message-sent);
            border-top-right-radius: 0;
            order: 1; /* Bubble first */
        }
        
        .sent .message-avatar {
            order: 2; /* Avatar second */
            margin-left: 6px;
            display: none; /* Hide user avatar to look like WhatsApp */
        }

        .received .message-bubble {
            background-color: var(--message-received);
            border-top-left-radius: 0;
        }
        
        .received .message-avatar {
            margin-right: 6px;
        }
        
        .sender-name {
            font-size: 13px;
            font-weight: 500;
            color: #d86c26; /* Randomish color for name */
            margin-bottom: 2px;
            line-height: 15px;
            display: none; /* Hide name since we have avatar/WhatsApp style */
        }

        .message-meta {
            float: right;
            margin-left: 7px;
            margin-top: 4px;
            display: flex;
            align-items: center;
            gap: 3px;
            position: relative;
            top: 3px;
        }

        .message-meta .time {
            font-size: 11px;
            color: rgba(255, 255, 255, 0.6);
        }
        
        .icon-status {
            font-size: 16px;
        }
        
        .icon-status.read {
            color: #53bdeb; /* Blue ticks */
        }

        /* Footer */
        .chat-footer {
            min-height: 60px;
            display: flex;
            align-items: center;
            padding: 6px 10px;
            background-color: var(--header-bg);
            z-index: 30;
        }

        .input-wrapper {
            flex: 1;
            background-color: #2A3942;
            border-radius: 24px;
            display: flex;
            align-items: flex-end;
            padding: 8px 12px;
            margin-right: 8px;
            min-height: 45px;
        }

        .input-wrapper .chat-input {
            flex: 1;
            background: transparent;
            border: none;
            color: var(--text-primary);
            font-size: 15px;
            padding: 0 10px;
            outline: none;
            max-height: 100px;
            overflow-y: auto;
            align-self: center;
        }
        
        .input-wrapper .chat-input::placeholder {
            color: var(--text-secondary);
        }

        .icon-btn {
            color: var(--text-secondary);
            font-size: 26px;
            cursor: pointer;
            user-select: none;
        }

        .voice-note-btn {
            width: 45px;
            height: 45px;
            background-color: var(--accent-green);
            border-radius: 50%;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            cursor: pointer;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.3);
            transition: all 0.3s ease;
        }
        
        .voice-note-btn .filled-icon {
            font-variation-settings: 'FILL' 1, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        
        .voice-note-btn.recording {
            background-color: #dc3545;
            animation: pulse 1.5s infinite;
        }
        
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }
        
        /* Recording UI */
        /* Recording UI */
        .recording-ui {
            position: absolute;
            left: 0;
            right: 0; /* Cover full width */
            top: 0;
            bottom: 0;
            background-color: #2A3942; /* Match input wrapper bg */
            display: flex;
            align-items: center;
            padding: 0 16px;
            border-radius: 24px;
            z-index: 100;
        }
        
        .recording-timer {
            color: var(--text-primary);
            font-size: 14px;
            margin-left: 10px;
            font-weight: 500;
        }
        
        .recording-waveform {
            display: flex;
            align-items: center;
            gap: 3px;
            margin-left: 15px;
        }
        
        .waveform-bar {
            width: 3px;
            background-color: var(--accent-green);
            border-radius: 2px;
            animation: wave 1s ease-in-out infinite;
        }
        
        .waveform-bar:nth-child(1) { height: 8px; animation-delay: 0s; }
        .waveform-bar:nth-child(2) { height: 16px; animation-delay: 0.1s; }
        .waveform-bar:nth-child(3) { height: 12px; animation-delay: 0.2s; }
        .waveform-bar:nth-child(4) { height: 20px; animation-delay: 0.3s; }
        .waveform-bar:nth-child(5) { height: 14px; animation-delay: 0.4s; }
        
        @keyframes wave {
            0%, 100% { transform: scaleY(0.5); }
            50% { transform: scaleY(1); }
        }
        
        .slide-cancel {
            color: var(--text-secondary);
            font-size: 13px;
            margin-left: auto;
            display: flex;
            align-items: center;
            gap: 5px;
        }
        
        /* Voice Message Player */
        .voice-message-player {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 6px 10px 6px 6px;
            background: rgba(0,0,0,0.15);
            border-radius: 8px;
            max-width: 320px;
            margin-top: 5px;
            position: relative;
        }
        
        .voice-play-btn {
            width: 42px;
            height: 42px;
            min-width: 42px;
            border-radius: 50%;
            background-color: rgba(255,255,255,0.15);
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            flex-shrink: 0;
            transition: all 0.2s;
            position: relative;
        }
        
        .voice-play-btn:hover {
            background-color: rgba(255,255,255,0.2);
        }
        
        .voice-play-btn .material-symbols-rounded {
            color: white;
            font-size: 24px;
            font-variation-settings: 'FILL' 1, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        
        .voice-mic-icon {
            position: absolute;
            bottom: -2px;
            right: -2px;
            width: 20px;
            height: 20px;
            background-color: var(--message-sent);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid var(--message-sent);
        }

        .voice-mic-icon.read {
            background-color: #53bdeb; /* Blue when played/seen */
            border-color: #53bdeb;
        }
        
        .voice-mic-icon .material-symbols-rounded {
            color: white;
            font-size: 12px;
        }
        
        .voice-waveform {
            flex: 1;
            display: flex;
            align-items: center;
            gap: 2px;
            height: 32px;
            padding: 0 5px;
        }
        
        .voice-wave-bar {
            width: 3px;
            background-color: rgba(255,255,255,0.4);
            border-radius: 2px;
            transition: all 0.1s;
        }
        
        .voice-message-player.playing .voice-wave-bar {
            background-color: rgba(255,255,255,0.8);
        }
        
        .voice-wave-bar:nth-child(odd) {
            animation: waveOdd 1.2s ease-in-out infinite;
        }
        
        .voice-wave-bar:nth-child(even) {
            animation: waveEven 1.2s ease-in-out infinite;
        }
        
        @keyframes waveOdd {
            0%, 100% { transform: scaleY(0.4); }
            50% { transform: scaleY(1); }
        }
        
        @keyframes waveEven {
            0%, 100% { transform: scaleY(1); }
            50% { transform: scaleY(0.4); }
        }
        
        .voice-duration {
            color: rgba(255,255,255,0.7);
            font-size: 11px;
            white-space: nowrap;
            flex-shrink: 0;
            min-width: 32px;
            text-align: right;
        }
        
        .attachment-link {
            display: flex;
            align-items: center;
            color: var(--text-primary);
            background: rgba(0,0,0,0.2);
            padding: 8px;
            border-radius: 6px;
            font-size: 13px;
            margin-top: 5px;
            text-decoration: none;
            gap: 5px;
        }

        .material-symbols-rounded {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        .material-symbols-rounded.filled-icon {
            font-variation-settings: 'FILL' 1;
        }
        
        /* Modal */
        .modal {
            display: none; 
            position: fixed; 
            z-index: 100; 
            left: 0;
            top: 0;
            width: 100%; 
            height: 100%; 
            overflow: auto; 
            background-color: rgba(0,0,0,0.6); 
            justify-content: center;
            align-items: center;
        }
        .modal-content {
            background-color: #202c33;
            margin: auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 400px;
            border-radius: 10px;
            color: #E9EDEF;
            text-align: center;
        }
        .modal-footer {
            margin-top: 20px;
            display: flex;
            justify-content: flex-end;
            gap: 10px;
        }
        .btn-modal {
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: 500;
        }
        .btn-cancel {
            background-color: transparent;
            color: var(--accent-green);
        }
        .btn-confirm {
            background-color: var(--accent-green);
            color: white;
        }
    </style>
</head>

<body>

    <div class="chat-app-container">
        <!-- Chat Header -->
        <header class="chat-header">
            <a href="{{ route('ticket.index') }}" class="back-btn" style="text-decoration: none;">
                <span class="material-symbols-rounded">arrow_back</span>
            </a>

            <div class="chat-header-info">
                 <!-- Modern Admin Avatar -->
                <img src="https://ui-avatars.com/api/?name=Support&background=00a884&color=fff&size=128&bold=true" alt="Profile" class="header-avatar">
                <div class="header-text">
                    <div class="header-name">Support Team</div>
                    <div class="header-status">
                         @if($myTicket->status == 3)
                            <span style="color: #ef5350;">Closed</span>
                        @else
                            <span style="color: #25D366;">Online</span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="chat-header-actions">
                @if ($myTicket->status != 3 && $myTicket->id)
                    <div class="dropdown-menu-container" style="position: relative;">
                        <button class="icon-btn" style="background:none; border:none;" onclick="toggleMenu()">
                            <span class="material-symbols-rounded">more_vert</span>
                        </button>
                        <div id="headerMenu" class="header-menu" style="display:none; position: absolute; right: 0; top: 40px; background: #202C33; border-radius: 6px; padding: 5px; min-width: 150px; box-shadow: 0 4px 10px rgba(0,0,0,0.3); z-index: 50;">
                             <button onclick="showCloseModal()" style="background:none; border:none; color: #ef5350; width: 100%; text-align: left; padding: 10px; display: flex; align-items: center; gap: 10px; cursor: pointer;">
                                 <span class="material-symbols-rounded" style="font-size: 20px;">block</span> Close Chat
                             </button>
                        </div>
                    </div>

                    <!-- Confirmation Modal Form (Hidden) -->
                    <form id="closeTicketForm" action="{{ route('ticket.close', $myTicket->id) }}" method="POST" style="display:none;">
                        @csrf
                    </form>
                @endif
            </div>
        </header>

        <!-- Chat Area (Messages) -->
        <div class="messages-area" id="messagesArea">
            @php
                $lastAdminMessage = $messages->where('admin_id', '!=', 0)->sortByDesc('created_at')->first();
                $lastAdminReplyTime = $lastAdminMessage ? $lastAdminMessage->created_at : null;
            @endphp

            @foreach ($messages as $message)
                @php
                    $isSent = $message->admin_id == 0;
                    $class = $isSent ? 'sent' : 'received';
                    
                    // Determine if message is read based on DB column
                    $isRead = $message->is_read == 1;
                @endphp
                <div class="message-row {{ $class }}">
                    @if(!$isSent)
                        <div class="message-avatar">
                             <img src="https://ui-avatars.com/api/?name=Admin&background=202C33&color=fff&size=64&bold=true" alt="Admin">
                        </div>
                    @endif
                    
                    <div class="message-bubble">
                        <!-- Only show name if received (Admin) -->
                        @if(!$isSent)
                            <div class="sender-name">Admin Support</div>
                        @endif
                        
                        <div class="message-text">
                            {{ $message->message }}
                            @if ($message->attachments->count() > 0)
                                <div class="mt-2 attachments-preview">
                                    @foreach ($message->attachments as $k => $image)
                                        @php
                                            $ext = pathinfo($image->attachment, PATHINFO_EXTENSION);
                                        @endphp
                                        @if(in_array(strtolower($ext), ['jpg','jpeg','png']))
                                            <a href="{{ route('ticket.download', encrypt($image->id)) }}" target="_blank">
                                                <img src="{{ route('ticket.download', encrypt($image->id)) }}" class="preview-img" style="max-width: 200px; border-radius: 8px; display:block; margin-top:5px;">
                                            </a>
                                        @elseif(in_array(strtolower($ext), ['webm','mp3','wav','ogg']))
                                            <div class="voice-message-player" data-audio-src="{{ route('ticket.download', encrypt($image->id)) }}">
                                                <button class="voice-play-btn" onclick="toggleVoicePlay(this)">
                                                    <span class="material-symbols-rounded">play_arrow</span>
                                                    <div class="voice-mic-icon {{ $isRead ? 'read' : '' }}">
                                                        <span class="material-symbols-rounded">mic</span>
                                                    </div>
                                                </button>
                                                <div class="voice-waveform">
                                                    <div class="voice-wave-bar" style="height: 8px;"></div>
                                                    <div class="voice-wave-bar" style="height: 16px;"></div>
                                                    <div class="voice-wave-bar" style="height: 12px;"></div>
                                                    <div class="voice-wave-bar" style="height: 20px;"></div>
                                                    <div class="voice-wave-bar" style="height: 14px;"></div>
                                                    <div class="voice-wave-bar" style="height: 18px;"></div>
                                                    <div class="voice-wave-bar" style="height: 10px;"></div>
                                                    <div class="voice-wave-bar" style="height: 16px;"></div>
                                                    <div class="voice-wave-bar" style="height: 12px;"></div>
                                                    <div class="voice-wave-bar" style="height: 20px;"></div>
                                                    <div class="voice-wave-bar" style="height: 15px;"></div>
                                                    <div class="voice-wave-bar" style="height: 11px;"></div>
                                                    <div class="voice-wave-bar" style="height: 17px;"></div>
                                                    <div class="voice-wave-bar" style="height: 13px;"></div>
                                                    <div class="voice-wave-bar" style="height: 19px;"></div>
                                                </div>
                                                <span class="voice-duration">0:00</span>
                                                <audio style="display:none;" src="{{ route('ticket.download', encrypt($image->id)) }}"></audio>
                                            </div>
                                        @else
                                            <a href="{{ route('ticket.download', encrypt($image->id)) }}" class="attachment-link">
                                                <span class="material-symbols-rounded" style="font-size: 20px;">description</span> 
                                                FILE {{ ++$k }}
                                            </a>
                                        @endif
                                    @endforeach
                                </div>
                            @endif
                        </div>
                        <div class="message-meta">
                            <span class="time">{{ $message->created_at->format('H:i') }}</span>
                            @if($isSent)
                                <span class="material-symbols-rounded icon-status {{ $isRead ? 'read' : '' }}">{{ $isRead ? 'done_all' : 'check' }}</span>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Chat Footer (Input) -->
        @if ($myTicket->status != 4)
            <footer class="chat-footer">
                @php
                    $formAction = $myTicket->id ? route('ticket.reply', $myTicket->id) : route('ticket.store');
                @endphp
                <form class="w-100 d-flex align-items-center" style="width:100%; display:flex;" method="post" action="{{ $formAction }}" enctype="multipart/form-data">
                    @csrf
                    
                    @if(!$myTicket->id)
                        <!-- Hidden fields for creating a new ticket -->
                        <input type="hidden" name="subject" value="Support Chat">
                        <input type="hidden" name="priority" value="2">
                    @endif

                    <div class="input-wrapper" style="position: relative;">
                         <!-- Recording UI Overlay -->
                         <div class="recording-ui" id="recordingUI" style="display: none;">
                             <span class="material-symbols-rounded" style="color: #dc3545; font-size: 20px;">mic</span>
                             <span class="recording-timer" id="recordingTimer">0:00</span>
                             <div class="recording-waveform">
                                 <div class="waveform-bar"></div>
                                 <div class="waveform-bar"></div>
                                 <div class="waveform-bar"></div>
                                 <div class="waveform-bar"></div>
                                 <div class="waveform-bar"></div>
                             </div>
                             <div class="slide-cancel">
                                 <span class="material-symbols-rounded" style="font-size: 16px;">chevron_left</span>
                                 <span>Slide to cancel</span>
                             </div>
                         </div>
                         
                         <!-- File input hidden -->
                         <input type="file" name="attachments[]" id="hiddenRefFile" style="display: none;" multiple onchange="alertFileSelection(this)">
                        
                        <span class="material-symbols-rounded icon-btn" style="font-size: 26px; margin-right: 8px; color: #8696a0;">sentiment_satisfied</span>
                        
                        <input type="text" name="message" placeholder="Message" class="chat-input" autocomplete="off">
                        
                        <div style="display:flex; gap: 15px; margin-left: 10px; align-self: center;">
                            <span class="material-symbols-rounded icon-btn" onclick="document.getElementById('hiddenRefFile').click()" style="transform: rotate(45deg); font-size: 24px; color: #8696a0;">attach_file</span>
                            <span class="material-symbols-rounded icon-btn" onclick="triggerCamera()" style="font-size: 24px; color: #8696a0;">photo_camera</span>
                        </div>
                    </div>
                    
                    <button type="button" class="voice-note-btn" id="voiceBtn" onclick="toggleVoiceRecording()">
                        <span class="material-symbols-rounded filled-icon" id="voiceIcon">mic</span>
                    </button>
                    <button type="submit" class="voice-note-btn" id="sendBtn" style="display:none;">
                        <span class="material-symbols-rounded filled-icon">send</span>
                    </button>
                </form>
            </footer>
        @endif
    </div>

    <!-- Custom Modal -->
    <div id="confirmationModal" class="modal">
        <div class="modal-content">
            <h3>Close Chat?</h3>
            <p>Are you sure you want to close this ticket?</p>
            <div class="modal-footer">
                <button class="btn-modal btn-cancel" onclick="closeModal()">CANCEL</button>
                <button class="btn-modal btn-confirm" onclick="confirmClose()">CLOSE</button>
            </div>
        </div>
    </div>

    <!-- Hidden Camera Input -->
    <input type="file" id="cameraInput" accept="image/*" capture="environment" style="display:none;" onchange="handleCameraSelect(this)">

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        .uploading-mask {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.4);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            border-radius: 8px;
            z-index: 10;
        }
        .spinner {
            width: 20px;
            height: 20px;
            border: 3px solid #fff;
            border-bottom-color: transparent;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        .preview-img {
            max-width: 100%;
            border-radius: 6px;
            margin-top: 5px;
            display: block;
        }
    </style>
    <script>
        // Auto scroll to bottom
        const messagesArea = document.getElementById('messagesArea');
        
        function scrollToBottom() {
            if(messagesArea) {
                messagesArea.scrollTop = messagesArea.scrollHeight;
                // Also use smooth scroll as fallback
                messagesArea.scrollTo({
                    top: messagesArea.scrollHeight,
                    behavior: 'smooth'
                });
            }
        }
        
        // Scroll on page load
        scrollToBottom();
        
        // Scroll after images load
        window.addEventListener('load', function() {
            setTimeout(scrollToBottom, 100);
        });
        
        // Observe new messages being added
        if(messagesArea) {
            const observer = new MutationObserver(function() {
                scrollToBottom();
            });
            observer.observe(messagesArea, { childList: true, subtree: true });
        }

        // Voice Recording System
        let mediaRecorder;
        let audioChunks = [];
        let isRecording = false;
        let recordingStartTime;
        
        function toggleVoiceRecording() {
            if (!isRecording) {
                startRecording();
            } else {
                stopRecording();
            }
        }
        
        async function startRecording() {
            try {
                const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
                mediaRecorder = new MediaRecorder(stream);
                audioChunks = [];
                
                mediaRecorder.ondataavailable = (event) => {
                    audioChunks.push(event.data);
                };
                
                mediaRecorder.onstop = () => {
                    const audioBlob = new Blob(audioChunks, { type: 'audio/webm' });
                    const audioFile = new File([audioBlob], 'voice_message.webm', { type: 'audio/webm' });
                    
                    // Add to file input
                    const dataTransfer = new DataTransfer();
                    dataTransfer.items.add(audioFile);
                    document.getElementById('hiddenRefFile').files = dataTransfer.files;
                    
                    // Auto-submit
                    $('.chat-footer form').submit();
                    
                    // Stop all tracks
                    stream.getTracks().forEach(track => track.stop());
                };
                
                mediaRecorder.start();
                isRecording = true;
                recordingStartTime = Date.now();
                
                // Update UI
                document.getElementById('voiceBtn').classList.add('recording');
                document.getElementById('voiceIcon').textContent = 'stop';
                
                // Show recording UI overlay
                document.getElementById('recordingUI').style.display = 'flex';
                
                // Start timer
                updateRecordingTimer();
                
            } catch (error) {
                console.error('Error accessing microphone:', error);
                alert('Could not access microphone. Please grant permission.');
            }
        }
        
        function updateRecordingTimer() {
            if (!isRecording) return;
            
            const elapsed = Math.floor((Date.now() - recordingStartTime) / 1000);
            const minutes = Math.floor(elapsed / 60);
            const seconds = elapsed % 60;
            document.getElementById('recordingTimer').textContent = 
                `${minutes}:${seconds.toString().padStart(2, '0')}`;
            
            setTimeout(updateRecordingTimer, 1000);
        }
        
        function stopRecording() {
            if (mediaRecorder && isRecording) {
                mediaRecorder.stop();
                isRecording = false;
                
                // Update UI
                document.getElementById('voiceBtn').classList.remove('recording');
                document.getElementById('voiceIcon').textContent = 'mic';
                
                // Hide recording UI overlay
                document.getElementById('recordingUI').style.display = 'none';
                document.getElementById('recordingTimer').textContent = '0:00';
            }
        }
        
        // Voice Message Player
        function toggleVoicePlay(button) {
            const player = button.closest('.voice-message-player');
            const audio = player.querySelector('audio');
            const icon = button.querySelector('.material-symbols-rounded');
            const durationSpan = player.querySelector('.voice-duration');
            
            if (audio.paused) {
                // Play
                audio.play();
                icon.textContent = 'pause';
                player.classList.add('playing');
                
                // Update duration
                audio.addEventListener('timeupdate', function() {
                    const current = Math.floor(audio.currentTime);
                    const total = Math.floor(audio.duration);
                    const remaining = total - current;
                    const minutes = Math.floor(remaining / 60);
                    const seconds = remaining % 60;
                    durationSpan.textContent = `${minutes}:${seconds.toString().padStart(2, '0')}`;
                });
                
                audio.addEventListener('ended', function() {
                    icon.textContent = 'play_arrow';
                    player.classList.remove('playing');
                    const total = Math.floor(audio.duration);
                    const minutes = Math.floor(total / 60);
                    const seconds = total % 60;
                    durationSpan.textContent = `${minutes}:${seconds.toString().padStart(2, '0')}`;
                });
            } else {
                // Pause
                audio.pause();
                icon.textContent = 'play_arrow';
                player.classList.remove('playing');
            }
        }
        
        // Toggle between mic and send button based on text input only
        document.querySelector('.chat-input').addEventListener('input', function() {
            const hasText = this.value.trim().length > 0;
            
            if (hasText) {
                document.getElementById('voiceBtn').style.display = 'none';
                document.getElementById('sendBtn').style.display = 'flex';
            } else {
                document.getElementById('voiceBtn').style.display = 'flex';
                document.getElementById('sendBtn').style.display = 'none';
            }
        });

        function alertFileSelection(input) {
            if(input.files.length > 0) {
                // Auto-submit the form when file is selected
                $('.chat-footer form').submit();
            }
        }
        
        // Handle Camera Button Click
        function triggerCamera() {
            document.getElementById('cameraInput').click();
        }
        
        function handleCameraSelect(input) {
            if(input.files && input.files[0]) {
                const mainInput = document.getElementById('hiddenRefFile');
                const dataTransfer = new DataTransfer();
                
                // Add existing files
                for (let i = 0; i < mainInput.files.length; i++) {
                    dataTransfer.items.add(mainInput.files[i]);
                }
                // Add new camera file
                dataTransfer.items.add(input.files[0]);
                
                mainInput.files = dataTransfer.files;
                
                // Auto-submit the form
                $('.chat-footer form').submit();
            }
        }

        function toggleMenu() {
            var menu = document.getElementById('headerMenu');
            if (menu.style.display === 'none') {
                menu.style.display = 'block';
            } else {
                menu.style.display = 'none';
            }
        }

        function showCloseModal() {
            document.getElementById('headerMenu').style.display = 'none'; // Hide menu if open
            document.getElementById('confirmationModal').style.display = 'flex';
        }

        function closeModal() {
            document.getElementById('confirmationModal').style.display = 'none';
        }

        function confirmClose() {
             document.getElementById('closeTicketForm').submit();
        }

        // Close modal if clicked outside
        window.onclick = function(event) {
            var modal = document.getElementById('confirmationModal');
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
        
        // Helper to read file as data URL
        function readFile(file) {
            return new Promise((resolve) => {
                const reader = new FileReader();
                reader.onloadend = () => resolve(reader.result);
                reader.readAsDataURL(file);
            });
        }
        
        // Message Queue System
        let isSending = false;
        let messageQueue = [];
        
        // Process message queue
        function processMessageQueue() {
            if (isSending || messageQueue.length === 0) {
                return;
            }
            
            const nextMessage = messageQueue.shift();
            sendMessage(nextMessage);
        }
        
        // AJAX Form Submission
        $('.chat-footer form').on('submit', async function(e) {
            e.preventDefault();
            
            let form = $(this);
            let formData = new FormData(this);
            
            // Get file input for preview generation
            let fileInput = document.getElementById('hiddenRefFile');
            let files = fileInput.files;
            let messageText = form.find('input[name="message"]').val();
            
            // Prevent sending empty messages (no text and no files)
            if (!messageText.trim() && files.length === 0) {
                console.log('Cannot send empty message');
                return false;
            }
            
            // Create message object
            const messageData = {
                form: form,
                formData: formData,
                actionUrl: form.attr('action'),
                messageText: messageText,
                files: Array.from(files),
                timestamp: Date.now()
            };
            
            // Add to queue
            messageQueue.push(messageData);
            
            // Clear inputs immediately for better UX
            form.find('input[name="message"]').val('');
            form.find('input[type="file"]').val('');
            document.getElementById('cameraInput').value = '';
            
            // Reset button state to show voice button
            document.getElementById('voiceBtn').style.display = 'flex';
            document.getElementById('sendBtn').style.display = 'none';
            
            // Process queue
            processMessageQueue();
        });
        
        // Send message function
        function sendMessage(messageData) {
            if (isSending) return;
            
            isSending = true;
            
            let actionUrl = messageData.actionUrl;
            let formData = messageData.formData;
            let messageText = messageData.messageText;
            let files = messageData.files;
            let submitBtn = $('.voice-note-btn');
            
            // 1. Generate Optimistic UI
            let tempId = 'temp_' + messageData.timestamp;
            let attachmentsHtml = '';
            
            if(files.length > 0) {
                attachmentsHtml += '<div class="mt-2 attachments-preview">';
                for(let i=0; i<files.length; i++) {
                    let file = files[i];
                    if(file.type.startsWith('image/')) {
                         let src = URL.createObjectURL(file);
                         attachmentsHtml += `<img src="${src}" class="preview-img">`;
                    } else if (file.type.startsWith('audio/') || file.name.endsWith('.webm') || file.name.endsWith('.mp3') || file.name.endsWith('.wav') || file.name.endsWith('.ogg')) {
                         let src = URL.createObjectURL(file);
                         attachmentsHtml += `<div class="voice-message-player" data-audio-src="${src}">
                                                <button class="voice-play-btn" onclick="toggleVoicePlay(this)">
                                                    <span class="material-symbols-rounded">play_arrow</span>
                                                    <div class="voice-mic-icon">
                                                        <span class="material-symbols-rounded">mic</span>
                                                    </div>
                                                </button>
                                                <div class="voice-waveform">
                                                    <div class="voice-wave-bar" style="height: 8px;"></div>
                                                    <div class="voice-wave-bar" style="height: 16px;"></div>
                                                    <div class="voice-wave-bar" style="height: 12px;"></div>
                                                    <div class="voice-wave-bar" style="height: 20px;"></div>
                                                    <div class="voice-wave-bar" style="height: 14px;"></div>
                                                    <div class="voice-wave-bar" style="height: 18px;"></div>
                                                    <div class="voice-wave-bar" style="height: 10px;"></div>
                                                    <div class="voice-wave-bar" style="height: 16px;"></div>
                                                    <div class="voice-wave-bar" style="height: 12px;"></div>
                                                    <div class="voice-wave-bar" style="height: 20px;"></div>
                                                    <div class="voice-wave-bar" style="height: 15px;"></div>
                                                    <div class="voice-wave-bar" style="height: 11px;"></div>
                                                    <div class="voice-wave-bar" style="height: 17px;"></div>
                                                    <div class="voice-wave-bar" style="height: 13px;"></div>
                                                    <div class="voice-wave-bar" style="height: 19px;"></div>
                                                </div>
                                                <span class="voice-duration">0:00</span>
                                                <audio style="display:none;" src="${src}" onloadedmetadata="
                                                    let duration = Math.floor(this.duration); 
                                                    let min = Math.floor(duration/60); 
                                                    let sec = duration%60; 
                                                    this.previousElementSibling.textContent = min + ':' + (sec < 10 ? '0' : '') + sec;
                                                "></audio>
                                            </div>`;
                    } else {
                         attachmentsHtml += `<div class="attachment-link"><span class="material-symbols-rounded" style="font-size: 20px;">description</span> ${file.name}</div>`;
                    }
                }
                attachmentsHtml += '</div>';
            }
            
            let tempHtml = `
                <div class="message-row sent" id="${tempId}">
                    <div class="message-bubble">
                        <div class="message-text">
                            ${messageText}
                            ${attachmentsHtml}
                        </div>
                        <div class="message-meta">
                            <span class="time">Sending...</span>
                            <span class="material-symbols-rounded icon-status">schedule</span>
                        </div>
                        <div class="uploading-mask">
                            <div class="spinner"></div>
                            <div class="progress-text" style="color: white; font-size: 10px; margin-top: 2px; font-weight: bold;">0%</div>
                        </div>
                    </div>
                </div>
            `;
            
            $('#messagesArea').append(tempHtml);
            scrollToBottom();
            
            // Disable button
            submitBtn.prop('disabled', true);
            
            $.ajax({
                url: actionUrl,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'Accept': 'application/json'
                },
                xhr: function() {
                    var xhr = new window.XMLHttpRequest();
                    xhr.upload.addEventListener("progress", function(evt) {
                        if (evt.lengthComputable) {
                            var percentComplete = Math.round((evt.loaded / evt.total) * 100);
                            $(`#${tempId} .progress-text`).text(percentComplete + '%');
                        }
                    }, false);
                    return xhr;
                },
                success: function(response) {
                    if(response.status === 'success') {
                        // Replace temp message with real HTML from server
                        if(response.data.html) {
                            $(`#${tempId}`).replaceWith(response.data.html);
                        } else {
                             $(`#${tempId} .uploading-mask`).remove();
                             $(`#${tempId} .icon-status`).text('check').removeClass('read');
                             $(`#${tempId} .time`).text('Now');
                        }
                        scrollToBottom();
                        
                        // If it was a new ticket creation, redirect to ensure proper state/url
                        if(response.data.redirect_url && !actionUrl.includes('reply')) {
                                 window.location.href = response.data.redirect_url;
                        }
                        
                    } else {
                        $(`#${tempId} .uploading-mask`).remove();
                        $(`#${tempId} .message-bubble`).css('border', '1px solid red');
                        alert('Something went wrong. Please try again.');
                    }
                },
                error: function(xhr) {
                   $(`#${tempId} .uploading-mask`).remove();
                   $(`#${tempId} .message-bubble`).css('border', '1px solid red');
                   alert('Error sending message');
                },
                complete: function() {
                    submitBtn.prop('disabled', false);
                    isSending = false; // Reset sending state
                    processMessageQueue(); // Process next message in queue
                }
            });
        }
    </script>
</body>

</html>
