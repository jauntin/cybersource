<?php

namespace Jauntin\CyberSource\Tests\Unit\Api\Internal;

use Illuminate\Support\Facades\App;
use Jauntin\CyberSource\Api\Internal\KeyResponseAdapter;
use Jauntin\CyberSource\Tests\TestCase;

class KeyResponseAdapterTest extends TestCase
{
    public function testFromResponse()
    {
        $input = ['keyId' => 'keyId'];
        $this->assertEquals($input, (array) App::make(KeyResponseAdapter::class)->fromResponse($input));
    }
}
