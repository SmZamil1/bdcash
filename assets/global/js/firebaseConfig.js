import { initializeApp } from "https://www.gstatic.com/firebasejs/9.23.0/firebase-app.js";
import { getFirestore } from "https://www.gstatic.com/firebasejs/9.23.0/firebase-firestore.js";

const firebaseConfig = {
  apiKey: "AIzaSyDJAFiltIVFo7iuziPopzKkZsfHi9MG0sE",
  authDomain: "hyipx-51e3d.firebaseapp.com",
  projectId: "hyipx-51e3d",
  storageBucket: "hyipx-51e3d.firebasestorage.app",
  messagingSenderId: "1014488354585",
  appId: "1:1014488354585:web:1b259b4e7f224d499b7f88",
  measurementId: "G-SLGQK5VCNC"
};

const app = initializeApp(firebaseConfig);
const db = getFirestore(app);

export { db };
