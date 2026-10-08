<script setup>
import { ref, onMounted } from "vue";

const props = defineProps({ moviesUrl: String, logoUrl: String });
const emit = defineEmits(["logout"]);

const movies = ref([]);
const page = ref(1);
const lastPage = ref(1);
const total = ref(0);
const title = ref("");
const year = ref("");
const loading = ref(false);
const hovered = ref(null);
const showers = ref([]);
let nextShowerId = 0;

// Each 10/10 adds its own shower, so several in a row keep the rain going.
function celebrate() {
    const shower = {
        id: nextShowerId++,
        drops: Array.from({ length: 30 }, () => ({
            left: Math.random() * 100,
            delay: Math.random() * 1.5,
            size: 24 + Math.random() * 24,
        })),
    };

    showers.value.push(shower);
    setTimeout(() => (showers.value = showers.value.filter((other) => other !== shower)), 3500);
}

const VERDICTS = [
    "Navet intergalactique",
    "Navet de compétition",
    "Navet bien mûr",
    "Presque comestible",
    "Bof bof",
    "Ça passe un dimanche",
    "Tes potes valident",
    "Validé par le navet",
    "Le navet s'incline",
    "Le roi du potager",
];

function shownScore(movie) {
    return hovered.value?.movieId === movie.id ? hovered.value.score : movie.myRating;
}

function verdict(movie) {
    const score = shownScore(movie);

    return score ? VERDICTS[score - 1] : "Pas encore jugé";
}

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

    if (response.ok && !removing && score === 10) {
        celebrate();
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
            <div class="poster">
                <img v-if="movie.poster" :src="movie.poster" :alt="`Affiche de ${movie.title}`" loading="lazy" @error="movie.poster = null">
                <div v-else class="no-poster">Pas d'affiche</div>
                <span v-if="movie.myRating === 1" class="stamp">Navet certifié</span>
            </div>
            <strong>{{ movie.title }}</strong>
            <small>{{ movie.releaseDate }}</small>
            <div class="rating">
                <div class="navets" @mouseleave="hovered = null">
                    <button
                        v-for="n in 10"
                        :key="n"
                        type="button"
                        :class="{ filled: n <= shownScore(movie) }"
                        :aria-label="`Noter ${n} sur 10`"
                        @mouseenter="hovered = { movieId: movie.id, score: n }"
                        @click="rate(movie, n)"
                    >
                        <img :src="logoUrl" alt="">
                    </button>
                </div>
                <small class="verdict">{{ verdict(movie) }}</small>
            </div>
        </li>
    </ul>

    <div v-if="showers.length" class="rain" aria-hidden="true">
        <template v-for="shower in showers" :key="shower.id">
            <img
                v-for="(drop, index) in shower.drops"
                :key="index"
                :src="logoUrl"
                alt=""
                :style="{ left: `${drop.left}%`, width: `${drop.size}px`, animationDelay: `${drop.delay}s` }"
            >
        </template>
    </div>

    <nav v-if="lastPage > 1" class="pagination">
        <button class="secondary" :disabled="page === 1" @click="goTo(page - 1)">Précédent</button>
        <span>Page {{ page }} / {{ lastPage }}</span>
        <button class="secondary" :disabled="page === lastPage" @click="goTo(page + 1)">Suivant</button>
    </nav>
</template>
