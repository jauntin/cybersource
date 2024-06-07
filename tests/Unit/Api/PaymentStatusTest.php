<?php

namespace Jauntin\CyberSource\Tests\Unit\Api;

use Jauntin\CyberSource\Api\PaymentStatus;
use Jauntin\CyberSource\Tests\TestCase;

class PaymentStatusTest extends TestCase
{
    public function testIsSuccessful()
    {
        $this->assertTrue(PaymentStatus::isSuccessful(PaymentStatus::STATUS_AUTHORIZED));
    }

    public function testIsUnsuccessful()
    {
        $this->assertTrue(PaymentStatus::isUnsuccessful(PaymentStatus::STATUS_AUTHORIZED_PENDING_REVIEW));
    }

    public function testIsError()
    {
        $this->assertTrue(PaymentStatus::isError(PaymentStatus::STATUS_INVALID_REQUEST));
    }
}
