<?php

namespace Tests;

use Hitech\IndonesiaLaravel\Providers\IndonesiaLaravelServiceProvider;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

class TestCase extends OrchestraTestCase
{
    protected function getPackageProviders($app): array
    {
        return [
            IndonesiaLaravelServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app): void
    {
        $app['config']->set('indonesia.pattern', 'ID');
        $app['config']->set('indonesia.table_prefix', 'indonesia');

        $app['config']->set('database.default', 'pgsql');

        $app['config']->set('database.connections.pgsql', [
            'driver' => 'pgsql',
            'host' => getenv('DB_HOST') ?: '127.0.0.1',
            'port' => getenv('DB_PORT') ?: '5432',
            'database' => getenv('DB_DATABASE') ?: 'indo',
            'username' => getenv('DB_USERNAME') ?: 'user',
            'password' => getenv('DB_PASSWORD') ?: '',
            'charset' => 'utf8',
            'prefix' => '',
            'schema' => 'public',
            'sslmode' => 'prefer',
        ]);
    }
}
