<?php

namespace App\Http\Controllers\Api\Auth;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\UserResource;
use App\Traits\AuthUserTrait;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AuthenticatedSessionController extends Controller
{
    use AuthUserTrait;

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): JsonResponse
    {
        try {
            $request->authenticate();
            $user = $this->getAuthUser();
            return ResponseHelper::jsonResponse(
                'User registered successfully',
                Response::HTTP_CREATED,
                [
                    'user' => new UserResource($user),
                    'token' => $user->createToken("test-token")->plainTextToken
                ],
                true
            );
        } catch (Exception $e) {
            return ResponseHelper::serverError($e);
        }
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): JsonResponse
    {
        $this->getAuthUser()->tokens()->delete();
        Auth::guard('sanctum')->logout();

        return ResponseHelper::jsonResponse(
            'User logged out successfully',
            Response::HTTP_OK,
            [],
            true
        );
    }
}
