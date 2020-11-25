<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Magento\SearchStorefrontElasticsearch\Model\Adapter\FieldMapper\Product;

use Magento\Framework\App\ResourceConnection;
use Magento\Framework\DataObject;
use Magento\SearchStorefrontElasticsearch\Model\Adapter\FieldMapper\Product\AttributeAdapter\DummyAttribute;

/**
 * Provide attribute adapter.
 * Copied from Magento\Elasticsearch\Model\Adapter\FieldMapper\Product\AttributeProvider
 * removed dependencies on eav and catalog
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
     * @var ResourceConnection
     */
    private $resourceConnection;

    /**
     * Factory constructor
     *
     * @param \Magento\Framework\ObjectManagerInterface $objectManager
     * @param ResourceConnection                        $resourceConnection
     * @param string                                    $instanceName
     */
    public function __construct(
        \Magento\Framework\ObjectManagerInterface $objectManager,
        ResourceConnection $resourceConnection,
        $instanceName = AttributeAdapter::class
    ) {
        $this->objectManager = $objectManager;
        $this->resourceConnection = $resourceConnection;
        $this->instanceName = $instanceName;
    }

    /**
     * @inheritdoc
     */
    public function getByAttributeCode(string $attributeCode): AttributeAdapter
    {
        if (!isset($this->cachedPool[$attributeCode])) {
            $attribute = $this->getAttributeByCode($attributeCode);

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

    /**
     * Replace for Attribute EAV stubs with direct SQL query
     *
     * @param $attributeCode
     * @return mixed|null
     */
    private function getAttributeByCode($attributeCode)
    {
        $connection = $this->resourceConnection->getConnection();
        $attrSelect = $connection->select()
            ->from(
                ['a' => $this->resourceConnection->getTableName('eav_attribute')],
                [
                    'a.attribute_id',
                    'a.attribute_code',
                    'a.backend_type'
                ]
            )
            ->joinLeft(
                ['c' => $this->resourceConnection->getTableName('catalog_eav_attribute')],
                'a.attribute_id = c.attribute_id',
                [
                    'c.is_visible',
//                    'c.is_searchable',
                    'c.is_filterable',
                    'c.used_for_sort_by'
                ]
            )
            ->where('a.attribute_code=?', $attributeCode);
        $row = $connection->fetchRow($attrSelect);
        $attribute = null;
        if ($row) {
            $attribute = $this->objectManager->create(DataObject::class);
            foreach ($row as $index => $value) {
                $attribute->setData($index, $value);
            }
        }
        return $attribute;
    }
}
