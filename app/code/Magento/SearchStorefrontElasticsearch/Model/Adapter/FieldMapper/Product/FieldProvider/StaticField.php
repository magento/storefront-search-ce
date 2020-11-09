<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Magento\SearchStorefrontElasticsearch\Model\Adapter\FieldMapper\Product\FieldProvider;

use Magento\SearchStorefrontElasticsearch\Model\Adapter\FieldMapper\Product\AttributeProvider;
use Magento\SearchStorefrontElasticsearch\Model\Adapter\FieldMapper\Product\FieldProvider\FieldIndex\ConverterInterface
    as IndexTypeConverterInterface;
use Magento\SearchStorefrontElasticsearch\Model\Adapter\FieldMapper\Product\FieldProvider\FieldIndex\ResolverInterface
    as FieldIndexResolver;
use Magento\SearchStorefrontElasticsearch\Model\Adapter\FieldMapper\Product\FieldProvider\FieldName\ResolverInterface;
use Magento\SearchStorefrontElasticsearch\Model\Adapter\FieldMapper\Product\FieldProvider\FieldType\ConverterInterface
    as FieldTypeConverterInterface;
use Magento\SearchStorefrontElasticsearch\Model\Adapter\FieldMapper\Product\FieldProvider\FieldType\ResolverInterface
    as FieldTypeResolver;
use Magento\SearchStorefrontElasticsearch\Model\Adapter\FieldMapper\Product\FieldProviderInterface;
use Magento\SearchStorefrontElasticsearch\Model\Adapter\FieldMapperInterface;

/**
 * Provide static fields for mapping of product.
 * Copied from  Magento\Elasticsearch\Model\Adapter\FieldMapper\Product\FieldProvider\StaticField
 * removed dependencies on eav and catalog
 */
class StaticField implements FieldProviderInterface
{
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
     * @var FieldTypeResolver
     */
    private $fieldTypeResolver;

    /**
     * @var FieldIndexResolver
     */
    private $fieldIndexResolver;

    /**
     * @var ResolverInterface
     */
    private $fieldNameResolver;

    /**
     * @var array
     */
    private $excludedAttributes;

    /**
     * @param FieldTypeConverterInterface $fieldTypeConverter
     * @param IndexTypeConverterInterface $indexTypeConverter
     * @param FieldTypeResolver $fieldTypeResolver
     * @param FieldIndexResolver $fieldIndexResolver
     * @param AttributeProvider $attributeAdapterProvider
     * @param ResolverInterface $fieldNameResolver
     * @param array $excludedAttributes
     */
    public function __construct(
        FieldTypeConverterInterface $fieldTypeConverter,
        IndexTypeConverterInterface $indexTypeConverter,
        FieldTypeResolver $fieldTypeResolver,
        FieldIndexResolver $fieldIndexResolver,
        AttributeProvider $attributeAdapterProvider,
        ResolverInterface $fieldNameResolver,
        array $excludedAttributes = []
    ) {
        $this->fieldTypeConverter = $fieldTypeConverter;
        $this->indexTypeConverter = $indexTypeConverter;
        $this->fieldTypeResolver = $fieldTypeResolver;
        $this->fieldIndexResolver = $fieldIndexResolver;
        $this->attributeAdapterProvider = $attributeAdapterProvider;
        $this->fieldNameResolver = $fieldNameResolver;
        $this->excludedAttributes = $excludedAttributes;
    }

    /**
     * Get static fields.
     *
     * @param array $context
     * @return array
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function getFields(array $context = []): array
    {
        return [];
    }

    /**
     * Get field mapping for specific attribute.
     *
     * @param $attribute
     * @return array
     */
    public function getField($attribute): array
    {
        $fieldMapping = [];
        if (in_array($attribute->getAttributeCode(), $this->excludedAttributes, true)) {
            return $fieldMapping;
        }

        $attributeAdapter = $this->attributeAdapterProvider->getByAttributeCode($attribute->getAttributeCode());
        $fieldName = $this->fieldNameResolver->getFieldName($attributeAdapter);

        $fieldMapping[$fieldName] = [
            'type' => $this->fieldTypeResolver->getFieldType($attributeAdapter),
        ];

        $index = $this->fieldIndexResolver->getFieldIndex($attributeAdapter);
        if (null !== $index) {
            $fieldMapping[$fieldName]['index'] = $index;
        }

        if ($attributeAdapter->isSortable()) {
            $sortFieldName = $this->fieldNameResolver->getFieldName(
                $attributeAdapter,
                ['type' => FieldMapperInterface::TYPE_SORT]
            );
            $fieldMapping[$fieldName]['fields'][$sortFieldName] = [
                'type' => $this->fieldTypeConverter->convert(
                    FieldTypeConverterInterface::INTERNAL_DATA_TYPE_KEYWORD
                ),
                'index' => $this->indexTypeConverter->convert(
                    IndexTypeConverterInterface::INTERNAL_NO_ANALYZE_VALUE
                )
            ];
        }

        if ($attributeAdapter->isTextType()) {
            $keywordFieldName = FieldTypeConverterInterface::INTERNAL_DATA_TYPE_KEYWORD;
            $index = $this->indexTypeConverter->convert(
                IndexTypeConverterInterface::INTERNAL_NO_ANALYZE_VALUE
            );
            $fieldMapping[$fieldName]['fields'][$keywordFieldName] = [
                'type' => $this->fieldTypeConverter->convert(
                    FieldTypeConverterInterface::INTERNAL_DATA_TYPE_KEYWORD
                )
            ];
            if ($index) {
                $fieldMapping[$fieldName]['fields'][$keywordFieldName]['index'] = $index;
            }
        }

        if ($attributeAdapter->isComplexType()) {
            $childFieldName = $this->fieldNameResolver->getFieldName(
                $attributeAdapter,
                ['type' => FieldMapperInterface::TYPE_QUERY]
            );
            $fieldMapping[$childFieldName] = [
                'type' => $this->fieldTypeConverter->convert(FieldTypeConverterInterface::INTERNAL_DATA_TYPE_STRING)
            ];
        }

        return $fieldMapping;
    }
}
