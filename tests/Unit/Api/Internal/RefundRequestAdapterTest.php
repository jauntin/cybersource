<?php

namespace Jauntin\CyberSource\Tests\Unit\Api\Internal;

use Jauntin\CyberSource\Api\Internal\RefundRequestAdapter;
use Jauntin\CyberSource\Api\RefundRequest;
use Jauntin\CyberSource\Tests\TestCase;
use Mockery\MockInterface;
use PHPUnit\Framework\Attributes\DataProvider;

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

    #[DataProvider('fromRefundRequestDataProvider')]
    public function testFromRefundRequest($testInvalidData, $fn)
    {
        $fn($this, (new RefundRequestAdapter($testInvalidData))->fromRefundRequest($this->refundRequest, $testInvalidData));
    }

    public static function fromRefundRequestDataProvider(): array
    {
        return [
            'normal' => [false, function (self $that, array $refundPaymentRequest) {
                $that->assertMatchesSnapshot($refundPaymentRequest);
            }],
            'testInvalidData' => [true, function (self $that, array $refundPaymentRequest) {
                $that->assertEquals('13', $refundPaymentRequest['paymentInformation']['card']['expirationMonth']);
                $that->assertMatchesSnapshot($refundPaymentRequest);
            }],
        ];
    }
}
