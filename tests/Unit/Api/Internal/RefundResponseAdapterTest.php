<?php

namespace Jauntin\CyberSource\Tests\Unit\Api\Internal;

use Illuminate\Support\Facades\App;
use Jauntin\CyberSource\Api\Internal\RefundResponseAdapter;
use Jauntin\CyberSource\Tests\TestCase;

class RefundResponseAdapterTest extends TestCase
{
    public function test_from_response()
    {
        $this->assertMatchesSnapshot(App::make(RefundResponseAdapter::class)->fromResponse([
            'id' => 'id',
            'status' => 'status',
            'reconciliationId' => 'reconciliationId',
            'clientReferenceInformation' => [
                'code' => 'code',
            ],
            'refundAmountDetails' => [
                'refundAmount' => 'refundAmount',
            ],
        ]));
    }
}
