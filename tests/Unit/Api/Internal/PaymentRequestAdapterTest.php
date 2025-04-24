<?php

namespace Jauntin\CyberSource\Tests\Unit\Api\Internal;

use Jauntin\CyberSource\Api\Internal\PaymentRequestAdapter;
use Jauntin\CyberSource\Api\PaymentRequest;
use Jauntin\CyberSource\Tests\TestCase;
use Mockery\MockInterface;
use PHPUnit\Framework\Attributes\DataProvider;

class PaymentRequestAdapterTest extends TestCase
{
    private PaymentRequest&MockInterface $paymentRequest;

    protected function setUp(): void
    {
        parent::setUp();
        $this->paymentRequest = $this->mock(PaymentRequest::class, function (MockInterface $mock) {
            foreach (get_class_vars(PaymentRequest::class) as $k => $_) {
                $mock->$k = $k;
            }
        });
    }

    #[DataProvider('fromPaymentRequestDataProvider')]
    public function test_from_payment_request($testDecline, $testInvalidData, $fn)
    {
        $fn($this, (new PaymentRequestAdapter($testDecline, $testInvalidData))->fromPaymentRequest($this->paymentRequest, $testDecline, $testInvalidData));
    }

    public static function fromPaymentRequestDataProvider(): array
    {
        return [
            'normal' => [false, false, function (self $that, array $createPaymentRequest) {
                $that->assertMatchesSnapshot($createPaymentRequest);
            }],
            'testDecline' => [true, false, function (self $that, array $createPaymentRequest) {
                $that->assertMatchesSnapshot($createPaymentRequest);
                $that->assertEquals('42423482938483873', $createPaymentRequest['paymentInformation']['card']['number']);
            }],
            'testInvalidData' => [false, true, function (self $that, array $createPaymentRequest) {
                $that->assertMatchesSnapshot($createPaymentRequest);
                $that->assertEquals('13', $createPaymentRequest['paymentInformation']['card']['expirationMonth']);
            }],
        ];
    }
}
