<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(\App\Repositories\Contracts\UserRepositoryInterface::class, \App\Repositories\UserRepository::class);
        $this->app->singleton(\App\Repositories\Contracts\RoleRepositoryInterface::class, \App\Repositories\RoleRepository::class);
        $this->app->singleton(\App\Repositories\Contracts\ServiceRepositoryInterface::class, \App\Repositories\ServiceRepository::class);
        $this->app->singleton(\App\Repositories\Contracts\BrandRepositoryInterface::class, \App\Repositories\BrandRepository::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
