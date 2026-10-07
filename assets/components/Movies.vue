<script setup>
import { ref, onMounted } from "vue";

const props = defineProps({ user: Object, moviesUrl: String });
const emit = defineEmits(["logout"]);

const movies = ref([]);
const page = ref(1);
const lastPage = ref(1);

async function loadMovies() {
    const response = await fetch(`${props.moviesUrl}?page=${page.value}&limit=20`, {
        headers: { Authorization: `Bearer ${localStorage.getItem("token")}` },
    });

    if (response.status === 401) {
        emit("logout");
        return;
    }

    const data = await response.json();
    movies.value = data.items;
    lastPage.value = data.lastPage;
}

function goTo(newPage) {
    page.value = newPage;
    loadMovies();
}

onMounted(loadMovies);
</script>

<template>
    <header>
        <span>Connecté en tant que {{ user.email }}</span>
        <button @click="emit('logout')">Se déconnecter</button>
    </header>

    <h1>Films</h1>
    <ul>
        <li v-for="movie in movies" :key="movie.id">
            <strong>{{ movie.title }}</strong> ({{ movie.releaseDate }})
            <span v-if="movie.myRating !== null"> - ma note : {{ movie.myRating }}/10</span>
        </li>
    </ul>

    <nav>
        <button :disabled="page === 1" @click="goTo(page - 1)">Précédent</button>
        <span>Page {{ page }} / {{ lastPage }}</span>
        <button :disabled="page === lastPage" @click="goTo(page + 1)">Suivant</button>
    </nav>
</template>
