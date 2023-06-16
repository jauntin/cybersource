<?php

namespace Jauntin\CyberSource\Tests\Unit\Api;

use CyberSource\Api\RefundApi;
use CyberSource\Model\PtsV2PaymentsRefundPost201Response;
use CyberSource\Model\RefundPaymentRequest;
use Exception;
use Illuminate\Support\Facades\App;
use Jauntin\CyberSource\Api\ErrorResponse;
use Jauntin\CyberSource\Api\RefundRequest;
use Jauntin\CyberSource\Api\RefundRequestAdapter;
use Jauntin\CyberSource\Api\RefundResponse;
use Jauntin\CyberSource\Api\RefundService;
use Mockery\MockInterface;
use Jauntin\CyberSource\Tests\TestCase;

class RefundServiceTest extends TestCase
{
    public function testRefund()
    {
        $cybersourceResponse = $this->mock(PtsV2PaymentsRefundPost201Response::class);
        $refundPaymentRequest = $this->mock(RefundPaymentRequest::class);
        $response = $this->mock(RefundResponse::class);
        $refundRequest = $this->mock(RefundRequest::class);
        $refundRequest->paymentRequestId = 'paymentRequestId';

        $this->mock(
            RefundRequestAdapter::class,
            function (MockInterface $mock) use ($refundRequest, $refundPaymentRequest) {
                $mock->shouldReceive('fromRefundRequest')
                    ->with($refundRequest, false)
                    ->once()
                    ->andReturn($refundPaymentRequest);
            }
        );
        $this->mock(RefundApi::class, function (MockInterface $mock) use ($refundPaymentRequest, $refundRequest, $cybersourceResponse) {
            $mock->shouldReceive('refundPayment')->with($refundPaymentRequest, $refundRequest->paymentRequestId)->andReturn([$cybersourceResponse]);
        });
        $this->mock(RefundResponse::class, function (MockInterface $mock) use ($cybersourceResponse, $response) {
            $mock->shouldReceive('fromResponse')->with($cybersourceResponse)->once()->andReturn($response);
        });

        $this->assertSame($response, App::make(RefundService::class)->refund($refundRequest));
    }

    public function testRefundThrowableErrorResponse()
    {
        $e = new Exception();
        $this->mock(RefundRequestAdapter::class, function (MockInterface $mock) use ($e) {
            $mock->shouldReceive('fromRefundRequest')->andThrow($e);
        });
        $this->mock(ErrorResponse::class, function (MockInterface $mock) use ($e) {
            $mock->shouldReceive('fromThrowable')->with($e)->once();
        });

        App::make(RefundService::class)->refund($this->mock(RefundRequest::class));
    }
}
