<?php

namespace Motomedialab\LaravelRedisDriver\Providers;

use Motomedialab\LaravelRedisDriver\Cache\RedisStore;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\ServiceProvider;

class CacheServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Cache::extend('redis', function (Application $app) {
            $connection = $config['connection'] ?? 'default';

            $prefix = $config['prefix'] ?? $app['config']['cache.prefix'];

            return Cache::repository(new RedisStore($app['redis'], $prefix, $connection));
        });
    }
}
