<?php

namespace Motomedialab\LaravelRedisDriver\Cache;

use Illuminate\Cache\RedisStore as BaseRedisStore;
use Illuminate\Redis\Connections\PhpRedisConnection;
use Illuminate\Redis\Connections\PredisConnection;
use Illuminate\Support\LazyCollection;

class RedisStore extends BaseRedisStore
{
    public function flush(): bool
    {
        $connection = $this->connection();

        $chunkSize = 2000;

        $items = $this->currentKeys($chunkSize);

        foreach ($items->chunk($chunkSize) as $chunk) {
            $connection->del($chunk->toArray());
        }

        return true;
    }

    protected function currentKeys($chunkSize = 1000)
    {
        $connection = $this->connection();

        // Connections can have a global prefix...
        $connectionPrefix = match (true) {
            $connection instanceof PhpRedisConnection => $connection->_prefix(''),
            /* @phpstan-ignore-next-line */
            $connection instanceof PredisConnection => $connection->getOptions()->prefix ?: '',
            default => '',
        };

        $prefix = $connectionPrefix . $this->getPrefix();

        /* @phpstan-ignore-next-line */
        return LazyCollection::make(function () use ($connection, $chunkSize, $prefix) {
            $cursor = $defaultCursorValue = '0';

            do {
                /* @phpstan-ignore-next-line */
                [$cursor, $tagsChunk] = $connection->scan($cursor, ['match' => $prefix . '*', 'count' => $chunkSize]);

                /* @phpstan-ignore-next-line */
                if (!is_array($tagsChunk)) {
                    break;
                }

                /* @phpstan-ignore-next-line */
                $tagsChunk = array_unique($tagsChunk);

                if (empty($tagsChunk)) {
                    continue;
                }

                foreach ($tagsChunk as $tag) {
                    yield $tag;
                }
            } while (((string)$cursor) !== $defaultCursorValue);
        })->map(fn (string $cacheKey) => str_replace($connectionPrefix, '', $cacheKey));
    }
}
