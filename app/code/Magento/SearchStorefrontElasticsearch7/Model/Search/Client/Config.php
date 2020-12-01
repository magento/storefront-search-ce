<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Magento\SearchStorefrontElasticsearch7\Model\Search\Client;

use Magento\Framework\Exception\ConfigurationMismatchException;

class Config implements \Magento\SearchStorefrontElasticsearch\Model\ConnectionConfigInterface
{
    const SEARCH_SERVICE_CONFIG_KEY = 'storefront-search';

    /**
     * Default Application config.
     *
     * @var array
     */
    private static $DEFAULT_CONFIG = [
        'connections' => [
            'magento' => [
                'protocol' => 'http',
                'hostname' => 'localhost',
                'enable_auth' => 0,
                'port' => '9200',
                'username' => '',
                'password' => '',
                'timeout' => 60,
                'engine' => 'elasticsearch7',
                'index_prefix' => 'magento2',
                'minimum_should_match' => 1
            ],
            'local' => [

            ]
        ]
    ];

    /**
     * @var array
     */
    private $config = [];

    /**
     * @var \Magento\Framework\App\DeploymentConfig\Reader|Reader
     */
    private $configReader;

    /**
     * Initialize Elasticsearch connection configuration
     *
     * @param \Magento\Framework\App\DeploymentConfig\Reader $configReader
     */
    public function __construct(
        \Magento\Framework\App\DeploymentConfig\Reader $configReader
    ) {
        $this->configReader = $configReader;
    }

    /**
     * Return connection config of the Client.
     *
     * @return array
     * @throws ConfigurationMismatchException
     */
    public function getConfig() : array
    {
        if (empty($this->config)) {
            try {
                $configData = $this->configReader->load(\Magento\Framework\Config\File\ConfigFilePool::APP_ENV);
            } catch (\Exception $e) {
                $configData = [];
            }

            $this->config = isset($configData[self::SEARCH_SERVICE_CONFIG_KEY])
                ? array_replace_recursive(self::$DEFAULT_CONFIG, $configData[self::SEARCH_SERVICE_CONFIG_KEY])
                : self::$DEFAULT_CONFIG;

            $options = $this->config['connections']['magento'];

            if (empty($options['hostname']) || ((!empty($options['enable_auth'])
                && ($options['enable_auth'] == 1))
                && (empty($options['username']) || empty($options['password'])))
            ) {
                throw new ConfigurationMismatchException(
                    __('The search failed because of a search engine misconfiguration.')
                );
            }
        }

        return $this->config;
    }

    /**
     * Get server hostname from config
     *
     * @return string
     * @throws ConfigurationMismatchException
     */
    public function getServerHostname(): string
    {
        return (string) $this->getConfig()['connections']['magento']['hostname'];
    }

    /**
     * Get server port from config
     *
     * @return string
     * @throws ConfigurationMismatchException
     */
    public function getServerPort(): string
    {
        return (string) $this->getConfig()['connections']['magento']['port'];
    }

    /**
     * Get index prefix from config
     *
     * @return string
     * @throws ConfigurationMismatchException
     */
    public function getIndexPrefix(): string
    {
        return (string) $this->getConfig()['connections']['magento']['index_prefix'];
    }

    /**
     * Get enable auth from config
     *
     * @return int
     * @throws ConfigurationMismatchException
     */
    public function getEnableAuth(): int
    {
        return (int) $this->getConfig()['connections']['magento']['enable_auth'];
    }

    /**
     * Get username from config
     *
     * @return string
     * @throws ConfigurationMismatchException
     */
    public function getUsername(): string
    {
        return (string) $this->getConfig()['connections']['magento']['username'];
    }

    /**
     * Get password from config
     *
     * @return string
     * @throws ConfigurationMismatchException
     */
    public function getPassword(): string
    {
        return (string) $this->getConfig()['connections']['magento']['password'];
    }

    /**
     * Get timeout from config
     *
     * @return int
     * @throws ConfigurationMismatchException
     */
    public function getTimeout(): int
    {
        return (int) $this->getConfig()['connections']['magento']['timeout'];
    }

    /**
     * Get engine name from config
     *
     * @return string
     * @throws ConfigurationMismatchException
     */
    public function getEngine(): string
    {
        return (string) $this->getConfig()['connections']['magento']['engine'];
    }

    /**
     * Get minimum should match from config
     *
     * @return string
     * @throws ConfigurationMismatchException
     */
    public function getMinimumShouldMatch(): string
    {
        return (string) $this->getConfig()['connections']['magento']['minimum_should_match'];
    }
}
