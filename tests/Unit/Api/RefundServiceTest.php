<?php

namespace Jauntin\CyberSource\Tests\Unit\Api;

use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Promise\RejectedPromise;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Http;
use Jauntin\CyberSource\Api\ErrorResponse;
use Jauntin\CyberSource\Api\Internal\RefundRequestAdapter;
use Jauntin\CyberSource\Api\Internal\RefundResponseAdapter;
use Jauntin\CyberSource\Api\Internal\RequestHeaders;
use Jauntin\CyberSource\Api\RefundRequest;
use Jauntin\CyberSource\Api\RefundResponse;
use Jauntin\CyberSource\Api\RefundService;
use Jauntin\CyberSource\Tests\TestCase;
use Mockery\MockInterface;

class RefundServiceTest extends TestCase
{
    private array $body;
    private array $response;
    private MockInterface|RefundRequest $refundRequest;
    private MockInterface|RefundRequestAdapter $refundRequestAdapter;
    private MockInterface|RefundResponse $refundResponse;
    private MockInterface|RefundResponseAdapter $refundResponseAdapter;
    protected function setUp(): void
    {
        parent::setUp();
        $this->body = ['a' => 'b'];
        $this->response = ['response' => 'response'];
        $this->refundRequest = $this->mock(RefundRequest::class);
        $this->refundRequest->paymentRequestId = 'paymentRequestId';
        $this->refundRequest->referenceNumber = 'referenceNumber';
        $this->refundRequestAdapter = $this->mock(RefundRequestAdapter::class);
        $this->refundResponse = $this->mock(RefundResponse::class);
        $this->refundResponseAdapter = $this->mock(RefundResponseAdapter::class);
        $this->mock(RequestHeaders::class, function ($mock) {
            $mock->shouldReceive('generate')
                ->with('/pts/v2/payments/paymentRequestId/refunds', 'post', json_encode($this->body, JSON_THROW_ON_ERROR))
                ->andReturn(['header' => 'a']);
        });
    }
    public function testRefund()
    {
        Http::fake(['*' => Http::response($this->response)]);
        $this->refundRequestAdapter->shouldReceive('fromRefundRequest')->with($this->refundRequest)->andReturn($this->body);
        $this->refundResponseAdapter->shouldReceive('fromResponse')->with($this->response)->andReturn($this->refundResponse);
        $this->assertEquals($this->refundResponse, App::make(RefundService::class)->refund($this->refundRequest));
    }

    public function testRefundFailedRequestErrorResponse()
    {
        Http::fake(['*' => Http::response(['response' => 'response'], 400)]);
        $this->refundRequestAdapter->shouldReceive('fromRefundRequest')->with($this->refundRequest)->andReturn($this->body);
        $this->refundResponseAdapter->shouldReceive('fromResponse')->with($this->response)->andReturn($this->refundResponse);
        $this->assertEquals($this->refundResponse, App::make(RefundService::class)->refund($this->refundRequest));
    }

    public function testRefundThrowableErrorResponse()
    {
        Http::fake(['*' => fn ($request) => new RejectedPromise(new ConnectException('Foo', $request->toPsrRequest()))]);
        $errorResponse = $this->mock(ErrorResponse::class, function (MockInterface $mock) {
            $mock->shouldReceive('fromThrowable')->once()->andReturnSelf();
        });

        $this->assertSame($errorResponse, App::make(RefundService::class)->refund($this->refundRequest));
    }
}
