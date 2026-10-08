<script setup>
import { ref } from "vue";

const props = defineProps({ registerUrl: String, loginUrl: String });
const emit = defineEmits(["login", "showLogin"]);

const email = ref("");
const password = ref("");
const message = ref("");

async function register() {
    const headers = { "Content-Type": "application/json", Accept: "application/json" };
    const body = JSON.stringify({ email: email.value, password: password.value });

    const response = await fetch(props.registerUrl, { method: "POST", headers, body });
    if (!response.ok) {
        message.value = (await response.json()).detail;
        return;
    }

    const loginResponse = await fetch(props.loginUrl, { method: "POST", headers, body });
    emit("login", await loginResponse.json());
}
</script>

<template>
    <form @submit.prevent="register">
        <h1>Inscription</h1>
        <input v-model="email" type="email" placeholder="Email">
        <input v-model="password" type="password" placeholder="Mot de passe (8 caractères min.)">
        <button>S'inscrire</button>
    </form>
    <p>{{ message }}</p>
    <p>Déjà un compte ? <button type="button" class="link" @click="emit('showLogin')">Se connecter</button></p>
</template>
