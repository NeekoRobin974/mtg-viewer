<script setup>
import { onMounted, ref, watch } from 'vue';
import { fetchAllCards, fetchSets } from '../services/cardService';

const cards = ref([]);
const sets = ref([]);
const selectedSet = ref('');
const page = ref(1);
const totalPages = ref(1);
const loadingCards = ref(true);

async function loadCards() {
    loadingCards.value = true;
    try {
        const result = await fetchAllCards(selectedSet.value, page.value);
        cards.value = result.data;
        totalPages.value = result.meta.totalPages;
    } catch (e) {
        console.error(e);
    } finally {
        loadingCards.value = false;
    }
}

async function loadSets() {
    try {
        sets.value = await fetchSets();
    } catch (e) {
        console.error(e);
    }
}

watch(selectedSet, () => {
    if (page.value === 1) {
        loadCards();
    } else {
        page.value = 1;
    }
});

watch(page, () => {
    loadCards();
});

onMounted(() => {
    loadSets();
    loadCards();
});

</script>

<template>
    <div>
        <h1>Toutes les cartes</h1>
        <div class="filters">
            <label for="set-select">Filtrer par set :</label>
            <select id="set-select" v-model="selectedSet">
                <option value="">Tous les sets</option>
                <option v-for="set in sets" :key="set" :value="set">{{ set }}</option>
            </select>
        </div>

        <div class="pagination">
            <button type="button" :disabled="page <= 1" @click="page--">Précédent</button>
            <span>Page {{ page }} / {{ totalPages }}</span>
            <button type="button" :disabled="page >= totalPages" @click="page++">Suivant</button>
        </div>
    </div>
    <div class="card-list">
        <div v-if="loadingCards">Loading...</div>
        <div v-else>
            <div class="card-result" v-for="card in cards" :key="card.id">
                <router-link :to="{ name: 'get-card', params: { uuid: card.uuid } }">
                    {{ card.name }} <span>({{ card.uuid }})</span>
                </router-link>
            </div>
        </div>
    </div>
</template>
