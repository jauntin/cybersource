<?php

/*
* Purpose : passing Authentication config object to the configuration
*/

namespace Jauntin\CyberSource\Api;

use CyberSource\Authentication\Core\MerchantConfiguration;
use CyberSource\Configuration;
use CyberSource\Logging\LogConfiguration;

/**
 * @final
 * @internal
 */
class ExternalConfiguration
{
    private MerchantConfiguration $merchantConfiguration;
    private Configuration $configuration;

    public function __construct()
    {
        $merchantConfiguration = new MerchantConfiguration();
        $merchantConfiguration->setauthenticationType(strtoupper(trim(config('cybersource.auth_type'))));
        $merchantConfiguration->setMerchantID(trim(config('cybersource.merchant_id')));
        $merchantConfiguration->setApiKeyID(config('cybersource.api_key_id'));
        $merchantConfiguration->setSecretKey(config('cybersource.secret_key'));
        $merchantConfiguration->setKeyFileName(trim(config('cybersource.merchant_id')));
        $merchantConfiguration->setKeyAlias(config('cybersource.merchant_id'));
        $merchantConfiguration->setKeyPassword(config('cybersource.merchant_id'));
        $merchantConfiguration->setUseMetaKey(config('cybersource.use_meta_key'));
        $merchantConfiguration->setPortfolioID(config('cybersource.portfolio_id'));
        $merchantConfiguration->setKeysDirectory(config('cybersource.key_directory'));
        $merchantConfiguration->setRunEnvironment(config('cybersource.run_env'));

        $logConfiguration = new LogConfiguration();
        $logConfiguration->enableLogging(config('cybersource.enable_log'));
        $logConfiguration->setDebugLogFile(config('cybersource.log_file_name'));
        $logConfiguration->setErrorLogFile(config('cybersource.log_file_name'));
        $logConfiguration->setLogDateFormat(config('cybersource.log_date_format'));
        $logConfiguration->setLogFormat(config('cybersource.log_format'));
        $logConfiguration->setLogMaxFiles(config('cybersource.log_max_files'));
        $logConfiguration->setLogLevel(config('cybersource.log_level'));
        $logConfiguration->enableMasking(config('cybersource.enable_masking'));

        $merchantConfiguration->setLogConfiguration($logConfiguration);
        $merchantConfiguration->validateMerchantData();

        $configuration = new Configuration();
        $configuration->setHost($merchantConfiguration->getHost());
        $configuration->setLogConfiguration($merchantConfiguration->getLogConfiguration());

        $this->merchantConfiguration = $merchantConfiguration;
        $this->configuration = $configuration;
    }

    public function merchantConfiguration(): MerchantConfiguration
    {
        return $this->merchantConfiguration;
    }

    public function configuration(): Configuration
    {
        return $this->configuration;
    }
}
