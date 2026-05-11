import { createServer } from 'node:http';
import { randomBytes, scryptSync, timingSafeEqual } from 'node:crypto';
import { existsSync, mkdirSync, readFileSync, writeFileSync } from 'node:fs';
import { dirname, join } from 'node:path';
import { fileURLToPath } from 'node:url';

const __dirname = dirname(fileURLToPath(import.meta.url));
const dataPath = join(__dirname, 'data', 'store.json');
const port = Number(process.env.PORT || 3001);

ensureStore();

function ensureStore() {
  if (!existsSync(dirname(dataPath))) {
    mkdirSync(dirname(dataPath), { recursive: true });
  }

  if (!existsSync(dataPath)) {
    writeStore({ users: [], calls: [], tokens: [] });
  }
}

function readStore() {
  return JSON.parse(readFileSync(dataPath, 'utf8'));
}

function writeStore(data) {
  writeFileSync(dataPath, `${JSON.stringify(data, null, 2)}\n`, 'utf8');
}

function sendJson(response, statusCode, payload) {
  response.writeHead(statusCode, {
    'Content-Type': 'application/json',
    'Access-Control-Allow-Origin': '*',
    'Access-Control-Allow-Headers': 'Content-Type, Authorization',
    'Access-Control-Allow-Methods': 'GET, POST, OPTIONS',
  });
  response.end(JSON.stringify(payload));
}

function parseBody(request) {
  return new Promise((resolve, reject) => {
    const chunks = [];
    request.on('data', (chunk) => chunks.push(chunk));
    request.on('end', () => {
      if (!chunks.length) {
        resolve({});
        return;
      }

      try {
        resolve(JSON.parse(Buffer.concat(chunks).toString('utf8')));
      } catch (error) {
        reject(new Error('Invalid JSON body.'));
      }
    });
    request.on('error', reject);
  });
}

function nowIso() {
  return new Date().toISOString();
}

function hashPassword(password) {
  const salt = randomBytes(16).toString('hex');
  const hash = scryptSync(password, salt, 64).toString('hex');
  return `${salt}:${hash}`;
}

function verifyPassword(password, storedValue) {
  const [salt, originalHash] = String(storedValue || '').split(':');
  if (!salt || !originalHash) {
    return false;
  }

  const candidate = scryptSync(password, salt, 64);
  const original = Buffer.from(originalHash, 'hex');
  return candidate.length === original.length && timingSafeEqual(candidate, original);
}

function createToken() {
  return randomBytes(24).toString('hex');
}

function getAuthorizationToken(request) {
  const header = request.headers.authorization || '';
  return header.startsWith('Bearer ') ? header.slice(7) : null;
}

function getAuthedUser(request, store) {
  const token = getAuthorizationToken(request);
  if (!token) {
    return null;
  }

  const tokenRow = store.tokens.find((item) => item.token === token);
  if (!tokenRow) {
    return null;
  }

  return store.users.find((user) => user.id === tokenRow.userId) || null;
}

function createAuthPayload(user, token = null) {
  return {
    authenticated: Boolean(user && token),
    token,
    user: user ? {
      id: user.id,
      name: user.name,
      email: user.email,
    } : null,
  };
}

function formatRelativeTime(isoString) {
  const created = new Date(isoString).getTime();
  const diffMinutes = Math.max(1, Math.round((Date.now() - created) / 60000));
  if (diffMinutes < 60) {
    return `${diffMinutes} min ago`;
  }
  const diffHours = Math.round(diffMinutes / 60);
  if (diffHours < 24) {
    return `${diffHours} hr ago`;
  }
  const diffDays = Math.round(diffHours / 24);
  return `${diffDays} day ago`;
}

function serializeCall(call, viewer, store) {
  const isCaller = call.callerId === viewer.id;
  const otherUser = store.users.find((user) => user.id === (isCaller ? call.calleeId : call.callerId));

  return {
    id: call.id,
    room_name: call.roomName,
    call_type: call.callType,
    status: call.status,
    role: isCaller ? 'caller' : 'callee',
    peer: {
      id: otherUser?.id ?? null,
      name: otherUser?.name ?? 'User',
      email: otherUser?.email ?? '',
    },
    created_at_human: formatRelativeTime(call.createdAt),
  };
}

function normalizeDescription(description) {
  const sdp = String(description?.sdp || '')
    .replace(/^\uFEFF/, '')
    .replace(/\r\n/g, '\n')
    .replace(/\r/g, '\n')
    .trim()
    .split('\n')
    .map((line) => line.trimEnd())
    .join('\r\n');

  return {
    type: String(description?.type || ''),
    sdp: sdp ? `${sdp}\r\n` : '',
  };
}

const server = createServer(async (request, response) => {
  if (request.method === 'OPTIONS') {
    sendJson(response, 204, {});
    return;
  }

  const url = new URL(request.url, `http://${request.headers.host}`);
  const path = url.pathname;

  try {
    if (request.method === 'GET' && path === '/api/auth/bootstrap') {
      const store = readStore();
      const user = getAuthedUser(request, store);
      const token = getAuthorizationToken(request);
      sendJson(response, 200, createAuthPayload(user, user ? token : null));
      return;
    }

    if (request.method === 'POST' && path === '/api/auth/register') {
      const body = await parseBody(request);
      if (!body.name || !body.email || !body.password || !body.password_confirmation) {
        sendJson(response, 422, { message: 'Name, email, password, and password confirmation are required.' });
        return;
      }
      if (body.password !== body.password_confirmation) {
        sendJson(response, 422, { message: 'Password confirmation does not match.' });
        return;
      }

      const store = readStore();
      if (store.users.some((user) => user.email.toLowerCase() === String(body.email).toLowerCase())) {
        sendJson(response, 422, { message: 'That email is already registered.' });
        return;
      }

      const user = {
        id: (store.users.at(-1)?.id || 0) + 1,
        name: String(body.name).trim(),
        email: String(body.email).trim().toLowerCase(),
        passwordHash: hashPassword(String(body.password)),
        createdAt: nowIso(),
      };

      const token = createToken();
      store.users.push(user);
      store.tokens.push({ token, userId: user.id, createdAt: nowIso() });
      writeStore(store);

      sendJson(response, 201, createAuthPayload(user, token));
      return;
    }

    if (request.method === 'POST' && path === '/api/auth/login') {
      const body = await parseBody(request);
      const store = readStore();
      const user = store.users.find((item) => item.email === String(body.email || '').trim().toLowerCase());

      if (!user || !verifyPassword(String(body.password || ''), user.passwordHash)) {
        sendJson(response, 422, { message: 'The provided credentials do not match our records.' });
        return;
      }

      const token = createToken();
      store.tokens = store.tokens.filter((item) => item.userId !== user.id);
      store.tokens.push({ token, userId: user.id, createdAt: nowIso() });
      writeStore(store);

      sendJson(response, 200, createAuthPayload(user, token));
      return;
    }

    if (request.method === 'POST' && path === '/api/auth/logout') {
      const store = readStore();
      const token = getAuthorizationToken(request);
      store.tokens = store.tokens.filter((item) => item.token !== token);
      writeStore(store);
      sendJson(response, 200, createAuthPayload(null, null));
      return;
    }

    const store = readStore();
    const user = getAuthedUser(request, store);

    if (!user) {
      sendJson(response, 401, { message: 'Authentication required.' });
      return;
    }

    if (request.method === 'GET' && path === '/api/dashboard') {
      const users = store.users
        .filter((item) => item.id !== user.id)
        .map((item) => ({ id: item.id, name: item.name, email: item.email }));

      const incomingCalls = store.calls
        .filter((call) => call.calleeId === user.id && ['ringing', 'accepted', 'connected'].includes(call.status))
        .sort((a, b) => b.id - a.id)
        .slice(0, 10)
        .map((call) => serializeCall(call, user, store));

      const recentCalls = store.calls
        .filter((call) => call.callerId === user.id || call.calleeId === user.id)
        .sort((a, b) => b.id - a.id)
        .slice(0, 20)
        .map((call) => serializeCall(call, user, store));

      sendJson(response, 200, {
        user: { id: user.id, name: user.name, email: user.email },
        users,
        incoming_calls: incomingCalls,
        recent_calls: recentCalls,
      });
      return;
    }

    if (request.method === 'POST' && path.startsWith('/api/users/') && path.endsWith('/start')) {
      const calleeId = Number(path.split('/')[3]);
      if (!calleeId || calleeId === user.id) {
        sendJson(response, 422, { message: 'You cannot call yourself.' });
        return;
      }

      const callee = store.users.find((item) => item.id === calleeId);
      if (!callee) {
        sendJson(response, 404, { message: 'User not found.' });
        return;
      }

      const body = await parseBody(request);
      const callType = body.type === 'video' ? 'video' : 'audio';

      store.calls.forEach((call) => {
        const samePair = (call.callerId === user.id && call.calleeId === calleeId) || (call.callerId === calleeId && call.calleeId === user.id);
        if (samePair && ['ringing', 'accepted', 'connected'].includes(call.status)) {
          call.status = 'ended';
          call.endedAt = nowIso();
        }
      });

      const call = {
        id: (store.calls.at(-1)?.id || 0) + 1,
        callerId: user.id,
        calleeId,
        roomName: `node-peer-${user.id}-${calleeId}-${Date.now()}`,
        callType,
        status: 'ringing',
        offerSdp: null,
        answerSdp: null,
        callerCandidates: [],
        calleeCandidates: [],
        acceptedAt: null,
        endedAt: null,
        createdAt: nowIso(),
      };

      store.calls.push(call);
      writeStore(store);

      sendJson(response, 201, { call: serializeCall(call, user, store) });
      return;
    }

    if (path.startsWith('/api/calls/')) {
      const segments = path.split('/').filter(Boolean);
      const callId = Number(segments[2]);
      const call = store.calls.find((item) => item.id === callId);

      if (!call || (call.callerId !== user.id && call.calleeId !== user.id)) {
        sendJson(response, 404, { message: 'Call not found.' });
        return;
      }

      const isCaller = call.callerId === user.id;

      if (request.method === 'GET' && segments.length === 3) {
        sendJson(response, 200, {
          call: serializeCall(call, user, store),
          offer_sdp: call.offerSdp,
          answer_sdp: call.answerSdp,
          caller_candidates: call.callerCandidates,
          callee_candidates: call.calleeCandidates,
          ice_servers: [
            { urls: ['stun:stun.l.google.com:19302', 'stun:stun1.l.google.com:19302', 'stun:openrelay.metered.ca:80'] },
            { urls: ['turn:openrelay.metered.ca:80', 'turn:openrelay.metered.ca:80?transport=tcp', 'turn:openrelay.metered.ca:443', 'turn:openrelay.metered.ca:443?transport=tcp'], username: 'openrelayproject', credential: 'openrelayproject' }
          ],
        });
        return;
      }

      if (request.method === 'GET' && segments[3] === 'poll') {
        sendJson(response, 200, {
          call: serializeCall(call, user, store),
          offer_sdp: call.offerSdp,
          answer_sdp: call.answerSdp,
          peer_candidates: isCaller ? call.calleeCandidates : call.callerCandidates,
        });
        return;
      }

      if (request.method === 'POST' && segments[3] === 'accept') {
        if (call.calleeId !== user.id) {
          sendJson(response, 403, { message: 'Only the receiver can accept this call.' });
          return;
        }
        if (call.status === 'ringing') {
          call.status = 'accepted';
          call.acceptedAt = nowIso();
          writeStore(store);
        }
        sendJson(response, 200, { ok: true });
        return;
      }

      if (request.method === 'POST' && segments[3] === 'offer') {
        if (!isCaller) {
          sendJson(response, 403, { message: 'Only the caller can send an offer.' });
          return;
        }
        const body = await parseBody(request);
        call.offerSdp = normalizeDescription(body.sdp);
        writeStore(store);
        sendJson(response, 200, { ok: true });
        return;
      }

      if (request.method === 'POST' && segments[3] === 'answer') {
        if (isCaller) {
          sendJson(response, 403, { message: 'Only the receiver can send an answer.' });
          return;
        }
        const body = await parseBody(request);
        call.answerSdp = normalizeDescription(body.sdp);
        call.status = 'connected';
        writeStore(store);
        sendJson(response, 200, { ok: true });
        return;
      }

      if (request.method === 'POST' && segments[3] === 'candidate') {
        const body = await parseBody(request);
        if (isCaller) {
          call.callerCandidates.push(body.candidate);
        } else {
          call.calleeCandidates.push(body.candidate);
        }
        writeStore(store);
        sendJson(response, 200, { ok: true });
        return;
      }

      if (request.method === 'POST' && segments[3] === 'connected') {
        if (call.status !== 'ended') {
          call.status = 'connected';
          writeStore(store);
        }
        sendJson(response, 200, { ok: true });
        return;
      }

      if (request.method === 'POST' && segments[3] === 'end') {
        call.status = 'ended';
        call.endedAt = nowIso();
        writeStore(store);
        sendJson(response, 200, { ok: true });
        return;
      }
    }

    sendJson(response, 404, { message: 'Not found.' });
  } catch (error) {
    sendJson(response, 500, { message: error.message || 'Server error.' });
  }
});

server.listen(port, () => {
  console.log(`Caller server running on http://localhost:${port}`);
});
