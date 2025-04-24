<?php

namespace Jauntin\CyberSource\Tests\Unit\Api\Internal;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Jauntin\CyberSource\Api\Internal\RequestHeaders;
use Jauntin\CyberSource\Tests\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;

class RequestHeadersTest extends TestCase
{
    #[DataProvider('headerOutputDataProvider')]
    public function test_header_output($method, $payload)
    {
        Config::set('cybersource.run_env', 'jauntin.com');
        Config::set('cybersource.merchant_id', 'jauntin');
        Config::set('cybersource.cybersource.api_key_id', 'api_key_id');
        Config::set('cybersource.secret_key', 'secret_key');
        $this->travelTo('2023-11-20 12:11:11');
        $this->assertMatchesSnapshot(App::make(RequestHeaders::class)->generate('resourcePath', $method, $payload));
    }

    public static function headerOutputDataProvider()
    {
        return [
            'get' => ['get', null],
            'post' => ['post', json_encode(['hello' => 'world'])],
        ];
    }
}
