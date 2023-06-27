<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CommentCreateRequest;
use App\Http\Requests\CommentUpdateRequest;
use App\Repositories\Interfaces\CommentRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Prettus\Repository\Exceptions\RepositoryException;

/**
 * Class CommentsController.
 *
 * @package namespace App\Http\Controllers\Api;
 */
class CommentsController extends Controller
{
    /**
     * @var CommentRepository
     */
    protected CommentRepository $repository;

    /**
     * CommentsController constructor.
     *
     * @param CommentRepository $repository
     */
    public function __construct(CommentRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @param int $movie
     * @return JsonResponse
     * @throws RepositoryException
     */
    public function index(Request $request, int $movie): JsonResponse
    {
        return $this->repository->index($request, $movie);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CommentCreateRequest $request
     *
     * @return JsonResponse
     *
     */
    public function store(CommentCreateRequest $request): JsonResponse
    {
        return $this->repository->store($request);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        return $this->repository->show($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param CommentUpdateRequest $request
     * @param int $id
     *
     * @return JsonResponse
     *
     */
    public function update(CommentUpdateRequest $request, int $id): JsonResponse
    {
        return $this->repository->updateComment($request, $id);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        return $this->repository->destroy($id);
    }
}
