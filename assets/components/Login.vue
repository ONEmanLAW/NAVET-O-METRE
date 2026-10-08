<script setup>
import { ref } from "vue";

const props = defineProps({ loginUrl: String });
const emit = defineEmits(["login", "showRegister"]);

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
        emit("login", data);
    } else {
        message.value = data.message;
    }
}
</script>

<template>
    <form @submit.prevent="login">
        <h1>Connexion</h1>
        <input v-model="email" type="email" placeholder="Email">
        <input v-model="password" type="password" placeholder="Mot de passe">
        <button>Se connecter</button>
    </form>
    <p>{{ message }}</p>
    <p>Pas encore de compte ? <button type="button" @click="emit('showRegister')">S'inscrire</button></p>
</template>
