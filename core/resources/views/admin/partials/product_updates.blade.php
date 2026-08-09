{{-- Product Update Notification Widget --}}
@php
    use Wise\Service\WiseService;
    
    $hasUpdate = WiseService::hasUpdate();
    $updateDetails = WiseService::getUpdateDetails();
@endphp

@if($hasUpdate && $updateDetails)
<div class="alert alert-info alert-dismissible fade show" role="alert">
    <div class="d-flex align-items-center">
        <i class="las la-download fs-3 me-3"></i>
        <div class="flex-grow-1">
            <h5 class="alert-heading mb-1">
                <i class="las la-exclamation-circle"></i> New Update Available!
            </h5>
            <p class="mb-2">
                Version <strong>{{ $updateDetails['version'] }}</strong> is now available. 
                @if($updateDetails['is_critical'])
                    <span class="badge bg-danger">Critical Update</span>
                @endif
            </p>
            
            @if(!empty($updateDetails['changelog']))
                <div class="mb-2">
                    <strong>What's New:</strong>
                    <ul class="mb-0 ps-3">
                        @foreach(array_slice($updateDetails['changelog'], 0, 3) as $change)
                            <li>{{ $change }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            
            @if($updateDetails['release_date'])
                <small class="text-muted">
                    <i class="las la-calendar"></i> 
                    Released: {{ \Carbon\Carbon::parse($updateDetails['release_date'])->diffForHumans() }}
                </small>
            @endif
        </div>
        <div class="ms-3">
            @if($updateDetails['download_url'])
                <a href="{{ $updateDetails['download_url'] }}" class="btn btn-primary btn-sm" target="_blank">
                    <i class="las la-download"></i> Download Update
                </a>
            @endif
        </div>
    </div>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

{{-- Broadcast Messages Widget --}}
@php
    $messages = WiseService::getBroadcastMessages();
@endphp

@if($messages && count($messages) > 0)
    @foreach($messages as $message)
        @php
            $messageType = $message['type'] ?? 'info';
            $iconMap = [
                'info' => 'la-info-circle',
                'warning' => 'la-exclamation-triangle',
                'danger' => 'la-exclamation-circle',
                'success' => 'la-check-circle',
            ];
            $icon = $iconMap[$messageType] ?? 'la-bullhorn';
        @endphp
        <div class="alert alert-{{ $messageType }} alert-dismissible fade show" role="alert">
            <div class="d-flex align-items-start">
                <i class="las {{ $icon }} fs-3 me-3"></i>
                <div class="flex-grow-1">
                    @if(!empty($message['title']))
                        <h6 class="alert-heading mb-1">{{ $message['title'] }}</h6>
                    @endif
                    <p class="mb-0">{{ $message['message'] ?? $message['content'] ?? '' }}</p>
                    @if(!empty($message['link']))
                        <a href="{{ $message['link'] }}" class="alert-link" target="_blank">
                            <i class="las la-external-link-alt"></i> Learn More
                        </a>
                    @endif
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endforeach
@endif

