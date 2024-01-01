<?php

namespace Database\Seeders;

use App\Models\EstateFieldItem;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EstateFieldItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    protected $estate_field_items = [
        [
            'estate_field_id' => 5,
            'title' => 'شش دانگ',
        ],
        [
            'estate_field_id' => 5,
            'title' => 'در دست اقدام',
        ],
        [
            'estate_field_id' => 5,
            'title' => 'قولنامه ای',
        ],
        [
            'estate_field_id' => 5,
            'title' => 'مادر',
        ],
        [
            'estate_field_id' => 5,
            'title' => 'وکالتی',
        ],
        [
            'estate_field_id' => 5,
            'title' => 'سرقفلی',
        ],
        [
            'estate_field_id' => 5,
            'title' => 'تعاونی',
        ],
        [
            'estate_field_id' => 5,
            'title' => 'منگوله دار',
        ],
        [
            'estate_field_id' => 5,
            'title' => 'بنجاق',
        ],
        [
            'estate_field_id' => 5,
            'title' => 'معاوض شهرداری',
        ],
        [
            'estate_field_id' => 5,
            'title' => 'اوقافی',
        ],
        [
            'estate_field_id' => 5,
            'title' => 'مشاع',
        ],
        [
            'estate_field_id' => 6,
            'title' => 'دارد',
        ],
        [
            'estate_field_id' => 6,
            'title' => 'ندارد',
        ],
        [
            'estate_field_id' => 6,
            'title' => 'دارد باطل شده',
        ],
        [
            'estate_field_id' => 6,
            'title' => 'دستور تهیه نقشه',
        ],
        [
            'estate_field_id' => 7,
            'title' => 'شمالی',
        ],
        [
            'estate_field_id' => 7,
            'title' => 'جنوبی',
        ],
        [
            'estate_field_id' => 7,
            'title' => 'شرقی',
        ],
        [
            'estate_field_id' => 7,
            'title' => 'غربی',
        ],
        [
            'estate_field_id' => 7,
            'title' => 'داکت',
        ],
        [
            'estate_field_id' => 8,
            'title' => 'شمالی',
        ],
        [
            'estate_field_id' => 8,
            'title' => 'جنوبی',
        ],
        [
            'estate_field_id' => 8,
            'title' => 'شرقی',
        ],
        [
            'estate_field_id' => 8,
            'title' => 'غربی',
        ],
        [
            'estate_field_id' => 20,
            'title' => 'دارد',
        ],
        [
            'estate_field_id' => 20,
            'title' => 'ندارد',
        ],
        [
            'estate_field_id' => 21,
            'title' => 'مسکونی',
        ],
        [
            'estate_field_id' => 21,
            'title' => 'تجاری',
        ],
        [
            'estate_field_id' => 21,
            'title' => 'اداری',
        ],
        [
            'estate_field_id' => 24,
            'title' => 'آب',
        ],
        [
            'estate_field_id' => 24,
            'title' => 'برق',
        ],
        [
            'estate_field_id' => 24,
            'title' => 'گاز',
        ],
        [
            'estate_field_id' => 24,
            'title' => 'تلفن',
        ],
        [
            'estate_field_id' => 25,
            'title' => 'آسانسور',
        ],
        [
            'estate_field_id' => 25,
            'title' => 'پارکینگ',
        ],
        [
            'estate_field_id' => 25,
            'title' => 'انباری',
        ],
        [
            'estate_field_id' => 26,
            'title' => 'هوشمند - لاکچری',
        ],
    ];
    public function run(): void
    {
        foreach ($this->estate_field_items as $item){
            EstateFieldItem::factory()->create([
                'estate_field_id' => $item['estate_field_id'],
                'title' => $item['title'],
            ]);
        }
    }
}
