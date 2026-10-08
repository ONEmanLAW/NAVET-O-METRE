<script setup>
import { ref, onMounted } from "vue";

const props = defineProps({ moviesUrl: String });
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
}

onMounted(loadMovies);
</script>

<template>
    <h1>Films</h1>
    <ul>
        <li v-for="movie in movies" :key="movie.id">
            <strong>{{ movie.title }}</strong> ({{ movie.releaseDate }})
            <div>
                <span v-for="n in 10" :key="n" @click="rate(movie, n)">{{ n <= movie.myRating ? "★" : "☆" }}</span>
            </div>
        </li>
    </ul>

    <nav>
        <button :disabled="page === 1" @click="goTo(page - 1)">Précédent</button>
        <span>Page {{ page }} / {{ lastPage }}</span>
        <button :disabled="page === lastPage" @click="goTo(page + 1)">Suivant</button>
    </nav>
</template>
