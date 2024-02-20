<?php

/******************************************************************************
 * Copyright (c) Archer++. 2024.                                              *
 ******************************************************************************/

namespace App\Utils;

use Hyperf\Contract\PackerInterface;

final class Packer implements PackerInterface
{
    public function pack(mixed $data): string
    {
        return \igbinary_serialize($data);
    }

    public function unpack(string $data): mixed
    {
        return \igbinary_unserialize($data);
    }

    public static function serialize(mixed $data): string
    {
        return \igbinary_serialize($data);
    }

    public static function unserialize(string $data): mixed
    {
        return \igbinary_unserialize($data);
    }
}
