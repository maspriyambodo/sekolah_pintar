<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\LoginRequest;
use App\Http\Requests\Api\V1\RegisterRequest;
use App\Http\Resources\Api\V1\UserResource;
use App\Services\AuthService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthController extends Controller
{
    use ApiResponseTrait;

    private AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function login(LoginRequest $request): JsonResponse
    {
        try {
            $credentials = $request->validated();
            $result = $this->authService->login(
                $credentials,
                $request->ip(),
                $request->userAgent() ?? ''
            );

            return $this->successResponse([
                'access_token' => $result['access_token'],
                'refresh_token' => $result['refresh_token'],
                'token_type' => $result['token_type'],
                'expires_in' => $result['expires_in'],
                'user' => new UserResource($result['user']),
            ], 'Login successful');
        } catch (\Exception $e) {
            Log::warning('Login failed', ['email' => $request->input('email'), 'error' => $e->getMessage()]);
            return $this->unauthorizedResponse('Invalid credentials');
        }
    }

    public function register(RegisterRequest $request): JsonResponse
    {
        try {
            $user = $this->authService->register($request->validated());

            return $this->createdResponse(
                new UserResource($user),
                'User registered successfully'
            );
        } catch (\Exception $e) {
            Log::error('Registration failed', ['error' => $e->getMessage()]);
            return $this->errorResponse('Registration failed: ' . $e->getMessage(), 500);
        }
    }

    public function logout(Request $request): JsonResponse
    {
        try {
            $token = $request->bearerToken();
            if (!$token) {
                return $this->unauthorizedResponse('Token not provided');
            }

            $this->authService->logout($token);

            return $this->successResponse(null, 'Logout successful');
        } catch (JWTException $e) {
            return $this->errorResponse('Failed to logout: ' . $e->getMessage(), 500);
        }
    }

    public function refresh(Request $request): JsonResponse
    {
        try {
            $refreshToken = $request->input('refresh_token');
            if (!$refreshToken) {
                return $this->unauthorizedResponse('Refresh token not provided');
            }

            $result = $this->authService->refresh($refreshToken);

            return $this->successResponse([
                'access_token' => $result['access_token'],
                'refresh_token' => $result['refresh_token'],
                'token_type' => $result['token_type'],
                'expires_in' => $result['expires_in'],
            ], 'Token refreshed successfully');
        } catch (\Exception $e) {
            return $this->unauthorizedResponse('Invalid refresh token');
        }
    }

    public function me(Request $request): JsonResponse
    {
        try {
            $user = $this->authService->me($request->user());
            return $this->successResponse(new UserResource($user));
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to get user data: ' . $e->getMessage(), 500);
        }
    }
}
