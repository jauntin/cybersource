<?php

namespace Jauntin\CyberSource\Api;

/**
 * @final
 */
class PaymentStatus
{
    const STATUS_AUTHORIZED = 'AUTHORIZED';
    const STATUS_AUTHORIZED_PENDING_REVIEW = 'AUTHORIZED_PENDING_REVIEW';
    const STATUS_AUTHORIZED_RISK_DECLINED = 'AUTHORIZED_RISK_DECLINED';
    const STATUS_DECLINED = 'DECLINED';
    const STATUS_PARTIAL_AUTHORIZED = 'PARTIAL_AUTHORIZED';
    const STATUS_INVALID_REQUEST = 'INVALID_REQUEST';
    const STATUS_PENDING_AUTHENTICATION = 'PENDING_AUTHENTICATION';
    const STATUS_PENDING = 'PENDING';
    const STATUS_SERVER_ERROR = 'SERVER_ERROR';

    const SUCCESSFUL_STATUS_VALUES = [
        self::STATUS_AUTHORIZED,
        self::STATUS_PARTIAL_AUTHORIZED,
    ];

    const UNSUCCESSFUL_STATUS_VALUES = [
        self::STATUS_AUTHORIZED_PENDING_REVIEW,
        self::STATUS_AUTHORIZED_RISK_DECLINED,
        self::STATUS_DECLINED,
        self::STATUS_PENDING_AUTHENTICATION,
        self::STATUS_PENDING,
    ];

    const ERROR_STATUS_VALUES = [
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
