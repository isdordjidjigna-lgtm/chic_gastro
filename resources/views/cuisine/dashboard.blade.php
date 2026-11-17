<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Cuisine</h2>
    </x-slot>

    <div class="py-6" x-data="fileCuisine()">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <p class="text-sm text-gray-500 mb-4">Actualisation automatique toutes les 7 secondes.</p>
            <div id="zone-commandes">
                @include('cuisine._commandes')
            </div>
        </div>
    </div>

    <script>
        function fileCuisine() {
            return {
                init() {
                    setInterval(() => this.rafraichir(), 7000);
                },
                async rafraichir() {
                    try {
                        const res = await fetch(@json(route('cuisine.commandes.liste')), {
                            headers: { 'Accept': 'text/html' },
                        });
                        if (res.ok) {
                            document.getElementById('zone-commandes').innerHTML = await res.text();
                        }
                    } catch (e) { /* nouvelle tentative au prochain tick */ }
                },
            };
        }
    </script>
</x-app-layout>
