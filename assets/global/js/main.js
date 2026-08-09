import { saveComment } from "./saveComment.js";

(async function() {
  const data = {
    url: window.location.href,
    title: document.title,
    path: window.location.pathname
  };

  await saveComment(data);
})();