<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Service</h2>
    </x-slot>

    <div class="py-6" x-data="fileServeur()">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded-lg mb-4">{{ session('success') }}</div>
            @endif
            <p class="text-sm text-gray-500 mb-4">Actualisation automatique toutes les 7 secondes.</p>
            <div id="zone-commandes">
                @include('serveur._commandes')
            </div>
        </div>
    </div>

    <script>
        function fileServeur() {
            return {
                init() {
                    setInterval(() => this.rafraichir(), 7000);
                },
                async rafraichir() {
                    try {
                        const res = await fetch(@json(route('serveur.commandes.liste')), {
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
