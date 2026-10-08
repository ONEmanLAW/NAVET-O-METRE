<script setup>
import { ref } from "vue";
import Login from "./components/Login.vue";
import Register from "./components/Register.vue";
import Movies from "./components/Movies.vue";
import Users from "./components/Users.vue";

defineProps({
    loginUrl: String,
    registerUrl: String,
    moviesUrl: String,
    usersUrl: String,
    followingUrl: String,
    followersUrl: String,
});

const user = ref(JSON.parse(localStorage.getItem("user")));
const page = ref("login");
const tab = ref("movies");

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
    tab.value = "movies";
}
</script>

<template>
    <template v-if="user">
        <header>
            <span>Connecté en tant que {{ user.email }}</span>
            <button @click="logout">Se déconnecter</button>
        </header>

        <nav class="tabs">
            <button :class="{ secondary: tab !== 'movies' }" :aria-pressed="tab === 'movies'" @click="tab = 'movies'">Films</button>
            <button :class="{ secondary: tab !== 'users' }" :aria-pressed="tab === 'users'" @click="tab = 'users'">Utilisateurs</button>
        </nav>

        <Movies v-if="tab === 'movies'" :movies-url="moviesUrl" @logout="logout" />
        <Users
            v-else
            :user="user"
            :users-url="usersUrl"
            :following-url="followingUrl"
            :followers-url="followersUrl"
            @logout="logout"
        />
    </template>
    <Register v-else-if="page === 'register'" :register-url="registerUrl" :login-url="loginUrl" @login="login" @show-login="page = 'login'" />
    <Login v-else :login-url="loginUrl" @login="login" @show-register="page = 'register'" />
</template>
