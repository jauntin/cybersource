<?php

namespace Jauntin\CyberSource\Tests\Unit\Api;

use CyberSource\Model\FlexV1KeysPost200Response;
use Illuminate\Support\Facades\App;
use Jauntin\CyberSource\Api\KeyResponse;
use Mockery\MockInterface;
use Jauntin\CyberSource\Tests\TestCase;

class KeyResponseTest extends TestCase
{
    public function testFromResponse()
    {
        $response = $this->mock(FlexV1KeysPost200Response::class, function(MockInterface $mock) {
            $mock->shouldReceive('getKeyId')->once()->andReturn('keyId');
        });

        $instance = App::make(KeyResponse::class)->fromResponse($response);
        $this->assertEquals('keyId', $instance->keyId);
    }
}
