<?php

namespace Jauntin\CyberSource\Api;

use CyberSource\Model\Ptsv2paymentsidcapturesOrderInformationAmountDetails;
use CyberSource\Model\Ptsv2paymentsidrefundsClientReferenceInformation;
use CyberSource\Model\Ptsv2paymentsidrefundsOrderInformation;
use CyberSource\Model\Ptsv2paymentsidrefundsPaymentInformation;
use CyberSource\Model\Ptsv2paymentsidrefundsPaymentInformationCard;
use CyberSource\Model\RefundPaymentRequest;
use Illuminate\Support\Facades\App;

/**
 * @final
 * @internal
 */
class RefundRequestAdapter
{
    public function fromRefundRequest(RefundRequest $refundRequest, bool $testInvalidData = false): RefundPaymentRequest
    {
        /** @var RefundPaymentRequest */
        $refundPaymentRequest = App::make(RefundPaymentRequest::class);
        $refundPaymentRequest->setClientReferenceInformation(new Ptsv2paymentsidrefundsClientReferenceInformation([
            "code" => $refundRequest->referenceNumber
        ]));

        if ($testInvalidData) {
            $refundPaymentRequest->setPaymentInformation(new Ptsv2paymentsidrefundsPaymentInformation([
                'card' => new Ptsv2paymentsidrefundsPaymentInformationCard([
                    'expirationMonth' => '13'
                ]),
            ]));
        }

        $refundPaymentRequest->setOrderInformation(new Ptsv2paymentsidrefundsOrderInformation([
            "amountDetails" => new Ptsv2paymentsidcapturesOrderInformationAmountDetails([
                "totalAmount" => $refundRequest->totalAmount,
                "currency" => $refundRequest->currency
            ])
        ]));

        return $refundPaymentRequest;
    }
}
