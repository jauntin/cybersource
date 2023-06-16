<?php

namespace Jauntin\CyberSource\Api;

use CyberSource\Api\RefundApi;
use Illuminate\Support\Facades\App;
use Throwable;

/**
 * @final
 */
class RefundService
{
    public function __construct(
        private RefundApi $refundApi,
        private RefundResponse $refundResponse,
        private RefundRequestAdapter $refundRequestAdapter,
        public bool $testInvalidData = false,
    ) {
    }

    public function refund(RefundRequest $refundRequest): RefundResponse|ErrorResponse
    {
        try {
            $refundPaymentRequest = $this->refundRequestAdapter->fromRefundRequest($refundRequest, $this->testInvalidData);
            return $this->refundResponse->fromResponse(
                $this->refundApi->refundPayment($refundPaymentRequest, $refundRequest->paymentRequestId)[0]
            );
        } catch (Throwable $e) {
            return App::make(ErrorResponse::class)->fromThrowable($e);
        }
    }
}
