# Laravel Redis Cache Store Override

A simple package to override the default behavior of the Laravel Redis cache store.

### Why?

The default behaviour of Laravel's Redis driver when calling `cache()->clear()` is to call a flush
for the entire Redis database. This is not always desirable, especially when using a multi tenancy Redis instance.

This package provides a simple override to this behavior, allowing you to clear only the keys that match
the cache prefix.

### Installation

Simply pull the package in using composer, as below and you're good to go!

```bash
composer require motomedialab/laravel-redis-driver
```