import { createApp } from "vue";
import App from "./App.vue";
import router from "./router";
// import store from "./store";

// Import plugins
import HttpPlugin from "./plugins/http";

// Import global styles
// import "./assets/css/global.css";

// Import auth service
import authService from "./services/auth";

const app = createApp(App);

// Install plugins
// app.use(store);
app.use(router);
app.use(HttpPlugin);

// Make auth service globally available
app.config.globalProperties.$auth = authService;
app.provide("$auth", authService);

// Global error handler
app.config.errorHandler = (err, instance, info) => {
  console.error("Global error:", err);
  console.error("Component info:", info);

  // You can also send errors to a logging service here
};

// For development: Set mock authentication
if (process.env.NODE_ENV === "development") {
  // You can set different roles for testing
  // authService.setMockToken('student');
  // authService.setMockToken('advisor');
}

app.mount("#app");
