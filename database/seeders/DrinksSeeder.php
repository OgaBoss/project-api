<?php

namespace Database\Seeders;

use App\Models\Drink;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DrinksSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Drink::insert([
            [
                'id' => Str::uuid()->toString(),
                'name' => 'Monster Ultra Sunrise',
                'safe_level' => 75,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => Str::uuid()->toString(),
                'name' => 'Black Coffee',
                'safe_level' => 95,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => Str::uuid()->toString(),
                'name' => 'Americano',
                'safe_level' => 77,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => Str::uuid()->toString(),
                'name' => 'Sugar free NOS',
                'safe_level' => 130,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => Str::uuid()->toString(),
                'name' => '5 Hour Energy',
                'safe_level' => 100,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ]);
    }
}
