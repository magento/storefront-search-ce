<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Magento\SearchStorefrontElasticsearch\Model\Adapter\FieldMapper\Product;

use Magento\SearchStorefront\Model\Eav\Attribute\CollectionFactory;
use Magento\SearchStorefront\Model\Eav\Attribute\Collection as AttributeCollection;
use Magento\SearchStorefront\Model\Elasticsearch\FieldMapper\Product\AttributeAdapter;
use Magento\SearchStorefrontElasticsearch\Model\Adapter\FieldMapper\Product\AttributeAdapter\DummyAttribute;
use Psr\Log\LoggerInterface;

/**
 * Provide attribute adapter.
 */
class AttributeProvider
{
    /**
     * Object Manager instance
     *
     * @var \Magento\Framework\ObjectManagerInterface
     */
    private $objectManager;

    /**
     * @var CollectionFactory
     */
    private $collectionFactory;

    /**
     * Instance name to create
     *
     * @var string
     */
    private $instanceName;

    /**
     * @var array
     */
    private $cachedPool = [];

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * Factory constructor
     *
     * @param \Magento\Framework\ObjectManagerInterface $objectManager
     * @param CollectionFactory $collectionFactory
     * @param LoggerInterface $logger
     * @param string $instanceName
     */
    public function __construct(
        \Magento\Framework\ObjectManagerInterface $objectManager,
        CollectionFactory $collectionFactory,
        LoggerInterface $logger,
        $instanceName = AttributeAdapter::class
    ) {
        $this->objectManager = $objectManager;
        $this->collectionFactory = $collectionFactory;
        $this->instanceName = $instanceName;
        $this->logger = $logger;
    }

    /**
     * @inheritdoc
     */
    public function getByAttributeCode(string $attributeCode): AttributeAdapter
    {
        if (!isset($this->cachedPool[$attributeCode])) {
            /** @var AttributeCollection $collection */
            $collection = $this->collectionFactory->create();
            $collection->addFieldToFilter('attribute_code', $attributeCode);
            $collection->setEntityTypeFilter('catalog_product')->load();
            $attribute = $collection->getFirstItem();
            $attribute = $attribute->getData('attribute_code') ? $attribute : null;

            if (null === $attribute) {
                $attribute = $this->objectManager->create(DummyAttribute::class);
            }

            $this->cachedPool[$attributeCode] = $this->objectManager->create(
                $this->instanceName,
                ['attribute' => $attribute, 'attributeCode' => $attributeCode]
            );
        }

        return $this->cachedPool[$attributeCode];
    }
}
