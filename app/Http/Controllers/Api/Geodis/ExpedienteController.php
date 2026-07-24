<?php

namespace App\Http\Controllers\Api\Geodis;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Geodis\ExpedienteIndexRequest;
use App\Http\Resources\Api\Geodis\ExpedienteResource;
use App\Services\Geodis\ExpedienteQueryService;
use Illuminate\Http\JsonResponse;

class ExpedienteController extends Controller
{
    public function index(
        ExpedienteIndexRequest $request,
        ExpedienteQueryService $queryService,
    ): JsonResponse {
        $paginator = $queryService->paginate($request->normalizedFilters());

        return response()->json([
            'data' => ExpedienteResource::collection($paginator->getCollection())->resolve($request),
            'meta' => [
                'current_page' => $paginator->currentPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
                'last_page' => $paginator->lastPage(),
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
