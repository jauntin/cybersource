<?php

namespace Jauntin\CyberSource\Api;

use CyberSource\Api\KeyGenerationApi;
use CyberSource\Model\GeneratePublicKeyRequest;
use Illuminate\Support\Facades\App;
use Throwable;

/**
 * @final
 */
class KeyService
{
    private const FORMAT = 'legacy';

    public function __construct(
        private KeyGenerationApi $api,
        private GeneratePublicKeyRequest $request,
        private KeyResponse $keyResponse
    ) {
    }

    public function generateKey(KeyRequest $keyRequest): KeyResponse|ErrorResponse
    {
        try {
            $this->request->setEncryptionType($keyRequest->encryptionType);
            return $this->keyResponse->fromResponse($this->api->generatePublicKey(self::FORMAT, $this->request)[0]);
        } catch (Throwable $e) {
            return App::make(ErrorResponse::class)->fromThrowable($e);
        }
    }
}
