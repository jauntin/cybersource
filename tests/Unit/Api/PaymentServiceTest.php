<?php

namespace Jauntin\CyberSource\Tests\Unit\Api;

use CyberSource\Api\PaymentsApi;
use CyberSource\Model\CreatePaymentRequest;
use CyberSource\Model\PtsV2PaymentsPost201Response;
use Exception;
use Illuminate\Support\Facades\App;
use Jauntin\CyberSource\Api\ErrorResponse;
use Jauntin\CyberSource\Api\PaymentRequest;
use Jauntin\CyberSource\Api\PaymentRequestAdapter;
use Jauntin\CyberSource\Api\PaymentResponse;
use Jauntin\CyberSource\Api\PaymentService;
use Mockery\MockInterface;
use Jauntin\CyberSource\Tests\TestCase;

class PaymentServiceTest extends TestCase
{
    public function testPay()
    {
        $cybersourceResponse = $this->mock(PtsV2PaymentsPost201Response::class);
        $createPaymentRequest = $this->mock(CreatePaymentRequest::class);
        $response = $this->mock(PaymentResponse::class);
        $paymentRequest = $this->mock(PaymentRequest::class);

        $this->mock(
            PaymentRequestAdapter::class,
            function (MockInterface $mock) use ($paymentRequest, $createPaymentRequest) {
                $mock->shouldReceive('fromPaymentRequest')
                    ->with($paymentRequest, false, false)
                    ->once()
                    ->andReturn($createPaymentRequest);
            }
        );
        $this->mock(PaymentsApi::class, function (MockInterface $mock) use ($createPaymentRequest, $cybersourceResponse) {
            $mock->shouldReceive('createPayment')->with($createPaymentRequest)->andReturn([$cybersourceResponse]);
        });
        $this->mock(PaymentResponse::class, function (MockInterface $mock) use ($cybersourceResponse, $response) {
            $mock->shouldReceive('fromResponse')->with($cybersourceResponse)->once()->andReturn($response);
        });


        $this->assertSame($response, App::make(PaymentService::class)->pay($paymentRequest));
    }

    public function testPayThrowableErrorResponse()
    {
        $e = new Exception();
        $this->mock(PaymentRequestAdapter::class, function (MockInterface $mock) use ($e) {
            $mock->shouldReceive('fromPaymentRequest')->andThrow($e);
        });
        $this->mock(ErrorResponse::class, function (MockInterface $mock) use ($e) {
            $mock->shouldReceive('fromThrowable')->with($e)->once();
        });

        App::make(PaymentService::class)->pay($this->mock(PaymentRequest::class));
    }
}
