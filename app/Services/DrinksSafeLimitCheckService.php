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

    /**
     * @param int $quantity
     * @param string $drinkId
     * @return array
     */
    public function index(int $quantity, string $drinkId): array
    {
        // Get the selected Drink
        $this->setSelectedDrink($drinkId);
        $this->setUnSelectedDrinks();

        // Get the consumed limit
        $this->remainderSafeLimit = self::SAFE_LIMIT - ($quantity * $this->selectedDrink->safe_level);

        // if remainder limit is 0
        // User cannot consume any drink
        if ($this->remainderSafeLimit < 0) {
            return [];
        }

        return [
            'option1' => $this->selectedDrinkPossibleConsumptionLimit(),
            'option2' => $this->otherDrinksPossibleConsumptionLimit(),
            'option3' => $this->otherDrinksCombinedConsumptionLimit()
        ];
    }

    /**
     * Return how many servings of the selected drink
     * the user can consume
     * @return array[]
     */
    protected function selectedDrinkPossibleConsumptionLimit(): array
    {
        $remainder = intdiv($this->remainderSafeLimit, $this->selectedDrink->safe_level);
        if ($remainder) {
            return [
                'quantity' => $remainder,
                'drink' => $this->selectedDrink->name,
                'caffeine_level' => $this->selectedDrink->safe_level
            ];
        }

        return [];
    }

    /**
     * Return how many servings of each remaining drink
     * a user can consume separately
     *
     * @return array
     */
    protected function otherDrinksPossibleConsumptionLimit(): array
    {
        return $this->otherDrinks->map(function($drink){
            $remainder = intdiv($this->remainderSafeLimit, $drink->safe_level);
            if ($remainder > 0) {
                return [
                    'quantity' => intdiv($this->remainderSafeLimit, $drink->safe_level),
                    'drink' => $drink->name,
                    'caffeine_level' => $drink->safe_level
                ];
            }
        })->filter()->toArray();
    }

    /**
     * Return how many servings of each remaining drink
     * a user can consume combined
     *
     * @return array
     */
    protected function otherDrinksCombinedConsumptionLimit(): array
    {
        return $this->otherDrinks->map(function($drink){
            if (intdiv($this->remainderSafeLimit, $drink->safe_level) > 0) {
                $this->remainderSafeLimit = $this->remainderSafeLimit - $drink->safe_level;

                return [
                    'quantity' => 1,
                    'drink' => $drink->name,
                    'caffeine_level' => $drink->safe_level
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

    protected function setSelectedDrink(string $drinkId): void
    {
        $this->selectedDrink = $this->drinksRepository->getADrink($drinkId);
    }
}
