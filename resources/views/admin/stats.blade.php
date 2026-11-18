@extends('layouts.admin')

@section('title', 'Statistiques')
@section('eyebrow', 'Analyses • 12 derniers mois')

@section('actions')
    <select id="periode" class="border-gray-300 rounded-xl shadow-sm text-sm px-3 py-2 bg-white">
        <option value="7">7 derniers jours</option>
        <option value="30" selected>30 derniers jours</option>
        <option value="90">90 derniers jours</option>
    </select>
@endsection

@section('content')
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-2xl shadow-sm p-5">
            <p class="text-3xl font-bold text-amber-700" id="kpi-ca">—</p>
            <p class="text-gray-600 text-sm">Chiffre d'affaires encaissé</p>
        </div>
        <div class="bg-white rounded-2xl shadow-sm p-5">
            <p class="text-3xl font-bold text-amber-800" id="kpi-commandes">—</p>
            <p class="text-gray-600 text-sm">Commandes (hors annulées)</p>
        </div>
        <div class="bg-white rounded-2xl shadow-sm p-5">
            <p class="text-3xl font-bold text-red-600" id="kpi-attente">—</p>
            <p class="text-gray-600 text-sm">Commandes en cours</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <div class="bg-white rounded-2xl shadow-sm p-5">
            <h3 class="font-bold text-gray-800 mb-4">Ventes par jour (CA)</h3>
            <canvas id="chart-ca" height="120"></canvas>
        </div>
        <div class="bg-white rounded-2xl shadow-sm p-5">
            <h3 class="font-bold text-gray-800 mb-4">Commandes par jour</h3>
            <canvas id="chart-commandes" height="120"></canvas>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm p-5 mb-6">
        <h3 class="font-bold text-gray-800 mb-4">CA par mois (12 derniers mois)</h3>
        <canvas id="chart-mois" height="100"></canvas>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-2xl shadow-sm p-5">
            <h3 class="font-bold text-gray-800 mb-4">Plats les plus commandés</h3>
            <canvas id="chart-top" height="120"></canvas>
        </div>
        <div class="bg-white rounded-2xl shadow-sm p-5">
            <h3 class="font-bold text-gray-800 mb-4">Plats les moins commandés</h3>
            <ul id="liste-peu" class="divide-y divide-gray-100 text-sm"></ul>
        </div>
    </div>

    @vite(['resources/js/stats.js'])
@endsection
