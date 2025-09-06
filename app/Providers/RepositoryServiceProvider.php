<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\Interfaces\ShipmentRepositoryInterface;
use App\Repositories\Eloquent\ShipmentRepository;
use App\Models\Shipment;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        // Bind ShipmentRepository
        $this->app->bind(ShipmentRepositoryInterface::class, function ($app) {
            return new ShipmentRepository(new Shipment());
        });

        // Add more repository bindings here as needed
        // $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        // $this->app->bind(AccountRepositoryInterface::class, AccountRepository::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}