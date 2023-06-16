<?php

namespace Jauntin\CyberSource\Api;

use CyberSource\Model\FlexV1KeysPost200Response;

/**
 * @final
 */
class KeyResponse
{
    public string $keyId;

    public function fromResponse(FlexV1KeysPost200Response $response): self
    {
        $this->keyId = $response->getKeyId();
        return $this;
    }
}
