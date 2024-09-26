<?php

namespace App\Providers;

use App\Interfaces\ICartRepository;
use App\Interfaces\ICategoryRepository;
use App\Interfaces\IProductRepository;
use App\Interfaces\IUserRepository;
use App\Models\Cart;
use App\Repositories\CartRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(
            IUserRepository::class,
            UserRepository::class
        );
        $this->app->bind(
            ICartRepository::class,
            CartRepository::class
        );
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
