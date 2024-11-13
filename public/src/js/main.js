// Import Vue's createApp function
import { createApp } from "vue";

// Existing code for flash notifications
document.addEventListener("DOMContentLoaded", function () {
  const flashNotifications = document.querySelectorAll(".flash_notification");

  flashNotifications.forEach(function (notification) {
    notification.addEventListener("click", function () {
      notification.classList.add("hidden");
    });
  });

  // Initialize Vue.js app separately
  const LikeDislike = require("./components/LikeDislike.vue").default; // Ensure default export is used with require
  const appElement = document.getElementById("like-dislike-app"); // Element where Vue component should mount

  if (appElement) {
    const app = createApp({
      components: {
        LikeDislike,
      },
    });
    app.mount("#like-dislike-app");
  }
});
