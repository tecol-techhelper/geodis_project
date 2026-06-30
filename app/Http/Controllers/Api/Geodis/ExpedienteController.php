<?php

namespace App\Http\Controllers\Api\Geodis;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Geodis\ExpedienteIndexRequest;
use Illuminate\Http\JsonResponse;

class ExpedienteController extends Controller
{
    public function index(ExpedienteIndexRequest $request): JsonResponse
    {
        $filters = $request->normalizedFilters();

        return response()->json([
            'data' => [],
            'meta' => [
                'current_page' => $filters['page'],
                'per_page' => $filters['per_page'],
                'total' => 0,
                'last_page' => 1,
            ],
        ]);
    }

    public function ping(): JsonResponse
    {
        return response()->json([
            'data' => [
                'status' => 'ok',
                'service' => 'geodis-api',
            ],
        ]);
    }
}
