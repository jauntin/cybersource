<?php

namespace Jauntin\CyberSource\Testing;

use Jauntin\CyberSource\Api\ErrorResponse;
use Jauntin\CyberSource\Api\KeyResponse;
use Jauntin\CyberSource\Api\KeyService;
use Jauntin\CyberSource\Api\PaymentResponse;
use Jauntin\CyberSource\Api\PaymentService;
use Jauntin\CyberSource\Api\PaymentStatus;
use Jauntin\CyberSource\Api\RefundResponse;
use Jauntin\CyberSource\Api\RefundService;
use Jauntin\CyberSource\Api\RefundStatus;
use Mockery\MockInterface;

trait MocksPaymentServices
{
    protected MockInterface|PaymentService $paymentService;

    protected MockInterface|RefundService $refundService;

    protected MockInterface|KeyService $keyService;

    protected function mockPaymentServices(): void
    {
        $this->paymentService = $this->mock(PaymentService::class);
        $this->refundService = $this->mock(RefundService::class);
        $this->keyService = $this->mock(KeyService::class);
    }

    protected function mockSuccessfulPayment(): void
    {
        $this->paymentService->shouldReceive('pay')->andReturn($this->paymentServiceSuccessfulResponse());
    }

    protected function mockUnsucessfulPayment(): void
    {
        $this->paymentService->shouldReceive('pay')->andReturn($this->paymentServiceUnsuccessfulResponse());
    }

    protected function mockErrorPayment(): void
    {
        $this->paymentService->shouldReceive('pay')->andReturn($this->errorResponse());
    }

    protected function paymentServiceSuccessfulResponse(array $values = []): PaymentResponse
    {
        return $this->responseAutomapper(new PaymentResponse, array_merge(['status' => PaymentStatus::STATUS_AUTHORIZED, 'remoteResponse' => ['remoteResponse']], $values));
    }

    protected function paymentServiceUnsuccessfulResponse(array $values = []): PaymentResponse
    {
        return $this->responseAutomapper(new PaymentResponse, array_merge(['status' => PaymentStatus::STATUS_DECLINED, 'remoteResponse' => ['remoteResponse']], $values));
    }

    protected function mockSuccessfulRefund(): void
    {
        $this->refundService->shouldReceive('refund')->andReturn($this->refundServiceSuccessfulResponse());
    }

    protected function mockUnsuccessfulRefund(): void
    {
        $this->refundService->shouldReceive('refund')->andReturn($this->errorResponse());
    }

    protected function refundServiceSuccessfulResponse(array $values = []): RefundResponse
    {
        return $this->responseAutomapper(new RefundResponse, array_merge(['status' => RefundStatus::STATUS_PENDING, 'remoteResponse' => ['remoteResponse']], $values));
    }

    protected function errorResponse(array $values = []): ErrorResponse
    {
        return $this->responseAutomapper(new ErrorResponse, array_merge(['statusCode' => 400, 'previous' => new \Exception], $values));
    }

    private function responseAutomapper($response, array $values): PaymentResponse|RefundResponse|KeyResponse|ErrorResponse
    {
        foreach (get_class_vars(get_class($response)) as $k => $_) {
            if (isset($values[$k])) {
                $response->$k = $values[$k];
            } else {
                $response->$k = $k;
            }
        }

        return $response;
    }
}
