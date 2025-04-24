<?php

namespace Jauntin\CyberSource\Tests\Unit\Api;

use Jauntin\CyberSource\Api\PaymentStatus;
use Jauntin\CyberSource\Tests\TestCase;

class PaymentStatusTest extends TestCase
{
    public function test_is_successful()
    {
        $this->assertTrue(PaymentStatus::isSuccessful(PaymentStatus::STATUS_AUTHORIZED));
    }

    public function test_is_unsuccessful()
    {
        $this->assertTrue(PaymentStatus::isUnsuccessful(PaymentStatus::STATUS_AUTHORIZED_PENDING_REVIEW));
    }

    public function test_is_error()
    {
        $this->assertTrue(PaymentStatus::isError(PaymentStatus::STATUS_INVALID_REQUEST));
    }
}
