<?php

namespace Jauntin\CyberSource\Api;

/**
 * @final
 */
class RefundResponse
{
    public string $id;
    public string $status;
    public string $orderReferenceNumber;
    public string $reconciliationId;
    public string $refundAmount;
    /**
     * @var mixed[]
     */
    public array $remoteResponse;
}
