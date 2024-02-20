<?php

/******************************************************************************
 * Copyright (c) Archer++. 2024.                                              *
 ******************************************************************************/

declare(strict_types=1);
namespace Arrows;

use Hyperf\Redis\Redis;

final class RedisStack
{
    private Redis $redis;

    public function __construct(Redis $redis)
    {
        $this->redis = $redis;
    }

    public function exists(string $key): bool|\Redis
    {
        $r = $this->redis->exists($key);
        if (is_bool($r)) {
            return $r;
        }
        if (is_int($r)) {
            return (bool)$r;
        }
        return $r;
    }

    public function add(): bool { return false; }
}
