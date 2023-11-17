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
- See `config` for environment variables that will trigger unsuccessful requests in the staging environment. Setting any of these to true will send invalid data to cybersource, and allow manual testers to see how your application responds to failed requests.
  - CS_TEST_PAYMENT_DECLINE
  - CS_TEST_PAYMENT_INVALID_DATA
  - CS_TEST_REFUND_INVALID_DATA