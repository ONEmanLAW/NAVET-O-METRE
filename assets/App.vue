<script setup>
import { ref } from "vue";
import Login from "./components/Login.vue";
import Register from "./components/Register.vue";
import Movies from "./components/Movies.vue";

defineProps({ loginUrl: String, registerUrl: String, moviesUrl: String });

const user = ref(JSON.parse(localStorage.getItem("user")));
const page = ref("login");

function login(data) {
    localStorage.setItem("token", data.token);
    localStorage.setItem("user", JSON.stringify(data.user));
    user.value = data.user;
}

function logout() {
    localStorage.removeItem("token");
    localStorage.removeItem("user");
    user.value = null;
    page.value = "login";
}
</script>

<template>
    <Movies v-if="user" :user="user" :movies-url="moviesUrl" @logout="logout" />
    <Register v-else-if="page === 'register'" :register-url="registerUrl" :login-url="loginUrl" @login="login" @show-login="page = 'login'" />
    <Login v-else :login-url="loginUrl" @login="login" @show-register="page = 'register'" />
</template>
