<?php

namespace Jauntin\CyberSource\Api;

use CyberSource\Model\CreatePaymentRequest;
use CyberSource\Model\Ptsv2paymentsClientReferenceInformation;
use CyberSource\Model\Ptsv2paymentsOrderInformation;
use CyberSource\Model\Ptsv2paymentsOrderInformationAmountDetails;
use CyberSource\Model\Ptsv2paymentsOrderInformationBillTo;
use CyberSource\Model\Ptsv2paymentsPaymentInformation;
use CyberSource\Model\Ptsv2paymentsPaymentInformationCard;
use CyberSource\Model\Ptsv2paymentsPaymentInformationCustomer;
use CyberSource\Model\Ptsv2paymentsProcessingInformation;
use Illuminate\Support\Facades\App;

/**
 * @final
 * @internal
 */
class PaymentRequestAdapter
{
    public function fromPaymentRequest(PaymentRequest $paymentRequest, bool $testDecline = false, bool $testInvalidData = false): CreatePaymentRequest
    {
        /** @var CreatePaymentRequest */
        $createPaymentRequest = App::make(CreatePaymentRequest::class);
        $createPaymentRequest->setClientReferenceInformation(new Ptsv2paymentsClientReferenceInformation([
            "code" => $paymentRequest->referenceNumber,
        ]));
        $createPaymentRequest->setOrderInformation(new Ptsv2paymentsOrderInformation([
            "amountDetails" => new Ptsv2paymentsOrderInformationAmountDetails([
                "totalAmount" => $paymentRequest->totalAmount,
                "currency" => $paymentRequest->currency
            ]),
            "billTo" => new Ptsv2paymentsOrderInformationBillTo([
                "address1" => $paymentRequest->address1,
                "postalCode" => $paymentRequest->zip,
                "locality" => $paymentRequest->city,
                "administrativeArea" => $paymentRequest->state,
                "country" => $paymentRequest->country,
                "firstName" => $paymentRequest->firstName,
                "lastName" => $paymentRequest->lastName,
                "company" => $paymentRequest->companyName,
            ]),
        ]));
        $createPaymentRequest->setPaymentInformation(new Ptsv2paymentsPaymentInformation(array_merge(
            $testDecline ? ["card" => new Ptsv2paymentsPaymentInformationCard([
                'number' => '42423482938483873',
            ]),] : [],
            $testInvalidData ? ["card" => new Ptsv2paymentsPaymentInformationCard([
                'expirationMonth' => '13',
            ]),] : [],
            [
                "customer" => new Ptsv2paymentsPaymentInformationCustomer([
                    "customerId" => $paymentRequest->creditCardToken
                ])
            ]
        )));
        $createPaymentRequest->setProcessingInformation(new Ptsv2paymentsProcessingInformation([
            "capture" => true,
            "commerceIndicator" => "internet"
        ]));
        return $createPaymentRequest;
    }
}
