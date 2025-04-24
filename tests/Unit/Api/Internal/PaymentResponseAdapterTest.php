<?php

namespace Jauntin\CyberSource\Tests\Unit\Api\Internal;

use Illuminate\Support\Facades\App;
use Jauntin\CyberSource\Api\Internal\PaymentResponseAdapter;
use Jauntin\CyberSource\Api\PaymentResponse;
use Jauntin\CyberSource\Tests\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;

class PaymentResponseAdapterTest extends TestCase
{
    #[DataProvider('fromResponseDataProvider')]
    public function test_from_response($cybersourceResponse)
    {
        $result = App::make(PaymentResponseAdapter::class)->fromResponse($cybersourceResponse);
        $this->assertInstanceOf(PaymentResponse::class, $result);
        $this->assertMatchesSnapshot($result);
    }

    public static function fromResponseDataProvider()
    {
        return [
            'successfulResponse' => [
                [
                    'id' => 'id',
                    'status' => 'status',
                    'clientReferenceInformation' => [
                        'code' => 'code',
                    ],
                    'processorInformation' => [
                        'approvalCode' => 'approvalCode',
                    ],
                    'reconciliationId' => 'reconciliationId',
                    'orderInformation' => [
                        'amountDetails' => [
                            'totalAmount' => 'totalAmount',
                        ],
                    ],
                ],
            ],
            'failureResponse' => [
                [
                    'id' => 'id',
                    'status' => 'status',
                ],
            ],
        ];
    }
}
