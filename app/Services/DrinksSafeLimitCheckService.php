<?php

namespace App\Services;

use App\Models\Drink;
use Illuminate\Database\Eloquent\Collection;

class DrinksSafeLimitCheckService
{
    public DrinksService $drinksRepository;

    public Drink $selectedDrink;

    public Collection $otherDrinks;

    public int $remainderSafeLimit;

    const SAFE_LIMIT = 500;

    /**
     * DrinksSafeLelCheckService constructor.
     * @param DrinksService $drinksRepository
     */
    public function __construct(DrinksService $drinksRepository)
    {
        $this->drinksRepository = $drinksRepository;
    }

    public function index(int $quantity, int $drinkId): array
    {
        // Get the selected Drink
        $this->setSelectedDrink($drinkId);
        $this->setUnSelectedDrinks();

        // Get the consumed limit
        $this->remainderSafeLimit = self::SAFE_LIMIT - ($quantity * $this->selectedDrink->safe_level);
        if ($this->remainderSafeLimit < 0) {
            return [];
        }

        return [
            'option1' => $this->selectedDrinkPossibleConsumptionLimit(),
            'option2' => $this->otherDrinksPossibleConsumptionLimit(),
            'option3' => $this->otherDrinksCombinedConsumptionLimit()
        ];
    }

    protected function selectedDrinkPossibleConsumptionLimit(): array
    {
        return [
            'quantity' => intdiv($this->remainderSafeLimit, $this->selectedDrink->safe_level),
            'drink' => $this->selectedDrink->name,
        ];
    }

    protected function otherDrinksPossibleConsumptionLimit(): array
    {
        return $this->otherDrinks->map(function($drink){
            return [
                'quantity' => intdiv($this->remainderSafeLimit, $drink->safe_level),
                'drink' => $drink->name,
            ];
        })->toArray();
    }

    protected function otherDrinksCombinedConsumptionLimit(): array
    {
        return $this->otherDrinks->map(function($drink){
            $this->remainderSafeLimit = $this->remainderSafeLimit - $drink->safe_level;

            if ($this->remainderSafeLimit % $drink->safe_level > 0) {

                return [
                    'quantity' => 1,
                    'drink' => $drink->name,
                ];
            }

        })->filter()->toArray();
    }

    protected function setUnSelectedDrinks(): void
    {
        $this->otherDrinks = $this->drinksRepository->getAllDrinks()->filter(function($drink){
            return $drink->id !== $this->selectedDrink->id;
        });
    }

    protected function setSelectedDrink(int $drinkId): void
    {
        $this->selectedDrink = $this->drinksRepository->getADrink($drinkId);
    }
}
