<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->app->bind(\App\Repositories\UserRepository::class, \App\Repositories\UserRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\Interfaces\MovieRepository::class, \App\Repositories\MovieRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\Interfaces\CommentRepository::class, \App\Repositories\CommentRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\Interfaces\MovieCharacterRepository::class, \App\Repositories\MovieCharacterRepositoryEloquent::class);
        //:end-bindings:
    }
}
