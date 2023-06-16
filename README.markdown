# Jauntin Cybersource client

Provides some basic Cybersource payment management for use by Jauntin

## Installation

1. Install using composer
    - Add this repository as a [vcs source](https://getcomposer.org/doc/05-repositories.md#vcs) using `"url": "https://github.com/jauntin/cybersource"`
    - `composer require jauntin/cybersource`
2. If needed, publish the config and set values. This is optional, as you can view `config/config.php` and set the correct environment variables without publishing.
    - `php artisan vendor:publish --provider="Jauntin\\CyberSource\\CyberSourceServiceProvider" --tag="config"`

## Cybersource-polyfill

Contains a direct copy of the classes used in `cybersource/rest-client-php:0.0.38`, which has been removed from the package, in order to continue using Flex Tokens temporarily.

## testing

Helpers for mocking payment services.