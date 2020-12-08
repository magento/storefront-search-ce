<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\SearchStorefrontElasticsearch\Elasticsearch5\Model\Adapter\FieldMapper;

use Magento\SearchStorefrontElasticsearch\Model\Adapter\FieldMapperInterface;
use Magento\SearchStorefrontElasticsearch\Model\Client\ClientResolver;

/**
 * Proxy for product fields mappers
 * Copy of Elasticsearch\Elasticsearch5\Model\Adapter\FieldMapper\ProductFieldMapperProxy
 */
class ProductFieldMapperProxy implements FieldMapperInterface
{
    /**
     * @var ClientResolver
     */
    private $clientResolver;

    /**
     * @var FieldMapperInterface[]
     */
    private $productFieldMappers;

    /**
     * CategoryFieldsProviderProxy constructor.
     *
     * @param ClientResolver         $clientResolver
     * @param FieldMapperInterface[] $productFieldMappers
     */
    public function __construct(
        ClientResolver $clientResolver,
        array $productFieldMappers
    ) {
        $this->clientResolver = $clientResolver;
        $this->productFieldMappers = $productFieldMappers;
    }

    /**
     * Get field mappers
     *
     * @return FieldMapperInterface
     */
    private function getProductFieldMapper()
    {
        return $this->productFieldMappers[$this->clientResolver->getCurrentEngine()];
    }

    /**
     * Get field name
     *
     * @param  string $attributeCode
     * @param  array  $context
     * @return string
     */
    public function getFieldName($attributeCode, $context = [])
    {
        return $this->getProductFieldMapper()->getFieldName($attributeCode, $context);
    }

    /**
     * Get all entity attribute types
     *
     * @param  array $context
     * @return array
     */
    public function getAllAttributesTypes($context = [])
    {
        return $this->getProductFieldMapper()->getAllAttributesTypes($context);
    }
}
