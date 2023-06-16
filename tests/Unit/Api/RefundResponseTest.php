<?php

namespace Jauntin\CyberSource\Tests\Unit\Api;

use CyberSource\Model\PtsV2PaymentsRefundPost201Response;
use CyberSource\Model\PtsV2PaymentsRefundPost201ResponseClientReferenceInformation;
use CyberSource\Model\PtsV2PaymentsRefundPost201ResponseRefundAmountDetails;
use Illuminate\Support\Facades\App;
use Jauntin\CyberSource\Api\RefundResponse;
use Jauntin\CyberSource\Tests\TestCase;

class RefundResponseTest extends TestCase
{
    public function testFromResponse()
    {
        $this->assertMatchesSnapshot(App::make(RefundResponse::class)->fromResponse(new PtsV2PaymentsRefundPost201Response([
            'id' => 'id',
            'status' => 'status',
            'clientReferenceInformation' => new PtsV2PaymentsRefundPost201ResponseClientReferenceInformation([
                'code' => 'code',
            ]),
            'reconciliationId' => 'reconciliationId',
            'refundAmountDetails' => new PtsV2PaymentsRefundPost201ResponseRefundAmountDetails([
                'refundAmount' => 'refundAmount',
            ]),
        ])));
    }
}
