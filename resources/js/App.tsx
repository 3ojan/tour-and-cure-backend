// import { getRequestsInProgress } from 'lib/firebase/requestHelpers';
import React, { memo, ReactElement } from 'react';
import { Provider } from 'react-redux';
import {
  BrowserRouter as Router,
  // Switch,
  // Route,
  // Link
} from 'react-router-dom';
import Routes from './routes';
import { store } from 'state';

export type AppProps = {};

function App(props: AppProps): ReactElement {
  // getRequestsInProgress()
  return (
    <div style={{ display: 'flex', height: '100%' }}>
      <Provider store={store}>
        <Router>
          <Routes />
        </Router>
      </Provider>
    </div>
  );
}

export default memo(App);
