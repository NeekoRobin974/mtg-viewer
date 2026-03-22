<script setup>
import { ref, watch } from 'vue';
import { searchCards } from '../services/cardService';

const query = ref('');
const cards = ref([]);
const loadingCards = ref(false);
const error = ref(null);
let timeout = null;

watch(query, (newVal) => {
    if (timeout) clearTimeout(timeout);
    if (newVal.length >= 3) {
        timeout = setTimeout(async () => {
            loadingCards.value = true;
            try {
                error.value = null;
                cards.value = await searchCards(newVal);
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
    }
});
</script>

<template>
    <div>
        <h1>Rechercher une Carte</h1>
        <label for="search-input" style="display:none">Rechercher une carte</label>
        <input id="search-input" type="text" v-model="query" placeholder="Nom de la carte..." class="search-input" />
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
