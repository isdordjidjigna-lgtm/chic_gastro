<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomeTest extends TestCase
{
    use RefreshDatabase;

    public function test_page_d_accueil_affichee_aux_visiteurs(): void
    {
        $this->get(route('home'))
            ->assertOk()
            ->assertSee('Chic Gastro')
            ->assertSee('QR Code')
            ->assertSee('Espace personnel');
    }

    public function test_visiteur_connecte_redirige_vers_son_espace(): void
    {
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
        $serveur = User::factory()->create(['role' => User::ROLE_SERVEUR]);

        $this->actingAs($admin)->get(route('home'))->assertRedirect(route('admin.dashboard'));
        $this->actingAs($serveur)->get(route('home'))->assertRedirect(route('serveur.dashboard'));
    }
}
