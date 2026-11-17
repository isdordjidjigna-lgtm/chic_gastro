<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Statistiques</h2>
            <select id="periode" class="border-gray-300 rounded-md shadow-sm text-sm">
                <option value="7">7 derniers jours</option>
                <option value="30" selected>30 derniers jours</option>
                <option value="90">90 derniers jours</option>
            </select>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-white rounded-xl shadow-sm p-5">
                    <p class="text-3xl font-bold text-amber-700" id="kpi-ca">—</p>
                    <p class="text-gray-600 text-sm">Chiffre d'affaires encaissé</p>
                </div>
                <div class="bg-white rounded-xl shadow-sm p-5">
                    <p class="text-3xl font-bold text-indigo-700" id="kpi-commandes">—</p>
                    <p class="text-gray-600 text-sm">Commandes (hors annulées)</p>
                </div>
                <div class="bg-white rounded-xl shadow-sm p-5">
                    <p class="text-3xl font-bold text-red-600" id="kpi-attente">—</p>
                    <p class="text-gray-600 text-sm">Commandes en cours</p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-white rounded-xl shadow-sm p-5">
                    <h3 class="font-bold text-gray-800 mb-4">Ventes par jour (CA)</h3>
                    <canvas id="chart-ca" height="120"></canvas>
                </div>
                <div class="bg-white rounded-xl shadow-sm p-5">
                    <h3 class="font-bold text-gray-800 mb-4">Commandes par jour</h3>
                    <canvas id="chart-commandes" height="120"></canvas>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm p-5">
                <h3 class="font-bold text-gray-800 mb-4">Plats les plus commandés</h3>
                <canvas id="chart-top" height="120"></canvas>
            </div>
        </div>
    </div>

    @vite(['resources/js/stats.js'])
</x-app-layout>
