export default function AuthScreen({
  authMode,
  busy,
  error,
  form,
  onChange,
  onModeChange,
  onSubmit,
  status,
}) {
  return (
    <div className="app-shell auth-shell">
      <div className="auth-card">
        <div className="eyebrow">React Caller</div>
        <h1>Standalone registration and login</h1>
        <p>Sign in here, see every registered user after login, and start direct browser calls from this React app.</p>

        <div className="auth-tabs">
          <button type="button" className={`tab-btn ${authMode === 'login' ? 'active' : ''}`} onClick={() => onModeChange('login')}>Login</button>
          <button type="button" className={`tab-btn ${authMode === 'register' ? 'active' : ''}`} onClick={() => onModeChange('register')}>Register</button>
        </div>

        <form className="auth-form" onSubmit={onSubmit}>
          {authMode === 'register' ? (
            <input
              className="text-input"
              placeholder="Full name"
              value={form.name}
              onChange={(event) => onChange((current) => ({ ...current, name: event.target.value }))}
              required
            />
          ) : null}

          <input
            className="text-input"
            type="email"
            placeholder="Email"
            value={form.email}
            onChange={(event) => onChange((current) => ({ ...current, email: event.target.value }))}
            required
          />

          <input
            className="text-input"
            type="password"
            placeholder="Password"
            value={form.password}
            onChange={(event) => onChange((current) => ({ ...current, password: event.target.value }))}
            required
          />

          {authMode === 'register' ? (
            <input
              className="text-input"
              type="password"
              placeholder="Confirm password"
              value={form.password_confirmation}
              onChange={(event) => onChange((current) => ({ ...current, password_confirmation: event.target.value }))}
              required
            />
          ) : null}

          <button className="primary-btn wide-btn" type="submit" disabled={busy}>
            {busy ? 'Please wait...' : authMode === 'login' ? 'Login' : 'Create Account'}
          </button>
        </form>

        <div className="status-card compact-card">
          <strong>Status</strong>
          <span>{status}</span>
          {error ? <span className="status-error">{error}</span> : null}
        </div>
      </div>
    </div>
  );
}
