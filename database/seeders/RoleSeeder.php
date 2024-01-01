<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    private array $roles = [
        'برنامه نویس',
        'مدیر سایت',
        'مشاور',
        'مشتری',
    ];
    public function run(): void
    {
        foreach ($this->roles as $item){
            Role::factory()->create([
                'title' => $item,
            ]);
        }
    }
}
