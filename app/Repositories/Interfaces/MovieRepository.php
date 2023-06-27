<?php

namespace App\Repositories\Interfaces;

use App\Http\Requests\MovieCreateRequest;
use App\Http\Requests\MovieUpdateRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Prettus\Repository\Contracts\RepositoryInterface;
use Prettus\Repository\Exceptions\RepositoryException;

/**
 * Interface MovieRepository.
 *
 * @package namespace App\Repositories\Interfaces;
 */
interface MovieRepository extends RepositoryInterface
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return JsonResponse
     * @throws RepositoryException
     */
    public function index(Request $request): JsonResponse;

    /**
     * Store a newly created resource in storage.
     *
     * @param MovieCreateRequest $request
     *
     * @return JsonResponse
     *
     */
    public function store(MovieCreateRequest $request): JsonResponse;

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse;

    /**
     * Update the specified resource in storage.
     *
     * @param MovieUpdateRequest $request
     * @param int $id
     *
     * @return JsonResponse
     *
     */
    public function updateMovie(MovieUpdateRequest $request, int $id): JsonResponse;


    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse;
}
