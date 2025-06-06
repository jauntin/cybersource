<?php

namespace Jauntin\CyberSource\Tests;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Orchestra\Testbench\TestCase as Base;
use Spatie\Snapshots\MatchesSnapshots;

abstract class TestCase extends Base
{
    use MatchesSnapshots;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setTestConfig();
        Http::preventStrayRequests();
    }

    private function setTestConfig(): void
    {
        foreach ($this->config() as $key => $value) {
            Config::set($key, $value);
        }
    }

    private function config(): array
    {
        return ['cybersource' => include (realpath(__DIR__.'/../config/config.php'))];
    }
}
