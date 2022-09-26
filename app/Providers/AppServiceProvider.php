<?php

namespace App\Providers;

use Illuminate\Filesystem\AwsS3V3Adapter;
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
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        AwsS3V3Adapter::macro('getClient', fn() => $this->client);
    }
}
