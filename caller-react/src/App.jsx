import { useEffect, useState } from 'react';

const apiPrefix = '/laravel';

function joinUrl(baseUrl, path) {
  if (!path) {
    return baseUrl || '';
  }

  if (/^https?:\/\//i.test(path)) {
    return path;
  }

  if (!baseUrl) {
    return path;
  }

  return `${baseUrl.replace(/\/$/, '')}/${path.replace(/^\//, '')}`;
}

function proxyPath(path) {
  return `${apiPrefix}${path.startsWith('/') ? path : `/${path}`}`;
}

async function requestCallMedia(type) {
  if (!navigator.mediaDevices?.getUserMedia) {
    return;
  }

  const stream = await navigator.mediaDevices.getUserMedia({
    audio: true,
    video: type === 'video',
  });

  stream.getTracks().forEach((track) => track.stop());
}

export default function App() {
  const [payload, setPayload] = useState(null);
  const [status, setStatus] = useState('Loading caller test data...');
  const [error, setError] = useState('');
  const [busyKey, setBusyKey] = useState('');

  async function loadData() {
    setError('');

    try {
      const response = await fetch(proxyPath('/caller/data'), {
        credentials: 'include',
        headers: {
          Accept: 'application/json',
        },
      });

      if (!response.ok) {
        throw new Error(`Could not load caller data. HTTP ${response.status}`);
      }

      const data = await response.json();
      setPayload(data);
      setStatus('Ready for a fresh caller test.');
    } catch (loadError) {
      setError(loadError.message);
      setStatus('Could not load the caller test data.');
    }
  }

  useEffect(() => {
    loadData();
  }, []);

  async function postJson(url, body, typeLabel, busyLabel) {
    setBusyKey(busyLabel);
    setError('');

    try {
      await requestCallMedia(typeLabel);

      const response = await fetch(proxyPath(url), {
        method: 'POST',
        credentials: 'include',
        headers: {
          Accept: 'application/json',
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': payload.csrf_token,
          'X-Requested-With': 'XMLHttpRequest',
        },
        body: JSON.stringify(body ?? {}),
      });

      if (!response.ok) {
        throw new Error(`Request failed. HTTP ${response.status}`);
      }

      return response.json();
    } finally {
      setBusyKey('');
    }
  }

  async function handleStartCall(vendor, type) {
    if (!payload) {
      return;
    }

    setStatus(`Preparing ${type} call permissions on this phone...`);

    try {
      const result = await postJson(vendor.call_url, { type }, type, `start-${vendor.id}-${type}`);
      setStatus(`Created call invite and opening ${type} call screen...`);

      if (result.call_url) {
        window.location.href = joinUrl(payload.base_url, result.call_url);
      }
    } catch (actionError) {
      setError(actionError.message);
      setStatus(`Could not start ${type} call. Check browser mic/camera permission on this phone.`);
    }
  }

  async function handleAcceptCall(call) {
    if (!payload) {
      return;
    }

    const type = call.call_type || 'audio';
    setStatus(`Preparing ${type} call permissions before accept...`);

    try {
      const result = await postJson(call.accept_url, {}, type, `accept-${call.id}`);
      setStatus(`Accepted call and opening live ${type} screen...`);

      if (result.call_url) {
        window.location.href = joinUrl(payload.base_url, result.call_url);
      }
    } catch (actionError) {
      setError(actionError.message);
      setStatus(`Could not accept ${type} call. Check browser mic/camera permission on this phone.`);
    }
  }

  return (
    <div className="app-shell">
      <div className="hero-card">
        <div>
          <div className="eyebrow">React Caller Lab</div>
          <h1>Isolated caller test project</h1>
          <p>
            This React app talks to the same Laravel caller endpoints so we can test fresh
            audio and video call flows outside the Blade screens.
          </p>
        </div>
        <div className="hero-actions">
          <button className="ghost-btn" type="button" onClick={loadData}>
            Refresh Data
          </button>
          {payload?.base_url ? (
            <a className="ghost-btn" href={joinUrl(payload.base_url, '/caller')}>
              Open Blade Caller
            </a>
          ) : null}
        </div>
      </div>

      <div className="status-card">
        <strong>Status</strong>
        <span>{status}</span>
        {payload?.viewer ? (
          <span className="status-meta">
            Signed in as {payload.viewer.name} ({payload.viewer.role})
          </span>
        ) : null}
        {error ? <span className="status-error">{error}</span> : null}
      </div>

      <div className="grid">
        {payload?.viewer?.is_buyer ? (
          <section className="panel">
            <div className="panel-header">
              <div>
                <h2>Start Calls</h2>
                <p>Pick a vendor and launch a fresh browser call from React.</p>
              </div>
            </div>
            <div className="list">
              {payload.vendors.length ? payload.vendors.map((vendor) => (
                <article className="item-card" key={vendor.id}>
                  <div className="item-top">
                    <div>
                      <h3>{vendor.shop_name}</h3>
                      <p>{vendor.address || 'No address added yet'}</p>
                    </div>
                    <span className={`pill ${vendor.is_live ? 'live' : ''}`}>
                      {vendor.is_live ? 'Live' : 'Offline'}
                    </span>
                  </div>
                  <div className="item-actions">
                    <button
                      className="primary-btn"
                      type="button"
                      disabled={busyKey === `start-${vendor.id}-audio`}
                      onClick={() => handleStartCall(vendor, 'audio')}
                    >
                      {busyKey === `start-${vendor.id}-audio` ? 'Starting...' : 'Start Audio'}
                    </button>
                    <button
                      className="ghost-btn"
                      type="button"
                      disabled={busyKey === `start-${vendor.id}-video`}
                      onClick={() => handleStartCall(vendor, 'video')}
                    >
                      {busyKey === `start-${vendor.id}-video` ? 'Starting...' : 'Start Video'}
                    </button>
                    <a className="ghost-btn" href={joinUrl(payload.base_url, vendor.vendor_url)}>
                      Open Vendor
                    </a>
                  </div>
                </article>
              )) : <EmptyState message="No vendors available for testing yet." />}
            </div>
          </section>
        ) : null}

        {payload?.viewer?.is_vendor ? (
          <section className="panel">
            <div className="panel-header">
              <div>
                <h2>Incoming Calls</h2>
                <p>Accept a buyer call here and jump into the live call screen.</p>
              </div>
            </div>
            <div className="list">
              {payload.incomingCalls.length ? payload.incomingCalls.map((call) => (
                <article className="item-card" key={call.id}>
                  <div className="item-top">
                    <div>
                      <h3>{call.buyer_name}</h3>
                      <p>Call #{call.id} | {call.call_type} | {call.status}</p>
                    </div>
                    <span className="pill">{call.status}</span>
                  </div>
                  <div className="item-actions">
                    {call.status === 'ringing' ? (
                      <button
                        className="primary-btn"
                        type="button"
                        disabled={busyKey === `accept-${call.id}`}
                        onClick={() => handleAcceptCall(call)}
                      >
                        {busyKey === `accept-${call.id}` ? 'Accepting...' : 'Accept'}
                      </button>
                    ) : null}
                    <a className="ghost-btn" href={joinUrl(payload.base_url, call.call_url)}>
                      Open Call
                    </a>
                  </div>
                </article>
              )) : <EmptyState message="No incoming calls right now. Refresh after the buyer starts one." />}
            </div>
          </section>
        ) : null}

        <section className="panel">
          <div className="panel-header">
            <div>
              <h2>Recent Invite Rows</h2>
              <p>Use this list to confirm both phones are opening the same call invite.</p>
            </div>
          </div>
          <div className="list">
            {payload?.recentCalls?.length ? payload.recentCalls.map((call) => (
              <article className="item-card" key={call.id}>
                <div className="item-top">
                  <div>
                    <h3>Call #{call.id} with {call.with_name}</h3>
                    <p>{call.call_type} | {call.status} | {call.created_at_human}</p>
                  </div>
                  <span className="pill">#{call.id}</span>
                </div>
                <div className="item-actions">
                  <a className="ghost-btn" href={joinUrl(payload.base_url, call.call_url)}>
                    Open Call
                  </a>
                </div>
              </article>
            )) : <EmptyState message="No call invites recorded yet." />}
          </div>
        </section>
      </div>
    </div>
  );
}

function EmptyState({ message }) {
  return (
    <div className="empty-state">
      {message}
    </div>
  );
}
