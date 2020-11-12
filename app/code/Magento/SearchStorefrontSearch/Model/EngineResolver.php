<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\SearchStorefrontSearch\Model;

use Magento\SearchStorefront\Model\Search\Client\Config;
use Magento\Framework\Search\EngineResolverInterface;
use Psr\Log\LoggerInterface;

/**
 * Search engine resolver model.
 */
class EngineResolver implements EngineResolverInterface
{
    /**
     * @var Config
    */
    protected $config;

    /**
     * Available engines
     * @var array
     */
    private $engines = [];

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var string
     */
    private $defaultEngine;

    /**
     * @param Config $config
     * @param array $engines
     * @param LoggerInterface $logger
     * @param string|null $defaultEngine
     */
    public function __construct(
        Config $config,
        array $engines,
        LoggerInterface $logger,
        $defaultEngine = null
    ) {
        $this->config = $config;
        $this->engines = $engines;
        $this->logger = $logger;
        $this->defaultEngine = $defaultEngine;
    }

    /**
     * Returns Current Search Engine
     *
     * It returns string identifier of Search Engine that is currently chosen in configuration
     *
     * @return string
    */
    public function getCurrentSearchEngine()
    {
        $engine = $this->config->getEngine();

        if (in_array($engine, $this->engines)) {
            return $engine;
        }

        //get default engine from default scope
        if ($this->defaultEngine && in_array($this->defaultEngine, $this->engines)) {
            $this->logger->error(
                $engine . ' search engine doesn\'t exist. Falling back to ' . $this->defaultEngine
            );
        } else {
            $this->logger->error(
                'Default search engine is not configured, fallback is not possible'
            );
        }
        return $this->defaultEngine;
    }
}
