<?php

namespace App\Repositories;

use App\Helpers\ResponseHelper;
use App\Http\Requests\MovieCreateRequest;
use App\Http\Requests\MovieUpdateRequest;
use App\Http\Resources\MovieResource;
use App\Models\Movie;
use App\Repositories\Interfaces\MovieRepository;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use JsonException;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Exceptions\RepositoryException;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class MovieRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class MovieRepositoryEloquent extends BaseRepository implements MovieRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model(): string
    {
        return Movie::class;
    }


    /**
     * Boot up the repository, pushing criteria
     * @throws RepositoryException
     */
    public function boot(): void
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return JsonResponse
     * @throws RepositoryException|JsonException
     */
    public function index(Request $request): JsonResponse
    {
        $cachedMovie = Redis::get('movies');

        if (isset($cachedMovie)) {
            $movies = json_decode($cachedMovie, false, 512, JSON_THROW_ON_ERROR);

            return ResponseHelper::jsonResponse('Movies retrieved from redis successfully.', 200, MovieResource::collection($movies), true);
        }


        $this->pushCriteria(app(RequestCriteria::class));
        $query = $this;

        //search
        if ($request->has('search')) {
            $query = $query->scopeQuery(function ($query) use ($request) {
                return $query->where('title', 'like', '%' . $request->get('search') . '%')
                    ->orWhere('description', 'like', '%' . $request->get('search') . '%')
                    ->orWhere('country', 'like', '%' . $request->get('search') . '%')
                    ->orWhere('genre', 'like', '%' . $request->get('search') . '%')
                    ->orWhere('characters', 'like', '%' . $request->get('search') . '%')
                    ->orWhere('rating', '=', $request->get('search'));
            });
        }

        $movies = $query->all();

        Redis::set('movies', $movies);

        return ResponseHelper::jsonResponse('Users retrieved successfully.', 200, MovieResource::collection($movies), true);
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
        try {
            $movie = $this->create($request->validated());
            // Set a new key with the movie id
            Redis::set('movie_' . $movie->id, $movie);
            return ResponseHelper::jsonResponse('Movie created successfully.', 200, new MovieResource($movie), true);
        } catch (Exception $e) {
            return ResponseHelper::serverError($e);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return JsonResponse
     * @throws JsonException
     */
    public function show(int $id): JsonResponse
    {
        $cachedMovie = Redis::get('movie_' . $id);

        if (isset($cachedMovie)) {
            $movies = json_decode($cachedMovie, false, 512, JSON_THROW_ON_ERROR);
            return ResponseHelper::jsonResponse('Movie retrieved from redis successfully.', 200, MovieResource::collection($movies), true);
        }

        $movie = $this->find($id);
        if (!$movie) {
            return ResponseHelper::clientError('Movie not found.', Response::HTTP_NOT_FOUND);
        }

        Redis::set('movie_' . $movie->id, $movie);
        return ResponseHelper::jsonResponse('Movie retrieved successfully.', Response::HTTP_OK, new MovieResource($movie), true);
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
    public function updateMovie(MovieUpdateRequest $request, int $id): JsonResponse
    {
        try {
            $movie = $this->find($id);
            if (!$movie) {
                return ResponseHelper::clientError('Movie not found.', Response::HTTP_NOT_FOUND);
            }
            $movie = $this->update($request->validated(), $id);

            // Delete movie_$id from Redis
            Redis::del('movie_' . $id);

            // Set a new key with the movie id
            Redis::set('movie_' . $id, $movie);

            return ResponseHelper::jsonResponse('Movie updated successfully.', Response::HTTP_OK, new MovieResource($movie), true);
        } catch (Exception $e) {
            return ResponseHelper::serverError($e);
        }
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
        $movie = $this->find($id);
        if (!$movie) {
            return ResponseHelper::clientError('Movie not found.', Response::HTTP_NOT_FOUND);
        }
        $this->delete($id);
        // Delete movie_$id from Redis
        Redis::del('movie_' . $id);
        return ResponseHelper::jsonResponse('Movie deleted successfully.', Response::HTTP_OK, [], true);
    }

}
