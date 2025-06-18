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
     *   clientReferenceInformation: array{'code': string},
     *   processingInformation: array{'capture': bool, 'commerceIndicator': string},
     *   orderInformation: array{'amountDetails': array{'totalAmount': string, 'currency': string}, 'billTo': array{'firstName': string, 'lastName': string, 'company': string, 'address1': string, 'locality': string, 'administrativeArea': string, 'postalCode': string, 'country': string}},
     *   paymentInformation?: array<array-key, mixed>,
     *   tokenInformation?: array{'transientTokenJwt': string},
     * }
     */
    public function fromPaymentRequest(PaymentRequest $paymentRequest, bool $testDecline = false, bool $testInvalidData = false): array
    {
        $request = [];
        if (isset($paymentRequest->creditCardToken)) {
            $request['paymentInformation'] = array_merge(
                $testDecline ? ['card' => ['number' => '42423482938483873']] : [],
                $testInvalidData ? ['card' => ['expirationMonth' => '13']] : [],
                [
                    'customer' => [
                        'customerId' => $paymentRequest->creditCardToken,
                    ],
                ]
            );
        }
        if (isset($paymentRequest->transientTokenJwt)) {
            $request['tokenInformation'] = [
                'transientTokenJwt' => $testDecline || $testInvalidData ? 'test' : $paymentRequest->transientTokenJwt,
            ];
        }

        return array_merge($request, [
            'clientReferenceInformation' => [
                'code' => $paymentRequest->referenceNumber,
            ],
            'processingInformation' => [
                'capture' => true,
                'commerceIndicator' => 'internet',
            ],
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
        ]);
    }
}
