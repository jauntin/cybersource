<?php

namespace Jauntin\CyberSource\Api\Internal;

use Jauntin\CyberSource\Api\KeyResponse;

/**
 * @final
 *
 * @internal
 */
class KeyResponseAdapter
{
    /**
     * @param  array{'keyId': string}  $response
     */
    public function fromResponse(array $response): KeyResponse
    {
        $keyResponse = new KeyResponse();
        $response = (object) $response;
        $keyResponse->keyId = $response->keyId;

        return $keyResponse;
    }
}
