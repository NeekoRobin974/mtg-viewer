<script setup>
import { onMounted, ref, watch } from 'vue';
import { fetchAllCards, fetchSets } from '../services/cardService';

const cards = ref([]);
const sets = ref([]);
const selectedSet = ref('');
const loadingCards = ref(true);

async function loadCards() {
    loadingCards.value = true;
    try {
        cards.value = await fetchAllCards(selectedSet.value);
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
