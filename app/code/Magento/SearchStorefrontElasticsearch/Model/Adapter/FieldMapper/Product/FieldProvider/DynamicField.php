<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Magento\SearchStorefrontElasticsearch\Model\Adapter\FieldMapper\Product\FieldProvider;

use Magento\Framework\App\ResourceConnection;
use Magento\SearchStorefrontElasticsearch\Model\Adapter\FieldMapper\Product\AttributeProvider;
use Magento\SearchStorefrontElasticsearch\Model\Adapter\FieldMapper\Product\FieldProvider\FieldIndex\ConverterInterface
    as IndexTypeConverterInterface;
use Magento\SearchStorefrontElasticsearch\Model\Adapter\FieldMapper\Product\FieldProvider\FieldName\ResolverInterface
    as FieldNameResolver;
use Magento\SearchStorefrontElasticsearch\Model\Adapter\FieldMapper\Product\FieldProvider\FieldType\ConverterInterface
    as FieldTypeConverterInterface;
use Magento\SearchStorefrontElasticsearch\Model\Adapter\FieldMapper\Product\FieldProviderInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;

/**
 * Provide dynamic fields for product.
 * Copied from  Magento\Elasticsearch\Model\Adapter\FieldMapper\Product\FieldProvider\DynamicField
 * removed dependencies on eav and catalog
 */
class DynamicField implements FieldProviderInterface
{
    /**
     * Search criteria builder.
     *
     * @var SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;

    /**
     * @var FieldTypeConverterInterface
     */
    private $fieldTypeConverter;

    /**
     * @var IndexTypeConverterInterface
     */
    private $indexTypeConverter;

    /**
     * @var AttributeProvider
     */
    private $attributeAdapterProvider;

    /**
     * @var FieldNameResolver
     */
    private $fieldNameResolver;

    /**
     * @var ResourceConnection
     */
    private $resourceConnection;

    /**
     * @param FieldTypeConverterInterface $fieldTypeConverter
     * @param IndexTypeConverterInterface $indexTypeConverter
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param FieldNameResolver $fieldNameResolver
     * @param AttributeProvider $attributeAdapterProvider
     * @param ResourceConnection $resourceConnection
     */
    public function __construct(
        FieldTypeConverterInterface $fieldTypeConverter,
        IndexTypeConverterInterface $indexTypeConverter,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        FieldNameResolver $fieldNameResolver,
        AttributeProvider $attributeAdapterProvider,
        ResourceConnection $resourceConnection
    ) {
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->fieldTypeConverter = $fieldTypeConverter;
        $this->indexTypeConverter = $indexTypeConverter;
        $this->fieldNameResolver = $fieldNameResolver;
        $this->attributeAdapterProvider = $attributeAdapterProvider;
        $this->resourceConnection = $resourceConnection;
    }

    /**
     * @inheritdoc
     */
    public function getFields(array $context = []): array
    {
        $allAttributes = [];
        $categoryIds = $this->getAllCategoryIds();
        $positionAttribute = $this->attributeAdapterProvider->getByAttributeCode('position');
        $categoryNameAttribute = $this->attributeAdapterProvider->getByAttributeCode('category_name');
        foreach ($categoryIds as $categoryId) {
            $categoryPositionKey = $this->fieldNameResolver->getFieldName(
                $positionAttribute,
                ['categoryId' => $categoryId]
            );
            $categoryNameKey = $this->fieldNameResolver->getFieldName(
                $categoryNameAttribute,
                ['categoryId' => $categoryId]
            );
            $allAttributes[$categoryPositionKey] = [
                'type' => $this->fieldTypeConverter->convert(FieldTypeConverterInterface::INTERNAL_DATA_TYPE_INT),
                'index' => $this->indexTypeConverter->convert(IndexTypeConverterInterface::INTERNAL_NO_INDEX_VALUE)
            ];
            $allAttributes[$categoryNameKey] = [
                'type' => $this->fieldTypeConverter->convert(FieldTypeConverterInterface::INTERNAL_DATA_TYPE_STRING),
                'index' => $this->indexTypeConverter->convert(IndexTypeConverterInterface::INTERNAL_NO_INDEX_VALUE)
            ];
        }

        $groups = $this->getAllCustomerGroupIds();
        $priceAttribute = $this->attributeAdapterProvider->getByAttributeCode('price');
        $ctx = isset($context['websiteId']) ? ['websiteId' => $context['websiteId']] : [];
        foreach ($groups as $groupId) {
            $ctx['customerGroupId'] = $groupId;
            $groupPriceKey = $this->fieldNameResolver->getFieldName(
                $priceAttribute,
                $ctx
            );
            $allAttributes[$groupPriceKey] = [
                'type' => $this->fieldTypeConverter->convert(FieldTypeConverterInterface::INTERNAL_DATA_TYPE_FLOAT),
                'store' => true
            ];
        }

        return $allAttributes;
    }

    /**
     * Get customer group ids instead of using repository interface.
     *
     * @return array
     */
    public function getAllCustomerGroupIds()
    {
        $connection = $this->resourceConnection->getConnection();
        $select = $connection->select()
            ->from(
                $connection->getTableName('customer_group'),
                ['customer_group_id']
            );

        return $connection->fetchCol($select);
    }

    /**
     * Get customer group ids instead of using repository interface.
     *
     * @return array
     */
    public function getAllCategoryIds()
    {
        $connection = $this->resourceConnection->getConnection();
        $select = $connection->select()
            ->from(
                $connection->getTableName('catalog_category_entity'),
                ['entity_id']
            );

        return $connection->fetchCol($select);
    }
}
