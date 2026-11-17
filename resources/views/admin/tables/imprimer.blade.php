<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>QR Codes — Chic Gastro</title>
    <style>
        body { font-family: sans-serif; }
        .grille { display: grid; grid-template-columns: repeat(3, 1fr); gap: 24px; padding: 20px; }
        .carte { border: 2px solid #333; border-radius: 12px; padding: 16px; text-align: center; page-break-inside: avoid; }
        .carte img { width: 100%; max-width: 220px; }
        .carte h1 { font-size: 20px; margin: 8px 0 4px; }
        .carte p { font-size: 13px; color: #555; margin: 0; }
        @media print { .no-print { display: none; } }
    </style>
</head>
<body>
    <div class="no-print" style="padding:12px;">
        <button onclick="window.print()" style="padding:10px 20px; font-size:16px;">Imprimer</button>
    </div>
    <div class="grille">
        @foreach($tables as $table)
            <div class="carte">
                <h1>Table {{ $table->numero }}</h1>
                <img src="{{ route('admin.tables.qr.png', $table) }}" alt="QR Table {{ $table->numero }}">
                <p>Scannez pour voir la carte et commander</p>
                <p>{{ $table->urlMenu() }}</p>
            </div>
        @endforeach
    </div>
</body>
</html>
