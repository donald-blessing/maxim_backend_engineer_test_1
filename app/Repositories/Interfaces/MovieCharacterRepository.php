<?php

namespace App\Repositories\Interfaces;

use App\Http\Requests\MovieCharacterCreateRequest;
use App\Http\Requests\MovieCharacterUpdateRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Prettus\Repository\Contracts\RepositoryInterface;
use Prettus\Repository\Exceptions\RepositoryException;

/**
 * Interface MovieCharacterRepository.
 *
 * @package namespace App\Repositories\Interfaces;
 */
interface MovieCharacterRepository extends RepositoryInterface
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @param  $movie
     * @return JsonResponse
     * @throws RepositoryException
     */
    public function index(Request $request, $movie = null): JsonResponse;

    /**
     * Store a newly created resource in storage.
     *
     * @param MovieCharacterCreateRequest $request
     *
     * @return JsonResponse
     *
     */
    public function store(MovieCharacterCreateRequest $request): JsonResponse;

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
     * @param MovieCharacterUpdateRequest $request
     * @param int $id
     *
     * @return JsonResponse
     *
     */
    public function updateMovieCharacter(MovieCharacterUpdateRequest $request, int $id): JsonResponse;


    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse;
}
