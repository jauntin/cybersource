<?php

namespace Jauntin\CyberSource\Api\Internal;

use Jauntin\CyberSource\Api\MicroformSessionResponse;

/**
 * @final
 *
 * @internal
 */
class MicroformSessionResponseAdapter
{
    public function fromResponse(string $response): MicroformSessionResponse
    {
        [$header, $payload] = explode('.', $response);
        $header = json_decode(base64_decode($header), true);
        $payload = json_decode(base64_decode($payload), true);

        $microformSessionResponse = new MicroformSessionResponse;
        $microformSessionResponse->captureContext = $response;
        $microformSessionResponse->header = $header;
        $microformSessionResponse->payload = $payload;
        $microformSessionResponse->kid = $header['kid'];
        $microformSessionResponse->alg = $header['alg'];
        $microformSessionResponse->clientLibrary = $payload['ctx'][0]['data']['clientLibrary'];
        $microformSessionResponse->clientLibraryIntegrity = $payload['ctx'][0]['data']['clientLibraryIntegrity'];

        return $microformSessionResponse;
    }
}
