<?php

namespace Jauntin\CyberSource\Api\Internal;

use Jauntin\CyberSource\Api\RefundRequest;

/**
 * @final
 *
 * @internal
 */
class RefundRequestAdapter
{
    /**
     * @return array{'clientReferenceInformation': array{'code': string}, 'orderInformation': array{'amountDetails': array{'totalAmount': string, 'currency': string}}, 'paymentInformation'?: array{'card': array{'expirationMonth': string }}}
     */
    public function fromRefundRequest(RefundRequest $refundRequest, bool $testInvalidData = false): array
    {
        $request = [
            'clientReferenceInformation' => [
                'code' => $refundRequest->referenceNumber,
            ],
            'orderInformation' => [
                'amountDetails' => [
                    'totalAmount' => $refundRequest->totalAmount,
                    'currency' => $refundRequest->currency,
                ],
            ],
        ];
        if ($testInvalidData) {
            $request['paymentInformation'] = [
                'card' => [
                    'expirationMonth' => '13',
                ],
            ];
        }

        return $request;
    }
}
