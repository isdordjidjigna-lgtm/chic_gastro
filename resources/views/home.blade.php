<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>Chic Gastro — Commande en ligne</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 min-h-screen flex flex-col">
    <main class="flex-1 flex items-center justify-center px-4 py-10">
        <div class="max-w-sm w-full text-center space-y-6">
            <div>
                <div class="flex justify-center mb-4"><x-application-logo class="text-4xl" /></div>
                <p class="mt-3 text-gray-600">Scannez le QR Code sur votre table pour consulter la carte et commander depuis votre smartphone.</p>
            </div>
            <div class="bg-white rounded-2xl shadow-sm p-6">
                <p class="text-sm text-gray-500 mb-4">Le personnel du restaurant passe par l'espace de connexion.</p>
                <a href="{{ route('login') }}" class="inline-block bg-amber-600 text-white font-semibold px-6 py-3 rounded-xl text-sm">
                    Espace personnel
                </a>
            </div>
        </div>
    </main>
    <footer class="text-center text-xs text-gray-400 pb-6">
        Chic Gastro — Commande sans compte, directement depuis votre table
    </footer>
</body>
</html>
