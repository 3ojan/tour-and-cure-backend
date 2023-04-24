import useNewRequestList from 'lib/hooks/useNewRequestList';
import React, { memo, ReactElement } from 'react';

export type NewRequestsProps = {};

function NewRequests(props: NewRequestsProps): ReactElement {
  const reqs = useNewRequestList();
  console.log(reqs);
  return <div>NewRequests</div>;
}

export default memo(NewRequests);
