<script setup>
import { ref } from "vue";
import Login from "./components/Login.vue";
import Movies from "./components/Movies.vue";

defineProps({ loginUrl: String, moviesUrl: String });

const user = ref(JSON.parse(localStorage.getItem("user")));

function login(data) {
    localStorage.setItem("token", data.token);
    localStorage.setItem("user", JSON.stringify(data.user));
    user.value = data.user;
}

function logout() {
    localStorage.removeItem("token");
    localStorage.removeItem("user");
    user.value = null;
}
</script>

<template>
    <Movies v-if="user" :user="user" :movies-url="moviesUrl" @logout="logout" />
    <Login v-else :login-url="loginUrl" @login="login" />
</template>
