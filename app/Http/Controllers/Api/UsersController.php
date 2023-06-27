<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserCreateRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Repositories\UserRepository;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Exceptions\RepositoryException;


/**
 * Class UsersController.
 *
 * @package namespace App\Http\Controllers;
 */
class UsersController extends Controller
{
    /**
     * @var UserRepository
     */
    protected UserRepository $repository;

    /**
     * UsersController constructor.
     *
     * @param UserRepository $repository
     */
    public function __construct(UserRepository $repository)
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
        $this->repository->pushCriteria(app(RequestCriteria::class));
        $users = $this->repository->all();

        return ResponseHelper::jsonResponse('Users retrieved successfully.', 200, $users, true);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param UserCreateRequest $request
     *
     * @return JsonResponse
     *
     */
    public function store(UserCreateRequest $request): JsonResponse
    {
        try {
            $user = $this->repository->create($request->all());

            return ResponseHelper::jsonResponse('User created successfully.', 200, $user, true);
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
     */
    public function show(int $id): JsonResponse
    {
        $user = $this->repository->find($id);

        return ResponseHelper::jsonResponse('User retrieved successfully.', 200, $user, true);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UserUpdateRequest $request
     * @param int $id
     *
     * @return JsonResponse
     *
     */
    public function update(UserUpdateRequest $request, int $id): JsonResponse
    {
        try {

            $user = $this->repository->update($request->all(), $id);
            return ResponseHelper::jsonResponse('User updated successfully.', 200, $user, true);

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
        $this->repository->delete($id);

        return ResponseHelper::jsonResponse('User deleted successfully.', 200, [], true);
    }
}
