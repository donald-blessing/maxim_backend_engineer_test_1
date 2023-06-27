<?php

namespace App\Repositories\Interfaces;

use App\Http\Requests\CommentCreateRequest;
use App\Http\Requests\CommentUpdateRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Prettus\Repository\Contracts\RepositoryInterface;
use Prettus\Repository\Exceptions\RepositoryException;

/**
 * Interface CommentRepository.
 *
 * @package namespace App\Repositories\Interfaces;
 */
interface CommentRepository extends RepositoryInterface
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @param int $movie
     * @return JsonResponse
     * @throws RepositoryException
     */
    public function index(Request $request, int $movie): JsonResponse;

    /**
     * Store a newly created resource in storage.
     *
     * @param CommentCreateRequest $request
     *
     * @return JsonResponse
     *
     */
    public function store(CommentCreateRequest $request): JsonResponse;

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
     * @param CommentUpdateRequest $request
     * @param int $id
     *
     * @return JsonResponse
     *
     */
    public function updateComment(CommentUpdateRequest $request, int $id): JsonResponse;


    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse;
}
