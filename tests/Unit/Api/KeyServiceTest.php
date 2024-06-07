<?php

namespace Jauntin\CyberSource\Tests\Unit\Api;

use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Promise\RejectedPromise;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Http;
use Jauntin\CyberSource\Api\ErrorResponse;
use Jauntin\CyberSource\Api\Internal\KeyResponseAdapter;
use Jauntin\CyberSource\Api\Internal\RequestHeaders;
use Jauntin\CyberSource\Api\KeyRequest;
use Jauntin\CyberSource\Api\KeyResponse;
use Jauntin\CyberSource\Api\KeyService;
use Jauntin\CyberSource\Tests\TestCase;
use Mockery\MockInterface;

class KeyServiceTest extends TestCase
{
    private array $response;

    private MockInterface|KeyRequest $keyRequest;

    private MockInterface|KeyResponse $keyResponse;

    private MockInterface|KeyResponseAdapter $keyResponseAdapter;

    protected function setUp(): void
    {
        parent::setUp();
        $this->response = ['response' => 'response'];
        $this->keyRequest = $this->mock(KeyRequest::class);
        $this->keyRequest->encryptionType = 'encryptionType';
        $this->keyResponse = $this->mock(KeyResponse::class);
        $this->keyResponseAdapter = $this->mock(KeyResponseAdapter::class);
        $this->mock(RequestHeaders::class, function ($mock) {
            $mock->shouldReceive('generate')
                ->with('/flex/v1/keys?format=legacy', 'post', json_encode($this->keyRequest, JSON_THROW_ON_ERROR))
                ->andReturn(['header' => 'a']);
        });
    }

    public function testGenerateKeyReturnsResponse()
    {
        Http::fake(['*' => Http::response($this->response)]);
        $this->keyResponseAdapter->shouldReceive('fromResponse')->with($this->response)->andReturn($this->keyResponse);
        $this->assertEquals($this->keyResponse, App::make(KeyService::class)->generateKey($this->keyRequest));
    }

    public function testGenerateKeyFailedRequestErrorResponse()
    {
        Http::fake(['*' => Http::response($this->response, 400)]);
        $keyResponse = App::make(KeyService::class)->generateKey($this->keyRequest);

        $this->assertInstanceOf(ErrorResponse::class, $keyResponse);
        $this->assertEquals(400, $keyResponse->statusCode);
    }

    public function testGenerateKeyThrowableErrorResponse()
    {
        Http::fake(['*' => fn ($request) => new RejectedPromise(new ConnectException('Foo', $request->toPsrRequest()))]);
        $errorResponse = $this->mock(ErrorResponse::class, function (MockInterface $mock) {
            $mock->shouldReceive('fromThrowable')->once()->andReturnSelf();
        });
        $this->assertSame($errorResponse, App::make(KeyService::class)->generateKey($this->keyRequest));
    }
}
