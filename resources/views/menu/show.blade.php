<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>La carte — Chic Gastro · Table {{ $table->numero }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 min-h-screen" x-data="panier()">
    <header class="bg-white shadow-sm sticky top-0 z-20">
        <div class="max-w-3xl mx-auto px-4 py-4 flex items-center justify-between">
            <div>
                <h1 class="text-xl font-bold text-gray-900">Chic Gastro</h1>
                <p class="text-sm text-gray-500">Table {{ $table->numero }}</p>
            </div>
            <button @click="panierOuvert = true" class="relative bg-amber-600 text-white px-4 py-2 rounded-full text-sm font-semibold">
                Panier
                <span x-show="nombreArticles > 0" x-text="nombreArticles" class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center"></span>
            </button>
        </div>
        <div class="max-w-3xl mx-auto px-4 pb-3">
            <input type="search" x-model="recherche" placeholder="Rechercher un plat…"
                   class="w-full border border-gray-300 rounded-full px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-amber-500">
        </div>
    </header>

    <main class="max-w-3xl mx-auto px-4 py-6">
        <template x-for="categorie in categoriesFiltrees()" :key="categorie.nom">
            <section class="mb-8">
                <h2 class="text-lg font-bold text-gray-800 mb-3 border-b border-gray-200 pb-1" x-text="categorie.nom"></h2>
                <div class="space-y-3">
                    <template x-for="plat in categorie.plats" :key="plat.id">
                        <article class="bg-white rounded-xl shadow-sm p-3 flex gap-3">
                            <img :src="plat.photo" :alt="plat.nom" class="w-20 h-20 object-cover rounded-lg bg-gray-100 shrink-0">
                            <div class="flex-1 min-w-0">
                                <h3 class="font-semibold text-gray-900" x-text="plat.nom"></h3>
                                <p class="text-xs text-gray-500 line-clamp-2" x-text="plat.description"></p>
                                <p class="font-bold text-amber-700 mt-1" x-text="formatPrix(plat.prix)"></p>
                            </div>
                            <div class="self-center flex items-center gap-2 shrink-0">
                                <template x-if="quantiteDansPanier(plat.id) > 0">
                                    <div class="flex items-center gap-2">
                                        <button @click="changerQuantite(plat, -1)" class="w-8 h-8 rounded-full bg-gray-200 text-gray-700 font-bold">−</button>
                                        <span class="w-5 text-center font-semibold" x-text="quantiteDansPanier(plat.id)"></span>
                                    </div>
                                </template>
                                <button @click="changerQuantite(plat, 1)" class="w-8 h-8 rounded-full bg-amber-600 text-white font-bold">+</button>
                            </div>
                        </article>
                    </template>
                </div>
            </section>
        </template>
        <p x-show="categoriesFiltrees().length === 0" class="text-center text-gray-500 py-10">Aucun plat ne correspond à votre recherche.</p>
    </main>

    {{-- Tiroir panier --}}
    <div x-show="panierOuvert" x-cloak class="fixed inset-0 z-30">
        <div class="absolute inset-0 bg-black/50" @click="panierOuvert = false"></div>
        <div class="absolute bottom-0 left-0 right-0 bg-white rounded-t-2xl max-h-[85vh] flex flex-col">
            <div class="p-4 border-b border-gray-200 flex items-center justify-between">
                <h2 class="text-lg font-bold">Votre commande</h2>
                <button @click="panierOuvert = false" class="text-gray-500 text-2xl leading-none">×</button>
            </div>

            <div class="overflow-y-auto flex-1 p-4 space-y-3">
                <template x-for="item in panier" :key="item.id">
                    <div class="border border-gray-200 rounded-xl p-3">
                        <div class="flex items-center justify-between gap-2">
                            <div class="min-w-0">
                                <p class="font-semibold truncate" x-text="item.nom"></p>
                                <p class="text-sm text-amber-700 font-semibold" x-text="formatPrix(item.prix * item.quantite)"></p>
                            </div>
                            <div class="flex items-center gap-2 shrink-0">
                                <button @click="changerQuantitePanier(item, -1)" class="w-8 h-8 rounded-full bg-gray-200 font-bold">−</button>
                                <span class="w-5 text-center font-semibold" x-text="item.quantite"></span>
                                <button @click="changerQuantitePanier(item, 1)" class="w-8 h-8 rounded-full bg-gray-200 font-bold">+</button>
                                <button @click="supprimerDuPanier(item.id)" class="text-red-500 text-sm ml-1">✕</button>
                            </div>
                        </div>
                        <input type="text" x-model="item.observations" maxlength="200"
                               placeholder="Observations (ex : sans piment, sans oignon…)"
                               class="mt-2 w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-amber-500">
                    </div>
                </template>
                <p x-show="panier.length === 0" class="text-center text-gray-500 py-6">Votre panier est vide.</p>
            </div>

            <div class="p-4 border-t border-gray-200 space-y-3">
                <textarea x-model="noteGlobale" maxlength="500" rows="2" placeholder="Note pour la commande (optionnel)"
                          class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-amber-500"></textarea>
                <div class="flex items-center justify-between">
                    <span class="text-gray-600">Total</span>
                    <span class="text-xl font-bold text-amber-700" x-text="formatPrix(totalPanier)"></span>
                </div>
                <p x-show="erreur" x-text="erreur" class="text-sm text-red-600"></p>
                <button @click="commander" :disabled="panier.length === 0 || envoi"
                        class="w-full bg-amber-600 text-white font-bold py-3 rounded-xl disabled:opacity-50"
                        x-text="envoi ? 'Envoi en cours…' : 'Valider la commande'"></button>
            </div>
        </div>
    </div>

    <script>
        const TOKEN = @json($table->token);
        const CATEGORIES = @json($categoriesJson);
        const STORE_URL = @json(route('menu.commandes.store', $table->token));
        const STORAGE_KEY = 'panier_' + TOKEN;

        function panier() {
            return {
                recherche: '',
                panier: JSON.parse(localStorage.getItem(STORAGE_KEY) || '[]'),
                panierOuvert: false,
                noteGlobale: '',
                envoi: false,
                erreur: '',

                init() {
                    this.sauvegarder();
                },

                sauvegarder() {
                    localStorage.setItem(STORAGE_KEY, JSON.stringify(this.panier));
                },

                formatPrix(p) {
                    return new Intl.NumberFormat('fr-FR').format(p) + ' FCFA';
                },

                categoriesFiltrees() {
                    const q = this.recherche.toLowerCase();
                    if (!q) return CATEGORIES;
                    return CATEGORIES
                        .map(c => ({ ...c, plats: c.plats.filter(p => (p.nom + ' ' + p.description).toLowerCase().includes(q)) }))
                        .filter(c => c.plats.length > 0);
                },

                platParId(id) {
                    for (const c of CATEGORIES) {
                        const p = c.plats.find(p => p.id === id);
                        if (p) return p;
                    }
                    return null;
                },

                quantiteDansPanier(id) {
                    const item = this.panier.find(i => i.id === id);
                    return item ? item.quantite : 0;
                },

                changerQuantite(plat, delta) {
                    const item = this.panier.find(i => i.id === plat.id);
                    const nouvelle = (item ? item.quantite : 0) + delta;
                    if (nouvelle <= 0) {
                        this.supprimerDuPanier(plat.id);
                    } else if (item) {
                        item.quantite = nouvelle;
                    } else {
                        this.panier.push({ id: plat.id, nom: plat.nom, prix: plat.prix, quantite: 1, observations: '' });
                    }
                    this.sauvegarder();
                },

                changerQuantitePanier(item, delta) {
                    item.quantite += delta;
                    if (item.quantite <= 0) this.supprimerDuPanier(item.id);
                    else this.sauvegarder();
                },

                supprimerDuPanier(id) {
                    this.panier = this.panier.filter(i => i.id !== id);
                    this.sauvegarder();
                },

                get nombreArticles() {
                    return this.panier.reduce((s, i) => s + i.quantite, 0);
                },

                get totalPanier() {
                    return this.panier.reduce((s, i) => s + i.prix * i.quantite, 0);
                },

                async commander() {
                    this.erreur = '';
                    this.envoi = true;
                    try {
                        const res = await fetch(STORE_URL, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                            },
                            body: JSON.stringify({
                                items: this.panier.map(i => ({
                                    plat_id: i.id,
                                    quantite: i.quantite,
                                    observations: i.observations || null,
                                })),
                                note: this.noteGlobale || null,
                            }),
                        });
                        const data = await res.json();
                        if (!res.ok) {
                            this.erreur = data.message || 'Erreur lors de l\'envoi de la commande.';
                            return;
                        }
                        localStorage.removeItem(STORAGE_KEY);
                        window.location.href = data.suivi_url;
                    } catch (e) {
                        this.erreur = 'Erreur réseau, veuillez réessayer.';
                    } finally {
                        this.envoi = false;
                    }
                },
            };
        }
    </script>
    <style>[x-cloak] { display: none !important; }</style>
</body>
</html>
