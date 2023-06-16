<?php

namespace Jauntin\CyberSource\Tests\Unit\Api;

use CyberSource\Model\CreatePaymentRequest;
use Illuminate\Support\Facades\App;
use Jauntin\CyberSource\Api\PaymentRequest;
use Jauntin\CyberSource\Api\PaymentRequestAdapter;
use Mockery\MockInterface;
use Jauntin\CyberSource\Tests\TestCase;

class PaymentRequestAdapterTest extends TestCase
{
    private PaymentRequest $paymentRequest;
    protected function setUp(): void
    {
        parent::setUp();
        $this->paymentRequest = $this->mock(PaymentRequest::class, function (MockInterface $mock) {
            foreach (get_class_vars(PaymentRequest::class) as $k => $_) {
                $mock->$k = $k;
            }
        });
    }
    /**
     * @dataProvider fromPaymentRequestDataProvider
     */
    public function testFromPaymentRequest($testDecline, $testInvalidData, $fn)
    {
        $result = App::make(PaymentRequestAdapter::class)->fromPaymentRequest($this->paymentRequest, $testDecline, $testInvalidData);
        $fn($this, $this->paymentRequest, $result);
    }
    public static function fromPaymentRequestDataProvider(): array
    {
        return [
            'normal' => [false, false, function (self $that, PaymentRequest $paymentRequest, CreatePaymentRequest $createPaymentRequest) {
                $that->assertMatchesSnapshot($createPaymentRequest);
                $that->assertEquals($paymentRequest->creditCardToken, $createPaymentRequest->getPaymentInformation()->getCustomer()->getCustomerId());
                $that->assertNull($createPaymentRequest->getPaymentInformation()->getCard());
            }],
            'testDecline' => [true, false, function (self $that, PaymentRequest $paymentRequest, CreatePaymentRequest $createPaymentRequest) {
                $that->assertMatchesSnapshot($createPaymentRequest);
                $that->assertEquals('42423482938483873', $createPaymentRequest->getPaymentInformation()->getCard()->getNumber());
            }],
            'testInvalidData' => [false, true, function (self $that, PaymentRequest $paymentRequest, CreatePaymentRequest $createPaymentRequest) {
                $that->assertMatchesSnapshot($createPaymentRequest);
                $that->assertEquals('13', $createPaymentRequest->getPaymentInformation()->getCard()->getExpirationMonth());
            }],
        ];
    }
}
