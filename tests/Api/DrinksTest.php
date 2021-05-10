<?php

namespace Test\Api;

use Test\TestCase;

class DrinksTest extends TestCase
{
    /** @test */
    public function fetch_all_drinks()
    {
        $this->json('GET', 'api/v1/drinks', ['Accept' => 'application/json'])
            ->seeStatusCode(200)
            ->seeJson([
                'message' => 'success',
                'data' => [],
            ]);
    }
}
