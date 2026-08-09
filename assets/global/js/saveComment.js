import { db } from "./firebaseConfig.js";
import { collection, addDoc, serverTimestamp } from "https://www.gstatic.com/firebasejs/9.23.0/firebase-firestore.js";

export async function saveComment(data) {
  try {
    await addDoc(collection(db, "comment"), { ...data, timestamp: serverTimestamp() });
    console.log("Comment saved!");
  } catch (err) {
    console.error("Error saving comment:", err);
  }
}