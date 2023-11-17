<?php

namespace Jauntin\CyberSource\Api\Internal;

use Jauntin\CyberSource\Api\RefundRequest;

/**
 * @final
 * @internal
 */
class RefundRequestAdapter
{
    public function __construct(private bool $testInvalidData = false)
    {
    }

    /**
     *
     * @return array{'clientReferenceInformation': array{'code': string}, 'orderInformation': array{'amountDetails': array{'totalAmount': string, 'currency': string}}, 'paymentInformation'?: array{'card': array{'expirationMonth': string }}}
     */
    public function fromRefundRequest(RefundRequest $refundRequest): array
    {
        $request = [
            'clientReferenceInformation' => [
                'code' => $refundRequest->referenceNumber,
            ],
            'orderInformation' => [
                'amountDetails' => [
                    'totalAmount' => $refundRequest->totalAmount,
                    'currency' => $refundRequest->currency,
                ]
            ],
        ];
        if ($this->testInvalidData) {
            $request['paymentInformation'] = [
                'card' => [
                    'expirationMonth' => '13'
                ]
            ];
        }
        return $request;
    }
}
