import { initializeApp } from 'firebase/app';
import { getAnalytics } from 'firebase/analytics';
import { getFirestore } from 'firebase/firestore';
import { getMessaging } from 'firebase/messaging';
import { getAuth } from 'firebase/auth';
// TODO: Add SDKs for Firebase products that you want to use
// https://firebase.google.com/docs/web/setup#available-libraries

// Your web app's Firebase configuration
// For Firebase JS SDK v7.20.0 and later, measurementId is optional
const firebaseConfig = {
  apiKey: 'AIzaSyAGK3rQNtP7lLszDvqw0hv1ZfqAZHF_Or8',
  authDomain: 'medical-tourism-25f6a.firebaseapp.com',
  projectId: 'medical-tourism-25f6a',
  storageBucket: 'medical-tourism-25f6a.appspot.com',
  messagingSenderId: '591835816344',
  appId: '1:591835816344:web:0a673187507b934fd84bed',
  measurementId: 'G-JZHN222BSR',
};

// Initialize Firebase
const app = initializeApp(firebaseConfig);
const analytics = getAnalytics(app);
const auth = getAuth(app);
const db = getFirestore(app);
const messaging = getMessaging(app);

export { app, analytics, auth, db, messaging };
