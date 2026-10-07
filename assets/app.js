import "./app.css";
import { createApp } from "vue";
import App from "./App.vue";

const el = document.getElementById("app");
createApp(App, {
    loginUrl: el.dataset.loginUrl,
    moviesUrl: el.dataset.moviesUrl,
}).mount(el);
