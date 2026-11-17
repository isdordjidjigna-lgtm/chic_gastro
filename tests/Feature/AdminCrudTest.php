<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Plat;
use App\Models\RestaurantTable;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminCrudTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->create(['role' => User::ROLE_ADMIN]);
    }

    public function test_admin_peut_gerer_les_categories(): void
    {
        $admin = $this->admin();

        $this->actingAs($admin)->post(route('admin.categories.store'), [
            'nom' => 'Boissons', 'ordre' => 4,
        ])->assertRedirect(route('admin.categories.index'));

        $categorie = Category::where('nom', 'Boissons')->firstOrFail();
        $this->assertDatabaseHas('categories', ['nom' => 'Boissons']);

        $this->actingAs($admin)->put(route('admin.categories.update', $categorie), [
            'nom' => 'Boissons & Desserts', 'ordre' => 5,
        ])->assertRedirect();

        $this->actingAs($admin)->delete(route('admin.categories.destroy', $categorie))->assertRedirect();
        $this->assertDatabaseMissing('categories', ['id' => $categorie->id]);
    }

    public function test_admin_peut_creer_un_plat_avec_photo(): void
    {
        Storage::fake('public');
        $admin = $this->admin();
        $categorie = Category::create(['nom' => 'Entrées', 'ordre' => 0]);

        $this->actingAs($admin)->post(route('admin.plats.store'), [
            'categorie_id' => $categorie->id,
            'nom' => 'Salade-test',
            'description' => 'Test',
            'prix' => 3000,
            'photo' => UploadedFile::fake()->image('salade.jpg'),
            'disponible' => '1',
        ])->assertRedirect(route('admin.plats.index'));

        $plat = Plat::where('nom', 'Salade-test')->firstOrFail();
        $this->assertTrue((bool) $plat->disponible);
        Storage::disk('public')->assertExists($plat->photo);

        // Bascule de disponibilité.
        $this->actingAs($admin)->patch(route('admin.plats.disponible', $plat))->assertRedirect();
        $this->assertFalse((bool) $plat->fresh()->disponible);
    }

    public function test_admin_peut_gerer_les_tables_et_telecharger_le_qr(): void
    {
        $admin = $this->admin();

        $this->actingAs($admin)->post(route('admin.tables.store'), [
            'numero' => 'T99', 'actif' => '1',
        ])->assertRedirect(route('admin.tables.index'));

        $table = RestaurantTable::where('numero', 'T99')->firstOrFail();
        $this->assertNotEmpty($table->token);

        $reponse = $this->actingAs($admin)->get(route('admin.tables.qr.png', $table));
        $reponse->assertOk();
        $this->assertSame('image/png', $reponse->headers->get('Content-Type'));

        $this->actingAs($admin)->get(route('admin.tables.imprimer'))->assertOk();
    }

    public function test_admin_peut_gerer_les_utilisateurs(): void
    {
        $admin = $this->admin();

        $this->actingAs($admin)->post(route('admin.users.store'), [
            'name' => 'Nouveau Serveur',
            'email' => 'nv@chicgastro.com',
            'role' => User::ROLE_SERVEUR,
            'actif' => '1',
            'password' => 'motdepasse123',
        ])->assertRedirect(route('admin.users.index'));

        $user = User::where('email', 'nv@chicgastro.com')->firstOrFail();
        $this->assertSame(User::ROLE_SERVEUR, $user->role);
        $this->assertTrue($user->actif);

        // Un admin ne peut pas se supprimer lui-même.
        $this->actingAs($admin)->delete(route('admin.users.destroy', $admin))->assertRedirect();
        $this->assertDatabaseHas('users', ['id' => $admin->id]);
    }

    public function test_les_crud_sont_reserves_a_l_admin(): void
    {
        $serveur = User::factory()->create(['role' => User::ROLE_SERVEUR]);
        $categorie = Category::create(['nom' => 'Test', 'ordre' => 0]);

        $this->actingAs($serveur)->get(route('admin.plats.index'))->assertForbidden();
        $this->actingAs($serveur)->get(route('admin.categories.index'))->assertForbidden();
        $this->actingAs($serveur)->get(route('admin.tables.index'))->assertForbidden();
        $this->actingAs($serveur)->get(route('admin.users.index'))->assertForbidden();
        $this->actingAs($serveur)->get(route('admin.commandes.index'))->assertForbidden();
        $this->actingAs($serveur)->delete(route('admin.categories.destroy', $categorie))->assertForbidden();
    }
}
