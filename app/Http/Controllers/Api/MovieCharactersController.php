<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\MovieCharacterCreateRequest;
use App\Http\Requests\MovieCharacterUpdateRequest;
use App\Repositories\Interfaces\MovieCharacterRepository;
use App\Traits\GeneralHelperTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Prettus\Repository\Exceptions\RepositoryException;

/**
 * Class MovieCharactersController.
 *
 * @package namespace App\Http\Controllers\Api;
 */
class MovieCharactersController extends Controller
{
    use GeneralHelperTrait;

    /**
     * @var MovieCharacterRepository
     */
    protected MovieCharacterRepository $repository;

    /**
     * MovieCharactersController constructor.
     *
     * @param MovieCharacterRepository $repository
     */
    public function __construct(MovieCharacterRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @param  $movie
     * @return JsonResponse
     * @throws RepositoryException
     */
    public function index(Request $request, $movie = null): JsonResponse
    {
        return $this->repository->index($request, $movie);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param MovieCharacterCreateRequest $request
     *
     * @return JsonResponse
     *
     */
    public function store(MovieCharacterCreateRequest $request): JsonResponse
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
     * @param MovieCharacterUpdateRequest $request
     * @param int $id
     *
     * @return JsonResponse
     *
     */
    public function update(MovieCharacterUpdateRequest $request, int $id): JsonResponse
    {
        return $this->repository->updateMovieCharacter($request, $id);
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
