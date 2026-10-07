<script setup>
import { ref } from "vue";

const props = defineProps({ loginUrl: String });

const email = ref("");
const password = ref("");
const message = ref("");

async function login() {
    const response = await fetch(props.loginUrl, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ email: email.value, password: password.value }),
    });
    const data = await response.json();

    if (response.ok) {
        localStorage.setItem("token", data.token);
        message.value = `Connecté en tant que ${data.user.email}`;
    } else {
        message.value = data.message;
    }
}
</script>

<template>
    <form @submit.prevent="login">
        <input v-model="email" type="email" placeholder="Email">
        <input v-model="password" type="password" placeholder="Mot de passe">
        <button>Se connecter</button>
    </form>
    <p>{{ message }}</p>
</template>
