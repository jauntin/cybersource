<?php

namespace Jauntin\CyberSource\Tests\Unit\Api;

use CyberSource\Model\PtsV2PaymentsPost201Response;
use CyberSource\Model\PtsV2PaymentsPost201ResponseClientReferenceInformation;
use CyberSource\Model\PtsV2PaymentsPost201ResponseOrderInformation;
use CyberSource\Model\PtsV2PaymentsPost201ResponseOrderInformationAmountDetails;
use CyberSource\Model\PtsV2PaymentsPost201ResponseProcessorInformation;
use Illuminate\Support\Facades\App;
use Jauntin\CyberSource\Api\PaymentResponse;
use Mockery\MockInterface;
use Jauntin\CyberSource\Tests\TestCase;

class PaymentResponseTest extends TestCase
{
    /**
     * @dataProvider fromResponseDataProvider
     */
    public function testFromResponse($cybersourceResponse)
    {
        $result = App::make(PaymentResponse::class)->fromResponse($cybersourceResponse);
        $this->assertInstanceOf(PaymentResponse::class, $result);
        $this->assertMatchesSnapshot($result);
    }

    public static function fromResponseDataProvider()
    {
        return [
            'successfulResponse' => [new PtsV2PaymentsPost201Response([
                'id' => 'id',
                'status' => 'status',
                'clientReferenceInformation' => new PtsV2PaymentsPost201ResponseClientReferenceInformation([
                    'code' => 'code',
                ]),
                'processorInformation' => new PtsV2PaymentsPost201ResponseProcessorInformation([
                    'approvalCode' => 'approvalCode',
                ]),
                'reconciliationId' => 'reconciliationId',
                'orderInformation' => new PtsV2PaymentsPost201ResponseOrderInformation([
                    'amountDetails' => new PtsV2PaymentsPost201ResponseOrderInformationAmountDetails([
                        'totalAmount' => 'totalAmount',
                    ])
                ])
            ])],
            'failureResponse' => [
                new PtsV2PaymentsPost201Response([
                    'id' => 'id',
                    'status' => 'status',
                ])
            ]
        ];
    }
}
