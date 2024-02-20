<?php

/******************************************************************************
 * Copyright (c) Archer++. 2024.                                              *
 ******************************************************************************/

declare(strict_types=1);

namespace Arrows;

use App\System\Model\SystemQueueMessage;
use App\System\Service\SystemQueueLogService;
use App\Utils\Vo\AMQPMessageVo;
use Hyperf\Context\ApplicationContext;

final class Message
{
    public static function sendSystemUserMessage(int $userId, string $title, string $content = '', array $receiveUsers = [], string $type = SystemQueueMessage::TYPE_PRIVATE_MESSAGE): bool
    {
        return ApplicationContext::getContainer()->get(SystemQueueLogService::class)->pushMessage(
            (new \App\System\Vo\QueueMessageVo())->setTitle($title)->setContentType($type)->setContent($content)
                ->setSendBy($userId), $receiveUsers
        );
    }

    public static function sendTelegram(string $text, string $chatId, string $parseMode = 'markdown'): bool
    {
        /** @var TgHelper $tg */
        $tg = ApplicationContext::getContainer()->get(TgHelper::class);
        $tg->setChatId($chatId);
        if ($parseMode !== 'markdown') {
            $tg->setParseMode($parseMode);
        }

        return $tg->sendMessage($text);
    }

    public static function sendAmqpQueue(AMQPMessageVo $message): bool
    {
        return false;
    }
}
