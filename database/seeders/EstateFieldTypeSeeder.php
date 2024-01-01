<?php

namespace Database\Seeders;

use App\Models\EstateFieldType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EstateFieldTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    private $estate_field_types = [
        'Text',
        'Number',
        'Select box',
        'Multiple Select',
        'Radio button',
        'Checkbox',
        'Textarea',
    ];
    public function run(): void
    {
        foreach ($this->estate_field_types as $item){
            EstateFieldType::factory()->create([
                'title' => $item,
            ]);
        }
    }
}
