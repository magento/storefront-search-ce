<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Magento\SearchStorefront\Model\Filter\Price;

use Magento\SearchStorefrontStub\Model\Search\Options;
use Magento\Framework\App\ResourceConnection;
use Magento\SearchStorefrontStore\Model\ScopeInterface;
use Magento\SearchStorefrontStore\Model\StoreManagerInterface;

class Range
{
    const PATH_RANGE_STEP = 'range_step';

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var ResourceConnection
     */
    private $resourceConnection;

    /**
     * @var \Magento\Framework\EntityManager\MetadataPool
     */
    private $metadataPool;

    /**
     * @var Options
     */
    private $rangeOptions;

    /**
     * @param StoreManagerInterface                         $storeManager
     * @param ResourceConnection                            $resourceConnection
     * @param \Magento\Framework\EntityManager\MetadataPool $metadataPool
     * @param Options                                       $rangeOptions
     */
    public function __construct(
        StoreManagerInterface $storeManager,
        ResourceConnection $resourceConnection,
        \Magento\Framework\EntityManager\MetadataPool $metadataPool,
        Options $rangeOptions
    ) {
        $this->storeManager = $storeManager;
        $this->resourceConnection = $resourceConnection;
        $this->metadataPool = $metadataPool;
        $this->rangeOptions = $rangeOptions;
    }

    /**
     * @return float
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getPriceRange()
    {
        // atm we use default settings from app/code/Magento/LayeredNavigation/etc/config.xml
        return $this->rangeOptions->get()[self::PATH_RANGE_STEP];
    }
}
