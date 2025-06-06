<?php

namespace Jauntin\CyberSource\Api\Internal;

use Jauntin\CyberSource\Api\PaymentRequest;

/**
 * @final
 *
 * @internal
 */
class PaymentRequestAdapter
{
    /**
     * @return array{
     *   'clientReferenceInformation': array{'code': string},
     *   'processingInformation': array{'capture': bool, 'commerceIndicator': string},
     *   'paymentInformation': mixed,
     *   'orderInformation': array{'amountDetails': array{'totalAmount': string, 'currency': string}, 'billTo': array{'firstName': string, 'lastName': string, 'company': string, 'address1': string, 'locality': string, 'administrativeArea': string, 'postalCode': string, 'country': string}}
     * }
     */
    public function fromPaymentRequest(PaymentRequest $paymentRequest, bool $testDecline = false, bool $testInvalidData = false): array
    {
        return [
            'clientReferenceInformation' => [
                'code' => $paymentRequest->referenceNumber,
            ],
            'processingInformation' => [
                'capture' => true,
                'commerceIndicator' => 'internet',
            ],
            'paymentInformation' => array_merge(
                $testDecline ? ['card' => ['number' => '42423482938483873']] : [],
                $testInvalidData ? ['card' => ['expirationMonth' => '13']] : [],
                [
                    'customer' => [
                        'customerId' => $paymentRequest->creditCardToken,
                    ],
                ]
            ),
            'orderInformation' => [
                'amountDetails' => [
                    'totalAmount' => $paymentRequest->totalAmount,
                    'currency' => $paymentRequest->currency,
                ],
                'billTo' => [
                    'firstName' => $paymentRequest->firstName,
                    'lastName' => $paymentRequest->lastName,
                    'company' => $paymentRequest->companyName,
                    'address1' => $paymentRequest->address1,
                    'locality' => $paymentRequest->city,
                    'administrativeArea' => $paymentRequest->state,
                    'postalCode' => $paymentRequest->zip,
                    'country' => $paymentRequest->country,
                ],
            ],
        ];
    }
}
