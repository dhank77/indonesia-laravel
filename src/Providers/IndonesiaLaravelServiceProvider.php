<?php

declare(strict_types=1);

namespace Hitech\IndonesiaLaravel\Providers;

use Hitech\IndonesiaLaravel\Commands\MakeIndonesia;
use Hitech\IndonesiaLaravel\Services\IndonesiaService;
use Hitech\IndonesiaLaravel\Supports\IndonesiaConfig;
use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class IndonesiaLaravelServiceProvider extends BaseServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(IndonesiaConfig::class, function () {
            $pattern = config('indonesia.pattern');
            $tablePrefix = config('indonesia.table_prefix');

            return new IndonesiaConfig(
                $pattern,
                $tablePrefix,
                $pattern === 'ID' ? 'kode_provinsi' : 'province_code',
                $pattern === 'ID' ? 'kode_kabupaten' : 'city_code',
                $pattern === 'ID' ? 'kode_kecamatan' : 'district_code',
                $pattern === 'ID' ? 'kode_desa' : 'village_code',
                $pattern === 'ID' ? 'kode' : 'code',
                $pattern === 'ID' ? 'nama' : 'name'
            );
        });

        $this->app->singleton(IndonesiaService::class, function ($app) {
            return new IndonesiaService($app->make(IndonesiaConfig::class));
        });

        $this->app->alias(IndonesiaConfig::class, 'indonesia.config');
        $this->app->alias(IndonesiaService::class, 'indonesia');

        $this->commands([
            MakeIndonesia::class,
        ]);
    }

    public function boot(): void
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
