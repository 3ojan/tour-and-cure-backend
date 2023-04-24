import { DocumentData } from 'firebase/firestore';
import { AuthedClinic, getNewRequests } from 'lib/firebase';
import { useEffect, useState } from 'react';
import useFirebaseAuth from './useFirebaseAuth';
import { authObserverInstance } from 'lib/firebase';

export default function useNewRequestList() {
  const [reqs, setReqs] = useState<DocumentData[] | null>(null);
  let user = authObserverInstance.clinic;
  const getReqs = async () => {
    const r = await getNewRequests(user as AuthedClinic);
    setReqs(r);
  };

  useEffect(() => {
    const initRequest = () => {
      user = authObserverInstance.clinic;
      if (user) getReqs();
      else {
        setTimeout(initRequest, 500);
      }
    };
    initRequest();
  }, []);
  return reqs;
}
