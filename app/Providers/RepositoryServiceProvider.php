<?php

declare(strict_types=1);

namespace App\Providers;

use App\Repositories\Contracts\SiswaRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\Contracts\WaliRepositoryInterface;
use App\Repositories\Eloquent\SiswaRepository;
use App\Repositories\Eloquent\UserRepository;
use App\Repositories\Eloquent\WaliRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(SiswaRepositoryInterface::class, SiswaRepository::class);
        $this->app->bind(WaliRepositoryInterface::class, WaliRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
