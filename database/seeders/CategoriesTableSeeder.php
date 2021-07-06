<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategoriesTableSeeder extends Seeder
{
    protected $categories = [
        [
            'name' => 'Заработная плата',
            'type' => '0'
        ],
        [
            'name' => 'Иные доходы',
            'type' => '0'
        ],
        [
            'name' => 'Продукты питания',
            'type' => '1'
        ],
        [
            'name' => 'Транспорт',
            'type' => '1'
        ],
        [
            'name' => 'Мобильная связь',
            'type' => '1'
        ],
        [
            'name' => 'Интернет',
            'type' => '1'
        ],
        [
            'name' => 'Развлечения',
            'type' => '1'
        ],
        [
            'name' => 'Другое',
            'type' => '1'
        ],
    ];
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->categories as $category) {
            Category::create($category);
        }
    }
}
