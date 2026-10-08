<script setup>
import { ref, onMounted } from "vue";

const props = defineProps({ user: Object, usersUrl: String, followingUrl: String, followersUrl: String });
const emit = defineEmits(["logout"]);

const users = ref([]);
const following = ref([]);
const followers = ref([]);

async function request(url, method = "GET") {
    const response = await fetch(url, {
        method,
        headers: { Accept: "application/json", Authorization: `Bearer ${localStorage.getItem("token")}` },
    });

    if (response.status === 401) {
        emit("logout");
    }

    return response;
}

async function loadList(url) {
    const response = await request(url);

    return response.ok ? response.json() : [];
}

async function loadUsers() {
    users.value = (await loadList(props.usersUrl)).filter((other) => other.id !== props.user.id);
    following.value = await loadList(props.followingUrl);
    followers.value = await loadList(props.followersUrl);
}

function isFollowing(other) {
    return following.value.some((followed) => followed.id === other.id);
}

async function toggleFollow(other) {
    const response = await request(`${props.usersUrl}/${other.id}/follow`, isFollowing(other) ? "DELETE" : "PUT");

    if (response.ok) {
        following.value = await loadList(props.followingUrl);
    }
}

onMounted(loadUsers);
</script>

<template>
    <h1>Utilisateurs</h1>
    <ul class="users">
        <li v-for="other in users" :key="other.id">
            <span>{{ other.email }}</span>
            <button :class="{ secondary: isFollowing(other) }" @click="toggleFollow(other)">
                {{ isFollowing(other) ? "Ne plus suivre" : "Suivre" }}
            </button>
        </li>
    </ul>

    <h2>Je suis abonné à ({{ following.length }})</h2>
    <ul>
        <li v-for="followed in following" :key="followed.id">{{ followed.email }}</li>
    </ul>
    <p v-if="!following.length">Tu ne suis personne pour l'instant.</p>

    <h2>Mes abonnés ({{ followers.length }})</h2>
    <ul>
        <li v-for="follower in followers" :key="follower.id">{{ follower.email }}</li>
    </ul>
    <p v-if="!followers.length">Personne ne te suit pour l'instant.</p>
</template>
