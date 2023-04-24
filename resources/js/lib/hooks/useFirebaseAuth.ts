import { useEffect, useState } from 'react';
import { authObserverInstance } from 'lib/firebase/authHelpers';
import { User } from 'firebase/auth';

export default function useFirebaseAuth() {
  const [user, setUser] = useState<User | null>(authObserverInstance.user);
  useEffect(() => {
    authObserverInstance.setCallback((user: User | null) => {
      setUser(user);
    });
  }, []);
  return user;
}
