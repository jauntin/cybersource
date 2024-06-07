<?php

namespace Jauntin\CyberSource\Api\Internal;

use Jauntin\CyberSource\Api\RefundResponse;

/**
 * @final
 *
 * @internal
 */
class RefundResponseAdapter
{
    /**
     * @param array{ 'id': string,
     * 'status': string,
     * 'clientReferenceInformation'?: array{'code': string},
     * 'reconciliationId': string,
     * 'refundAmountDetails'?: array{'refundAmount': string}
     * } $response
     */
    public function fromResponse(array $response): RefundResponse
    {
        $refundResponse = new RefundResponse();
        $r = json_decode(json_encode($response, JSON_THROW_ON_ERROR), false);
        $refundResponse->id = $r->id ?? '';
        $refundResponse->status = $r->status ?? '';
        $refundResponse->orderReferenceNumber = $r->clientReferenceInformation->code ?? '';
        $refundResponse->reconciliationId = $r->reconciliationId ?? '';
        $refundResponse->refundAmount = $r->refundAmountDetails->refundAmount ?? '';
        $refundResponse->remoteResponse = $response;

        return $refundResponse;
    }
}
