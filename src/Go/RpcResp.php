<?php

/******************************************************************************
 * Copyright (c) Archer++. 2024.                                              *
 ******************************************************************************/

declare(strict_types=1);
namespace Arrows\Go;

use App\Utils\JSON;

final class RpcResp
{
    private int $code;

    private string $msg;

    private ?array $data;

    public function __construct(array $data)
    {
        $this->code = $data['code'] ?? -1;
        $this->msg  = $data['msg'] ?? '';
        $this->data = $data['data'] ?? null;
    }

    public function getCode(): int
    {
        return (int)$this->code;
    }

    public function getMsg(): string
    {
        return trim((string)$this->msg);
    }

    public function getData(): ?array
    {
        return $this->data;
    }

    public function __toArray(): array
    {
        return ['code' => $this->code, 'msg' => $this->msg, 'data' => $this->data];
    }

    public function __toString(): string
    {
        return JSON::encode(['code' => $this->code, 'msg' => $this->msg, 'data' => $this->data]);
    }
}
