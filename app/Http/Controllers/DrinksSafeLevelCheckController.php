<?php

namespace App\Http\Controllers;

use App\Exceptions\MissingQuantityException;
use App\Services\DrinksSafeLimitCheckService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DrinksSafeLevelCheckController extends Controller
{
    public DrinksSafeLimitCheckService $service;

    /**
     * DrinksSafeLevelCheckController constructor.
     * @param DrinksSafeLimitCheckService $service
     */
    public function __construct(DrinksSafeLimitCheckService $service)
    {
        $this->service = $service;
    }

    /**
     * @param Request $request
     * @param string $drinkId
     * @return JsonResponse
     */
    public function index(Request $request, string $drinkId): JsonResponse
    {
        try {
            if (!$request->query('quantity')) {
                throw new MissingQuantityException('Make sure quantity to be consumed is provided');
            }

            return response()->json([
                'data' => $this->service->index($request->query('quantity'), $drinkId),
                'status' => 'success'
            ], 200);
        } catch (MissingQuantityException $exception) {
            return response()->json([
                'status' => 'error',
                'data' => [],
                'message' => $exception->getMessage()
            ], 500);
        } catch (\Throwable $exception) {
            return response()->json([
                'status' => 'error',
                'data' => [],
                'message' => 'Oops, something went wrong, please try again.'
            ], 500);
        }
    }
}
