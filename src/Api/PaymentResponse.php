<?php

namespace Jauntin\CyberSource\Api;

/**
 * @final
 */
class PaymentResponse
{
    public string $id;

    public string $status;

    public ?string $orderReferenceNumber;

    public ?string $approvalCode;

    public ?string $reconciliationId;

    public ?string $amount;

    /**
     * @var mixed[]
     */
    public array $remoteResponse;
}
