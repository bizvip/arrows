<?php

/******************************************************************************
 * Copyright (c) Archer++. 2024.                                              *
 ******************************************************************************/

declare(strict_types=1);

namespace Arrows\Constants;

use Hyperf\Constants\AbstractConstants;
use Hyperf\Constants\Annotation\Constants;

#[Constants]
final class ErrCode extends AbstractConstants
{
    /**
     * @Message("successfully")
     */
    public const HTTP_SUCCESS = 200;

    /**
     * @Message("Auth Failed")
     */
    public const HTTP_AUTH_FAILED = 401;

    /**
     * @Message("Auth Requreid")
     */
    public const HTTP_AUTH_REQUIRED = 403;

    /**
     * @Message("Bad HTTP Request")
     */
    public const HTTP_BAD_REQUEST = 400;

    /**
     * @Message("Internal Server Error")
     */
    public const HTTP_SERVER_ERROR = 500;

    /**
     * @Message("Server Busy")
     */
    public const HTTP_SERVER_BUSY = 503;

    /**
     * @Message("Please Try Again Later")
     */
    public const HTTP_RATE_LIMIT = 429;

    /**
     * @Message("Not Found")
     */
    public const HTTP_NOT_FOUND = 404;

    /**
     * @Message("biz.request.locked")
     */
    public const REQUEST_LOCKED = 418;

    ######################################################################################

    /**
     * @Message("biz.login.failed")
     */
    public const LOGIN_FAILED = 1001;
    /**
     * @Message("biz.login.password.invalid")
     */
    public const PASSWORD_INVALID = 1002;
    /**
     * @Message("biz.login.need.captcha")
     */
    public const LOGIN_NEED_CAPTCHA = 1003;
    /**
     * @Message("biz.login.captcha.invalid")
     */
    public const CAPTCHA_INVALID = 1004;
    /**
     * @Message("biz.login.too_many_times")
     */
    public const LOGIN_TOO_MANY_TIMES = 1005;

    ######################################################################################

    /**
     * @Message("biz.user.account.blocked")
     */
    public const USER_ACCOUNT_BLOCKED = 1103;

    /**
     * @Message("biz.user.account.not_available")
     */
    public const USER_ACCOUNT_NOT_AVAILABLE = 1104;

    /**
     * @Message("biz.user.account.is_virtual")
     */
    public const USER_ACCOUNT_IS_VIRTUAL = 1105;

    /**
     * @Message("biz.user.account.temporary.blocked")
     */
    public const USER_ACCOUNT_TEMPORARY_BLOCKED = 1106;

    /**
     * @Message("biz.user.account.email.not_equal")
     */
    public const USER_EMAIL_NOT_EQUAL = 1107;

    /**
     * @Message("biz.user.account.email.not_unique")
     */
    public const USER_EMAIL_EXISTS = 1108;

    /**
     * @Message("biz.user.gold_not_enough")
     */
    public const USER_GOLD_NOT_ENOUGH = 1109;

    /**
     * @Message("biz.user.cannot_handle_self")
     */
    public const USER_CANNOT_HANDLE_SELF = 1110;

    /**
     * @Message("biz.user.withdrawal_under_processing")
     */
    public const WITHDRAWAL_ORDER_UNDER_PROCESSING = 1111;

    /**
     * @Message("biz.user.request_repeat")
     */
    public const USER_REQUEST_REPEAT = 1112;

    ######################################################################################

    /**
     * @Message("biz.token.expired")
     */
    public const TOKEN_EXPIRED = 1201;

    ######################################################################################

    /**
     * @Message("biz.validated.failed")
     */
    public const VALIDATED_FAILED = 1301;

    ######################################################################################

    /**
     * @Message("biz.invalid.behavior.name")
     */
    public const UNKNOWN_BEHAVIOR_NAME = 1401;

    ######################################################################################

    /**
     * @Message("biz.topic.not_available")
     */
    public const TOPIC_NOT_AVAILABLE = 1501;
    /**
     * @Message("biz.topic.is_vip_only")
     */
    public const TOPIC_IS_VIP_ONLY = 1502;

    ######################################################################################

    /**
     * @Message("biz.db.no_changes")
     */
    public const DB_NO_CHANGES = 2001;

    /**
     * @Message("biz.db.item_exists")
     */
    public const DB_ITEM_EXISTS = 2002;

    /**
     * @Message("biz.db.item_not_exists")
     */
    public const DB_ITEM_NOT_EXISTS = 2003;

    /**
     * @Message("biz.bankcard.unavailable")
     */
    public const BANK_CARD_UNAVAILABLE = 2101;

    ######################################################################################

    /**
     * @Message("biz.search.page.num.illegal")
     */
    public const PAGE_NUM_ILLEGAL = 3001;

    /**
     * @Message("biz.search.page.size.illegal")
     */
    public const PAGE_SIZE_ILLEGAL = 3002;

    /**
     * @Message("biz.search.unknown.type")
     */
    public const UNKNOWN_SEARCH_TYPE = 3003;

    ######################################################################################

    /**
     * @Message("biz.upload.file.not.exists")
     */
    public const UPLOAD_FILE_NOT_EXISTS = 4001;

    /**
     * @Message("biz.upload.failed")
     */
    public const UPLOAD_FILE_FAILED = 4002;

    ######################################################################################

    /**
     * @Message("biz.feedback.waiting.reply")
     */
    public const FEEDBACK_WAITING_REPLY = 5001;
    /**
     * @Message("biz.feedback.closed")
     */
    public const FEEDBACK_CLOSED = 5002;
}
