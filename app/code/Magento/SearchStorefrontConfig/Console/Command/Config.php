<?php

/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types = 1);

namespace Magento\SearchStorefrontConfig\Console\Command;

use Magento\Framework\App\DeploymentConfig\Writer;
use Magento\Framework\Exception\FileSystemException;
use Magento\Framework\Stdlib\DateTime;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Command for grpc server and grpc_services_map initialization
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class Config extends Command
{
    /**
     * Command name
     * @var string
     */
    private const COMMAND_NAME = 'storefront:search:init';

    /**
     * Configuration for Elasticsearch
     */
    const ELASTICSEARCH_HOST         = 'elastic';
    const ELASTICSEARCH_ENGINE       = 'storefrontElasticsearch7';
    const ELASTICSEARCH_PORT         = '9200';
    const ELASTICSEARCH_INDEX_PREFIX = 'magento2';

    /**
     * @var Writer
     */
    private $deploymentConfigWriter;

    /**
     * @var DateTime
     */
    private $dateTime;

    /**
     * Installer constructor.
     * @param Writer          $deploymentConfigWriter
     * @param DateTime        $dateTime
     */
    public function __construct(
        Writer $deploymentConfigWriter,
        DateTime $dateTime
    ) {
        parent::__construct();
        $this->deploymentConfigWriter = $deploymentConfigWriter;
        $this->dateTime = $dateTime;
    }

    /**
     * @inheritDoc
     */
    protected function configure()
    {
        $this->setName(self::COMMAND_NAME)->setDescription(
            'Adds minimum required config data to env.php'
        );

        parent::configure();
    }

    /**
     * Prepare cache list
     *
     * @return array
     */
    private function getCacheTypes(): array
    {
        return [
            'config'          => 1,
            'compiled_config' => 1
        ];
    }

    /**
     * @inheritDoc
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     * @throws FileSystemException
     */
    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $config = [
            'app_env' => [
                'crypt' => [
                    'key' => 'crypt_key'
                ],
                'resource' => [
                    'default_setup' => [
                        'connection' => 'default'
                    ]
                ],
                'system'      => [
                    'stores' => [
                        'catalog' => [
                            'layered_navigation' => [
                                'price_range_calculation' => 'auto',
                                'interval_division_limit' => 1,
                                'price_range_step' => 100,
                                'price_range_max_intervals' => 10,
                                'one_price_interval' => 1
                            ]
                        ]
                    ]
                ],
                'db' => [
                    'connection' => [
                        'default' => [
                            'host' => 'db',
                            'dbname' => 'magento',
                            'username' => 'root',
                            'password' => '',
                            'model' => 'mysql4',
                            'engine' => 'innodb',
                            'initStatements' => 'SET NAMES utf8;',
                            'active' => '1',
                            'driver_options' => [
                                1014 => false
                            ]
                        ]
                    ],
                    'table_prefix' => ''
                ],
                'search-store-front' => [
                    'connections' => [
                        'default' => [
                            'protocol' => 'http',
                            'hostname' => self::ELASTICSEARCH_HOST,
                            'port' => self::ELASTICSEARCH_PORT,
                            'enable_auth' => '',
                            'username' => '',
                            'password' => '',
                            'timeout' => 30
                        ]
                    ],
                    'engine' => self::ELASTICSEARCH_ENGINE,
                    'index_prefix' => self::ELASTICSEARCH_INDEX_PREFIX
                ],
                'install'     => [
                    'date' => $this->dateTime->formatDate(true)
                ],
                'MAGE_MODE' => 'developer'
            ],
            'app_config' => [
                'modules' => [
                    'Magento_SearchStorefrontConfig' => 1,
                    'Magento_SearchStorefrontStore' => 1,
                    'Magento_Grpc' => 1,
                    'Magento_SearchStorefrontSearch' => 1,
                    'Magento_SearchStorefrontApi' => 1,
                    'Magento_SearchStorefrontElasticsearch' => 1,
                    'Magento_SearchStorefrontElasticsearch6' => 1,
                    'Magento_SearchStorefront' => 1
                ]
            ]
        ];

        $this->deploymentConfigWriter->saveConfig($config);
    }
}
