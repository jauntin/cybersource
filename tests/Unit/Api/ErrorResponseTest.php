<?php

namespace Jauntin\CyberSource\Tests\Unit\Api;

use Exception;
use Illuminate\Support\Facades\App;
use Jauntin\CyberSource\Api\ErrorResponse;
use Jauntin\CyberSource\Tests\TestCase;

class ErrorResponseTest extends TestCase
{
    public function test_from_throwable()
    {
        $e = new Exception('a', 1);
        /** @var ErrorResponse */
        $errorResponse = App::make(ErrorResponse::class);
        $this->assertSame($errorResponse, $errorResponse->fromThrowable($e));
        $this->assertEquals('a', $errorResponse->message);
        $this->assertEquals(1, $errorResponse->statusCode);
        $this->assertSame($e, $errorResponse->previous);
    }
}
