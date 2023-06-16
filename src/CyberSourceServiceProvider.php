<?php

namespace Jauntin\CyberSource;

use CyberSource\Api\KeyGenerationApi;
use CyberSource\Api\PaymentsApi;
use CyberSource\Api\RefundApi;
use CyberSource\ApiClient;
use Exception;
use Illuminate\Contracts\Container\Container;
use Illuminate\Support\ServiceProvider;
use Jauntin\CyberSource\Api\ExternalConfiguration;

final class CyberSourceServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/config.php' => config_path('cybersource.php'),
            ], 'config');
        }
    }

    /**
     * Register the application services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/config.php', 'cybersource');

        $this->app->bind(
            ApiClient::class,
            function (Container $container) {
                if (config('app.env') === 'testing') {
                    throw new Exception('External requests can never be allowed in testing');
                }
                $externalConfiguration = $container->get(ExternalConfiguration::class);
                return new ApiClient(
                    $externalConfiguration->configuration(),
                    $externalConfiguration->merchantConfiguration(),
                );
            }
        );
    }
}
