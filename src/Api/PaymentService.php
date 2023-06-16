<?php

namespace Jauntin\CyberSource\Api;

use CyberSource\Api\PaymentsApi;
use Illuminate\Support\Facades\App;
use Throwable;

/**
 * @final
 */
class PaymentService
{
    public function __construct(
        private PaymentsApi $api,
        private PaymentResponse $paymentResponse,
        private PaymentRequestAdapter $paymentRequestAdapter,
        public bool $testDecline = false,
        public bool $testInvalidData = false,
    ) {
    }
    public function pay(PaymentRequest $paymentRequest): PaymentResponse|ErrorResponse
    {
        try {
            $createPaymentRequest = $this->paymentRequestAdapter->fromPaymentRequest($paymentRequest, $this->testDecline, $this->testInvalidData);
            return $this->paymentResponse->fromResponse($this->api->createPayment($createPaymentRequest)[0]);
        } catch (Throwable $e) {
            return App::make(ErrorResponse::class)->fromThrowable($e);
        }
    }
}
