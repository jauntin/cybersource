<?php

namespace Jauntin\CyberSource\Tests\Unit\Api;

use CyberSource\Api\KeyGenerationApi;
use CyberSource\Model\FlexV1KeysPost200Response;
use CyberSource\Model\GeneratePublicKeyRequest;
use Exception;
use Illuminate\Support\Facades\App;
use Jauntin\CyberSource\Api\ErrorResponse;
use Jauntin\CyberSource\Api\KeyRequest;
use Jauntin\CyberSource\Api\KeyResponse;
use Jauntin\CyberSource\Api\KeyService;
use Mockery\MockInterface;
use Jauntin\CyberSource\Tests\TestCase;

class KeyServiceTest extends TestCase
{
    private KeyRequest $keyRequest;

    protected function setUp(): void
    {
        parent::setUp();
        $this->keyRequest = $this->mock(KeyRequest::class, function (MockInterface $mock) {
            foreach (get_class_vars(KeyRequest::class) as $k => $_) {
                $mock->$k = $k;
            }
        });
    }
    public function testGenerateKeyReturnsResponse()
    {
        $generatePublicKeyRequest = $this->mock(GeneratePublicKeyRequest::class, function (MockInterface $mock) {
            $mock->shouldReceive('setEncryptionType')->with($this->keyRequest->encryptionType)->once()->andReturnNull();
        });
        $flexV1KeysPost200Response = $this->mock(FlexV1KeysPost200Response::class);
        $this->mock(KeyGenerationApi::class, function (MockInterface $mock) use ($generatePublicKeyRequest, $flexV1KeysPost200Response) {
            $mock->shouldReceive('generatePublicKey')->with('legacy', $generatePublicKeyRequest)->once()->andReturn([$flexV1KeysPost200Response]);
        });
        $response = $this->mock(KeyResponse::class, function (MockInterface $mock) use ($flexV1KeysPost200Response) {
            $mock->shouldReceive('fromResponse')->with($flexV1KeysPost200Response)->once()->andReturn($mock);
        });

        $this->assertSame($response, App::make(KeyService::class)->generateKey($this->keyRequest));
    }

    public function testGenerateKeyThrowableErrorResponse()
    {
        $e = new Exception();
        $this->mock(KeyGenerationApi::class, function (MockInterface $mock) use ($e) {
            $mock->shouldReceive('generatePublicKey')->andThrow($e);
        });
        $this->mock(ErrorResponse::class, function (MockInterface $mock) use ($e) {
            $mock->shouldReceive('fromThrowable')->with($e)->once();
        });

        App::make(KeyService::class)->generateKey($this->keyRequest);
    }
}
