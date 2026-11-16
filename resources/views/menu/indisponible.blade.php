<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carte indisponible — Chic Gastro</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-6">
    <div class="bg-white rounded-2xl shadow-lg p-8 max-w-md text-center">
        <h1 class="text-2xl font-bold text-gray-800 mb-3">Chic Gastro</h1>
        <p class="text-gray-600">
            Le service de commande en ligne est temporairement indisponible
            @if(isset($table)) pour la table {{ $table->numero }}@endif.
            Veuillez vous adresser à un membre du personnel.
        </p>
    </div>
</body>
</html>
