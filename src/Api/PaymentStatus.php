<?php

namespace Jauntin\CyberSource\Api;

/**
 * @final
 */
class PaymentStatus
{
    public const STATUS_AUTHORIZED = 'AUTHORIZED';

    public const STATUS_AUTHORIZED_PENDING_REVIEW = 'AUTHORIZED_PENDING_REVIEW';

    public const STATUS_AUTHORIZED_RISK_DECLINED = 'AUTHORIZED_RISK_DECLINED';

    public const STATUS_DECLINED = 'DECLINED';

    public const STATUS_PARTIAL_AUTHORIZED = 'PARTIAL_AUTHORIZED';

    public const STATUS_INVALID_REQUEST = 'INVALID_REQUEST';

    public const STATUS_PENDING_AUTHENTICATION = 'PENDING_AUTHENTICATION';

    public const STATUS_PENDING = 'PENDING';

    public const STATUS_SERVER_ERROR = 'SERVER_ERROR';

    public const SUCCESSFUL_STATUS_VALUES = [
        self::STATUS_AUTHORIZED,
        self::STATUS_PARTIAL_AUTHORIZED,
    ];

    public const UNSUCCESSFUL_STATUS_VALUES = [
        self::STATUS_AUTHORIZED_PENDING_REVIEW,
        self::STATUS_AUTHORIZED_RISK_DECLINED,
        self::STATUS_DECLINED,
        self::STATUS_PENDING_AUTHENTICATION,
        self::STATUS_PENDING,
    ];

    public const ERROR_STATUS_VALUES = [
        self::STATUS_INVALID_REQUEST,
        self::STATUS_SERVER_ERROR,
    ];

    public static function isSuccessful(string $status): bool
    {
        return in_array($status, self::SUCCESSFUL_STATUS_VALUES);
    }

    public static function isUnsuccessful(string $status): bool
    {
        return in_array($status, self::UNSUCCESSFUL_STATUS_VALUES);
    }

    public static function isError(string $status): bool
    {
        return in_array($status, self::ERROR_STATUS_VALUES);
    }
}
