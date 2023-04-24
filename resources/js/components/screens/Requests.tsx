import useRequestsInProgress from 'lib/hooks/useRequestsInProgress';
import React, { memo, ReactElement } from 'react';

export type RequestsProps = {};

function Requests(props: RequestsProps): ReactElement {
  const requests = useRequestsInProgress();
  console.log(requests);
  return <div>Requests</div>;
}

export default memo(Requests);
