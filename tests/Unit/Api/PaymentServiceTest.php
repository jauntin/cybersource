<?php

namespace Jauntin\CyberSource\Tests\Unit\Api;

use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Promise\RejectedPromise;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Http;
use Jauntin\CyberSource\Api\ErrorResponse;
use Jauntin\CyberSource\Api\Internal\PaymentRequestAdapter;
use Jauntin\CyberSource\Api\Internal\PaymentResponseAdapter;
use Jauntin\CyberSource\Api\Internal\RequestHeaders;
use Jauntin\CyberSource\Api\PaymentRequest;
use Jauntin\CyberSource\Api\PaymentResponse;
use Jauntin\CyberSource\Api\PaymentService;
use Jauntin\CyberSource\Tests\TestCase;
use Mockery\MockInterface;

class PaymentServiceTest extends TestCase
{
    private array $body;

    private array $response;

    private MockInterface|PaymentRequest $paymentRequest;

    private MockInterface|PaymentRequestAdapter $paymentRequestAdapter;

    private MockInterface|PaymentResponse $paymentResponse;

    private MockInterface|PaymentResponseAdapter $paymentResponseAdapter;

    protected function setUp(): void
    {
        parent::setUp();
        $this->body = ['a' => 'b'];
        $this->response = ['response' => 'response'];
        $this->paymentRequest = $this->mock(PaymentRequest::class);
        $this->paymentRequest->referenceNumber = 'referenceNumber';
        $this->paymentRequestAdapter = $this->mock(PaymentRequestAdapter::class);
        $this->paymentResponse = $this->mock(PaymentResponse::class);
        $this->paymentResponseAdapter = $this->mock(PaymentResponseAdapter::class);
        $this->mock(RequestHeaders::class, function ($mock) {
            $mock->shouldReceive('generate')
                ->with('/pts/v2/payments', 'post', json_encode($this->body, JSON_THROW_ON_ERROR))
                ->andReturn(['header' => 'a']);
        });
    }

    public function test_pay()
    {
        Http::fake(['*' => Http::response($this->response)]);
        $this->paymentRequestAdapter->shouldReceive('fromPaymentRequest')->with($this->paymentRequest, false, false)->andReturn($this->body);
        $this->paymentResponseAdapter->shouldReceive('fromResponse')->with($this->response)->andReturn($this->paymentResponse);
        $this->assertEquals($this->paymentResponse, App::make(PaymentService::class)->pay($this->paymentRequest));
    }

    public function test_pay_failed_request_error_response()
    {
        Http::fake(['*' => Http::response(['response' => 'response'], 400)]);
        $this->paymentRequestAdapter->shouldReceive('fromPaymentRequest')->with($this->paymentRequest, false, false)->andReturn($this->body);
        $this->paymentResponseAdapter->shouldReceive('fromResponse')->with($this->response)->andReturn($this->paymentResponse);
        $this->assertEquals($this->paymentResponse, App::make(PaymentService::class)->pay($this->paymentRequest));
    }

    public function test_pay_throwable_error_response()
    {
        Http::fake(['*' => fn ($request) => new RejectedPromise(new ConnectException('Foo', $request->toPsrRequest()))]);
        $errorResponse = $this->mock(ErrorResponse::class, function (MockInterface $mock) {
            $mock->shouldReceive('fromThrowable')->once()->andReturnSelf();
        });

        $this->assertSame($errorResponse, App::make(PaymentService::class)->pay($this->paymentRequest));
    }
}
