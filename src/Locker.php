<?php

/******************************************************************************
 * Copyright (c) Archer++. 2024.                                              *
 ******************************************************************************/

declare(strict_types=1);

namespace Arrows;

use Hyperf\Context\ApplicationContext;
use Hyperf\Redis\Redis;
use Lysice\HyperfRedisLock\RedisLock;

final class Locker
{
    public static function buildLockKey(string $key, int $ttl, string $prefix = ''): string
    {
        return $prefix.':'.$key.$ttl;
    }

    public static function create(int|string $key, int $ttl, ?string $owner = null)
    {
        return make(name: RedisLock::class, parameters: [
            ApplicationContext::getContainer()->get(Redis::class),
            self::buildLockKey($key, $ttl),
            $ttl,
            $owner,
        ]);
    }
}
