<?php

declare(strict_types=1);

namespace Hitech\IndonesiaLaravel\Providers;

use Hitech\IndonesiaLaravel\Commands\MakeIndonesia;
use Hitech\IndonesiaLaravel\Services\IndonesiaService;
use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class IndonesiaLaravelServiceProvider extends BaseServiceProvider
{
    public function register()
    {
        $this->app->bind('indonesia', function () {
            return new IndonesiaService;
        });

        $this->commands([
            MakeIndonesia::class,
        ]);
    }

    public function boot()
    {
        $this->mergeConfigFrom(__DIR__ . '/../../config/indonesia.php', 'indonesia');

        $databasePath = __DIR__ . '/../../database/migrations';
        $this->publishes([$databasePath => App::databasePath('migrations')], 'migrations');

        $this->publishes(
            [
                __DIR__ . '/../../config/indonesia.php' => App::configPath('indonesia.php'),
            ],
            'config'
        );

    }
}
