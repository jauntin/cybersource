<?php

namespace Jauntin\CyberSource\Api;

/**
 * @final
 */
final class RefundStatus
{
    public const STATUS_PENDING = 'PENDING';
    public const SUCCESSFUL_STATUS_VALUES = [
        self::STATUS_PENDING,
    ];

    public static function isSuccessful(string $status): bool
    {
        return in_array($status, self::SUCCESSFUL_STATUS_VALUES);
    }
}
