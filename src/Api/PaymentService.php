<?php

namespace Jauntin\CyberSource\Api;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Jauntin\CyberSource\Api\Internal\Configuration;
use Jauntin\CyberSource\Api\Internal\PaymentRequestAdapter;
use Jauntin\CyberSource\Api\Internal\PaymentResponseAdapter;
use Jauntin\CyberSource\Api\Internal\RequestHeaders;
use Throwable;

/**
 * @final
 */
class PaymentService
{
    public function __construct(
        private PaymentResponseAdapter $paymentResponseAdapter,
        private PaymentRequestAdapter $paymentRequestAdapter,
        private RequestHeaders $requestHeaders,
        private Configuration $configuration,
    ) {
    }

    public function pay(PaymentRequest $paymentRequest): PaymentResponse|ErrorResponse
    {
        $logKey = sprintf('cybersource.pay %s: ', $paymentRequest->referenceNumber);
        try {
            Log::info($logKey.'Begin request');
            $resourcePath = '/pts/v2/payments';
            $body = json_encode($this->paymentRequestAdapter->fromPaymentRequest($paymentRequest), JSON_THROW_ON_ERROR);
            $request = Http::withHeaders($this->requestHeaders->generate($resourcePath, RequestHeaders::METHOD_POST, $body));
            $request->withBody($body);
            $response = $request->post('https://'.$this->configuration->host.$resourcePath);
            if ($response->successful()) {
                Log::info($logKey.'Request successful');
            } else {
                Log::info($logKey.'Request failed', [
                    'request' => (array) $paymentRequest,
                    'response' => ['status' => $response->status(), 'body' => $response->body()],
                ]);
            }

            return $this->paymentResponseAdapter->fromResponse($response->json());
        } catch (Throwable $e) {
            Log::error($logKey.'Request failed', [
                'request' => (array) $paymentRequest,
                'error' => ['message' => $e->getMessage()],
            ]);

            return App::make(ErrorResponse::class)->fromThrowable($e);
        }
    }
}
