<?php

namespace Test\Unit;

use App\Models\Drink;
use Database\Seeders\DatabaseSeeder;
use Database\Seeders\DrinksSeeder;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Test\TestCase;
use App\Services\DrinksService;
use App\Services\DrinksSafeLimitCheckService;

class DrinksSafeLimitCheckServiceTest extends TestCase
{
    use DatabaseMigrations;

    public DrinksService $drinksRepository;

    public DrinksSafeLimitCheckService $service;

    public function setUp(): void
    {
        parent::setUp();

        (new DatabaseSeeder())->call(DrinksSeeder::class);

        $this->drinksRepository = $this->getMockBuilder(DrinksService::class)->getMock();

        $this->service = new DrinksSafeLimitCheckService($this->drinksRepository);
    }

    /** @test */
    public function consumption_response_when_limit_exceed_safe_limit()
    {
        $allDrinks = Drink::all();
        $selectedDrink = $allDrinks->where('safe_level', 100)->first();

        $this->drinksRepository->expects($this->once())->method('getADrink')->willReturn($selectedDrink);
        $this->drinksRepository->expects($this->once())->method('getAllDrinks')->willReturn($allDrinks);

        $response = $this->service->index(10, intval($selectedDrink->id));

        $this->assertEquals([], $response);
    }
}
