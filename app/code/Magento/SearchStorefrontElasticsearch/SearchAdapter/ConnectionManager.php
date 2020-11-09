<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\SearchStorefrontElasticsearch\SearchAdapter;

use Magento\SearchStorefrontAdvancedSearch\Model\Client\ClientOptionsInterface;
use Magento\SearchStorefrontAdvancedSearch\Model\Client\ClientFactoryInterface;
use Magento\SearchStorefrontAdvancedSearch\Model\Client\ClientInterface as Elasticsearch;
use Psr\Log\LoggerInterface;

/**
 * Class provides interface for Elasticsearch connection
 *
 * Copy of Magento\Elasticsearch\SearchAdapter\ConnectionManager
 */
class ConnectionManager
{
    /**
     * @var Elasticsearch
     */
    protected $client;

    /**
     * @var LoggerInterface
    */
    protected $logger;

    /**
     * @var ClientFactoryInterface
    */
    protected $clientFactory;

    /**
     * @var ClientOptionsInterface
    */
    protected $clientConfig;

    /**
     * @param ClientFactoryInterface $clientFactory
     * @param ClientOptionsInterface $clientConfig
     * @param LoggerInterface $logger
     */
    public function __construct(
        ClientFactoryInterface $clientFactory,
        ClientOptionsInterface $clientConfig,
        LoggerInterface $logger
    ) {
        $this->logger = $logger;
        $this->clientFactory = $clientFactory;
        $this->clientConfig = $clientConfig;
    }

    /**
     * Get shared connection
     *
     * @param array $options
     * @throws \RuntimeException
     * @return Elasticsearch
    */
    public function getConnection($options = [])
    {
        if (!$this->client) {
            $this->connect($options);
        }

        return $this->client;
    }

    /**
     * Connect to Elasticsearch client with default options
     *
     * @param array $options
     * @throws \RuntimeException
     * @return void
     */
    private function connect($options)
    {
        try {
            $this->client = $this->clientFactory->create($this->clientConfig->prepareClientOptions($options));
        } catch (\Exception $e) {
            $this->logger->critical($e);
            throw new \RuntimeException('Elasticsearch client is not set.');
        }
    }
}
