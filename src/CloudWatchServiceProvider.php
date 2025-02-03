<?php

namespace Molxno\LaravelCloudwatch;

use Illuminate\Support\ServiceProvider;
use Monolog\Logger;

class CloudWatchServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->app->make('log')->extend('cloudwatch', function ($app, array $config) {
            return new Logger('cloudwatch', [
                new CloudWatchLogger($config),
            ]);
        });
    }
}
