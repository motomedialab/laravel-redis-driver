<?php

namespace Motomedialab\LaravelRedisDriver\Providers;

use Motomedialab\LaravelRedisDriver\Cache\RedisStore;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\ServiceProvider;

class CacheServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->booting(function () {
            Cache::extend('redis', function (Application $app, array $config) {
                return Cache::repository(new RedisStore(
                    $app['redis'],
                    $config['prefix'] ?? $app['config']['cache.prefix'],
                        $config['connection'] ?? 'default'
                ));
            });
        });
    }
}
