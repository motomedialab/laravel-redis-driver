# Laravel Redis Cache Store Override

A simple package that overrides the default behavior when flushing the Laravel Redis cache store.

### Why?

The default behaviour of Laravel's Redis driver when calling `cache()->clear()` while using the Redis cache
driver is to call a `flushdb` command for the entire Redis database. This is not always desirable,
especially when using a multi tenancy Redis instance.

This package overrides that behaviour by clearing only the keys that match the cache prefix, stopping
a global `flushdb` command from being called.

### Installation

Simply pull the package in using composer, as below and you're good to go!

```bash
composer require motomedialab/laravel-redis-driver
```