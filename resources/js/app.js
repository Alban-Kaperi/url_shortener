import "./bootstrap";

import router from "./router/index";
import { createApp } from "vue";

import Toast from "vue-toastification";
// Import the CSS or use your own!
import "vue-toastification/dist/index.css";

import App from "./App.vue";

createApp(App).use(router).use(Toast).mount("#app");
