<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Translation;

class TranslationSeeder extends Seeder
{
    public function run(): void
    {
        Translation::factory()->count(100000)->create();
    }
}