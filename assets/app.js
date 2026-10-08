import "./app.css";
import { createApp } from "vue";
import App from "./App.vue";

const el = document.getElementById("app");
if (el) {
    createApp(App, {
        loginUrl: el.dataset.loginUrl,
        adminUrl: el.dataset.adminUrl,
        adminConnectUrl: el.dataset.adminConnectUrl,
        adminLogoutUrl: el.dataset.adminLogoutUrl,
        registerUrl: el.dataset.registerUrl,
        moviesUrl: el.dataset.moviesUrl,
        usersUrl: el.dataset.usersUrl,
        followingUrl: el.dataset.followingUrl,
        followersUrl: el.dataset.followersUrl,
    }).mount(el);
}
