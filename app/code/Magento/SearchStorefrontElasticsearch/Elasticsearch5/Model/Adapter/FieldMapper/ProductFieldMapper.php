<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Magento\SearchStorefrontElasticsearch\Elasticsearch5\Model\Adapter\FieldMapper;

use Magento\SearchStorefrontElasticsearch\Model\Adapter\FieldMapper\Product\AttributeProvider;
use Magento\SearchStorefrontElasticsearch\Model\Adapter\FieldMapper\Product\FieldProvider\FieldName\ResolverInterface;
use Magento\SearchStorefrontElasticsearch\Model\Adapter\FieldMapperInterface;

/**
 * Class ProductFieldMapper provides field name by attribute code and retrieve all attribute types
 * Copy of Magento\Elasticsearch\Elasticsearch5\Model\Adapter\FieldMapper\ProductFieldMapper
 * removed usage of FieldProviderInterface as it's not used during search request
 */
class ProductFieldMapper implements FieldMapperInterface
{
    /**
     * @var AttributeProvider
     */
    private $attributeAdapterProvider;

    /**
     * @var ResolverInterface
     */
    private $fieldNameResolver;

    /**
     * @param ResolverInterface $fieldNameResolver
     * @param AttributeProvider $attributeAdapterProvider
     */
    public function __construct(
        ResolverInterface $fieldNameResolver,
        AttributeProvider $attributeAdapterProvider
    ) {
        $this->fieldNameResolver = $fieldNameResolver;
        $this->attributeAdapterProvider = $attributeAdapterProvider;
    }

    /**
     * Get field name.
     *
     * @param string $attributeCode
     * @param array $context
     * @return string
     */
    public function getFieldName($attributeCode, $context = [])
    {
        $attributeAdapter = $this->attributeAdapterProvider->getByAttributeCode($attributeCode);
        return $this->fieldNameResolver->getFieldName($attributeAdapter, $context);
    }

    /**
     * Get all attributes types.
     *
     * @param array $context
     * @return array
     */
    public function getAllAttributesTypes($context = [])
    {
        return [];
    }
}
