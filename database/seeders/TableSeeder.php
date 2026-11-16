<?php

namespace Database\Seeders;

use App\Models\RestaurantTable;
use Illuminate\Database\Seeder;

class TableSeeder extends Seeder
{
    public function run(): void
    {
        for ($i = 1; $i <= 12; $i++) {
            RestaurantTable::updateOrCreate(
                ['numero' => 'T'.str_pad((string) $i, 2, '0', STR_PAD_LEFT)],
                ['actif' => true]
            );
        }
    }
}
