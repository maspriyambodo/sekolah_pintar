<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\System\SysLoginLog;
use App\Models\System\SysUser;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redis;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class AuthService
{
    private UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function login(array $credentials, string $ipAddress, string $userAgent): array
    {
        $user = $this->userRepository->findActiveByEmail($credentials['email']);

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            $this->logLoginAttempt($credentials['email'], 2, $ipAddress, $userAgent);
            throw new \Exception('Invalid credentials', 401);
        }

        $token = JWTAuth::fromUser($user);
        $refreshToken = $this->generateRefreshToken($user);

        $this->logLoginAttempt($user->email, 1, $ipAddress, $userAgent, $user->id);

        return [
            'access_token' => $token,
            'refresh_token' => $refreshToken,
            'token_type' => 'bearer',
            'expires_in' => config('jwt.ttl') * 60,
            'user' => $user->load(['roles', 'roles.permissions']),
        ];
    }

    public function register(array $data): SysUser
    {
        return DB::transaction(function () use ($data) {
            $user = $this->userRepository->create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'role' => $data['role'],
                'is_active' => true,
            ]);

            return $user;
        });
    }

    public function logout(string $token): void
    {
        JWTAuth::setToken($token)->invalidate();

        // Add to Redis blacklist for additional security
        $jti = JWTAuth::setToken($token)->getPayload()->get('jti');
        Redis::setex("jwt:blacklist:{$jti}", config('jwt.ttl') * 60, 'blacklisted');
    }

    public function refresh(string $refreshToken): array
    {
        // Verify refresh token from Redis
        $tokenData = Redis::get("refresh_token:{$refreshToken}");

        if (!$tokenData) {
            throw new \Exception('Invalid refresh token', 401);
        }

        $data = json_decode($tokenData, true);
        $user = $this->userRepository->find($data['user_id']);

        if (!$user || !$user->is_active) {
            Redis::del("refresh_token:{$refreshToken}");
            throw new \Exception('User not found or inactive', 401);
        }

        // Invalidate old refresh token
        Redis::del("refresh_token:{$refreshToken}");

        // Generate new tokens
        $newToken = JWTAuth::fromUser($user);
        $newRefreshToken = $this->generateRefreshToken($user);

        return [
            'access_token' => $newToken,
            'refresh_token' => $newRefreshToken,
            'token_type' => 'bearer',
            'expires_in' => config('jwt.ttl') * 60,
        ];
    }

    public function me(SysUser $user): SysUser
    {
        return $user->load(['roles.permissions', 'guru', 'siswa.kelas', 'wali']);
    }

    private function generateRefreshToken(SysUser $user): string
    {
        $refreshToken = bin2hex(random_bytes(32));
        $ttl = config('jwt.refresh_ttl') * 60; // Convert to seconds

        Redis::setex(
            "refresh_token:{$refreshToken}",
            $ttl,
            json_encode(['user_id' => $user->id, 'created_at' => now()->toIso8601String()])
        );

        return $refreshToken;
    }

    private function logLoginAttempt(
        string $email,
        int $status,
        string $ipAddress,
        string $userAgent,
        ?int $userId = null
    ): void {
        SysLoginLog::create([
            'sys_user_id' => $userId,
            'email' => $email,
            'status' => $status,
            'ip_address' => $ipAddress,
            'user_agent' => substr($userAgent, 0, 255),
        ]);
    }

    public function isTokenBlacklisted(string $jti): bool
    {
        return Redis::exists("jwt:blacklist:{$jti}") === 1;
    }
}
