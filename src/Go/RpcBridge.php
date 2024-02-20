<?php

/******************************************************************************
 * Copyright (c) Archer++. 2024.                                              *
 ******************************************************************************/

declare(strict_types=1);
namespace Arrows\Go;

use App\Exception\BusinessException;
use App\Utils\Logger;
use Hyperf\Config\Annotation\Value;
use Spiral\Goridge;
use Spiral\Goridge\RPC\RPC;

use function Hyperf\Config\config;

final class RpcBridge
{
    #[Value('app.rpc.addr')]
    private string $tcpAddr = '127.0.0.1:6001';

    #[Value('app.rpc.type')]
    private string $type = 'tcp';

    public function __construct()
    {
        $this->tcpAddr = config('app.rpc.addr');
        $this->type    = config('app.rpc.type');
    }

    public function call(string $method, mixed $payload, mixed $options = null): false|RpcResp
    {
        try {
            $result = $this->connect()->call($method, $payload, $options);
            if ($result || is_array($result)) {
                return new RpcResp($result);
            }
        } catch (Goridge\RPC\Exception\ServiceException $serviceException) {
            throw new BusinessException(message: $serviceException->getMessage());
        } catch (\Throwable $e) {
            Logger::error($e);
        }

        return false;
    }

    // public function withServicePrefix(string $service): RPCInterface { }
    //
    // public function withCodec(CodecInterface $codec): RPCInterface { }

    // private function isConnected(): bool
    // {
    //     try {
    //         $this->rpcClient->call('App.Ping', '');
    //
    //         return true;
    //     } catch (\Throwable $e) {
    //         Logger::error($e->getMessage());
    //
    //         return false;
    //     }
    // }

    private function connect(): RPC
    {
        $cli = match ($this->type) {
            'tcp' => new RPC(Goridge\Relay::create("tcp://{$this->tcpAddr}"), new Goridge\RPC\Codec\JsonCodec()),
            'stream' => new RPC(Goridge\Relay::create('pipes://stdin:stdout'), new Goridge\RPC\Codec\JsonCodec()),
            'unix' => new RPC(Goridge\Relay::create("unix://{$this->tcpAddr}"), new Goridge\RPC\Codec\JsonCodec()),
            default => throw new \InvalidArgumentException("Unsupported connection type: {$this->type}"),
        };
        if (!$cli) {
            throw new BusinessException(message: '创建rpc客户端链接失败');
        }

        return $cli;
    }
}
