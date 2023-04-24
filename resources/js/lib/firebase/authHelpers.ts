import {
  signInWithEmailAndPassword,
  onAuthStateChanged,
  User,
  Unsubscribe,
  Auth,
} from 'firebase/auth';
import { doc, getDoc } from 'firebase/firestore';
import { IClinic } from 'medToursTypes';
import { auth, db } from './init';

export const signIn = async ({
  email,
  password,
}: {
  email: string;
  password: string;
}) => {
  console.log(11, email, password);
  const userCredentials = await signInWithEmailAndPassword(
    auth,
    email,
    password,
  );
  console.log(userCredentials);
};

type CB = (User) => void;

export type AuthedClinic = IClinic & User;

class AuthObserverInstance {
  unsubscribe: Unsubscribe;
  user: User | null;
  auth: Auth;
  cbs: CB[];
  #clinic: IClinic | null;
  clinic: AuthedClinic | null;

  constructor() {
    this.cbs = [] as CB[];
    this.auth = auth;
    this.user = auth.currentUser;
    this.#clinic = null;
    this.clinic = null;

    if (this.user && this.user.uid) {
      this.getClinic(this.user.uid);
    }

    this.unsubscribe = onAuthStateChanged(auth, user => {
      if (user && user.refreshToken !== this.user?.refreshToken) {
        this.cbs.forEach(cb => cb(user));
        this.user = user;
        if (this.user && this.user.uid && !this.#clinic) {
          this.getClinic(this.user.uid);
        }
      } else {
        //   cb(null);
      }
    });
  }
  combine = () => {
    if (this.user && this.#clinic) {
      this.clinic = { ...this.#clinic, ...this.user } as AuthedClinic;
    }
  };

  private getClinic = async (uid: string) => {
    const docRef = doc(db, 'clinics', uid);
    const docSnap = await getDoc(docRef);
    this.#clinic = docSnap.data() as IClinic;
    this.combine();
    console.log(this);
  };
  setCallback = (cb: CB) => this.cbs.push(cb);
}

export const authObserverInstance = new AuthObserverInstance();
