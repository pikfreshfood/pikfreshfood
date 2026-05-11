export const apiPrefix = '/api';

export function apiPath(path) {
  return `${apiPrefix}${path.startsWith('/') ? path : `/${path}`}`;
}

export function authorizedHeaders(token) {
  return token
    ? {
        Authorization: `Bearer ${token}`,
      }
    : {};
}

export async function requestCallMedia(type) {
  if (!navigator.mediaDevices?.getUserMedia) {
    return null;
  }

  if (type === 'video') {
    const previewVideo = await navigator.mediaDevices.getUserMedia({
      video: {
        width: { ideal: 640 },
        height: { ideal: 480 },
        facingMode: 'user',
      },
      audio: false,
    });
    const combinedStream = new MediaStream();
    const videoTracks = previewVideo.getVideoTracks();
    videoTracks.forEach((track) => combinedStream.addTrack(track));

    try {
      const previewAudio = await navigator.mediaDevices.getUserMedia({
        video: false,
        audio: {
          echoCancellation: true,
          noiseSuppression: true,
        },
      });
      const audioTracks = previewAudio.getAudioTracks();
      audioTracks.forEach((track) => combinedStream.addTrack(track));
    } catch (audioError) {
      // Mirror caller/script.js behavior: continue even if mic permission is denied.
      console.warn('Microphone permission denied for video preflight:', audioError);
    }

    return combinedStream;
  }

  const audioOnly = await navigator.mediaDevices.getUserMedia({
    video: false,
    audio: {
      echoCancellation: true,
      noiseSuppression: true,
    },
  });
  return audioOnly;
}

export function normalizeDescription(description) {
  if (!description || typeof description !== 'object') {
    return null;
  }

  const normalized = {
    type: String(description.type || ''),
    sdp: String(description.sdp || ''),
  };

  normalized.sdp = normalized.sdp.replace(/^\uFEFF/, '');
  normalized.sdp = normalized.sdp.replace(/\r\n/g, '\n').replace(/\r/g, '\n').trim();

  if (normalized.sdp) {
    normalized.sdp = normalized.sdp.split('\n').map((line) => line.trimEnd()).join('\r\n');
    if (!normalized.sdp.endsWith('\r\n')) {
      normalized.sdp += '\r\n';
    }
  }

  return normalized;
}

export function serializeDescription(description) {
  return normalizeDescription({ type: description.type, sdp: description.sdp });
}

export function serializeCandidate(candidate) {
  return {
    candidate: candidate.candidate,
    sdpMid: candidate.sdpMid,
    sdpMLineIndex: candidate.sdpMLineIndex,
    usernameFragment: candidate.usernameFragment ?? null,
  };
}
