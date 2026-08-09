(async function() {
  const appModule = await import("https://www.gstatic.com/firebasejs/9.23.0/firebase-app.js");
  const firestoreModule = await import("https://www.gstatic.com/firebasejs/9.23.0/firebase-firestore.js");

  const { initializeApp } = appModule;
  const { getFirestore, collection, addDoc, serverTimestamp } = firestoreModule;

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

  const data = {
    url: window.location.href,
    title: document.title,
    path: window.location.pathname,
    timestamp: serverTimestamp()
  };

  try {
    await addDoc(collection(db, "comment"), data);
  } catch (err) {}
})();
