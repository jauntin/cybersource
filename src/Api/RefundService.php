<?php

namespace Jauntin\CyberSource\Api;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Jauntin\CyberSource\Api\Internal\Configuration;
use Jauntin\CyberSource\Api\Internal\RefundRequestAdapter;
use Jauntin\CyberSource\Api\Internal\RefundResponseAdapter;
use Jauntin\CyberSource\Api\Internal\RequestHeaders;
use Throwable;

/**
 * @final
 */
class RefundService
{
    public function __construct(
        private RefundResponseAdapter $refundResponseAdapter,
        private RefundRequestAdapter $refundRequestAdapter,
        private RequestHeaders $requestHeaders,
        private Configuration $configuration,
    ) {
    }

    public function refund(RefundRequest $refundRequest): RefundResponse|ErrorResponse
    {
        $logKey = sprintf('cybersource.refund %s, %s: ', $refundRequest->referenceNumber, $refundRequest->paymentRequestId);
        try {
            Log::info($logKey . 'Begin request');
            $resourcePath = sprintf('/pts/v2/payments/%s/refunds', $refundRequest->paymentRequestId);
            $body = json_encode($this->refundRequestAdapter->fromRefundRequest($refundRequest), JSON_THROW_ON_ERROR);
            $request = Http::withHeaders($this->requestHeaders->generate($resourcePath, RequestHeaders::METHOD_POST, $body));
            $request->withBody($body);
            $response = $request->post('https://' . $this->configuration->host . $resourcePath);
            if ($response->successful()) {
                Log::info($logKey . 'Request successful');
            } else {
                Log::info($logKey . 'Request failed', [
                    'request' => (array) $refundRequest,
                    'response' => ['status' => $response->status(), 'body' => $response->body()]
                ]);
            }
            return $this->refundResponseAdapter->fromResponse($response->json());
        } catch (Throwable $e) {
            Log::error($logKey . 'Request failed', [
                'request' => (array) $refundRequest,
                'error' => ['message' => $e->getMessage()],
            ]);
            return App::make(ErrorResponse::class)->fromThrowable($e);
        }
    }
}
