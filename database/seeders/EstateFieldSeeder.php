<?php

namespace Database\Seeders;

use App\Models\EstateField;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EstateFieldSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    protected $estate_fields = [
        [
            'id_slug' => 1,
            'title' => 'کد ملک',
            'estate_field_type_id' => 2,
            'want_estate_field_type_id' => 2,
        ],
        [
            'id_slug' => 2,
            'title' => 'متراژ',
            'estate_field_type_id' => 2,
            'want_estate_field_type_id' => 2,
        ],
        [
            'id_slug' => 3,
            'title' => 'متراژ زمین',
            'estate_field_type_id' => 2,
            'want_estate_field_type_id' => 2,
        ],
        [
            'id_slug' => 4,
            'title' => 'متراژ ساخت',
            'estate_field_type_id' => 2,
            'want_estate_field_type_id' => 2,
        ],
        [
            'id_slug' => 5,
            'title' => 'وضعیت سند',
            'estate_field_type_id' => 3,
            'want_estate_field_type_id' => 4,
        ],
        [
            'id_slug' => 6,
            'title' => 'پروانه',
            'estate_field_type_id' => 3,
            'want_estate_field_type_id' => 4,
        ],
        [
            'id_slug' => 7,
            'title' => 'نورگیری',
            'estate_field_type_id' => 4,
            'want_estate_field_type_id' => 4,
        ],
        [
            'id_slug' => 8,
            'title' => 'موقعیت زمین',
            'estate_field_type_id' => 4,
            'want_estate_field_type_id' => 4,
        ],
        [
            'id_slug' => 9,
            'title' => 'کد نوسازی',
            'estate_field_type_id' => 2,
            'want_estate_field_type_id' => 2,
        ],
        [
            'id_slug' => 10,
            'title' => 'درصد ساخت',
            'estate_field_type_id' => 2,
            'want_estate_field_type_id' => 2,
        ],
        [
            'id_slug' => 11,
            'title' => 'سن ساخت',
            'estate_field_type_id' => 2,
            'want_estate_field_type_id' => 2,
        ],
        [
            'id_slug' => 12,
            'title' => 'تعداد طبقه',
            'estate_field_type_id' => 2,
            'want_estate_field_type_id' => 2,
        ],
        [
            'id_slug' => 13,
            'title' => 'تعداد واحد',
            'estate_field_type_id' => 2,
            'want_estate_field_type_id' => 2,
        ],
        [
            'id_slug' => 14,
            'title' => 'تعداد واحد در طبقه',
            'estate_field_type_id' => 2,
            'want_estate_field_type_id' => 2,
        ],
        [
            'id_slug' => 15,
            'title' => 'تعداد خواب',
            'estate_field_type_id' => 2,
            'want_estate_field_type_id' => 2,
        ],
        [
            'id_slug' => 16,
            'title' => 'عرض پلاک',
            'estate_field_type_id' => 2,
            'want_estate_field_type_id' => 2,
        ],
        [
            'id_slug' => 17,
            'title' => 'عرض گذر',
            'estate_field_type_id' => 2,
            'want_estate_field_type_id' => 2,
        ],
        [
            'id_slug' => 18,
            'title' => 'تعداد ساخت روی پیلوت',
            'estate_field_type_id' => 2,
            'want_estate_field_type_id' => 2,
        ],
        [
            'id_slug' => 19,
            'title' => 'ارتفاع سقف',
            'estate_field_type_id' => 2,
            'want_estate_field_type_id' => 2,
        ],
        [
            'id_slug' => 20,
            'title' => 'مالکیت زمین',
            'estate_field_type_id' => 3,
            'want_estate_field_type_id' => 4,
        ],
        [
            'id_slug' => 21,
            'title' => 'کاربری',
            'estate_field_type_id' => 4,
            'want_estate_field_type_id' => 4,
        ],
        [
            'id_slug' => 22,
            'title' => 'وضعیت آب باغی',
            'estate_field_type_id' => 1,
            'want_estate_field_type_id' => 1,
        ],
        [
            'id_slug' => 23,
            'title' => 'سن درختان',
            'estate_field_type_id' => 1,
            'want_estate_field_type_id' => 1,
        ],
        [
            'id_slug' => 24,
            'title' => 'انشعابات',
            'estate_field_type_id' => 4,
            'want_estate_field_type_id' => 4,
        ],
        [
            'id_slug' => 25,
            'title' => 'امکانات',
            'estate_field_type_id' => 4,
            'want_estate_field_type_id' => 4,
        ],
        [
            'id_slug' => 26,
            'title' => 'ویژگی های خاص',
            'estate_field_type_id' => 4,
            'want_estate_field_type_id' => 4,
        ],
        [
            'id_slug' => 27,
            'title' => 'توضیحات',
            'estate_field_type_id' => 7,
            'want_estate_field_type_id' => 7,
        ],
    ];
    public function run(): void
    {
        foreach ($this->estate_fields as $item){
            EstateField::factory()->create([
                'title' => $item['title'],
                'estate_field_type_id' => $item['estate_field_type_id'],
                'want_estate_field_type_id' => $item['want_estate_field_type_id'],
            ]);
        }
    }
}
