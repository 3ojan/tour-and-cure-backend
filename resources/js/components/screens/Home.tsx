import {
  getRequestsInProgress,
  useFirebaseAuth,
  useRequestsInProgress,
} from 'lib';
import React, { memo, ReactElement, useEffect } from 'react';
import { useLazyGetAccountQuery } from 'state';

export type HomeProps = {};

function Home(props: HomeProps): ReactElement {
  const [getUser, result] = useLazyGetAccountQuery();
  useEffect(() => {
    const res = getUser();
    console.log(res);
  }, []);
  console.log(getUser, result);
  return <div>Home</div>;
}

export default memo(Home);
