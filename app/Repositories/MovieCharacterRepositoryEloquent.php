<?php

namespace App\Repositories;

use App\Helpers\ResponseHelper;
use App\Http\Requests\MovieCharacterCreateRequest;
use App\Http\Requests\MovieCharacterUpdateRequest;
use App\Http\Resources\MovieCharacterResource;
use App\Models\MovieCharacter;
use App\Repositories\Interfaces\MovieCharacterRepository;
use App\Traits\GeneralHelperTrait;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Str;
use JsonException;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Exceptions\RepositoryException;

/**
 * Class MovieCharacterRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class MovieCharacterRepositoryEloquent extends BaseRepository implements MovieCharacterRepository
{
    use GeneralHelperTrait;

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model(): string
    {
        return MovieCharacter::class;
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
     * @param  $movie
     * @return JsonResponse
     * @throws JsonException
     * @throws RepositoryException
     */
    public function index(Request $request, $movie = null): JsonResponse
    {

        $cachedMovieCharacter = Redis::get('movieCharacters');

        if (isset($cachedMovieCharacter)) {
            $movieCharacters = json_decode($cachedMovieCharacter, false, 512, JSON_THROW_ON_ERROR);

            return ResponseHelper::jsonResponse('MovieCharacters retrieved from redis successfully.', 200, MovieCharacterResource::collection($movieCharacters), true);
        }

        $this->pushCriteria(app(RequestCriteria::class));
        $query = $this;

        //filter
        if ($request->get('filter')) {
            $filter = $request->get('filter');
            $query = $query->scopeQuery(function ($query) use ($filter) {
                return $query->where('name', 'like', "%$filter%")
                    ->orWhere('gender', 'like', "%$filter%");
            });
        }

        //sort
        if ($request->get('sortBy') && $request->get('sortOrder')) {
            $sortBy = $request->get('sortBy');
            $order = Str::startsWith($request->get('sortOrder'), 'asc') ? 'asc' : 'desc';
            $query = $query->scopeQuery(function ($query) use ($sortBy, $order) {
                return $query->orderBy($sortBy, $order);
            });
        }

        if ($movie !== null) {
            $query = $query->scopeQuery(function ($query) use ($movie) {
                return $query->where('movie_id', $movie);
            });
        }
        $movieCharacters = $query->all();

        $totalHeight = $movieCharacters->sum('height');

        $data = MovieCharacterResource::collection($movieCharacters)
            ->additional([
                'meta' => [
                    'total_number_of_characters' => $query->count(),
                    'total_height_in_cm' => $totalHeight,
                    'total_height_in_feet' => self::cm2feet($totalHeight),
                ]
            ]);

        Redis::set('movieCharacters', $data);

        return ResponseHelper::jsonResponse(
            'Movie characters retrieved successfully.',
            200,
            $data,
            true
        );
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
        try {
            $movieCharacter = $this->create($request->validated());

            // Set a new key with the movieCharacter id
            Redis::set('movieCharacter_' . $movieCharacter->id, $movieCharacter);

            $response = [
                'message' => 'Movie Character created.',
            ];
            return ResponseHelper::jsonResponse(
                $response['message'],
                200,
                new MovieCharacterResource($movieCharacter),
                true
            );

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
        $cachedMovieCharacter = Redis::get('movieCharacter_' . $id);

        if (isset($cachedMovieCharacter)) {
            $movieCharacters = json_decode($cachedMovieCharacter, false, 512, JSON_THROW_ON_ERROR);
            return ResponseHelper::jsonResponse('MovieCharacter retrieved from redis successfully.', 200, MovieCharacterResource::collection($movieCharacters), true);
        }

        $movieCharacter = $this->find($id);
        if (!$movieCharacter) {
            return ResponseHelper::clientError('Movie Character not found.', 404);
        }
        Redis::set('movieCharacter_' . $movieCharacter->id, $movieCharacter);
        return ResponseHelper::jsonResponse(
            'Movie Character retrieved successfully.',
            200,
            new MovieCharacterResource($movieCharacter),
            true
        );
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
    public function updateMovieCharacter(MovieCharacterUpdateRequest $request, int $id): JsonResponse
    {
        try {
            $movieCharacter = $this->find($id);
            if (!$movieCharacter) {
                return ResponseHelper::clientError('Movie Character not found.', 404);
            }
            $movieCharacter = $this->update($request->validated(), $id);

            // Delete movieCharacter_$id from Redis
            Redis::del('movieCharacter_' . $id);

            // Set a new key with the movieCharacter id
            Redis::set('movieCharacter_' . $id, $movieCharacter);

            $response = [
                'message' => 'Movie Character updated.',
            ];

            return ResponseHelper::jsonResponse(
                $response['message'],
                200,
                new MovieCharacterResource($movieCharacter),
                true
            );
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
        $movieCharacter = $this->find($id);
        if (!$movieCharacter) {
            return ResponseHelper::clientError('Movie Character not found.', 404);
        }
        $this->delete($id);

        // Delete movieCharacter_$id from Redis
        Redis::del('movieCharacter_' . $id);

        return ResponseHelper::jsonResponse(
            'Movie Character deleted successfully.',
            200,
            null,
            true
        );
    }

}
