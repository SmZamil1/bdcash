<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $pageTitle ?? 'Support Chat' }}</title>
    <!-- Fonts and Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,1,0" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <!-- Bootstrap CSS (Basic utilities) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #0B141A;
            color: #E9EDEF;
            font-family: 'Roboto', sans-serif;
            height: 100vh;
            overflow: hidden;
        }
    </style>
</head>
<body>

    <div class="chat-app-container">
        <!-- Chat Header -->
        <header class="chat-header">
            <a href="{{ route('admin.ticket.index') }}" class="back-btn" style="text-decoration: none; color: inherit; display: flex; align-items: center; justify-content: center; margin-right: 8px;">
                <span class="material-symbols-rounded">arrow_back</span>
            </a>

            <div class="chat-header-info">
                <!-- User Avatar -->
                <img src="https://thumbs.dreamstime.com/b/default-avatar-profile-icon-vector-social-media-user-image-182145777.jpg" alt="Profile" class="header-avatar">
                <div class="header-text">
                    <div class="header-name">{{ $ticket->name }} <small>({{ @$ticket->user->username }})</small></div>
                    <div class="header-status">
                        @if ($ticket->status == Status::TICKET_OPEN)
                            <span class="badge badge--success">Opened</span>
                        @elseif ($ticket->status == Status::TICKET_ANSWER)
                            <span class="badge badge--primary">Answered</span>
                        @elseif ($ticket->status == Status::TICKET_REPLY)
                            <span class="badge badge--warning">Customer Reply</span>
                        @elseif ($ticket->status == Status::TICKET_CLOSE)
                            <span class="badge badge--dark">Closed</span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="chat-header-actions">
                @if ($ticket->status != Status::TICKET_CLOSE)
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
                @endif
            </div>
        </header>

        <!-- Chat Area (Messages) -->
        <div class="messages-area" id="messagesArea">
            @foreach ($messages as $message)
                @php
                    // Admin ID != 0 means it is an ADMIN reply (Sent/You)
                    $isSent = $message->admin_id != 0;
                    $class = $isSent ? 'sent' : 'received';
                    $senderName = $isSent ? 'You' : $ticket->name;
                    $avatarName = $isSent ? 'Admin' : $ticket->name;
                    $avatarColor = $isSent ? '202C33' : '0D8ABC';
                    $isRead = $message->is_read == 1;
                @endphp
                <div class="message-row {{ $class }}">
                    @if(!$isSent)
                        <div class="message-avatar">
                             <img src="https://thumbs.dreamstime.com/b/default-avatar-profile-icon-vector-social-media-user-image-182145777.jpg" alt="{{ $avatarName }}">
                        </div>
                    @endif
                    
                    <div class="message-bubble">
                        @if(!$isSent)
                            <div class="sender-name">{{ $ticket->name }}</div>
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
                                            <a href="{{ route('admin.ticket.download', encrypt($image->id)) }}" target="_blank">
                                                <img src="{{ route('admin.ticket.download', encrypt($image->id)) }}" class="preview-img" style="max-width: 200px; border-radius: 8px; display:block; margin-top:5px;">
                                            </a>
                                        @elseif(in_array(strtolower($ext), ['webm','mp3','wav','ogg']))
                                            <div class="voice-message-player" data-audio-src="{{ route('admin.ticket.download', encrypt($image->id)) }}">
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
                                                </div>
                                                <span class="voice-duration">0:00</span>
                                                <audio style="display:none;" src="{{ route('admin.ticket.download', encrypt($image->id)) }}"></audio>
                                            </div>
                                        @else
                                            <a href="{{ route('admin.ticket.download', encrypt($image->id)) }}" class="attachment-link">
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
        <footer class="chat-footer">
            <form class="w-100 d-flex align-items-end" method="post" action="{{ route('admin.ticket.reply', $ticket->id) }}" enctype="multipart/form-data" id="chatForm">
                @csrf
                <input type="hidden" name="replayTicket" value="1">
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
                         <div class="slide-cancel" onclick="toggleVoiceRecording()">
                             <span class="material-symbols-rounded" style="font-size: 16px;">chevron_left</span>
                             <span>Slide to cancel</span>
                         </div>
                     </div>
                     
                     <input type="file" name="attachments[]" id="hiddenRefFile" style="display: none;" multiple onchange="alertFileSelection(this)">
                    <span class="material-symbols-rounded icon-btn smiley" style="font-size: 26px; margin-right: 8px; color: #8696a0;">sentiment_satisfied</span>
                    <input type="text" name="message" placeholder="Message" class="chat-input" autocomplete="off">
                    <div style="display:flex; gap: 15px; margin-left: 10px; align-self: center;">
                        <span class="material-symbols-rounded icon-btn" onclick="document.getElementById('hiddenRefFile').click()" style="transform: rotate(45deg); font-size: 24px; color: #8696a0;">attach_file</span>
                        <!-- No Camera for Admin -->
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
    </div>

    <!-- Confirmation Modal -->
    <!-- Custom Modal -->
    <div id="confirmationModal" class="modal">
        <div class="modal-content">
            <h3>Close Chat?</h3>
            <p>Are you sure you want to close this ticket?</p>
            <div class="modal-footer">
                <button class="btn-modal btn-cancel" onclick="closeModal()">CANCEL</button>
                <button class="btn-modal btn-confirm" onclick="confirmClose()">CLOSE</button>
            </div>
            <!-- Hidden Form for Close Action -->
            <form id="closeTicketForm" action="{{ route('admin.ticket.close', $ticket->id) }}" method="POST" style="display:none;">
                @csrf
            </form>
        </div>
    </div>

    <!-- Styles -->
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

        /* Admin Specific Overrides */
        .btn--primary { background-color: #eab308; border-color: #eab308; color: black; font-weight: 500;}
        .btn--danger { background-color: #ef4444; border-color: #ef4444; color: white; }
        .badge { display: inline-block; padding: 0.35em 0.65em; font-size: 0.75em; font-weight: 700; line-height: 1; text-align: center; white-space: nowrap; vertical-align: baseline; border-radius: 0.25rem; }
        .badge--success { background-color: #10b981; color: white; }
        .badge--warning { background-color: #f59e0b; color: white; }
         
        .chat-app-container {
            display: flex;
            flex-direction: column;
            height: 100vh;
            width: 100%; 
            max-width: 480px;
            margin: 0 auto;
            background-color: var(--bg-color);
            position: relative;
        }
        
        /* Header */
        .chat-header {
            background-color: var(--header-bg);
            padding: 10px 10px;
            display: flex;
            align-items: center;
            color: var(--text-primary);
            z-index: 20;
            height: 60px;
            border-bottom: 1px solid rgba(255,255,255,0.05);
            user-select: none;
        }

        .chat-header-info {
            flex: 1;
            display: flex;
            align-items: center;
            margin-left: 5px;
            overflow: hidden;
            cursor: pointer;
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
        
        .chat-header-actions {
            display: flex;
            align-items: center;
            gap: 10px;
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
        .message-row.sent { justify-content: flex-end; }
        .message-row.received { justify-content: flex-start; }

        .message-avatar {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            overflow: hidden;
            flex-shrink: 0;
            margin-bottom: 5px;
        }
        .message-avatar img { width: 100%; height: 100%; object-fit: cover; }

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
            order: 1; 
        }
        .sent .message-avatar { order: 2; margin-left: 6px; display: none; }

        .received .message-bubble {
            background-color: var(--message-received);
            border-top-left-radius: 0;
        }
        .received .message-avatar { margin-right: 6px; }
        
        .sender-name {
            font-size: 13px;
            font-weight: 500;
            color: #d86c26;
            margin-bottom: 2px;
            line-height: 15px;
            display: none; 
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
        .message-meta .time { font-size: 11px; color: rgba(255, 255, 255, 0.6); }
        .icon-status { font-size: 16px; }
        .icon-status.read { color: #53bdeb; }

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
        .input-wrapper .chat-input::placeholder { color: var(--text-secondary); }

        .icon-btn { color: var(--text-secondary); font-size: 26px; cursor: pointer; user-select: none; }

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
        .voice-note-btn.recording { background-color: #dc3545; animation: pulse 1.5s infinite; }
        @keyframes pulse { 0%, 100% { transform: scale(1); } 50% { transform: scale(1.1); } }
        
        /* Recording UI */
        .recording-ui {
            position: absolute;
            left: 0; right: 0; top: 0; bottom: 0;
            background-color: #2A3942;
            display: flex; align-items: center; padding: 0 16px;
            border-radius: 24px; z-index: 100;
        }
        .recording-timer { color: var(--text-primary); font-size: 14px; margin-left: 10px; font-weight: 500; }
        .recording-waveform { display: flex; align-items: center; gap: 3px; margin-left: 15px; }
        .waveform-bar {
            width: 3px; background-color: var(--accent-green); border-radius: 2px;
            animation: wave 1s ease-in-out infinite;
        }
        .waveform-bar:nth-child(1) { height: 8px; animation-delay: 0s; }
        .waveform-bar:nth-child(2) { height: 16px; animation-delay: 0.1s; }
        .waveform-bar:nth-child(3) { height: 12px; animation-delay: 0.2s; }
        .waveform-bar:nth-child(4) { height: 20px; animation-delay: 0.3s; }
        .waveform-bar:nth-child(5) { height: 14px; animation-delay: 0.4s; }
        @keyframes wave { 0%, 100% { transform: scaleY(0.5); } 50% { transform: scaleY(1); } }
        
        .slide-cancel { color: var(--text-secondary); font-size: 13px; margin-left: auto; display: flex; align-items: center; gap: 5px; }

        /* Voice Player */
        .voice-message-player {
            display: flex; align-items: center; gap: 8px; padding: 6px 10px 6px 6px;
            background: rgba(0,0,0,0.15); border-radius: 8px; max-width: 320px; margin-top: 5px; position: relative;
        }
        .voice-play-btn {
            width: 42px; height: 42px; min-width: 42px; border-radius: 50%;
            background-color: rgba(255,255,255,0.15); border: none;
            display: flex; align-items: center; justify-content: center; cursor: pointer; flex-shrink: 0; position: relative;
        }
        .voice-mic-icon {
            position: absolute; bottom: -2px; right: -2px; width: 20px; height: 20px;
            background-color: var(--message-sent); border-radius: 50%;
            display: flex; align-items: center; justify-content: center; border: 2px solid var(--message-sent);
        }
        .voice-mic-icon.read { background-color: #53bdeb; border-color: #53bdeb; }
        .voice-mic-icon .material-symbols-rounded { color: white; font-size: 12px; }
        .voice-waveform { flex: 1; display: flex; align-items: center; gap: 2px; height: 32px; padding: 0 5px; }
        .voice-wave-bar { width: 3px; background-color: rgba(255,255,255,0.4); border-radius: 2px; transition: all 0.1s; }
        .voice-message-player.playing .voice-wave-bar { background-color: rgba(255,255,255,0.8); }
        .voice-duration { color: rgba(255,255,255,0.7); font-size: 11px; white-space: nowrap; flex-shrink: 0; min-width: 32px; text-align: right; }
        
        /* Attachments */
        .attachments-preview { display: flex; flex-wrap: wrap; gap: 8px; }
        .preview-img { max-width: 200px; border-radius: 8px; display: block; }
        .attachment-link { display: flex; align-items: center; color: var(--text-primary); background: rgba(0,0,0,0.2); padding: 8px; border-radius: 6px; font-size: 13px; margin-top: 5px; text-decoration: none; gap: 5px; }

        /* Material Symbols */
        .material-symbols-rounded { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
        .material-symbols-rounded.filled-icon { font-variation-settings: 'FILL' 1; }

        /* Upload Mask */
        .uploading-mask {
            position: absolute; top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(0,0,0,0.4);
            display: flex; align-items: center; justify-content: center; flex-direction: column;
            border-radius: 8px; z-index: 10;
        }
        .spinner { width: 20px; height: 20px; border: 3px solid #fff; border-bottom-color: transparent; border-radius: 50%; animation: spin 1s linear infinite; }
        @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
        .progress-text { color: white; font-size: 10px; margin-top: 2px; font-weight: bold; }
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


    <!-- Scripts -->
    <script>
        (function ($) {
            "use strict";

            // Scroll to bottom
            // Scroll to bottom logic
            function scrollToBottom() {
                const messagesArea = document.getElementById('messagesArea');
                if(messagesArea) {
                    messagesArea.scrollTop = messagesArea.scrollHeight;
                }
            }

            // Initial Scroll
            scrollToBottom();
            
            // Scroll again after window load (for images/resources)
            $(window).on('load', function() {
                scrollToBottom();
            });

            // Monitor image loading within chat to adjust scroll
            $('#messagesArea img').on('load', function() {
                scrollToBottom();
            });
            
            // Observer for dynamic content (though we have explicit calls too)
            const observer = new MutationObserver(function() {
                scrollToBottom();
            });
            if(document.getElementById('messagesArea')) {
                observer.observe(document.getElementById('messagesArea'), { childList: true, subtree: true });
            }
            
            // --- Menu & Modal Logic ---
            window.toggleMenu = function() {
                const menu = document.getElementById('headerMenu');
                if(menu.style.display === 'none' || menu.style.display === '') {
                    menu.style.display = 'block';
                } else {
                    menu.style.display = 'none';
                }
            }
            
            // Close menu when clicking outside
            document.addEventListener('click', function(event) {
                const menu = document.getElementById('headerMenu');
                const btn = document.querySelector('.icon-btn[onclick="toggleMenu()"]');
                if (menu && menu.style.display === 'block' && !menu.contains(event.target) && !btn.contains(event.target)) {
                    menu.style.display = 'none';
                }
            });

            window.showCloseModal = function() {
                document.getElementById('confirmationModal').style.display = 'flex';
                document.getElementById('headerMenu').style.display = 'none';
            }
            
            window.closeModal = function() {
                document.getElementById('confirmationModal').style.display = 'none';
            }
            
            window.confirmClose = function() {
                document.getElementById('closeTicketForm').submit();
            }

            // --- Voice Recording & Chat Logic ---
            let mediaRecorder;
            let audioChunks = [];
            let isRecording = false;
            let recordingStartTime;
            let recordingTimerInterval;
            
            window.toggleVoiceRecording = async function() {
                const voiceBtn = document.getElementById('voiceBtn');
                const voiceIcon = document.getElementById('voiceIcon');
                const recordingUI = document.getElementById('recordingUI');
                
                if (!isRecording) {
                    // Start Recording
                    try {
                        const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
                        mediaRecorder = new MediaRecorder(stream);
                        audioChunks = [];
                        
                        mediaRecorder.ondataavailable = event => {
                            audioChunks.push(event.data);
                        };
                        
                        mediaRecorder.onstop = async () => {
                            const audioBlob = new Blob(audioChunks, { type: 'audio/webm' });
                            // Send the Voice Note
                            sendVoiceMessage(audioBlob);
                        };
                        
                        mediaRecorder.start();
                        isRecording = true;
                        recordingStartTime = Date.now();
                        
                        // UI Updates
                        voiceBtn.classList.add('recording');
                        voiceIcon.textContent = 'stop'; // or send
                        recordingUI.style.display = 'flex';
                        
                        // Timer
                        recordingTimerInterval = setInterval(updateRecordingTimer, 1000);
                        
                    } catch (err) {
                        console.error("Error accessing microphone:", err);
                        alert("Could not access microphone.");
                    }
                } else {
                    // Stop Recording (Send)
                    mediaRecorder.stop();
                    stopRecordingUI();
                }
            };
            
            function stopRecordingUI() {
                isRecording = false;
                clearInterval(recordingTimerInterval);
                document.getElementById('voiceBtn').classList.remove('recording');
                document.getElementById('voiceIcon').textContent = 'mic';
                document.getElementById('recordingUI').style.display = 'none';
                document.getElementById('recordingTimer').textContent = '0:00';
            }
            
            function updateRecordingTimer() {
                const elapsed = Math.floor((Date.now() - recordingStartTime) / 1000);
                const min = Math.floor(elapsed / 60);
                const sec = elapsed % 60;
                document.getElementById('recordingTimer').textContent = `${min}:${sec < 10 ? '0' : ''}${sec}`;
            }
            
            window.alertFileSelection = function(input) {
                if(input.files.length > 0) {
                     $('#chatForm').submit();
                }
            }
            
            window.triggerCamera = function() {
                // In a real PWA this would trigger camera. For web, trigger file input for images.
                const fileInput = document.getElementById('hiddenRefFile');
                fileInput.setAttribute('accept', 'image/*');
                fileInput.click();
                // Reset accept after...
                setTimeout(() => fileInput.removeAttribute('accept'), 1000);
            }
            
            // --- Sending Logic ---
            
            // Handle Text Input Typing
            $('.chat-input').on('input', function() {
                const val = $(this).val().trim();
                if(val.length > 0) {
                    $('#voiceBtn').hide();
                    $('#sendBtn').show();
                } else {
                    $('#sendBtn').hide();
                    $('#voiceBtn').show();
                }
            });
            
            // Handle Form Submission (Text/Files)
            // --- Message Queue ---
            let messageQueue = [];
            let isSending = false;

            function processQueue() {
                if(isSending || messageQueue.length === 0) return;
                
                const data = messageQueue.shift();
                sendMessageInternal(data);
            }

            function sendMessageInternal(data) {
                isSending = true;
                const { formData, tempId, actionUrl } = data;

                $.ajax({
                    url: actionUrl,
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                       'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
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
                        if(response.data && response.data.html) {
                             $(`#${tempId}`).replaceWith(response.data.html);
                             const messagesArea = document.getElementById('messagesArea');
                             if(messagesArea) messagesArea.scrollTop = messagesArea.scrollHeight;
                        } else {
                            $(`#${tempId} .uploading-mask`).remove();
                            const meta = document.getElementById(tempId).querySelector('.icon-status');
                            if(meta) {
                                meta.textContent = 'check'; 
                                meta.classList.remove('read');
                            }
                        }
                    },
                    error: function(xhr) {
                        $(`#${tempId} .uploading-mask`).remove();
                        const meta = document.getElementById(tempId).querySelector('.icon-status');
                        if(meta) {
                            meta.textContent = 'error';
                            meta.style.color = 'red';
                        }
                         $(`#${tempId} .message-bubble`).css('border', '1px solid red');
                        // alert('Error sending message');
                    },
                    complete: function() {
                        isSending = false;
                        processQueue();
                    }
                });
            }

            $('#chatForm').on('submit', function(e) {
                e.preventDefault();
                
                const form = $(this);
                const messageInput = form.find('input[name="message"]');
                const fileInput = document.getElementById('hiddenRefFile');
                const text = messageInput.val().trim();
                const files = fileInput.files;

                if (text === '' && files.length === 0) return;

                const tempId = 'temp_' + Date.now();
                const actionUrl = form.attr('action');
                let formData = new FormData(this);

                // 2. Optimistic UI
                appendMessageToUI(text, files, tempId);
                
                // 3. Queue Logic
                messageQueue.push({ formData, tempId, actionUrl });
                
                // 4. Clear Input
                messageInput.val('');
                fileInput.value = ''; 
                $('#voiceBtn').show();
                $('#sendBtn').hide();

                processQueue();
            });

            function appendMessageToUI(text, files, tempId) {
                const now = new Date();
                const timeStr = now.getHours().toString().padStart(2, '0') + ':' + now.getMinutes().toString().padStart(2, '0');
                
                let filePreviews = '';
                let hasFiles = files.length > 0;
                
                if(hasFiles) {
                    filePreviews = '<div class="mt-2 attachments-preview">';
                    Array.from(files).forEach(file => {
                        if(file.type.startsWith('image/')) {
                            const url = URL.createObjectURL(file);
                            filePreviews += `<img src="${url}" class="preview-img" style="max-width: 200px; border-radius: 8px; display:block; margin-top:5px;">`;
                        } else {
                            filePreviews += `
                                <a href="#" class="attachment-link" onclick="return false;">
                                    <span class="material-symbols-rounded">description</span> 
                                    ${file.name}
                                </a>`;
                        }
                    });
                    filePreviews += '</div>';
                }

                const loadingMask = hasFiles ? `
                    <div class="uploading-mask" style="position: absolute; top:0; left:0; right:0; bottom:0; background:rgba(0,0,0,0.5); display:flex; flex-direction:column; align-items:center; justify-content:center; border-radius: 12px;">
                        <div class="spinner-border text-light spinner-border-sm" role="status"></div>
                        <div class="progress-text" style="color: white; font-size: 10px; margin-top: 2px; font-weight: bold;">0%</div>
                    </div>` : '';

                const html = `
                <div class="message-row sent" id="${tempId}">
                    <div class="message-bubble" style="position: relative;">
                        <div class="message-text">
                            ${text}
                            ${filePreviews}
                        </div>
                        <div class="message-meta">
                            <span class="time">${timeStr}</span>
                            <span class="material-symbols-rounded icon-status">schedule</span>
                        </div>
                        ${loadingMask}
                    </div>
                </div>
                `;
                
                $('#messagesArea').append(html);
                const messagesArea = document.getElementById('messagesArea');
                messagesArea.scrollTop = messagesArea.scrollHeight;
            }
            
            function sendVoiceMessage(blob) {
                const tempId = 'temp_voice_' + Date.now();
                
                // Create FormData
                let formData = new FormData();
                formData.append('_token', "{{ csrf_token() }}");
                formData.append('replayTicket', 1);
                // Append Blob as file
                const file = new File([blob], "voice_message.webm", { type: 'audio/webm' });
                formData.append('attachments[]', file);
                
                // Optimistic UI
                const blobUrl = URL.createObjectURL(blob);
                appendVoiceMessageToUI(blobUrl, tempId);

                // Queue
                 messageQueue.push({ 
                    formData, 
                    tempId, 
                    actionUrl: "{{ route('admin.ticket.reply', $ticket->id) }}"
                });
                processQueue();
            }

            function appendVoiceMessageToUI(blobUrl, tempId) {
                const now = new Date();
                const timeStr = now.getHours().toString().padStart(2, '0') + ':' + now.getMinutes().toString().padStart(2, '0');
                
                const html = `
                <div class="message-row sent" id="${tempId}">
                    <div class="message-bubble" style="position: relative;">
                        <div class="message-text">
                            <div class="mt-2 attachments-preview">
                                <div class="voice-message-player">
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
                                    <audio style="display:none;" src="${blobUrl}"></audio>
                                </div>
                            </div>
                        </div>
                        <div class="message-meta">
                            <span class="time">${timeStr}</span>
                            <span class="material-symbols-rounded icon-status">schedule</span>
                        </div>
                        <div class="uploading-mask" style="position: absolute; top:0; left:0; right:0; bottom:0; background:rgba(0,0,0,0.5); display:flex; flex-direction:column; align-items:center; justify-content:center; border-radius: 12px;">
                            <div class="spinner-border text-light spinner-border-sm" role="status"></div>
                            <div class="progress-text" style="color: white; font-size: 10px; margin-top: 2px; font-weight: bold;">0%</div>
                        </div>
                    </div>
                </div>
                `;
                
                $('#messagesArea').append(html);
                const messagesArea = document.getElementById('messagesArea');
                messagesArea.scrollTop = messagesArea.scrollHeight;
            }
            
            // --- Voice Player Logic ---
            window.toggleVoicePlay = function(btn) {
                const player = btn.closest('.voice-message-player');
                const audio = player.querySelector('audio');
                const icon = btn.querySelector('.material-symbols-rounded'); // play_arrow
                
                // Stop others
                document.querySelectorAll('audio').forEach(a => {
                    if(a !== audio) {
                        a.pause();
                        a.currentTime = 0;
                        a.parentElement.querySelector('.material-symbols-rounded').textContent = 'play_arrow';
                    }
                });
                
                if (audio.paused) {
                    audio.play();
                    icon.textContent = 'pause';
                    // Optional: Visualizer animation start
                } else {
                    audio.pause();
                    icon.textContent = 'play_arrow';
                }
                
                audio.onended = function() {
                    icon.textContent = 'play_arrow';
                };
            };

        })(jQuery);
    </script>
</body>
</html>
