<?php

namespace Jauntin\CyberSource\Tests\Unit\Api;

use Jauntin\CyberSource\Api\ExternalConfiguration;
use Jauntin\CyberSource\Tests\TestCase;

class ExternalConfigurationTest extends TestCase
{
    public function testMerchantConfiguration()
    {
        $this->assertMatchesSnapshot((new ExternalConfiguration())->merchantConfiguration());
    }

    public function testConfiguration()
    {
        $this->assertMatchesSnapshot((new ExternalConfiguration())->configuration());
    }
}
