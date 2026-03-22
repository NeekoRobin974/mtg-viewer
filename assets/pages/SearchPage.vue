<script setup>
import { onMounted, ref, watch } from 'vue';
import { searchCards, fetchSets } from '../services/cardService';

const query = ref('');
const cards = ref([]);
const sets = ref([]);
const selectedSet = ref('');
const loadingCards = ref(false);
const error = ref(null);
let timeout = null;

async function loadSets() {
    try {
        sets.value = await fetchSets();
    } catch (e) {
        console.error(e);
    }
}

onMounted(() => {
    loadSets();
});

const performSearch = () => {
    if (timeout) clearTimeout(timeout);
    if (query.value.length >= 3) {
        timeout = setTimeout(async () => {
            loadingCards.value = true;
            try {
                error.value = null;
                cards.value = await searchCards(query.value, selectedSet.value);
            } catch (e) {
                error.value = 'Une erreur est survenue lors de la recherche.';
                cards.value = [];
            } finally {
                loadingCards.value = false;
            }
        }, 300);
    } else {
        cards.value = [];
        error.value = null;
        if (query.value.length > 0) {
            // Clear
        }
    }
};

watch(query, performSearch);
watch(selectedSet, performSearch);
</script>

<template>
    <div>
        <h1>Rechercher une Carte</h1>
        <div class="search-controls">
            <label for="search-input" style="display:none">Rechercher une carte</label>
            <input id="search-input" type="text" v-model="query" placeholder="Nom de la carte..." class="search-input" />

            <label for="set-select" class="set-label">Set :</label>
            <select id="set-select" v-model="selectedSet" class="set-select">
                <option value="">Tous les sets</option>
                <option v-for="set in sets" :key="set" :value="set">{{ set }}</option>
            </select>
        </div>
    </div>
    <div class="card-list">
        <div v-if="loadingCards">Recherche en cours...</div>
        <div v-else-if="error">{{ error }}</div>
        <div v-else-if="cards.length === 0 && query.length >= 3">Aucune carte trouvée.</div>
        <div v-else>
            <div class="card-result" v-for="card in cards" :key="card.id">
                <router-link :to="{ name: 'get-card', params: { uuid: card.uuid } }">
                    {{ card.name }} <span>({{ card.uuid }})</span>
                </router-link>
            </div>
        </div>
    </div>
</template>

<style scoped>
.search-input {
    margin: 20px 0;
    padding: 10px;
    width: 100%;
    max-width: 400px;
    font-size: 16px;
}
.card-result {
    margin-bottom: 10px;
}
</style>
