@extends('layouts.app')

@section('title', 'Call - PikFreshFood')
@if(request()->boolean('embedded'))
@section('hide_header', 'hidden')
@endif

@section('styles')
<style>
    @if(request()->boolean('embedded'))
    .header,
    .bottom-nav {
        display: none !important;
    }
    .main-content {
        min-height: auto;
        padding-bottom: 0;
    }
    .call-page {
        margin: 0;
        padding: 16px;
    }
    @endif
    .call-page {
        max-width: 1100px;
        margin: 24px auto;
        padding: 0 16px 32px;
        display: grid;
        gap: 18px;
    }
    .call-card,
    .call-panel,
    .call-modal-card {
        background: var(--bottom-sheet-bg);
        border: 1px solid var(--border-color);
        border-radius: 24px;
        box-shadow: 0 12px 28px var(--shadow-color);
    }
    .call-card {
        padding: 22px;
        display: flex;
        justify-content: space-between;
        align-items: start;
        gap: 16px;
        flex-wrap: wrap;
    }
    .call-card h1 {
        margin: 0 0 8px;
        color: var(--text-color);
    }
    .call-copy,
    .call-meta,
    .call-debug-line {
        color: var(--muted-color);
        line-height: 1.6;
    }
    .call-badges,
    .call-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }
    .call-badge,
    .call-btn {
        min-height: 42px;
        padding: 0 14px;
        border-radius: 999px;
        border: 1px solid var(--border-color);
        background: white;
        color: var(--text-color);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        text-decoration: none;
    }
    .call-btn {
        border-radius: 14px;
        cursor: pointer;
    }
    .call-btn.primary {
        background: var(--primary-color);
        border-color: var(--primary-color);
        color: white;
    }
    .call-btn.danger {
        background: #ef4444;
        border-color: #ef4444;
        color: white;
    }
    .call-btn:disabled {
        opacity: 0.55;
        cursor: not-allowed;
    }
    .call-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 18px;
    }
    .call-panel {
        padding: 16px;
    }
    .call-panel h2 {
        margin: 0 0 12px;
        color: var(--text-color);
        font-size: 1rem;
    }
    .call-video {
        width: 100%;
        min-height: 340px;
        border-radius: 18px;
        background: #0f172a;
        object-fit: cover;
        cursor: zoom-in;
    }
    .call-audio-state {
        min-height: 340px;
        border-radius: 18px;
        background: linear-gradient(145deg, #0f172a 0%, #1e293b 100%);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
        padding: 24px;
        line-height: 1.7;
        font-weight: 700;
    }
    .call-debug {
        padding: 16px 18px;
    }
    .call-modal {
        position: fixed;
        inset: 0;
        background: rgba(15, 23, 42, 0.58);
        display: none;
        align-items: center;
        justify-content: center;
        padding: 20px;
        z-index: 1600;
    }
    .call-modal.is-open {
        display: flex;
    }
    .call-modal-card {
        width: min(560px, 100%);
        padding: 22px;
    }
    .call-modal-card h3 {
        margin: 0 0 12px;
        color: var(--text-color);
    }
    @media (max-width: 820px) {
        .call-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 10px;
        }
        .call-video,
        .call-audio-state {
            min-height: 180px;
        }
    }
</style>
@endsection

@section('content')
<div class="call-page">
    <div class="call-card">
        <div>
            <h1>{{ $isBuyer ? ($callInvite->vendor->shop_name ?? 'Vendor Call') : ($callInvite->buyer->name ?? 'Buyer Call') }}</h1>
        </div>
        <div class="call-badges">
            <span class="call-badge">{{ $isBuyer ? 'Buyer' : 'Vendor' }}</span>
            <span class="call-badge">{{ ucfirst($callType) }} call</span>
            <span class="call-badge" id="statusBadge">{{ ucfirst($callInvite->status) }}</span>
        </div>
    </div>

    <div class="call-actions">
        <button type="button" class="call-btn primary" id="startButton">{{ $isBuyer ? 'Connecting...' : 'Joining...' }}</button>
        <button type="button" class="call-btn" id="muteButton" disabled>Mute</button>
        @if($callType === 'video')
            <button type="button" class="call-btn" id="cameraButton" disabled>Camera Off</button>
        @endif
        <button type="button" class="call-btn danger" id="endButton">End Call</button>
    </div>

    <div class="call-grid">
        <div class="call-panel">
            <h2>Your Media</h2>
            @if($callType === 'video')
                <video id="localVideo" class="call-video" autoplay playsinline muted></video>
            @else
                <div class="call-audio-state" id="localAudioState">Waiting to start microphone access.</div>
            @endif
        </div>
        <div class="call-panel">
            <h2>{{ $isBuyer ? ($callInvite->vendor->shop_name ?? 'Vendor') : ($callInvite->buyer->name ?? 'Buyer') }}</h2>
            @if($callType === 'video')
                <video id="remoteVideo" class="call-video" autoplay playsinline></video>
            @else
                <div class="call-audio-state" id="remoteAudioState">Remote audio will begin here after the peer connection is established.</div>
                <audio id="remoteAudio" autoplay playsinline></audio>
            @endif
        </div>
    </div>

    <div class="call-card call-debug">
        <div>
            <strong id="debugHeadline">Preparing call page...</strong>
            <div class="call-debug-line" id="debugBody">We have not started media yet.</div>
        </div>
    </div>
</div>

<div class="call-modal" id="errorModal">
    <div class="call-modal-card">
        <h3>Call connection issue</h3>
        <div class="call-debug-line" id="errorSummary">No issue yet.</div>
        <div class="call-debug-line" id="errorDetails" style="margin-top: 12px;"></div>
        <div class="call-actions" style="margin-top: 16px;">
            <button type="button" class="call-btn primary" id="retryButton">Retry Call</button>
            <button type="button" class="call-btn" id="closeErrorButton">Close</button>
        </div>
    </div>
</div>
@endsection

@section('scripts')
@php
    $pollUrl = route('calls.poll', ['callInvite' => $callInvite], false);
    $offerUrl = route('calls.offer', ['callInvite' => $callInvite], false);
    $answerUrl = route('calls.answer', ['callInvite' => $callInvite], false);
    $candidateUrl = route('calls.candidate', ['callInvite' => $callInvite], false);
    $connectedUrl = route('calls.connected', ['callInvite' => $callInvite], false);
    $endUrl = route('calls.end', ['callInvite' => $callInvite], false);
    $backUrl = $isBuyer
        ? route('vendor.show', ['vendor' => $callInvite->vendor], false)
        : route('vendor.dashboard', [], false);
@endphp
@if(false)
<script type="module">
    (async function () {
        const { Room, RoomEvent, Track } = await import('https://cdn.jsdelivr.net/npm/livekit-client/dist/livekit-client.esm.mjs');
        const config = {
            role: @json($isBuyer ? 'buyer' : 'vendor'),
            callType: @json($callType),
            csrfToken: @json(csrf_token()),
            pollUrl: @json($pollUrl),
            connectedUrl: @json($connectedUrl),
            endUrl: @json($endUrl),
            backUrl: @json($backUrl),
            livekitUrl: @json($livekitUrl),
            livekitToken: @json($livekitToken),
            embedded: @json(request()->boolean('embedded')),
            title: @json(($isBuyer ? ($callInvite->vendor->shop_name ?? 'Vendor') : ($callInvite->buyer->name ?? 'Buyer')).' '.ucfirst($callType).' call'),
        };

        let room = null;
        let pollTimer = null;
        let stage = 'idle';
        let networkError = 'none';
        let pollFailures = 0;
        let connectedNotified = false;
        let roomJoined = false;
        let autoJoinAttempted = false;

        const startButton = document.getElementById('startButton');
        const muteButton = document.getElementById('muteButton');
        const cameraButton = document.getElementById('cameraButton');
        const endButton = document.getElementById('endButton');
        const retryButton = document.getElementById('retryButton');
        const closeErrorButton = document.getElementById('closeErrorButton');
        const statusBadge = document.getElementById('statusBadge');
        const debugHeadline = document.getElementById('debugHeadline');
        const debugBody = document.getElementById('debugBody');
        const errorModal = document.getElementById('errorModal');
        const errorSummary = document.getElementById('errorSummary');
        const errorDetails = document.getElementById('errorDetails');
        const localVideo = document.getElementById('localVideo');
        const remoteVideo = document.getElementById('remoteVideo');
        const remoteAudio = document.getElementById('remoteAudio');
        const localAudioState = document.getElementById('localAudioState');
        const remoteAudioState = document.getElementById('remoteAudioState');

        function notifyParent(type, payload) {
            if (!config.embedded || window.parent === window) {
                return;
            }

            window.parent.postMessage(Object.assign({ type: type }, payload || {}), window.location.origin);
        }

        function updateDebug(headline, body) {
            debugHeadline.textContent = headline;
            debugBody.textContent = body;
        }

        function sessionSummary() {
            return [
                'Stage: ' + stage,
                'Room connected: ' + (roomJoined ? 'yes' : 'no'),
                'LiveKit state: ' + (room ? room.state : 'disconnected'),
                'Poll failures: ' + pollFailures,
                'Network error: ' + networkError,
            ].join(' | ');
        }

        function showError(summary) {
            errorSummary.textContent = summary;
            errorDetails.textContent = sessionSummary();
            errorModal.classList.add('is-open');
        }

        function syncDebugDetails() {
            if (errorModal.classList.contains('is-open')) {
                errorDetails.textContent = sessionSummary();
            }
        }

        function hideError() {
            errorModal.classList.remove('is-open');
        }

        async function postJson(url, payload) {
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': config.csrfToken,
                    'Accept': 'application/json',
                },
                body: JSON.stringify(payload),
            });

            if (!response.ok) {
                let body = '';
                try {
                    body = await response.text();
                } catch (error) {
                    body = '';
                }
                throw new Error('HTTP ' + response.status + (body ? ' ' + body.slice(0, 180) : ''));
            }

            return response.json().catch(() => ({}));
        }

        async function joinRoom() {
            if (roomJoined) {
                return;
            }

            stage = 'joining-room';
            updateDebug('Joining LiveKit room...', 'Connecting to the call API and publishing local media.');

            room = new Room({
                adaptiveStream: true,
                dynacast: true,
            });

            room
                .on(RoomEvent.TrackSubscribed, function (track) {
                    if (track.kind === Track.Kind.Video && remoteVideo) {
                        track.attach(remoteVideo);
                    }

                    if (track.kind === Track.Kind.Audio && remoteAudio) {
                        track.attach(remoteAudio);
                    }

                    if (remoteAudioState) {
                        remoteAudioState.textContent = 'Remote media connected.';
                    }
                })
                .on(RoomEvent.Disconnected, function () {
                    roomJoined = false;
                    stage = 'disconnected';
                    updateDebug('Call disconnected.', 'The LiveKit room connection has closed.');
                    errorDetails.textContent = sessionSummary();
                })
                .on(RoomEvent.LocalTrackPublished, function (publication) {
                    if (publication.track && publication.track.kind === Track.Kind.Video && localVideo) {
                        publication.track.attach(localVideo);
                    }
                    if (localAudioState) {
                        localAudioState.textContent = 'Microphone is active on this device.';
                    }
                });

            await room.connect(config.livekitUrl, config.livekitToken, {
                autoSubscribe: true,
            });

            await room.localParticipant.setMicrophoneEnabled(true);
            if (config.callType === 'video') {
                await room.localParticipant.setCameraEnabled(true);
            });

            roomJoined = true;
            muteButton.disabled = false;
            if (cameraButton) {
                cameraButton.disabled = config.callType !== 'video';
            }
            stage = 'connected';
            statusBadge.textContent = 'Connected';
            updateDebug('Call connected.', 'LiveKit room joined successfully.');
            hideError();
            notifyParent('pikfresh-call-title', { title: config.title });

            if (!connectedNotified) {
                connectedNotified = true;
                await postJson(config.connectedUrl, {});
            }
        }

        async function poll() {
            try {
                const response = await fetch(config.pollUrl, {
                    headers: { 'Accept': 'application/json' },
                });
                const data = await response.json();

                pollFailures = 0;
                statusBadge.textContent = (data.status || 'unknown').replace(/^./, (character) => character.toUpperCase());

                if (data.status === 'ended') {
                    stage = 'ended';
                    updateDebug('Call ended.', 'The other participant ended this call.');
                    window.clearInterval(pollTimer);
                    if (room) {
                        room.disconnect();
                    }
                    if (config.embedded) {
                        window.setTimeout(function () {
                            notifyParent('pikfresh-call-close');
                        }, 900);
                    }
                    return;
                }

                if ((data.status === 'accepted' || data.status === 'connected') && !roomJoined) {
                    await joinRoom();
                }
            } catch (error) {
                pollFailures += 1;
                networkError = error.message;
                stage = 'poll-failed';
                if (pollFailures >= 2) {
                    showError('The live call status request failed.');
                }
            }
        }

        async function manualStart() {
            if (autoJoinAttempted && roomJoined) {
                return;
            }

            autoJoinAttempted = true;
            hideError();
            networkError = 'none';
            startButton.disabled = true;

            try {
                if (config.role === 'buyer') {
                    stage = 'ringing';
                    updateDebug('Waiting for vendor...', 'The buyer is waiting for the vendor to accept before joining the room.');
                    await poll();
                } else {
                    await joinRoom();
                }
            } catch (error) {
                networkError = error.message;
                stage = 'start-failed';
                showError('The browser could not start local media or peer setup.');
                startButton.disabled = false;
            }
        }

        function endCall() {
            if (room) {
                room.disconnect();
            }

            postJson(config.endUrl, {}).finally(function () {
                if (config.embedded) {
                    notifyParent('pikfresh-call-close');
                    return;
                }

                window.location.href = config.backUrl;
            });
        }

        startButton.addEventListener('click', manualStart);
        retryButton.addEventListener('click', manualStart);
        closeErrorButton.addEventListener('click', hideError);
        endButton.addEventListener('click', endCall);

        muteButton.addEventListener('click', function () {
            if (!room) {
                return;
            }
            room.localParticipant.setMicrophoneEnabled(muteButton.textContent === 'Unmute').then(function () {
                muteButton.textContent = muteButton.textContent === 'Unmute' ? 'Mute' : 'Unmute';
            });
        });

        if (cameraButton) {
            cameraButton.addEventListener('click', function () {
                if (!room) {
                    return;
                }
                room.localParticipant.setCameraEnabled(cameraButton.textContent === 'Camera On').then(function () {
                    cameraButton.textContent = cameraButton.textContent === 'Camera On' ? 'Camera Off' : 'Camera On';
                });
            });
        }

        async function checkAndAutoStart() {
            try {
                if (navigator.permissions && navigator.permissions.query) {
                    const micStatus = await navigator.permissions.query({ name: 'microphone' });
                    const camStatus = config.callType === 'video' ? await navigator.permissions.query({ name: 'camera' }) : { state: 'granted' };
                    
                    if (micStatus.state === 'granted' && camStatus.state === 'granted') {
                        manualStart();
                        return;
                    }
                }
            } catch (e) {}

            startButton.disabled = false;
            startButton.textContent = config.callType === 'video' ? 'Allow Camera & Mic' : 'Allow Microphone';
            updateDebug('Media Permission Required', 'Please click the button above to grant access before joining.');
        }

        pollTimer = window.setInterval(poll, 2000);
        notifyParent('pikfresh-call-title', { title: config.title });
        checkAndAutoStart();
    })();
</script>
@else
<script>
    (function () {
        const config = {
            role: @json($isBuyer ? 'buyer' : 'vendor'),
            callType: @json($callType),
            csrfToken: @json(csrf_token()),
            pollUrl: @json($pollUrl),
            offerUrl: @json($offerUrl),
            answerUrl: @json($answerUrl),
            candidateUrl: @json($candidateUrl),
            connectedUrl: @json($connectedUrl),
            endUrl: @json($endUrl),
            backUrl: @json($backUrl),
            iceServers: @json($iceServers),
            embedded: @json(request()->boolean('embedded')),
            title: @json(($isBuyer ? ($callInvite->vendor->shop_name ?? 'Vendor') : ($callInvite->buyer->name ?? 'Buyer')).' '.ucfirst($callType).' call'),
        };

        let peer = null;
        let localStream = null;
        let remoteDescriptionSet = false;
        let pollTimer = null;
        let connectedNotified = false;
        let autoStarted = false;
        let hasStarted = false;
        let pollFailures = 0;
        let networkError = 'none';
        let stage = 'idle';

        const pendingCandidates = [];
        const sentCandidateKeys = new Set();
        const receivedCandidateKeys = new Set();

        const startButton = document.getElementById('startButton');
        const muteButton = document.getElementById('muteButton');
        const cameraButton = document.getElementById('cameraButton');
        const endButton = document.getElementById('endButton');
        const retryButton = document.getElementById('retryButton');
        const closeErrorButton = document.getElementById('closeErrorButton');
        const statusBadge = document.getElementById('statusBadge');
        const debugHeadline = document.getElementById('debugHeadline');
        const debugBody = document.getElementById('debugBody');
        const errorModal = document.getElementById('errorModal');
        const errorSummary = document.getElementById('errorSummary');
        const errorDetails = document.getElementById('errorDetails');
        const localVideo = document.getElementById('localVideo');
        const remoteVideo = document.getElementById('remoteVideo');
        const remoteAudio = document.getElementById('remoteAudio');
        const localAudioState = document.getElementById('localAudioState');
        const remoteAudioState = document.getElementById('remoteAudioState');

        function notifyParent(type, payload) {
            if (!config.embedded || window.parent === window) return;
            window.parent.postMessage(Object.assign({ type: type }, payload || {}), window.location.origin);
        }

        function updateDebug(headline, body) {
            debugHeadline.textContent = headline;
            debugBody.textContent = body;
        }

        function sessionSummary() {
            return [
                'Stage: ' + stage,
                'Peer: ' + (peer ? peer.connectionState : 'idle'),
                'ICE: ' + (peer ? peer.iceConnectionState : 'idle'),
                'Poll failures: ' + pollFailures,
                'Network error: ' + networkError,
            ].join(' | ');
        }

        function showError(summary) {
            errorSummary.textContent = summary;
            errorDetails.textContent = sessionSummary();
            errorModal.classList.add('is-open');
        }

        function hideError() {
            errorModal.classList.remove('is-open');
        }

        async function postJson(url, payload) {
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': config.csrfToken,
                    'Accept': 'application/json',
                    'ngrok-skip-browser-warning': 'true'
                },
                body: JSON.stringify(payload || {}),
                credentials: 'same-origin'
            });

            if (!response.ok) {
                let body = '';
                try { body = await response.text(); } catch (error) { body = ''; }
                throw new Error('HTTP ' + response.status + (body ? ' ' + body.slice(0, 180) : ''));
            }

            return response.json().catch(function () { return {}; });
        }

        function ensureVideoPlayback(element, muted) {
            if (!element) return;
            element.autoplay = true;
            element.playsInline = true;
            if (muted) element.muted = true;
            const playPromise = element.play();
            if (playPromise && typeof playPromise.catch === 'function') {
                playPromise.catch(function () {});
            }
        }

        async function ensureAudioPlayback(element) {
            if (!element) return true;
            element.autoplay = true;
            element.playsInline = true;
            element.muted = false;
            element.volume = 1;
            try {
                await element.play();
                return true;
            } catch (error) {
                return false;
            }
        }

        async function requestFrameFullscreen(element) {
            if (!element) return;
            if (document.fullscreenElement) {
                await document.exitFullscreen();
                return;
            }

            if (typeof element.requestFullscreen === 'function') {
                await element.requestFullscreen();
                return;
            }

            if (typeof element.webkitEnterFullscreen === 'function') {
                element.webkitEnterFullscreen();
            }
        }

        function bindVideoFullscreen(element) {
            if (!element) return;
            element.addEventListener('click', function () {
                requestFrameFullscreen(element).catch(function () {});
            });
        }

        async function initMedia() {
            if (localStream) return localStream;
            console.log('Requesting media access for:', config.callType);
            if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
                throw new Error('This browser does not support camera and microphone access. Please ensure you are using HTTPS or a localhost tunnel.');
            }

            try {
                if (config.callType === 'video') {
                    const videoOnlyStream = await navigator.mediaDevices.getUserMedia({
                        video: { width: { ideal: 640 }, height: { ideal: 480 }, facingMode: 'user' },
                        audio: false,
                    });
                    localStream = new MediaStream();
                    videoOnlyStream.getVideoTracks().forEach(function (track) { 
                        console.log('Video track acquired.');
                        localStream.addTrack(track); 
                    });

                    try {
                        const audioOnlyStream = await navigator.mediaDevices.getUserMedia({
                            video: false,
                            audio: { echoCancellation: true, noiseSuppression: true },
                        });
                        audioOnlyStream.getAudioTracks().forEach(function (track) { 
                            console.log('Audio track acquired.');
                            localStream.addTrack(track); 
                        });
                    } catch (audioError) {
                        console.warn('Audio unavailable, continuing with video only:', audioError);
                    }
                } else {
                    localStream = await navigator.mediaDevices.getUserMedia({
                        video: false,
                        audio: { echoCancellation: true, noiseSuppression: true },
                    });
                    console.log('Audio stream acquired.');
                }
            } catch (mediaError) {
                console.error('Media access error:', mediaError);
                throw new Error('Could not access ' + (config.callType === 'video' ? 'camera/microphone' : 'microphone') + '. Please check your browser permissions.');
            }

            if (localVideo) {
                localVideo.srcObject = localStream;
                ensureVideoPlayback(localVideo, true);
            }
            if (localAudioState) {
                localAudioState.textContent = 'Microphone is active on this device.';
            }

            muteButton.disabled = false;
            if (cameraButton) cameraButton.disabled = config.callType !== 'video';
            return localStream;
        }

        async function addOrQueueCandidate(candidate) {
            if (!peer || !candidate) return;
            if (!remoteDescriptionSet || !peer.remoteDescription) {
                pendingCandidates.push(candidate);
                return;
            }
            try {
                await peer.addIceCandidate(new RTCIceCandidate(candidate));
            } catch (error) {
                networkError = error.message || 'candidate-failed';
            }
        }

        async function flushPendingCandidates() {
            if (!peer || !remoteDescriptionSet || !peer.remoteDescription || pendingCandidates.length === 0) return;
            const queued = pendingCandidates.splice(0, pendingCandidates.length);
            for (const candidate of queued) {
                try {
                    await peer.addIceCandidate(new RTCIceCandidate(candidate));
                } catch (error) {
                    networkError = error.message || 'candidate-failed';
                }
            }
        }

        async function ensurePeer() {
            if (peer) return peer;
            console.log('Initializing peer connection and local media...');
            await initMedia();

            peer = new RTCPeerConnection({ iceServers: config.iceServers });
            console.log('RTCPeerConnection created with ICE servers:', config.iceServers.length);

            localStream.getTracks().forEach(function (track) { 
                console.log('Adding local track:', track.kind);
                peer.addTrack(track, localStream); 
            });

            peer.ontrack = function (event) {
                console.log('Remote track received:', event.track.kind);
                const stream = event.streams && event.streams[0] ? event.streams[0] : null;
                if (!stream) {
                    console.warn('No remote stream found in ontrack event.');
                    return;
                }

                if (remoteVideo && event.track.kind === 'video') {
                    remoteVideo.srcObject = stream;
                    ensureVideoPlayback(remoteVideo, false);
                }
                if (remoteAudio && event.track.kind === 'audio') {
                    remoteAudio.srcObject = stream;
                    console.log('Attaching remote audio stream.');
                    ensureAudioPlayback(remoteAudio).then(function (playing) {
                        if (!playing) {
                            startButton.disabled = false;
                            startButton.textContent = 'Enable Audio';
                            updateDebug('Audio needs tap.', 'Tap "Enable Audio" to allow remote sound on this device.');
                            console.warn('Audio playback blocked by browser. User interaction required.');
                        } else {
                            console.log('Remote audio is playing.');
                        }
                    });
                }
                if (remoteAudioState) remoteAudioState.textContent = 'Remote media connected.';
            };

            peer.onicecandidate = async function (event) {
                if (!event.candidate) return;
                const key = JSON.stringify({
                    candidate: event.candidate.candidate,
                    sdpMid: event.candidate.sdpMid,
                    sdpMLineIndex: event.candidate.sdpMLineIndex,
                });
                if (sentCandidateKeys.has(key)) return;
                sentCandidateKeys.add(key);
                try {
                    await postJson(config.candidateUrl, {
                        candidate: {
                            candidate: event.candidate.candidate,
                            sdpMid: event.candidate.sdpMid,
                            sdpMLineIndex: event.candidate.sdpMLineIndex,
                            usernameFragment: event.candidate.usernameFragment || null,
                        },
                    });
                } catch (error) {
                    networkError = error.message;
                }
            };

            peer.onconnectionstatechange = async function () {
                if (peer.connectionState === 'connected') {
                    stage = 'connected';
                    statusBadge.textContent = 'Connected';
                    updateDebug('Call connected.', 'Peer connection established successfully.');
                    hideError();
                    
                    if (config.callType === 'audio' && remoteAudio) {
                        ensureAudioPlayback(remoteAudio).then(function (playing) {
                            if (!playing) {
                                startButton.disabled = false;
                                startButton.textContent = 'Enable Audio';
                            } else {
                                startButton.textContent = 'Connected';
                                startButton.disabled = true;
                            }
                        });
                    } else {
                        startButton.textContent = 'Connected';
                        startButton.disabled = true;
                    }

                    if (!connectedNotified) {
                        connectedNotified = true;
                        await postJson(config.connectedUrl, {});
                    }
                }
            };

            peer.oniceconnectionstatechange = function () {
                console.log('ICE state change:', peer.iceConnectionState);
                if (peer.iceConnectionState === 'failed') {
                    stage = 'ice-failed';
                    showError('ICE could not find a working route between both devices. This usually happens when both devices are behind restrictive NATs or firewalls. If you are using ngrok, ensure you have a stable connection.');
                }
            };

            peer.onicegatheringstatechange = function () {
                console.log('ICE gathering state:', peer.iceGatheringState);
            };

            return peer;
        }

        async function startOffer() {
            stage = 'creating-offer';
            updateDebug('Starting call...', 'Creating local offer and sending it to the other participant.');
            await ensurePeer();
            const offer = await peer.createOffer({
                offerToReceiveAudio: true,
                offerToReceiveVideo: config.callType === 'video',
            });
            await peer.setLocalDescription(offer);
            await postJson(config.offerUrl, { sdp: { type: offer.type, sdp: offer.sdp } });
            stage = 'offer-sent';
            statusBadge.textContent = 'Ringing';
            updateDebug('Offer sent.', 'Waiting for the other participant to answer.');
        }

        async function answerOffer(offerSdp) {
            await ensurePeer();
            if (!peer.currentRemoteDescription) {
                await peer.setRemoteDescription(new RTCSessionDescription(offerSdp));
                remoteDescriptionSet = true;
                await flushPendingCandidates();
            }
            const answer = await peer.createAnswer();
            await peer.setLocalDescription(answer);
            await postJson(config.answerUrl, { sdp: { type: answer.type, sdp: answer.sdp } });
            stage = 'answer-sent';
            statusBadge.textContent = 'Connecting';
            updateDebug('Answer sent.', 'Finalizing peer connection.');
        }

        async function applyAnswer(answerSdp) {
            if (!peer || !answerSdp || peer.currentRemoteDescription) return;
            await peer.setRemoteDescription(new RTCSessionDescription(answerSdp));
            remoteDescriptionSet = true;
            await flushPendingCandidates();
            stage = 'answer-applied';
            statusBadge.textContent = 'Connecting';
            updateDebug('Answer received.', 'Waiting for ICE to complete.');
        }

        async function applyPeerCandidates(candidates) {
            for (const candidate of candidates || []) {
                const key = JSON.stringify(candidate);
                if (receivedCandidateKeys.has(key)) continue;
                receivedCandidateKeys.add(key);
                await addOrQueueCandidate(candidate);
            }
        }

        async function poll() {
            try {
                const response = await fetch(config.pollUrl, {
                    headers: { 
                        'Accept': 'application/json',
                        'ngrok-skip-browser-warning': 'true'
                    },
                    credentials: 'same-origin'
                });
                const data = await response.json();
                pollFailures = 0;

                statusBadge.textContent = (data.status || 'unknown').replace(/^./, function (character) {
                    return character.toUpperCase();
                });

                if (data.status === 'ended') {
                    stage = 'ended';
                    updateDebug('Call ended.', 'The call has been closed.');
                    window.clearInterval(pollTimer);
                    return;
                }

                if (config.role === 'vendor' && data.offer_sdp && (!peer || !peer.currentRemoteDescription)) {
                    await answerOffer(data.offer_sdp);
                }

                if (config.role === 'buyer' && data.answer_sdp) {
                    await applyAnswer(data.answer_sdp);
                }

                const peerCandidates = config.role === 'buyer'
                    ? (data.vendor_candidates || [])
                    : (data.buyer_candidates || []);
                await applyPeerCandidates(peerCandidates);
            } catch (error) {
                pollFailures += 1;
                networkError = error.message;
                if (pollFailures >= 2) {
                    showError('Network polling failed while syncing call state.');
                }
            }
        }

        async function manualStart() {
            if (hasStarted) {
                if (config.callType === 'audio' && remoteAudio) {
                    const playing = await ensureAudioPlayback(remoteAudio);
                    if (playing) {
                        startButton.textContent = 'Audio Enabled';
                        startButton.disabled = true;
                    }
                }
                return;
            }

            hasStarted = true;
            hideError();
            networkError = 'none';
            startButton.disabled = true;

            try {
                if (config.role === 'buyer') {
                    await startOffer();
                } else {
                    stage = 'waiting-offer';
                    updateDebug('Joining call...', 'Waiting for buyer offer, then answering automatically.');
                    await ensurePeer();
                    await poll();
                }
            } catch (error) {
                stage = 'start-failed';
                networkError = error.message;
                showError('Could not initialize camera/microphone and start the call.');
                startButton.disabled = false;
            }
        }

        function endCall() {
            if (pollTimer) window.clearInterval(pollTimer);
            if (localStream) localStream.getTracks().forEach(function (track) { track.stop(); });
            if (peer) peer.close();

            postJson(config.endUrl, {}).finally(function () {
                if (config.embedded) {
                    notifyParent('pikfresh-call-close');
                    return;
                }
                window.location.href = config.backUrl;
            });
        }

        startButton.addEventListener('click', manualStart);
        retryButton.addEventListener('click', manualStart);
        closeErrorButton.addEventListener('click', hideError);
        endButton.addEventListener('click', endCall);

        muteButton.addEventListener('click', function () {
            const track = localStream && localStream.getAudioTracks ? localStream.getAudioTracks()[0] : null;
            if (!track) return;
            track.enabled = !track.enabled;
            muteButton.textContent = track.enabled ? 'Mute' : 'Unmute';
        });

        if (cameraButton) {
            cameraButton.addEventListener('click', function () {
                const track = localStream && localStream.getVideoTracks ? localStream.getVideoTracks()[0] : null;
                if (!track) return;
                track.enabled = !track.enabled;
                cameraButton.textContent = track.enabled ? 'Camera Off' : 'Camera On';
            });
        }

        bindVideoFullscreen(localVideo);
        bindVideoFullscreen(remoteVideo);

        async function checkAndAutoStart() {
            try {
                if (navigator.permissions && navigator.permissions.query) {
                    const micStatus = await navigator.permissions.query({ name: 'microphone' });
                    const camStatus = config.callType === 'video' ? await navigator.permissions.query({ name: 'camera' }) : { state: 'granted' };
                    
                    if (micStatus.state === 'granted' && camStatus.state === 'granted') {
                        manualStart();
                        return;
                    }
                }
            } catch (e) {}

            startButton.disabled = false;
            startButton.textContent = config.callType === 'video' ? 'Allow Camera & Mic' : 'Allow Microphone';
            updateDebug('Media Permission Required', 'Please click the button above to grant access before the call can start.');
        }

        pollTimer = window.setInterval(poll, 2000);
        notifyParent('pikfresh-call-title', { title: config.title });

        if (!autoStarted) {
            autoStarted = true;
            checkAndAutoStart();
        }
    })();
</script>
@endif
@endsection
