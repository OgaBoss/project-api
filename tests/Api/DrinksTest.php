<?php

namespace Test\Api;

use Database\Seeders\DatabaseSeeder;
use Database\Seeders\DrinksSeeder;
use Test\TestCase;
use App\Models\Drink;
use Laravel\Lumen\Testing\DatabaseMigrations;

class DrinksTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function fetch_all_drinks()
    {
        $drink = Drink::factory()->count(5)->create();
        $this->json('GET', 'v1/drinks', ['Accept' => 'application/json'])
            ->seeStatusCode(200)
            ->seeJsonEquals([
                'status' => 'success',
                'data' => $drink,
            ]);
    }

    /** @test */
    public function drink_limit_possible_response_with_high_limit()
    {
        (new DatabaseSeeder())->call(DrinksSeeder::class);
        $selectedDrink = Drink::where('safe_level', 75)->first();

        $this->json('GET', "v1/drinks/{$selectedDrink->id}/safe_limit_check?quantity=10", ['Accept' => 'application/json'])
            ->seeStatusCode(200)
            ->seeJsonEquals([
                'status' => 'success',
                'data' => [],
            ]);
    }
}
