<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Commande;
use App\Models\Facture;
use App\Models\Paiement;
use App\Models\Plat;
use App\Models\RestaurantTable;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaiementStatsTest extends TestCase
{
    use RefreshDatabase;

    private function commandeServie(): array
    {
        $categorie = Category::create(['nom' => 'Plats', 'ordre' => 0]);
        $plat = Plat::create([
            'categorie_id' => $categorie->id,
            'nom' => 'Poulet braisé',
            'prix' => 6000,
            'disponible' => true,
        ]);
        $table = RestaurantTable::create(['numero' => 'T02', 'actif' => true]);
        $serveur = User::factory()->create(['role' => User::ROLE_SERVEUR]);
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);

        $this->postJson(route('menu.commandes.store', $table->token), [
            'items' => [['plat_id' => $plat->id, 'quantite' => 2]],
        ])->assertCreated();

        $commande = Commande::firstOrFail();
        $commande->update(['statut' => Commande::SERVIE]);

        return [$commande, $plat, $serveur, $admin];
    }

    public function test_encaissement_cree_paiement_facture_et_termine_la_commande(): void
    {
        [$commande, , $serveur] = $this->commandeServie();

        $this->actingAs($serveur)
            ->post(route('serveur.commandes.payer', $commande))
            ->assertRedirect(route('serveur.dashboard'));

        $commande->refresh();
        $this->assertSame(Commande::TERMINEE, $commande->statut);

        $paiement = $commande->paiement;
        $this->assertNotNull($paiement);
        $this->assertSame(Paiement::MODE_ESPECES, $paiement->mode);
        $this->assertEquals(12000, (float) $paiement->montant);
        $this->assertSame($serveur->id, $paiement->encaisse_par);

        $facture = $commande->facture;
        $this->assertNotNull($facture);
        $this->assertSame('FAC-'.now()->format('Ymd').'-0001', $facture->numero);
        $this->assertEquals(12000, (float) $facture->montant);
    }

    public function test_encaissement_refuse_si_commande_non_servie(): void
    {
        [$commande, , $serveur] = $this->commandeServie();
        $commande->update(['statut' => Commande::PRETE]);

        $this->actingAs($serveur)
            ->post(route('serveur.commandes.payer', $commande))
            ->assertStatus(422);

        $this->assertSame(0, Paiement::count());
        $this->assertSame(0, Facture::count());
    }

    public function test_encaissement_double_refuse(): void
    {
        [$commande, , $serveur] = $this->commandeServie();

        $this->actingAs($serveur)->post(route('serveur.commandes.payer', $commande));
        $this->actingAs($serveur)->post(route('serveur.commandes.payer', $commande))
            ->assertStatus(422);

        $this->assertSame(1, Paiement::count());
    }

    public function test_facture_imprimable_visible_par_admin(): void
    {
        [$commande, , $serveur, $admin] = $this->commandeServie();
        $this->actingAs($serveur)->post(route('serveur.commandes.payer', $commande));

        $this->actingAs($admin)
            ->get(route('admin.commandes.facture', $commande))
            ->assertOk()
            ->assertSee($commande->facture->numero)
            ->assertSee('Chic Gastro');
    }

    public function test_stats_donnees_json_et_acces_admin_seul(): void
    {
        [$commande, $plat, $serveur, $admin] = $this->commandeServie();
        $this->actingAs($serveur)->post(route('serveur.commandes.payer', $commande));

        // Le serveur ne peut pas voir les stats.
        $this->actingAs($serveur)->get(route('admin.stats.donnees'))->assertForbidden();

        $reponse = $this->actingAs($admin)->getJson(route('admin.stats.donnees'));
        $reponse->assertOk()->assertJsonStructure([
            'labels', 'ca', 'commandes', 'top_plats', 'plats_peu',
            'labels_mois', 'ca_mois',
            'ca_total', 'nb_commandes', 'nb_en_attente',
        ]);

        $data = $reponse->json();
        $this->assertSame('12 000', $data['ca_total']);
        $this->assertSame(1, $data['nb_commandes']);
        $this->assertSame('Poulet braisé', $data['top_plats'][0]['nom']);
        $this->assertSame(2, $data['top_plats'][0]['qte']);
        $this->assertCount(12, $data['labels_mois']);
        $this->assertCount(12, $data['ca_mois']);
    }

    public function test_page_stats_affichee(): void
    {
        [, , , $admin] = $this->commandeServie();
        $this->actingAs($admin)->get(route('admin.stats'))->assertOk();
    }
}
