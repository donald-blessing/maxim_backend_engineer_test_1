<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\MovieCreateRequest;
use App\Http\Requests\MovieUpdateRequest;
use App\Repositories\Interfaces\MovieRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Prettus\Repository\Exceptions\RepositoryException;

/**
 * Class MoviesController.
 *
 * @package namespace App\Http\Controllers\Api;
 */
class MoviesController extends Controller
{
    /**
     * @var MovieRepository
     */
    protected MovieRepository $repository;

    /**
     * MoviesController constructor.
     *
     * @param MovieRepository $repository
     */
    public function __construct(MovieRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return JsonResponse
     * @throws RepositoryException
     */
    public function index(Request $request): JsonResponse
    {
        return $this->repository->index($request);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param MovieCreateRequest $request
     *
     * @return JsonResponse
     *
     */
    public function store(MovieCreateRequest $request): JsonResponse
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
     * @param MovieUpdateRequest $request
     * @param int $id
     *
     * @return JsonResponse
     *
     */
    public function update(MovieUpdateRequest $request, int $id): JsonResponse
    {
        return $this->repository->updateMovie($request, $id);
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
