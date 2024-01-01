<?php

namespace Database\Seeders;

use App\Models\EstateType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EstateTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    private $estate_type = [
        'آپارتمان',
        'ویلایی و آپارتمان یکجا',
        'زمین و کلنگی',
        'اداری',
        'تجاری',
        'باغ و زمین کشاورزی',
        'کارگاه و کارخانه',
        'مصالح',
        'خودرو',
    ];
    public function run(): void
    {
        foreach ($this->estate_type as $item){
            EstateType::factory()->create([
                'title' => $item,
            ]);
        }
    }
}
