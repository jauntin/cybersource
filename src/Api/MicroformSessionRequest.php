<?php

namespace Jauntin\CyberSource\Api;

/**
 * @final
 */
class MicroformSessionRequest
{
    public string $clientVersion = 'v2';

    /** @var string[] */
    public array $targetOrigins;

    /** @var CardNetworks[] */
    public array $allowedCardNetworks;

    /** @var PaymentTypes[] */
    public array $allowedPaymentTypes;

    /** @var ?array{'includeCardPrefix': bool} */
    public ?array $transientTokenResponseOptions = null;
}
