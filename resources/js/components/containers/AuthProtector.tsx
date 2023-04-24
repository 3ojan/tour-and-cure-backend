import { useFirebaseAuth } from 'lib';
import React, { memo, ReactElement } from 'react';
import { useLocation, Navigate } from 'react-router-dom';

export type AuthProtectorProps = {
  children: ReactElement;
};

function AuthProtector({ children }: AuthProtectorProps): ReactElement {
  const user = true;
  const location = useLocation();
  if (!user) return <Navigate to="/login" state={{ from: location }} replace />;
  return children;
}

export default memo(AuthProtector);
