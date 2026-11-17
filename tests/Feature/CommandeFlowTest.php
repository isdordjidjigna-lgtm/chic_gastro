<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Commande;
use App\Models\Plat;
use App\Models\RestaurantTable;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommandeFlowTest extends TestCase
{
    use RefreshDatabase;

    private function contexte(): array
    {
        $categorie = Category::create(['nom' => 'Entrées', 'ordre' => 0]);
        $plat = Plat::create([
            'categorie_id' => $categorie->id,
            'nom' => 'Salade',
            'prix' => 4000,
            'disponible' => true,
        ]);
        $table = RestaurantTable::create(['numero' => 'T01', 'actif' => true]);

        return [$categorie, $plat, $table];
    }

    public function test_page_menu_affichee_pour_table_active(): void
    {
        [, , $table] = $this->contexte();

        $this->get(route('menu.show', $table->token))->assertOk();
    }

    public function test_page_menu_indisponible_pour_table_desactivee(): void
    {
        [, , $table] = $this->contexte();
        $table->update(['actif' => false]);

        $this->get(route('menu.show', $table->token))->assertOk()
            ->assertSee('indisponible');
    }

    public function test_commande_creee_avec_total_recalcule_cote_serveur(): void
    {
        [, $plat, $table] = $this->contexte();

        $reponse = $this->postJson(route('menu.commandes.store', $table->token), [
            'items' => [
                ['plat_id' => $plat->id, 'quantite' => 2, 'observations' => 'sans piment'],
            ],
        ]);

        $reponse->assertCreated()->assertJsonStructure(['numero', 'suivi_url']);

        $commande = Commande::firstOrFail();
        $this->assertSame(Commande::EN_ATTENTE, $commande->statut);
        $this->assertEquals(8000, (float) $commande->total);
        $this->assertCount(1, $commande->lignes);
        $this->assertSame('sans piment', $commande->lignes->first()->observations);
    }

    public function test_commande_refusee_si_plat_indisponible(): void
    {
        [, $plat, $table] = $this->contexte();
        $plat->update(['disponible' => false]);

        $this->postJson(route('menu.commandes.store', $table->token), [
            'items' => [['plat_id' => $plat->id, 'quantite' => 1]],
        ])->assertStatus(422);

        $this->assertSame(0, Commande::count());
    }

    public function test_commande_refusee_pour_table_desactivee(): void
    {
        [, $plat, $table] = $this->contexte();
        $table->update(['actif' => false]);

        $this->postJson(route('menu.commandes.store', $table->token), [
            'items' => [['plat_id' => $plat->id, 'quantite' => 1]],
        ])->assertForbidden();
    }

    public function test_flux_cuisine_puis_serveur(): void
    {
        [, $plat, $table] = $this->contexte();
        $cuisinier = User::factory()->create(['role' => User::ROLE_CUISINIER]);
        $serveur = User::factory()->create(['role' => User::ROLE_SERVEUR]);

        $this->postJson(route('menu.commandes.store', $table->token), [
            'items' => [['plat_id' => $plat->id, 'quantite' => 1]],
        ])->assertCreated();

        $commande = Commande::firstOrFail();

        // Les dashboards se rendent (vue + partiel de polling).
        $this->actingAs($cuisinier)->get(route('cuisine.dashboard'))->assertOk();
        $this->actingAs($cuisinier)->get(route('cuisine.commandes.liste'))->assertOk();
        $this->actingAs($serveur)->get(route('serveur.dashboard'))->assertOk();
        $this->actingAs($serveur)->get(route('serveur.commandes.liste'))->assertOk();

        // Le cuisinier accepte puis signale prête.
        $this->actingAs($cuisinier)->post(route('cuisine.commandes.accepter', $commande))
            ->assertRedirect();
        $this->assertSame(Commande::EN_PREPARATION, $commande->fresh()->statut);

        $this->actingAs($cuisinier)->post(route('cuisine.commandes.prete', $commande))
            ->assertRedirect();
        $this->assertSame(Commande::PRETE, $commande->fresh()->statut);

        // Le serveur marque servie.
        $this->actingAs($serveur)->post(route('serveur.commandes.servie', $commande))
            ->assertRedirect();
        $this->assertSame(Commande::SERVIE, $commande->fresh()->statut);
    }

    public function test_acces_refuse_selon_le_role(): void
    {
        $cuisinier = User::factory()->create(['role' => User::ROLE_CUISINIER]);
        $serveur = User::factory()->create(['role' => User::ROLE_SERVEUR]);

        $this->actingAs($cuisinier)->get(route('serveur.dashboard'))->assertForbidden();
        $this->actingAs($serveur)->get(route('cuisine.dashboard'))->assertForbidden();
        $this->actingAs($serveur)->get(route('admin.dashboard'))->assertForbidden();
    }
}
