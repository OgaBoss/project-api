<?php


namespace App\Http\Controllers;


use App\Services\DrinksService;
use Illuminate\Http\JsonResponse;

class DrinksController extends Controller
{
    protected DrinksService $drinksRepository;

    /**
     * DrinksController constructor.
     * @param DrinksService $drinksRepository
     */
    public function __construct(DrinksService $drinksRepository)
    {
        $this->drinksRepository = $drinksRepository;
    }

    public function index(): JsonResponse
    {
        try {
            return response()->json([
                'data' => $this->drinksRepository->getAllDrinks(),
                'status' => 'success'
            ], 200);
        } catch (\Throwable $exception) {
            return response()->json([
                'status' => 'error',
                'data' => [],
                'message' => $exception->getMessage()
            ], 500);
        }
    }
}
