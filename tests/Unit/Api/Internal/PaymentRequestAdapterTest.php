<?php

namespace Jauntin\CyberSource\Tests\Unit\Api\Internal;

use Jauntin\CyberSource\Api\Internal\PaymentRequestAdapter;
use Jauntin\CyberSource\Api\PaymentRequest;
use Jauntin\CyberSource\Tests\TestCase;
use Mockery\MockInterface;

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

    public function test_from_payment_request_credit_card_token_normal()
    {
        unset($this->paymentRequest->transientTokenJwt);
        $result = (new PaymentRequestAdapter)->fromPaymentRequest($this->paymentRequest);
        $this->assertArrayHasKey('paymentInformation', $result);
        $this->assertArrayNotHasKey('tokenInformation', $result);
        $this->assertEquals('creditCardToken', $result['paymentInformation']['customer']['customerId']);
        $this->assertMatchesSnapshot($result);
    }

    public function test_from_payment_request_credit_card_token_decline()
    {
        unset($this->paymentRequest->transientTokenJwt);
        $result = (new PaymentRequestAdapter)->fromPaymentRequest($this->paymentRequest, true);
        $this->assertEquals('42423482938483873', $result['paymentInformation']['card']['number']);
        $this->assertMatchesSnapshot($result);
    }

    public function test_from_payment_request_credit_card_token_invalid_data()
    {
        unset($this->paymentRequest->transientTokenJwt);
        $result = (new PaymentRequestAdapter)->fromPaymentRequest($this->paymentRequest, false, true);
        $this->assertEquals('13', $result['paymentInformation']['card']['expirationMonth']);
        $this->assertMatchesSnapshot($result);
    }

    public function test_from_payment_request_with_transient_token_jwt_only()
    {
        unset($this->paymentRequest->creditCardToken);
        $result = (new PaymentRequestAdapter)->fromPaymentRequest($this->paymentRequest);
        $this->assertArrayHasKey('tokenInformation', $result);
        $this->assertArrayNotHasKey('paymentInformation', $result);
        $this->assertEquals('transientTokenJwt', $result['tokenInformation']['transientTokenJwt']);
        $this->assertMatchesSnapshot($result);
    }

    public function test_from_payment_request_with_transient_token_jwt_and_test_decline()
    {
        unset($this->paymentRequest->creditCardToken);
        $result = (new PaymentRequestAdapter)->fromPaymentRequest($this->paymentRequest, true);
        $this->assertEquals('test', $result['tokenInformation']['transientTokenJwt']);
        $this->assertMatchesSnapshot($result);
    }

    public function test_from_payment_request_with_transient_token_jwt_and_test_invalid_data()
    {
        unset($this->paymentRequest->creditCardToken);
        $result = (new PaymentRequestAdapter)->fromPaymentRequest($this->paymentRequest, false, true);
        $this->assertEquals('test', $result['tokenInformation']['transientTokenJwt']);
        $this->assertMatchesSnapshot($result);
    }
}
