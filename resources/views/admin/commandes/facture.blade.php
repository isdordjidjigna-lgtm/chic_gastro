<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Facture {{ $commande->facture->numero }}</title>
    <style>
        body { font-family: sans-serif; max-width: 700px; margin: 40px auto; color: #222; }
        h1 { text-align: center; letter-spacing: 2px; }
        .meta { display: flex; justify-content: space-between; margin: 20px 0; font-size: 14px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; font-size: 14px; }
        th { background: #f5f0e8; }
        .total { text-align: right; font-size: 18px; font-weight: bold; margin-top: 16px; }
        .pied { margin-top: 40px; font-size: 12px; color: #666; text-align: center; }
        @media print { .no-print { display: none; } }
    </style>
</head>
<body>
    <div class="no-print" style="margin-bottom:20px;">
        <button onclick="window.print()" style="padding:10px 20px;">Imprimer</button>
        <a href="{{ route('admin.commandes.show', $commande) }}" style="margin-left:10px;">← Retour</a>
    </div>

    <h1>CHIC GASTRO</h1>
    <p style="text-align:center; color:#666; margin-top:-10px;">Facture {{ $commande->facture->numero }}</p>

    <div class="meta">
        <div>
            <strong>Table :</strong> {{ $commande->table->numero ?? '—' }}<br>
            <strong>Commande :</strong> {{ $commande->numero }}<br>
            <strong>Date :</strong> {{ $commande->facture->date->format('d/m/Y H:i') }}
        </div>
        <div style="text-align:right;">
            <strong>Réglé par :</strong> {{ $commande->paiement->encaisseur->name ?? '—' }}<br>
            <strong>Mode :</strong> Espèces
        </div>
    </div>

    <table>
        <thead>
            <tr><th>Plat</th><th>Qté</th><th>Prix unit.</th><th>Total</th></tr>
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
    <p class="total" style="font-size:14px; font-weight:normal;">Montant payé en espèces : {{ number_format($commande->paiement->montant ?? $commande->facture->montant, 0, ',', ' ') }} FCFA</p>

    <p class="pied">Merci de votre visite — Chic Gastro</p>
</body>
</html>
