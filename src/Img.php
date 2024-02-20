<?php

/******************************************************************************
 * Copyright (c) Archer++. 2024.                                              *
 ******************************************************************************/

declare(strict_types=1);

namespace Arrows;

final class Img
{
    public static function getInfoFromUrl(string $url): array
    {
        $info           = getimagesize($url);
        $data['height'] = $info[0];
        $data['width']  = $info[1];
        $data['size']   = get_headers($url, true)['Content-Length'];
        $data['mime']   = $info['mime'];

        return $data;
    }
}
