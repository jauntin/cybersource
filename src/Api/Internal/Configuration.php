<?php

/*
* Purpose : passing Authentication config object to the configuration
*/

namespace Jauntin\CyberSource\Api\Internal;

/**
 * @final
 * @internal
 */
class Configuration
{
    public readonly string $host;
    public readonly string $merchantId;
    public readonly string $apiKeyId;
    public readonly string $secretKey;

    public function __construct()
    {
        $this->host = trim(config('cybersource.run_env'));
        $this->merchantId = trim(config('cybersource.merchant_id'));
        $this->apiKeyId = trim(config('cybersource.api_key_id'));
        $this->secretKey = trim(config('cybersource.secret_key'));
    }
}
