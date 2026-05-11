export default function DashboardScreen({
  busyKey,
  dashboard,
  error,
  onAcceptCall,
  onLogout,
  onOpenCall,
  onRefresh,
  onStartCall,
  status,
}) {
  return (
    <div className="app-shell">
      <div className="hero-card">
        <div>
          <div className="eyebrow">Caller Dashboard</div>
          <h1>Registered users are ready to call</h1>
          <p>Everyone on this page is a registered account. Start audio or video calls directly between users from this standalone React client.</p>
        </div>
        <div className="hero-actions">
          <button className="ghost-btn" type="button" onClick={onRefresh}>Refresh Users</button>
          <button className="ghost-btn" type="button" onClick={onLogout} disabled={busyKey === 'logout'}>
            {busyKey === 'logout' ? 'Signing out...' : 'Logout'}
          </button>
        </div>
      </div>

      <div className="status-card">
        <strong>Status</strong>
        <span>{status}</span>
        {dashboard?.user ? <span className="status-meta">Signed in as {dashboard.user.name} ({dashboard.user.email})</span> : null}
        {error ? <span className="status-error">{error}</span> : null}
      </div>

      <div className="grid">
        <section className="panel">
          <div className="panel-header">
            <h2>All Registered Users</h2>
            <p>Start a direct audio or video call with any other account listed here.</p>
          </div>
          <div className="list">
            {dashboard?.users?.length ? dashboard.users.map((user) => (
              <article className="item-card" key={user.id}>
                <div className="item-top">
                  <div>
                    <h3>{user.name}</h3>
                    <p>{user.email}</p>
                  </div>
                  <span className="pill">{user.role}</span>
                </div>
                <div className="item-actions">
                  <button className="primary-btn" type="button" disabled={busyKey === `start-${user.id}-audio`} onClick={() => onStartCall(user, 'audio')}>
                    {busyKey === `start-${user.id}-audio` ? 'Starting...' : 'Audio Call'}
                  </button>
                  <button className="ghost-btn" type="button" disabled={busyKey === `start-${user.id}-video`} onClick={() => onStartCall(user, 'video')}>
                    {busyKey === `start-${user.id}-video` ? 'Starting...' : 'Video Call'}
                  </button>
                </div>
              </article>
            )) : <div className="empty-state">No other registered users yet.</div>}
          </div>
        </section>

        <section className="panel">
          <div className="panel-header">
            <h2>Incoming Calls</h2>
            <p>Accept a ringing call here, or reopen an active session.</p>
          </div>
          <div className="list">
            {dashboard?.incoming_calls?.length ? dashboard.incoming_calls.map((call) => (
              <article className="item-card" key={call.id}>
                <div className="item-top">
                  <div>
                    <h3>{call.peer.name}</h3>
                    <p>Call #{call.id} | {call.call_type} | {call.status}</p>
                  </div>
                  <span className="pill">{call.status}</span>
                </div>
                <div className="item-actions">
                  {call.status === 'ringing' ? (
                    <button className="primary-btn" type="button" disabled={busyKey === `accept-${call.id}`} onClick={() => onAcceptCall(call)}>
                      {busyKey === `accept-${call.id}` ? 'Accepting...' : 'Accept'}
                    </button>
                  ) : null}
                  <button className="ghost-btn" type="button" onClick={() => onOpenCall(call.id)}>Open Call</button>
                </div>
              </article>
            )) : <div className="empty-state">No incoming calls right now.</div>}
          </div>
        </section>

        <section className="panel panel-span-2">
          <div className="panel-header">
            <h2>Recent Calls</h2>
            <p>Use this list to reopen the same call row from either side while testing.</p>
          </div>
          <div className="list">
            {dashboard?.recent_calls?.length ? dashboard.recent_calls.map((call) => (
              <article className="item-card" key={call.id}>
                <div className="item-top">
                  <div>
                    <h3>Call #{call.id} with {call.peer.name}</h3>
                    <p>{call.call_type} | {call.status} | {call.created_at_human}</p>
                  </div>
                  <span className="pill">#{call.id}</span>
                </div>
                <div className="item-actions">
                  <button className="ghost-btn" type="button" onClick={() => onOpenCall(call.id)}>Open Call</button>
                </div>
              </article>
            )) : <div className="empty-state">No direct calls recorded yet.</div>}
          </div>
        </section>
      </div>
    </div>
  );
}
