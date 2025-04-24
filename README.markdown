# Jauntin Cybersource client

Provides some basic Cybersource payment management for use by Jauntin

## Installation

1. Install using composer
    - Add this repository as a [vcs source](https://getcomposer.org/doc/05-repositories.md#vcs) using `"url": "https://github.com/jauntin/cybersource"`
    - `composer require jauntin/cybersource`
2. If needed, publish the config and set values. This is optional, as you can view `config/config.php` and set the correct environment variables without publishing.
    - `php artisan vendor:publish --provider="Jauntin\\CyberSource\\CyberSourceServiceProvider" --tag="config"`

## Testing

- Helpers for mocking payment services are located in the `testing` directory.
- To send test failures, use `PaymentServce::pay testDecline, testInvalidData` and `RefundService::refund testInvalidData` when calling from an app.