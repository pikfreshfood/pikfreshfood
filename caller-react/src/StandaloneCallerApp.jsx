import { useEffect, useMemo, useState } from 'react';
import { apiPath, authorizedHeaders, requestCallMedia } from './api';
import { clearPreparedStream, setPreparedStream } from './callMediaSession';
import AuthScreen from './components/AuthScreen';
import CallScreen from './components/CallScreen';
import DashboardScreen from './components/DashboardScreen';
import LoadingState from './components/LoadingState';

export default function StandaloneCallerApp() {
  const [auth, setAuth] = useState({ authenticated: false, user: null, token: '' });
  const [authMode, setAuthMode] = useState('login');
  const [form, setForm] = useState({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
  });
  const [dashboard, setDashboard] = useState(null);
  const [status, setStatus] = useState('Loading standalone caller...');
  const [error, setError] = useState('');
  const [busyKey, setBusyKey] = useState('');
  const [activeCallId, setActiveCallId] = useState(null);
  const [booted, setBooted] = useState(false);

  async function bootstrap() {
    setError('');

    try {
      const storedToken = window.localStorage.getItem('caller_token') || '';
      const response = await fetch(apiPath('/auth/bootstrap'), {
        headers: {
          Accept: 'application/json',
          ...authorizedHeaders(storedToken),
        },
      });
      const payload = await response.json();
      setAuth(payload);
      setBooted(true);

      if (payload.authenticated) {
        setStatus(`Signed in as ${payload.user.name}. Loading registered users...`);
      } else {
        setStatus('Sign in or register to start direct user-to-user calls.');
      }
    } catch (loadError) {
      setError(loadError.message);
      setStatus('Could not reach the standalone caller backend.');
      setBooted(true);
    }
  }

  async function loadDashboard() {
    try {
      const response = await fetch(apiPath('/dashboard'), {
        headers: {
          Accept: 'application/json',
          ...authorizedHeaders(auth.token),
        },
      });

      if (!response.ok) {
        throw new Error(`Could not load users. HTTP ${response.status}`);
      }

      const payload = await response.json();
      setDashboard(payload);
      if (!activeCallId) {
        setStatus(`Welcome ${payload.user.name}. Pick any registered user to start a call.`);
      }
    } catch (loadError) {
      setError(loadError.message);
      setStatus('Could not load the caller dashboard.');
    }
  }

  async function postJson(url, body, token = auth.token) {
    const response = await fetch(apiPath(url), {
      method: 'POST',
      headers: {
        Accept: 'application/json',
        'Content-Type': 'application/json',
        ...authorizedHeaders(token),
      },
      body: JSON.stringify(body ?? {}),
    });

    const data = await response.json().catch(() => ({}));
    if (!response.ok) {
      throw new Error(data.message || `Request failed. HTTP ${response.status}`);
    }

    return data;
  }

  async function submitAuth(event) {
    event.preventDefault();
    setBusyKey('auth');
    setError('');

    try {
      const endpoint = authMode === 'login' ? '/auth/login' : '/auth/register';
      const payload = authMode === 'login'
        ? { email: form.email, password: form.password }
        : form;
      const result = await postJson(endpoint, payload, '');
      setAuth(result);
      if (result.token) {
        window.localStorage.setItem('caller_token', result.token);
      }
      setStatus(`Signed in as ${result.user.name}.`);
      setForm({ name: '', email: '', password: '', password_confirmation: '' });
    } catch (authError) {
      setError(authError.message);
      setStatus(authMode === 'login' ? 'Login failed.' : 'Registration failed.');
    } finally {
      setBusyKey('');
    }
  }

  async function logout() {
    setBusyKey('logout');

    try {
      const result = await postJson('/auth/logout', {});
      clearPreparedStream();
      setAuth(result);
      window.localStorage.removeItem('caller_token');
      setStatus('You have been signed out.');
      setDashboard(null);
      setActiveCallId(null);
    } catch (logoutError) {
      setError(logoutError.message);
    } finally {
      setBusyKey('');
    }
  }

  async function startCall(user, type) {
    setBusyKey(`start-${user.id}-${type}`);
    setError('');
    setStatus(`Preparing ${type} call permissions for ${user.name}...`);

    try {
      const preparedStream = await requestCallMedia(type);
      setPreparedStream(preparedStream);
      const payload = await postJson(`/users/${user.id}/start`, { type });
      setActiveCallId(payload.call.id);
      setStatus(`Created ${type} call with ${user.name}. Open the other account and accept it.`);
      await loadDashboard();
    } catch (callError) {
      clearPreparedStream();
      setError(callError.message);
      setStatus(`Could not start ${type} call.`);
    } finally {
      setBusyKey('');
    }
  }

  async function acceptCall(call) {
    setBusyKey(`accept-${call.id}`);
    setError('');
    setStatus(`Preparing ${call.call_type} call permissions before accept...`);

    try {
      const preparedStream = await requestCallMedia(call.call_type);
      setPreparedStream(preparedStream);
      await postJson(`/calls/${call.id}/accept`, {});
      setActiveCallId(call.id);
      setStatus(`Accepted ${call.call_type} call from ${call.peer.name}.`);
      await loadDashboard();
    } catch (callError) {
      clearPreparedStream();
      setError(callError.message);
      setStatus(`Could not accept ${call.call_type} call.`);
    } finally {
      setBusyKey('');
    }
  }

  useEffect(() => {
    bootstrap();
  }, []);

  useEffect(() => {
    if (auth.authenticated) {
      loadDashboard();
      const timer = window.setInterval(loadDashboard, 5000);
      return () => window.clearInterval(timer);
    }

    setDashboard(null);
    setActiveCallId(null);
    return undefined;
  }, [auth.authenticated]);

  const activeCallFromDashboard = useMemo(() => {
    if (!dashboard || !activeCallId) {
      return null;
    }

    return [...dashboard.incoming_calls, ...dashboard.recent_calls].find((call) => call.id === activeCallId) || null;
  }, [dashboard, activeCallId]);

  if (!booted) {
    return <LoadingState message="Loading standalone caller..." />;
  }

  if (!auth.authenticated) {
    return (
      <AuthScreen
        authMode={authMode}
        busy={busyKey === 'auth'}
        error={error}
        form={form}
        onChange={setForm}
        onModeChange={setAuthMode}
        onSubmit={submitAuth}
        status={status}
      />
    );
  }

  if (activeCallId) {
    return (
      <CallScreen
        auth={auth}
        callId={activeCallId}
        dashboardCall={activeCallFromDashboard}
        onBack={() => {
          setActiveCallId(null);
          loadDashboard();
        }}
        onFatalError={setError}
        setStatus={setStatus}
      />
    );
  }

  return (
    <DashboardScreen
      busyKey={busyKey}
      dashboard={dashboard}
      error={error}
      onAcceptCall={acceptCall}
      onLogout={logout}
      onOpenCall={setActiveCallId}
      onRefresh={loadDashboard}
      onStartCall={startCall}
      status={status}
    />
  );
}
