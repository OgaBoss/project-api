<?php

namespace App\Services;

use App\Models\Drink;
use Illuminate\Database\Eloquent\Collection;

class DrinksService
{
    public function getAllDrinks(): Collection
    {
        return Drink::all();
    }

    public function getADrink(string $drinkId): Drink
    {
        return Drink::find($drinkId);
    }
}
