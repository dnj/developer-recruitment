import React from 'react';
import ReactDOM from 'react-dom/client';
import './assets/scss/reset.scss';
import App from './app-timer/app-timer';

const root = ReactDOM.createRoot(document.getElementById('root'));
root.render(
  <React.StrictMode>
    <App />
  </React.StrictMode>
);

