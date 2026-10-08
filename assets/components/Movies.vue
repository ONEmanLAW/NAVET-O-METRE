<script setup>
import { ref, onMounted } from "vue";

const props = defineProps({ moviesUrl: String });
const emit = defineEmits(["logout"]);

const movies = ref([]);
const page = ref(1);
const lastPage = ref(1);
const total = ref(0);
const title = ref("");
const year = ref("");
const loading = ref(false);

async function loadMovies() {
    const params = new URLSearchParams({ page: page.value, limit: 24 });
    if (title.value) params.set("title", title.value);
    if (year.value) params.set("year", year.value);

    loading.value = true;
    const response = await fetch(`${props.moviesUrl}?${params}`, {
        headers: { Authorization: `Bearer ${localStorage.getItem("token")}` },
    });
    loading.value = false;

    if (response.status === 401) {
        emit("logout");
        return;
    }

    const data = await response.json();
    movies.value = data.items;
    lastPage.value = data.lastPage;
    total.value = data.total;
}

function search() {
    page.value = 1;
    loadMovies();
}

async function rate(movie, score) {
    const removing = score === movie.myRating;

    const response = await fetch(`${props.moviesUrl}/${movie.id}/rating`, {
        method: removing ? "DELETE" : "PUT",
        headers: {
            "Content-Type": "application/json",
            Authorization: `Bearer ${localStorage.getItem("token")}`,
        },
        body: removing ? null : JSON.stringify({ score }),
    });

    if (response.status === 401) {
        emit("logout");
        return;
    }

    if (response.ok) {
        movie.myRating = removing ? null : score;
    }
}

function goTo(newPage) {
    page.value = newPage;
    loadMovies();
    window.scrollTo({ top: 0 });
}

onMounted(loadMovies);
</script>

<template>
    <h1>Films</h1>

    <form class="search" role="search" @submit.prevent="search">
        <input v-model.trim="title" type="search" placeholder="Rechercher un film" aria-label="Titre du film">
        <input v-model="year" type="number" min="1888" max="2100" placeholder="Année" aria-label="Année de sortie">
        <button>Rechercher</button>
    </form>

    <p v-if="loading" aria-live="polite">Chargement des films...</p>
    <p v-else-if="!movies.length">Aucun film ne correspond à ta recherche.</p>
    <p v-else>{{ total }} films</p>

    <ul class="movies" :aria-busy="loading">
        <li v-for="movie in movies" :key="movie.id">
            <img v-if="movie.poster" :src="movie.poster" :alt="`Affiche de ${movie.title}`" loading="lazy" @error="movie.poster = null">
            <div v-else class="no-poster">Pas d'affiche</div>
            <strong>{{ movie.title }}</strong>
            <small>{{ movie.releaseDate }}</small>
            <div class="stars">
                <span v-for="n in 10" :key="n" @click="rate(movie, n)">{{ n <= movie.myRating ? "★" : "☆" }}</span>
            </div>
        </li>
    </ul>

    <nav v-if="lastPage > 1" class="pagination">
        <button class="secondary" :disabled="page === 1" @click="goTo(page - 1)">Précédent</button>
        <span>Page {{ page }} / {{ lastPage }}</span>
        <button class="secondary" :disabled="page === lastPage" @click="goTo(page + 1)">Suivant</button>
    </nav>
</template>
