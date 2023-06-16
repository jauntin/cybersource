<?php

namespace Jauntin\CyberSource\Tests\Unit\Api;

use CyberSource\Model\RefundPaymentRequest;
use Illuminate\Support\Facades\App;
use Jauntin\CyberSource\Api\RefundRequest;
use Jauntin\CyberSource\Api\RefundRequestAdapter;
use Mockery\MockInterface;
use Jauntin\CyberSource\Tests\TestCase;

class RefundRequestAdapterTest extends TestCase
{
    private RefundRequest $refundRequest;
    protected function setUp(): void
    {
        parent::setUp();
        $this->refundRequest = $this->mock(RefundRequest::class, function (MockInterface $mock) {
            foreach (get_class_vars(RefundRequest::class) as $k => $_) {
                $mock->$k = $k;
            }
        });
    }
    /**
     * @dataProvider fromRefundRequestDataProvider
     */
    public function testFromRefundRequest($testInvalidData, $fn)
    {
        $result = App::make(RefundRequestAdapter::class)->fromRefundRequest($this->refundRequest, $testInvalidData);
        $fn($this, $this->refundRequest, $result);
    }
    public static function fromRefundRequestDataProvider(): array
    {
        return [
            'normal' => [false, function (self $that, RefundRequest $refundRequest, RefundPaymentRequest $refundPaymentRequest) {
                $that->assertNull($refundPaymentRequest->getPaymentInformation());
                $that->assertMatchesSnapshot($refundPaymentRequest);
            }],
            'testInvalidData' => [true, function (self $that, RefundRequest $refundRequest, RefundPaymentRequest $refundPaymentRequest) {
                $that->assertEquals('13', $refundPaymentRequest->getPaymentInformation()->getCard()->getExpirationMonth());
                $that->assertMatchesSnapshot($refundPaymentRequest);
            }],
        ];
    }
}
