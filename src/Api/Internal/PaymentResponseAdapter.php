<?php

namespace Jauntin\CyberSource\Api\Internal;

use Jauntin\CyberSource\Api\PaymentResponse;

/**
 * @final
 *
 * @internal
 */
class PaymentResponseAdapter
{
    /**
     * @param array{'id': string,
     * 'status': string,
     * 'reconciliationId'?: string,
     * 'clientReferenceInformation'?: array{'code': string},
     * 'processorInformation'?: array{'approvalCode': string},
     * 'orderInformation'?: array{'amountDetails': array{'totalAmount': string }}
     * } $response
     */
    public function fromResponse(array $response): PaymentResponse
    {
        $paymentResponse = new PaymentResponse();
        $r = json_decode(json_encode($response, JSON_THROW_ON_ERROR), false);
        $paymentResponse->id = $r->id ?? '';
        $paymentResponse->status = $r->status ?? '';
        $paymentResponse->reconciliationId = $r->reconciliationId ?? null;
        $paymentResponse->orderReferenceNumber = $r->clientReferenceInformation->code ?? null;
        $paymentResponse->approvalCode = $r->processorInformation->approvalCode ?? null;
        $paymentResponse->amount = $r->orderInformation->amountDetails->totalAmount ?? null;
        $paymentResponse->remoteResponse = $response;

        return $paymentResponse;
    }
}
