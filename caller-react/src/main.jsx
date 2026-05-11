import React from 'react';
import ReactDOM from 'react-dom/client';
import StandaloneCallerApp from './StandaloneCallerApp.jsx';
import './styles.css';

ReactDOM.createRoot(document.getElementById('root')).render(
  <React.StrictMode>
    <StandaloneCallerApp />
  </React.StrictMode>,
);
