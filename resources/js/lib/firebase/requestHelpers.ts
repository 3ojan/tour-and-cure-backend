import {
  collection,
  DocumentData,
  getDocs,
  query,
  where,
} from 'firebase/firestore';
import { db } from './init';
import { subDays } from 'date-fns/esm';
// import {IClinic} from 'medToursTypes';
import { AuthedClinic } from './authHelpers';

export const getRequests = async () => {
  const querySnapshot = await getDocs(collection(db, 'requests'));
  return querySnapshot.docs;
};

export const getRequestsInProgress = async ({
  uid,
  requestTypes,
}: AuthedClinic) => {
  if (!uid) return null;
  const res = [] as DocumentData[];
  const requestRef = collection(db, 'requests');
  const _query = query(requestRef, where(`messages.${uid}`, '!=', null));
  const querySnapshot = await getDocs(_query);
  querySnapshot.forEach(doc => {
    res.push(doc.data());
  });
  return res;
};

export const getNewRequests = async ({ uid, requestTypes }: AuthedClinic) => {
  if (!uid) return null;
  const res = [] as DocumentData[];
  const requestRef = collection(db, 'requests');
  const weekOld = subDays(new Date(), 7);
  console.log(requestTypes, uid, weekOld);
  const _query = query(
    requestRef,
    // where(`messages.${uid}`, '==', 0),
    where('type', 'in', requestTypes),
    where('createdAt', '>=', weekOld),
  );
  const querySnapshot = await getDocs(_query);
  console.log(querySnapshot);
  querySnapshot.forEach(doc => {
    res.push(doc.data());
  });
  return res;
};
