<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Http\Resources\EstateTypeResource;
use App\Models\EstateField;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
            EstateFieldTypeSeeder::class,
            EstateFieldSeeder::class,
            EstateFieldItemSeeder::class,
            EstateTypeSeeder::class,
        ]);
    }
}
