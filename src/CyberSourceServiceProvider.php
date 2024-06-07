<?php

namespace Jauntin\CyberSource;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;
use Jauntin\CyberSource\Api\Internal\PaymentRequestAdapter;
use Jauntin\CyberSource\Api\Internal\RefundRequestAdapter;

final class CyberSourceServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/config.php' => config_path('cybersource.php'),
            ], 'config');
        }
    }

    /**
     * Register the application services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'cybersource');

        $this->app->bind(PaymentRequestAdapter::class, fn () => new PaymentRequestAdapter(
            testDecline: Config::get('cybersource.test.payment.decline'),
            testInvalidData: Config::get('cybersource.test.payment.invalid_data')
        ));
        $this->app->bind(RefundRequestAdapter::class, fn () => new RefundRequestAdapter(testInvalidData: Config::get('cybersource.test.refund.invalid_data')));
    }
}
