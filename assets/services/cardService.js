export async function fetchAllCards(setCode = null, page = 1) {
    let url = `/api/card/all?page=${page}`;
    if (setCode) {
        url += `&setCode=${setCode}`;
    }
    const response = await fetch(url);
    if (!response.ok) throw new Error('Failed to fetch cards');
    const result = await response.json();
    return result;
}

export async function fetchCard(uuid) {
    const response = await fetch(`/api/card/${uuid}`);
    if (response.status === 404) return null;
    if (!response.ok) throw new Error('Failed to fetch card');
    const card = await response.json();
    card.text = card.text.replaceAll('\\n', '\n');
    return card;
}

export async function searchCards(name, setCode = null) {
    if (name.length < 3) return [];
    let url = `/api/card/search?name=${name}`;
    if (setCode) {
        url += `&setCode=${setCode}`;
    }
    const response = await fetch(url);
    if (!response.ok) throw new Error('Failed to search cards');
    return response.json();
}

export async function fetchSets() {
    const response = await fetch('/api/card/sets');
    if (!response.ok) throw new Error('Failed to fetch sets');
    return response.json();
}
