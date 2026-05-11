export default function LoadingState({ message }) {
  return (
    <div className="app-shell centered-shell">
      <div className="status-card">
        <strong>{message}</strong>
      </div>
    </div>
  );
}
