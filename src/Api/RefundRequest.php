<?php

namespace Jauntin\CyberSource\Api;

/**
 * @final
 */
class RefundRequest
{
    public string $paymentRequestId;

    public string $referenceNumber;

    public string $currency;

    public string $totalAmount;
}
