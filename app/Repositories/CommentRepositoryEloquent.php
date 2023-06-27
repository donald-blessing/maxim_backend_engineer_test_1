<?php

namespace App\Repositories;

use App\Helpers\ResponseHelper;
use App\Http\Requests\CommentCreateRequest;
use App\Http\Requests\CommentUpdateRequest;
use App\Http\Resources\CommentResource;
use App\Models\Comment;
use App\Repositories\Interfaces\CommentRepository;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use JsonException;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Exceptions\RepositoryException;

/**
 * Class CommentRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class CommentRepositoryEloquent extends BaseRepository implements CommentRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model(): string
    {
        return Comment::class;
    }


    /**
     * Boot up the repository, pushing criteria
     */
    public function boot(): void
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @param int $movie
     * @return JsonResponse
     * @throws JsonException
     * @throws RepositoryException
     */
    public function index(Request $request, int $movie): JsonResponse
    {
        $cachedComment = Redis::get('comments');

        if (isset($cachedComment)) {
            $comments = json_decode($cachedComment, false, 512, JSON_THROW_ON_ERROR);

            return ResponseHelper::jsonResponse('Comments retrieved from redis successfully.', 200, CommentResource::collection($comments), true);
        }

        $this->pushCriteria(app(RequestCriteria::class));
        $comments = $this->scopeQuery(function ($query) use ($movie) {
            return $query->whereHas('movie', function ($q) use ($movie) {
                return $q->where('id', $movie);
            });
        })->all();
        Redis::set('comments', $comments);
        return ResponseHelper::jsonResponse('Comments retrieved successfully.', 200, CommentResource::collection($comments), true);
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
        try {
            $comment = $this->create($request->validated());

            // Set a new key with the comment id
            Redis::set('comment_' . $comment->id, $comment);
            return ResponseHelper::jsonResponse('Comment created successfully.', 200, new CommentResource($comment), true);
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
        $cachedComment = Redis::get('comment_' . $id);

        if (isset($cachedComment)) {
            $comments = json_decode($cachedComment, false, 512, JSON_THROW_ON_ERROR);
            return ResponseHelper::jsonResponse('Comment retrieved from redis successfully.', 200, CommentResource::collection($comments), true);
        }

        $comment = $this->find($id);
        if (!$comment) {
            return ResponseHelper::clientError('Comment not found.', 404);
        }
        Redis::set('comment_' . $comment->id, $comment);
        return ResponseHelper::jsonResponse('Comment retrieved successfully.', 200, new CommentResource($comment), true);
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
    public function updateComment(CommentUpdateRequest $request, int $id): JsonResponse
    {
        try {
            $comment = $this->find($id);
            if (!$comment) {
                return ResponseHelper::clientError('Comment not found.', 404);
            }
            $comment = $this->update($request->validated(), $id);

            // Delete comment_$id from Redis
            Redis::del('comment_' . $id);

            // Set a new key with the comment id
            Redis::set('comment_' . $id, $comment);

            return ResponseHelper::jsonResponse('Comment updated successfully.', 200, new CommentResource($comment), true);
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
        $comment = $this->find($id);
        if (!$comment) {
            return ResponseHelper::clientError('Comment not found.', 404);
        }
        $this->delete($id);
        // Delete comment_$id from Redis
        Redis::del('comment_' . $id);

        return ResponseHelper::jsonResponse('Comment deleted successfully.', 200, null, true);
    }

}
