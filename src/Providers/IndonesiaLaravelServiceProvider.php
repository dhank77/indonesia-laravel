<?php

namespace Hitech\IndonesiaLaravel\Providers;

use Hitech\IndonesiaLaravel\Commands\MakeIndonesia;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Foundation\Application;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use Illuminate\Support\Str;

class ServiceProvider extends BaseServiceProvider
{
    public function register()
    {
        $this->app->bind('indonesia', function () {
            return new IndonesiaService();
        });

        $this->commands([
            MakeIndonesia::class,
        ]);
    }

    public function boot()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/indonesia.php', 'indonesia');

        $databasePath = __DIR__.'/../database/migrations';
        $this->publishes([$databasePath => App::databasePath('migrations')], 'migrations');

        if (class_exists(Application::class)) {
            $this->publishes(
                [
                    __DIR__.'/../config/indonesia.php' => App::configPath('indonesia.php'),
                ],
                'config'
            );
        }


        $this->registerMacro();
    }

    protected function registerMacro()
    {
        EloquentBuilder::macro('whereLike', function ($attributes, string $searchTerm) {
            $this->where(function (EloquentBuilder $query) use ($attributes, $searchTerm) {
                foreach (Arr::wrap($attributes) as $attribute) {
                    $query->when(
                        Str::contains($attribute, '.'),
                        function (EloquentBuilder $query) use ($attribute, $searchTerm) {
                            [$relationName, $relationAttribute] = explode('.', $attribute);

                            $query->orWhereHas(
                                $relationName,
                                function (EloquentBuilder $query) use ($relationAttribute, $searchTerm) {
                                    $query->where($relationAttribute, 'LIKE', "%{$searchTerm}%");
                                }
                            );
                        },
                        function (EloquentBuilder $query) use ($attribute, $searchTerm) {
                            $table = $query->getModel()->getTable();
                            $query->orWhere(sprintf('%s.%s', $table, $attribute), 'LIKE', "%{$searchTerm}%");
                        }
                    );
                }
            });

            return $this;
        });
    }

}