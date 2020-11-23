<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\SearchStorefrontConfig\Model;

use Magento\Framework\App\DeploymentConfig\Writer;
use Magento\Framework\Stdlib\DateTime;

class Installer
{
    /**
     * Configuration for Search Service DB connection
     */
    const DB_HOST = 'db-host';
    const DB_NAME = 'db-name';
    const DB_USER = 'db-user';
    const DB_PASSWORD = 'db-password';
    const DB_TABLE_PREFIX = 'db-table-prefix';

    /**
     * Configuration for Search Service ElasticSearch
     */
    const ES_ENGINE = 'es-engine';
    const ES_HOSTNAME = 'es-hostname';
    const ES_PORT = 'es-port';
    const ES_INDEX_PREFIX = 'es-index-prefix';
    const ES_USERNAME = 'es-username';
    const ES_PASSWORD = 'es-password';

    /**
     * @var Writer
     */
    private $deploymentConfigWriter;

    /**
     * @var DateTime
     */
    private $dateTime;

    /**
     * @var ModulesCollector
     */
    private $modulesCollector;

    /**
     * Installer constructor.
     * @param Writer $deploymentConfigWriter
     * @param DateTime $dateTime
     * @param ModulesCollector $modulesCollector
     */
    public function __construct(
        Writer $deploymentConfigWriter,
        DateTime $dateTime,
        ModulesCollector $modulesCollector
    ) {
        $this->deploymentConfigWriter = $deploymentConfigWriter;
        $this->dateTime = $dateTime;
        $this->modulesCollector = $modulesCollector;
    }

    /**
     * Create env.php file configuration
     *
     * @param array $parameters
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    public function install(array $required, array $optional): void
    {
        $config = [
            'app_env' => [
                'install' => [
                    'date' => $this->dateTime->formatDate(true)
                ],
                'resource' => [
                    'default_setup' => [
                        'connection' => 'default'
                    ]
                ],
                'db' => [
                    'connection' => [
                        'default' => [
                            'host' => $required[self::DB_HOST],
                            'dbname' => $required[self::DB_NAME],
                            'username' => $required[self::DB_USER],
                            'password' => $required[self::DB_PASSWORD],
                            'model' => 'mysql4',
                            'engine' => 'innodb',
                            'initStatements' => 'SET NAMES utf8;',
                            'active' => '1',
                            'driver_options' => [
                                1014 => false
                            ]
                        ]
                    ],
                    'table_prefix' => $optional[self::DB_TABLE_PREFIX]
                ],
                'search-store-front' => [
                    'connections' => [
                        'default' => [
                            'protocol' => 'http',
                            'hostname' => $required[self::ES_HOSTNAME],
                            'port' => $optional[self::ES_PORT],
                            'enable_auth' => $optional[self::ES_USERNAME] !== null,
                            'username' => $optional[self::ES_USERNAME],
                            'password' => $optional[self::ES_PASSWORD],
                            'timeout' => 30
                        ]
                    ],
                    'engine' => $required[self::ES_ENGINE],
                    'index_prefix' => $required[self::ES_INDEX_PREFIX]
                ],
                'MAGE_MODE' => 'developer'
            ],
            'app_config' => [
                'modules' => $this->modulesCollector->execute()
            ]
        ];

        $this->deploymentConfigWriter->saveConfig($config);
    }
}
