<?php

/******************************************************************************
 * Copyright (c) Archer++. 2024.                                              *
 ******************************************************************************/

declare(strict_types=1);
namespace App\Utils\Vo;

final class AMQPMessageVo
{
    private int $delaySeconds = 0;

    private string $title;

    private string $content;

    private string $exchangeName;

    private string $routingKey;

    private string $queueName;

    private int $createdBy;

    public function getDelaySeconds(): int
    {
        return $this->delaySeconds;
    }

    public function setDelaySeconds(int $delaySeconds): AMQPMessageVo
    {
        $this->delaySeconds = $delaySeconds;
        return $this;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): AMQPMessageVo
    {
        $this->title = $title;
        return $this;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function setContent(string $content): AMQPMessageVo
    {
        $this->content = $content;
        return $this;
    }

    public function getExchangeName(): string
    {
        return $this->exchangeName;
    }

    public function setExchangeName(string $exchangeName): AMQPMessageVo
    {
        $this->exchangeName = $exchangeName;
        return $this;
    }

    public function getRoutingKey(): string
    {
        return $this->routingKey;
    }

    public function setRoutingKey(string $routingKey): AMQPMessageVo
    {
        $this->routingKey = $routingKey;
        return $this;
    }

    public function getQueueName(): string
    {
        return $this->queueName;
    }

    public function setQueueName(string $queueName): AMQPMessageVo
    {
        $this->queueName = $queueName;
        return $this;
    }

    public function getCreatedBy(): int
    {
        return $this->createdBy;
    }

    public function setCreatedBy(int $createdBy): AMQPMessageVo
    {
        $this->createdBy = $createdBy;
        return $this;
    }
}
