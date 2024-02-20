<?php

/******************************************************************************
 * Copyright (c) Archer++. 2024.                                              *
 ******************************************************************************/

declare(strict_types=1);
namespace App\Utils\Model;

use Carbon\Carbon;
use ReflectionClass;

trait PropertyCastTrait
{
    private static function getClassProperties(): array
    {
        $reflectionClass = new ReflectionClass(static::class);
        $docComment      = $reflectionClass->getDocComment();

        $properties = [];
        if ($docComment !== false) {
            preg_match_all('/@property\s+(\S+)\s+\$(\S+)\s+(.*)/', $docComment, $matches, PREG_SET_ORDER);
            foreach ($matches as $match) {
                // 2 变量名，1 类型
                $properties[$match[2]] = $match[1];
            }
        }

        return $properties;
    }

    public static function transformType(array $data): array
    {
        $transformed = [];
        $typeMap     = self::getClassProperties();

        foreach ($data as $key => $value) {
            if (array_key_exists($key, $typeMap)) {
                $transformed[$key] = self::convertType($value, $typeMap[$key]);
            } else {
                $transformed[$key] = $value;
            }
        }

        return $transformed;
    }

    private static function convertType($value, string $type): Carbon|int|string
    {
        return match ($type) {
            'int'    => (int)$value,
            'string' => (string)$value,
            'Carbon' => new Carbon($value),
            default  => $value,
        };
    }

}
