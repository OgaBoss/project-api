<?php

namespace Test\Api;

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
        $this->json('GET', 'api/v1/drinks', ['Accept' => 'application/json'])
            ->seeStatusCode(200)
            ->seeJsonEquals([
                'status' => 'success',
                'data' => $drink,
            ]);
    }

    /** @test */
    public function drink_limit_possible_response()
    {

    }
}
