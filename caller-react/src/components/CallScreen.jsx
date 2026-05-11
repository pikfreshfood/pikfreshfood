import { useEffect, useRef, useState } from 'react';
import { apiPath, authorizedHeaders, normalizeDescription, serializeCandidate, serializeDescription } from '../api';
import { clearPreparedStream, takePreparedStream } from '../callMediaSession';
import LoadingState from './LoadingState';

export default function CallScreen({ auth, callId, dashboardCall, onBack, onFatalError, setStatus }) {
  const [callData, setCallData] = useState(null);
  const [stage, setStage] = useState('loading');
  const [debug, setDebug] = useState('Preparing peer call...');
  const [modalError, setModalError] = useState('');
  const [summary, setSummary] = useState('');
  const [answerApplied, setAnswerApplied] = useState(false);
  const [answerInProgress, setAnswerInProgress] = useState(false);
  const [pollFailures, setPollFailures] = useState(0);
  const [networkError, setNetworkError] = useState('none');
  const [micMuted, setMicMuted] = useState(false);
  const [cameraOff, setCameraOff] = useState(false);
  const [actionBusy, setActionBusy] = useState('');
  const localVideoRef = useRef(null);
  const remoteVideoRef = useRef(null);
  const remoteAudioRef = useRef(null);
  const localStreamRef = useRef(null);
  const remoteStreamRef = useRef(new MediaStream());
  const peerRef = useRef(null);
  const pollTimerRef = useRef(null);
  const sentCandidateKeysRef = useRef(new Set());
  const receivedCandidateKeysRef = useRef(new Set());
  const connectedNotifiedRef = useRef(false);
  const autoStartTriggeredRef = useRef(false);
  const pendingCandidatesRef = useRef([]);
  const remoteDescriptionReadyRef = useRef(false);

  function playMediaElement(element) {
    if (!element) {
      return;
    }

    const playPromise = element.play();
    if (playPromise && typeof playPromise.catch === 'function') {
      playPromise.catch(() => null);
    }
  }

  function debugSummary() {
    const peer = peerRef.current;
    return [
      `Stage: ${stage}`,
      `Peer state: ${peer ? peer.connectionState : 'new'}`,
      `ICE state: ${peer ? peer.iceConnectionState : 'new'}`,
      `Signaling state: ${peer ? peer.signalingState : 'stable'}`,
      `Offer present: ${peer?.localDescription?.type === 'offer' ? 'yes' : 'no'}`,
      `Answer present: ${answerApplied ? 'yes' : 'no'}`,
      `Poll failures: ${pollFailures}`,
      `Network error: ${networkError}`,
    ].join(' | ');
  }

  function syncSummary() {
    setSummary(debugSummary());
  }

  function showError(message) {
    setModalError(message);
    setSummary(debugSummary());
  }

  async function fetchCallData() {
    const response = await fetch(apiPath(`/calls/${callId}`), {
      headers: {
        Accept: 'application/json',
        ...authorizedHeaders(auth.token),
      },
    });

    if (!response.ok) {
      throw new Error(`Could not load call ${callId}. HTTP ${response.status}`);
    }

    return response.json();
  }

  async function postJson(url, body) {
    const response = await fetch(apiPath(url), {
      method: 'POST',
      headers: {
        Accept: 'application/json',
        'Content-Type': 'application/json',
        ...authorizedHeaders(auth.token),
      },
      body: JSON.stringify(body ?? {}),
    });

    const data = await response.json().catch(() => ({}));
    if (!response.ok) {
      throw new Error(data.message || `Request failed. HTTP ${response.status}`);
    }

    return data;
  }

  async function ensureMedia() {
    if (localStreamRef.current) {
      return localStreamRef.current;
    }

    const preparedStream = takePreparedStream();
    if (preparedStream && preparedStream.getTracks().length) {
      localStreamRef.current = preparedStream;
      if (localVideoRef.current) {
        localVideoRef.current.srcObject = preparedStream;
        playMediaElement(localVideoRef.current);
      }
      setDebug('Using prepared call media from accept/start.');
      return preparedStream;
    }

    if (!navigator.mediaDevices?.getUserMedia) {
      throw new Error('This browser does not support media capture.');
    }

    const wantsVideo = callData.call.call_type === 'video';
    const videoConstraints = {
      width: { ideal: 640 },
      height: { ideal: 480 },
      facingMode: 'user',
    };
    let stream;

    try {
      if (wantsVideo) {
        setDebug('Requesting camera...');
        const videoOnlyStream = await navigator.mediaDevices.getUserMedia({
          video: videoConstraints,
          audio: false,
        });

        stream = new MediaStream();
        videoOnlyStream.getVideoTracks().forEach((track) => stream.addTrack(track));

        try {
          setDebug('Camera ready. Requesting microphone...');
          const audioOnlyStream = await navigator.mediaDevices.getUserMedia({
            video: false,
            audio: {
              echoCancellation: true,
              noiseSuppression: true,
            },
          });
          audioOnlyStream.getAudioTracks().forEach((track) => stream.addTrack(track));
          setDebug('Camera and microphone ready.');
        } catch (audioError) {
          console.warn('Microphone unavailable for video call, continuing with camera only:', audioError);
          setDebug('Camera ready. Microphone unavailable, continuing with video only.');
        }
      } else {
        setDebug('Requesting microphone...');
        stream = await navigator.mediaDevices.getUserMedia({
          video: false,
          audio: {
            echoCancellation: true,
            noiseSuppression: true,
          },
        });
        setDebug('Microphone ready.');
      }
    } catch (mediaError) {
      const reason = mediaError?.name || 'UnknownError';
      if (reason === 'NotAllowedError') {
        throw new Error(wantsVideo
          ? 'Camera permission was denied. Allow camera access and try again.'
          : 'Microphone permission was denied. Allow microphone access and try again.');
      }
      if (reason === 'NotReadableError') {
        throw new Error('Your camera or microphone is already in use by another app/tab.');
      }
      throw mediaError;
    }

    localStreamRef.current = stream;

    if (localVideoRef.current) {
      localVideoRef.current.srcObject = stream;
      playMediaElement(localVideoRef.current);
    }

    return stream;
  }

  function canApplyRemoteCandidates() {
    const peer = peerRef.current;
    return Boolean(peer && peer.remoteDescription && remoteDescriptionReadyRef.current);
  }

  async function addOrQueueCandidate(candidate) {
    if (!peerRef.current || !candidate) {
      return;
    }

    if (!canApplyRemoteCandidates()) {
      pendingCandidatesRef.current.push(candidate);
      return;
    }

    try {
      await peerRef.current.addIceCandidate(new RTCIceCandidate(candidate));
    } catch (candidateError) {
      setNetworkError(candidateError.message);
    }
  }

  async function flushPendingCandidates() {
    if (!canApplyRemoteCandidates()) {
      return;
    }

    if (!pendingCandidatesRef.current.length) {
      return;
    }

    const queued = [...pendingCandidatesRef.current];
    pendingCandidatesRef.current = [];

    for (const candidate of queued) {
      try {
        await peerRef.current.addIceCandidate(new RTCIceCandidate(candidate));
      } catch (candidateError) {
        setNetworkError(candidateError.message);
      }
    }
  }

  function ensurePeer() {
    if (peerRef.current) {
      return peerRef.current;
    }

    remoteDescriptionReadyRef.current = false;
    pendingCandidatesRef.current = [];

    const peer = new RTCPeerConnection({
      iceServers: callData.ice_servers,
    });

    peer.ontrack = (event) => {
      event.streams[0].getTracks().forEach((track) => remoteStreamRef.current.addTrack(track));
      if (remoteVideoRef.current) {
        remoteVideoRef.current.srcObject = remoteStreamRef.current;
        playMediaElement(remoteVideoRef.current);
      }
      if (remoteAudioRef.current) {
        remoteAudioRef.current.srcObject = remoteStreamRef.current;
        playMediaElement(remoteAudioRef.current);
      }
      setDebug('Remote media connected.');
      syncSummary();
    };

    peer.onconnectionstatechange = () => {
      if (peer.connectionState === 'connected') {
        setStage('connected');
        setDebug('Call connected.');
        setModalError('');
        if (!connectedNotifiedRef.current) {
          connectedNotifiedRef.current = true;
          postJson(`/calls/${callId}/connected`, {}).catch(() => null);
        }
      }

      if (['failed', 'disconnected', 'closed'].includes(peer.connectionState)) {
        setStage('connection-failed');
        showError('The peer connection did not finish connecting.');
      }

      syncSummary();
    };

    peer.oniceconnectionstatechange = () => {
      if (peer.iceConnectionState === 'checking') {
        setDebug('Checking network path between both users...');
      }

      if (peer.iceConnectionState === 'failed') {
        setStage('ice-failed');
        showError('ICE could not find a working route between both users.');
      }

      syncSummary();
    };

    peer.onicecandidate = async (event) => {
      if (!event.candidate) {
        return;
      }

      const key = JSON.stringify(serializeCandidate(event.candidate));
      if (sentCandidateKeysRef.current.has(key)) {
        return;
      }

      sentCandidateKeysRef.current.add(key);

      try {
        await postJson(`/calls/${callId}/candidate`, {
          candidate: serializeCandidate(event.candidate),
        });
      } catch (candidateError) {
        setNetworkError(candidateError.message);
        setStage('candidate-post-failed');
        showError('A network error occurred while posting ICE candidates.');
      }

      syncSummary();
    };

    peerRef.current = peer;
    syncSummary();
    return peer;
  }

  async function attachLocalTracks() {
    const stream = await ensureMedia();
    const peer = ensurePeer();
    const senders = peer.getSenders();

    stream.getTracks().forEach((track) => {
      if (!senders.find((sender) => sender.track === track)) {
        peer.addTrack(track, stream);
      }
    });
  }

  async function startOffer() {
    await attachLocalTracks();
    const peer = ensurePeer();
    setStage('creating-offer');
    setDebug('Creating offer...');

    const offer = await peer.createOffer({
      offerToReceiveAudio: true,
      offerToReceiveVideo: callData.call.call_type === 'video',
    });
    await peer.setLocalDescription(offer);
    syncSummary();

    await postJson(`/calls/${callId}/offer`, {
      sdp: serializeDescription(offer),
    });
    setStage('offer-sent');
    setDebug('Offer sent. Waiting for the other user to join.');
  }

  async function answerOffer(offer) {
    if (answerInProgress || answerApplied || (peerRef.current?.localDescription?.type === 'answer')) {
      return;
    }

    setAnswerInProgress(true);

    try {
      await attachLocalTracks();
      const peer = ensurePeer();
      const normalizedOffer = normalizeDescription(offer);

      if (!peer.currentRemoteDescription) {
        await peer.setRemoteDescription(new RTCSessionDescription(normalizedOffer));
        remoteDescriptionReadyRef.current = true;
        await flushPendingCandidates();
      }

      setStage('creating-answer');
      setDebug('Creating answer...');

      const answer = await peer.createAnswer();
      await peer.setLocalDescription(answer);
      syncSummary();

      await postJson(`/calls/${callId}/answer`, {
        sdp: serializeDescription(answer),
      });

      setAnswerApplied(true);
      setStage('answer-sent');
      setDebug('Answer sent. Waiting for the caller peer connection to finish.');
    } finally {
      setAnswerInProgress(false);
    }
  }

  async function applyAnswer(answer) {
    if (!peerRef.current || peerRef.current.currentRemoteDescription || !answer) {
      return;
    }

    const normalizedAnswer = normalizeDescription(answer);
    await peerRef.current.setRemoteDescription(new RTCSessionDescription(normalizedAnswer));
    remoteDescriptionReadyRef.current = true;
    await flushPendingCandidates();
    setAnswerApplied(true);
    setStage('answer-applied');
    setDebug('Answer received. Waiting for ICE to settle.');
    syncSummary();
  }

  async function applyCandidates(candidates) {
    if (!peerRef.current) {
      return;
    }

    for (const candidate of candidates || []) {
      const key = JSON.stringify(candidate);
      if (receivedCandidateKeysRef.current.has(key)) {
        continue;
      }

      receivedCandidateKeysRef.current.add(key);

      try {
        await addOrQueueCandidate(candidate);
      } catch {
        // addOrQueueCandidate already tracks errors via networkError state.
      }
    }

    syncSummary();
  }

  async function poll() {
    try {
      const response = await fetch(apiPath(`/calls/${callId}/poll`), {
        headers: {
          Accept: 'application/json',
          ...authorizedHeaders(auth.token),
        },
      });
      const data = await response.json();

      setPollFailures(0);
      setCallData((current) => current ? ({ ...current, call: data.call }) : current);
      setStage((current) => (current === 'connected' || current === 'answer-sent' || current === 'answer-applied' || current === 'offer-sent')
        ? current
        : (data.call.status || current));

      const peer = peerRef.current;
      const canAutoAnswer = data.call.role === 'callee'
        && Boolean(data.offer_sdp)
        && !answerApplied
        && !answerInProgress
        && !(peer?.localDescription?.type === 'answer')
        && !peer?.currentRemoteDescription;

      if (data.answer_sdp && callData?.call?.role === 'caller') {
        await applyAnswer(data.answer_sdp);
      }

      if (canAutoAnswer) {
        setDebug('Offer received from caller. Creating answer automatically...');
        await answerOffer(data.offer_sdp);
      }

      await applyCandidates(data.peer_candidates);

      if (data.call.status === 'ended') {
        setStage('ended');
        setDebug('Call ended.');
        window.clearInterval(pollTimerRef.current);
      }
    } catch (pollError) {
      setPollFailures((current) => current + 1);
      setNetworkError(pollError.message);
      setStage('poll-failed');
    }
  }

  async function manualStart() {
    setActionBusy('start');
    setModalError('');
    setNetworkError('none');

    try {
      if (callData.call.role === 'caller') {
        await startOffer();
      } else {
        const response = await fetch(apiPath(`/calls/${callId}/poll`), {
          headers: {
            Accept: 'application/json',
            ...authorizedHeaders(auth.token),
          },
        });
        const data = await response.json();

        if (!data.offer_sdp) {
          setStage('accepted-without-offer');
          showError('The caller has not sent a call offer yet.');
          return;
        }

        await answerOffer(data.offer_sdp);
      }
    } catch (startError) {
      setNetworkError(startError.message);
      setStage('start-failed');
      showError('The browser could not start local media or peer setup.');
    } finally {
      setActionBusy('');
      syncSummary();
    }
  }

  async function endCall() {
    if (localStreamRef.current) {
      localStreamRef.current.getTracks().forEach((track) => track.stop());
    }

    if (peerRef.current) {
      peerRef.current.close();
    }

    clearPreparedStream();
    pendingCandidatesRef.current = [];
    remoteDescriptionReadyRef.current = false;

    try {
      await postJson(`/calls/${callId}/end`, {});
    } catch (endError) {
      onFatalError(endError.message);
    }

    onBack();
  }

  function toggleMute() {
    const track = localStreamRef.current?.getAudioTracks?.()[0];
    if (!track) {
      return;
    }

    track.enabled = !track.enabled;
    setMicMuted(!track.enabled);
  }

  function toggleCamera() {
    const track = localStreamRef.current?.getVideoTracks?.()[0];
    if (!track) {
      return;
    }

    track.enabled = !track.enabled;
    setCameraOff(!track.enabled);
  }

  useEffect(() => {
    let mounted = true;

    fetchCallData()
      .then((payload) => {
        if (!mounted) {
          return;
        }

        setCallData(payload);
        setStage(payload.call.status || 'ready');
        setDebug(`Call with ${payload.call.peer.name} is ready.`);
        setStatus(`Opened direct ${payload.call.call_type} call with ${payload.call.peer.name}.`);
      })
      .catch((loadError) => {
        if (!mounted) {
          return;
        }

        setModalError(loadError.message);
        onFatalError(loadError.message);
      });

    return () => {
      mounted = false;
    };
  }, [callId]);

  useEffect(() => {
    if (!callData) {
      return undefined;
    }

    pollTimerRef.current = window.setInterval(poll, 2000);
    poll();

    return () => {
      if (pollTimerRef.current) {
        window.clearInterval(pollTimerRef.current);
      }
    };
  }, [callId, callData?.call?.id]);

  useEffect(() => {
    if (!callData || autoStartTriggeredRef.current) {
      return;
    }

    if (callData.call.role !== 'caller') {
      return;
    }

    autoStartTriggeredRef.current = true;
    manualStart();
  }, [callData?.call?.id, callData?.call?.role]);

  useEffect(() => () => {
    if (pollTimerRef.current) {
      window.clearInterval(pollTimerRef.current);
    }

    if (localStreamRef.current) {
      localStreamRef.current.getTracks().forEach((track) => track.stop());
    }

    if (peerRef.current) {
      peerRef.current.close();
    }

    clearPreparedStream();
    pendingCandidatesRef.current = [];
    remoteDescriptionReadyRef.current = false;
  }, []);

  if (!callData) {
    return <LoadingState message="Loading active call..." />;
  }

  const peerName = dashboardCall?.peer?.name || callData.call.peer.name;

  return (
    <div className="app-shell">
      <div className="hero-card">
        <div>
          <div className="eyebrow">Live Call</div>
          <h1>{peerName}</h1>
          <p>Direct user-to-user WebRTC call inside the standalone React app.</p>
        </div>
        <div className="hero-actions">
          <button className="ghost-btn" type="button" onClick={onBack}>Back</button>
          <button className="ghost-btn" type="button" onClick={endCall}>End Call</button>
        </div>
      </div>

      <div className="status-card">
        <strong>Status</strong>
        <span>{debug}</span>
        <span className="status-meta">{summary || debugSummary()}</span>
        {modalError ? <span className="status-error">{modalError}</span> : null}
      </div>

      <div className="call-actions-row">
        <button className="primary-btn" type="button" disabled={actionBusy === 'start'} onClick={manualStart}>
          {actionBusy === 'start' ? 'Working...' : callData.call.role === 'caller' ? 'Re-initiate Call' : 'Join Call'}
        </button>
        <button className="ghost-btn" type="button" onClick={toggleMute}>{micMuted ? 'Unmute' : 'Mute'}</button>
        {callData.call.call_type === 'video' ? (
          <button className="ghost-btn" type="button" onClick={toggleCamera}>{cameraOff ? 'Camera On' : 'Camera Off'}</button>
        ) : null}
      </div>

      <div className="grid">
        <section className="panel">
          <div className="panel-header">
            <h2>Your Media</h2>
          </div>
          {callData.call.call_type === 'video' ? (
            <video className="call-video" ref={localVideoRef} autoPlay muted playsInline />
          ) : (
            <div className="audio-state">Microphone will activate when you start or join the call.</div>
          )}
        </section>

        <section className="panel">
          <div className="panel-header">
            <h2>{peerName}</h2>
          </div>
          {callData.call.call_type === 'video' ? (
            <video className="call-video" ref={remoteVideoRef} autoPlay playsInline />
          ) : (
            <>
              <div className="audio-state">Remote audio appears here when the peer connection finishes.</div>
              <audio ref={remoteAudioRef} autoPlay playsInline />
            </>
          )}
        </section>
      </div>
    </div>
  );
}
