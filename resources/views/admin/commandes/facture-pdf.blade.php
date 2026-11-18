<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Facture {{ $commande->facture->numero }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #222; }
        h1 { text-align: center; font-size: 20px; letter-spacing: 2px; margin-bottom: 2px; }
        .sous-titre { text-align: center; color: #666; font-size: 11px; margin-top: 0; }
        table.meta { width: 100%; margin: 16px 0; }
        table.meta td { vertical-align: top; font-size: 11px; line-height: 1.5; }
        table.lignes { width: 100%; border-collapse: collapse; margin-top: 12px; }
        table.lignes th, table.lignes td { border: 1px solid #999; padding: 6px 8px; text-align: left; }
        table.lignes th { background: #f5f0e8; font-size: 11px; }
        .total { text-align: right; font-size: 15px; font-weight: bold; margin-top: 12px; }
        .total-detail { text-align: right; font-size: 11px; color: #444; margin-top: 4px; }
        .pied { margin-top: 36px; font-size: 10px; color: #666; text-align: center; }
    </style>
</head>
<body>
    <h1>CHIC GASTRO</h1>
    <p class="sous-titre">Facture {{ $commande->facture->numero }}</p>

    <table class="meta">
        <tr>
            <td style="width:50%;">
                <strong>Table :</strong> {{ $commande->table->numero ?? '—' }}<br>
                <strong>Commande :</strong> {{ $commande->numero }}<br>
                <strong>Date :</strong> {{ $commande->facture->date->format('d/m/Y H:i') }}
            </td>
            <td style="width:50%; text-align:right;">
                <strong>Réglé par :</strong> {{ $commande->paiement->encaisseur->name ?? '—' }}<br>
                <strong>Mode :</strong> Espèces
            </td>
        </tr>
    </table>

    <table class="lignes">
        <thead>
            <tr><th>Plat</th><th style="width:45px;">Qté</th><th style="width:90px;">Prix unit.</th><th style="width:100px;">Total</th></tr>
        </thead>
        <tbody>
            @foreach($commande->lignes as $ligne)
                <tr>
                    <td>{{ $ligne->plat->nom ?? 'Plat supprimé' }}
                        @if($ligne->observations)<br><small>{{ $ligne->observations }}</small>@endif
                    </td>
                    <td>{{ $ligne->quantite }}</td>
                    <td>{{ number_format($ligne->prix_unitaire, 0, ',', ' ') }} FCFA</td>
                    <td>{{ number_format($ligne->sousTotal(), 0, ',', ' ') }} FCFA</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <p class="total">Total : {{ number_format($commande->facture->montant, 0, ',', ' ') }} FCFA</p>
    <p class="total-detail">Montant payé en espèces : {{ number_format($commande->paiement->montant ?? $commande->facture->montant, 0, ',', ' ') }} FCFA</p>

    <p class="pied">Merci de votre visite — Chic Gastro</p>
</body>
</html>
