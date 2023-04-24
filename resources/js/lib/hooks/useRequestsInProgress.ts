import { DocumentData } from 'firebase/firestore';
import { AuthedClinic, getRequestsInProgress } from 'lib/firebase';
import { useEffect, useState } from 'react';
import useFirebaseAuth from './useFirebaseAuth';
import { IClinic } from 'medToursTypes';

export default function useRequestsInProgress() {
  const [reqs, setReqs] = useState<DocumentData[] | null>(null);
  const user = useFirebaseAuth();
  const getReqs = async () => {
    const r = await getRequestsInProgress(user as AuthedClinic);
    setReqs(r);
  };

  useEffect(() => {
    getReqs();
  }, []);
  return reqs;
}
