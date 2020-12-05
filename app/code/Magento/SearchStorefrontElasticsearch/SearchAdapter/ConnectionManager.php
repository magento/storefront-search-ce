<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\SearchStorefrontElasticsearch\SearchAdapter;

use Magento\SearchStorefrontElasticsearch\Model\Client\ClientFactoryInterface;
use Magento\SearchStorefrontElasticsearch\Model\Client\ClientInterface as Elasticsearch;
use Magento\SearchStorefrontElasticsearch\Model\Client\ClientOptionsInterface;
use Psr\Log\LoggerInterface;

/**
 * Class provides interface for Elasticsearch connection
 *
 * Copy of Elasticsearch\SearchAdapter\ConnectionManager
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
     * @param LoggerInterface        $logger
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
     * @param  array $options
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
     * @param  array $options
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
