<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Magento\SearchStorefrontElasticsearch\Model\Client;

use Magento\Framework\ObjectManagerInterface;
use Magento\SearchStorefrontStub\Framework\Model\EngineResolverInterface;

/**
 * Copied from Magento_AdvancedSearch
 */
class ClientResolver
{
    /**
     * Object Manager instance
     *
     * @var ObjectManagerInterface
     */
    protected $objectManager;

    /**
     * Pool of existing client factories
     *
     * @var array
     */
    private $clientFactoryPool;

    /**
     * Pool of client option classes
     *
     * @var array
     */
    private $clientOptionsPool;

    /**
     * @var EngineResolverInterface
     */
    private $engineResolver;

    /**
     * @param ObjectManagerInterface  $objectManager
     * @param EngineResolverInterface $engineResolver
     * @param array                   $clientFactories
     * @param array                   $clientOptions
     */
    public function __construct(
        ObjectManagerInterface $objectManager,
        EngineResolverInterface $engineResolver,
        array $clientFactories = [],
        array $clientOptions = []
    ) {
        $this->objectManager = $objectManager;
        $this->engineResolver = $engineResolver;
        $this->clientFactoryPool = $clientFactories;
        $this->clientOptionsPool = $clientOptions;
    }

    /**
     * Returns configured search engine
     *
     * @return string
     */
    public function getCurrentEngine()
    {
        return $this->engineResolver->getCurrentSearchEngine();
    }

    /**
     * Create client instance
     *
     * @param  string $engine
     * @param  array  $data
     * @return ClientInterface
     */
    public function create($engine = '', array $data = [])
    {
        $engine = $engine ?: $this->getCurrentEngine();

        if (!isset($this->clientFactoryPool[$engine])) {
            throw new \LogicException(
                'There is no such client factory: ' . $engine
            );
        }
        $factoryClass = $this->clientFactoryPool[$engine];
        $factory = $this->objectManager->create($factoryClass);
        if (!($factory instanceof ClientFactoryInterface)) {
            throw new \InvalidArgumentException(
                'Client factory must implement 
                \Magento\SearchStorefrontElasticsearch\Model\Client\ClientFactoryInterface'
            );
        }

        $optionsClass = $this->clientOptionsPool[$engine];
        $clientOptions = $this->objectManager->create($optionsClass);
        if (!($clientOptions instanceof ClientOptionsInterface)) {
            throw new \InvalidArgumentException(
                'Client options must implement 
                \Magento\SearchStorefrontElasticsearch\Model\Client\ClientInterface'
            );
        }

        return $factory->create($clientOptions->prepareClientOptions($data));
    }
}
