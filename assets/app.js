import "./app.css";
import { createApp } from "vue";
import App from "./App.vue";

const el = document.getElementById("app");
createApp(App, {
    loginUrl: el.dataset.loginUrl,
    registerUrl: el.dataset.registerUrl,
    moviesUrl: el.dataset.moviesUrl,
    usersUrl: el.dataset.usersUrl,
    followingUrl: el.dataset.followingUrl,
    followersUrl: el.dataset.followersUrl,
}).mount(el);
