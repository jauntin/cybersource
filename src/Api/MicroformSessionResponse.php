<?php

namespace Jauntin\CyberSource\Api;

/**
 * @final
 */
class MicroformSessionResponse
{
    public string $captureContext;

    /** @var array{kid: string, alg: string} */
    public array $header;

    public string $kid;

    public string $alg;

    /** @var array{ctx: array{data: array{clientLibrary: string, clientLibraryIntegrity: string}}} */
    public array $payload;

    public string $clientLibrary;

    public string $clientLibraryIntegrity;
}
