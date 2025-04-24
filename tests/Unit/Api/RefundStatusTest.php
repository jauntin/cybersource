<?php

namespace Jauntin\CyberSource\Tests\Unit\Api;

use Jauntin\CyberSource\Api\RefundStatus;
use Jauntin\CyberSource\Tests\TestCase;

class RefundStatusTest extends TestCase
{
    public function test_is_successful()
    {
        $this->assertTrue(RefundStatus::isSuccessful(RefundStatus::STATUS_PENDING));
    }
}
