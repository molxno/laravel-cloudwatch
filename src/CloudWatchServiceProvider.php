<?php

namespace Molxno\LaravelCloudwatch;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\ServiceProvider;
use Monolog\Logger;

class CloudWatchServiceProvider extends ServiceProvider
{
    /**
     * @throws BindingResolutionException
     */
    public function boot()
    {
        $this->app->make('log')->extend('cloudwatch', function ($app, array $config) {
            return new Logger('cloudwatch', [
                new CloudWatchLogger($config),
            ]);
        });
    }
}
