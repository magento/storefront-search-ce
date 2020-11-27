<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\SearchStorefront\Plugin\Search\Request;

use Magento\Framework\App\ResourceConnection;
use Magento\Framework\DataObject;
use Magento\Framework\ObjectManagerInterface;
use Magento\Framework\Search\Request\FilterInterface;
use Magento\Framework\Search\Request\QueryInterface;
use Magento\SearchStorefront\Model\Search\RequestGenerator;
use Magento\SearchStorefront\Model\Search\RequestGenerator\GeneratorResolver;

/**
 * Copied from Magento\CatalogGraphQl
 *
 * Add search request configuration to config for give ability filter and search products
 */
class ConfigReader
{
    /**
     * Bucket name suffix
     */
    private const BUCKET_SUFFIX = '_bucket';

    /**
     * @var string
     */
    private $requestNameWithAggregation = 'search_service_request';

    /**
     * @var string
     */
    private $requestName = 'search_service_request_without_aggregation';

    /**
     * @var GeneratorResolver
     */
    private $generatorResolver;

    /**
     * @var array
     */
    private $exactMatchAttributes = [];

    /**
     * @var ResourceConnection
     */
    private $resourceConnection;

    /**
     * @var ObjectManagerInterface
     */
    private $objectManager;

    /**
     * @param GeneratorResolver      $generatorResolver
     * @param ResourceConnection     $resourceConnection
     * @param ObjectManagerInterface $objectManager
     * @param array                  $exactMatchAttributes
     */
    public function __construct(
        GeneratorResolver $generatorResolver,
        ResourceConnection $resourceConnection,
        ObjectManagerInterface $objectManager,
        array $exactMatchAttributes = []
    ) {
        $this->generatorResolver = $generatorResolver;
        $this->exactMatchAttributes = array_merge($this->exactMatchAttributes, $exactMatchAttributes);
        $this->resourceConnection = $resourceConnection;
        $this->objectManager = $objectManager;
    }

    /**
     * Merge reader's value with generated
     *
     * @param                                         \Magento\Framework\Config\ReaderInterface $subject
     * @param                                         array                                     $result
     * @return                                        array
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterRead(
        \Magento\Framework\Config\ReaderInterface $subject,
        array $result
    ) {
        $searchRequestNameWithAggregation = $this->generateRequest();
        $searchRequest = $searchRequestNameWithAggregation;
        $searchRequest['queries'][$this->requestName] = $searchRequest['queries'][$this->requestNameWithAggregation];
        unset($searchRequest['queries'][$this->requestNameWithAggregation], $searchRequest['aggregations']);

        return array_merge_recursive(
            $result,
            [
                $this->requestNameWithAggregation => $searchRequestNameWithAggregation,
                $this->requestName => $searchRequest,
            ]
        );
    }

    /**
     * Retrieve searchable attributes
     *
     * @return Attribute[]
     */

    private function getSearchableAttributes(): array
    {
        $attributes = [];
        $productAttributes = $this->getAttributes();

        foreach ($productAttributes as $attribute) {
            $attributes[$attribute->getAttributeCode()] = $attribute;
        }

        return $attributes;
    }

    /**
     * Generate search request for search products via GraphQL
     *
     * @return                                       array
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    private function generateRequest()
    {
        $request = [];
        foreach ($this->getSearchableAttributes() as $attribute) {
            if (\in_array($attribute->getAttributeCode(), ['price', 'visibility', 'category_ids'])) {
                //some fields have special semantics
                continue;
            }
            $queryName = $attribute->getAttributeCode() . '_query';
            $filterName = $attribute->getAttributeCode() . RequestGenerator::FILTER_SUFFIX;
            $request['queries'][$this->requestNameWithAggregation]['queryReference'][] = [
                'clause' => 'must',
                'ref' => $queryName,
            ];

            switch ($attribute->getBackendType()) {
                case 'static':
                case 'text':
                case 'varchar':
                    if ($this->isExactMatchAttribute($attribute)) {
                        $request['queries'][$queryName] = $this->generateFilterQuery($queryName, $filterName);
                        $request['filters'][$filterName] = $this->generateTermFilter($filterName, $attribute);
                    } else {
                        $request['queries'][$queryName] = $this->generateMatchQuery($queryName, $attribute);
                    }
                    break;
                case 'decimal':
                case 'datetime':
                case 'date':
                    $request['queries'][$queryName] = $this->generateFilterQuery($queryName, $filterName);
                    $request['filters'][$filterName] = $this->generateRangeFilter($filterName, $attribute);
                    break;
                default:
                    $request['queries'][$queryName] = $this->generateFilterQuery($queryName, $filterName);
                    $request['filters'][$filterName] = $this->generateTermFilter($filterName, $attribute);
            }
            $generator = $this->generatorResolver->getGeneratorForType($attribute->getBackendType());

            if ($attribute->getData('is_filterable')) {
                $bucketName = $attribute->getAttributeCode() . self::BUCKET_SUFFIX;
                $request['aggregations'][$bucketName] = $generator->getAggregationData($attribute, $bucketName);
            }

            $this->addSearchAttributeToFullTextSearch($attribute, $request);
        }

        return $request;
    }

    /**
     * Add attribute with specified boost to "search" query used in full text search
     *
     * @param  DataObject $attribute
     * @param  array      $request
     * @return void
     */
    private function addSearchAttributeToFullTextSearch($attribute, &$request): void
    {
        // Match search by custom price attribute isn't supported
        if ($attribute->getFrontendInput() !== 'price') {
            $request['queries']['search']['match'][] = [
                'field' => $attribute->getAttributeCode(),
                'boost' => $attribute->getSearchWeight() ?: 1,
            ];
        }
    }

    /**
     * Return array representation of range filter
     *
     * @param  string     $filterName
     * @param  DataObject $attribute
     * @return array
     */
    private function generateRangeFilter(string $filterName, $attribute)
    {
        return [
            'field' => $attribute->getAttributeCode(),
            'name' => $filterName,
            'type' => FilterInterface::TYPE_RANGE,
            'from' => '$' . $attribute->getAttributeCode() . '.from$',
            'to' => '$' . $attribute->getAttributeCode() . '.to$',
        ];
    }

    /**
     * Return array representation of term filter
     *
     * @param  string     $filterName
     * @param  DataObject $attribute
     * @return array
     */
    private function generateTermFilter(string $filterName, $attribute)
    {
        return [
            'type' => FilterInterface::TYPE_TERM,
            'name' => $filterName,
            'field' => $attribute->getAttributeCode(),
            'value' => '$' . $attribute->getAttributeCode() . '$',
        ];
    }

    /**
     * Return array representation of query based on filter
     *
     * @param  string $queryName
     * @param  string $filterName
     * @return array
     */
    private function generateFilterQuery(string $queryName, string $filterName)
    {
        return [
            'name' => $queryName,
            'type' => QueryInterface::TYPE_FILTER,
            'filterReference' => [
                [
                    'ref' => $filterName,
                ],
            ],
        ];
    }

    /**
     * Return array representation of match query
     *
     * @param  string     $queryName
     * @param  DataObject $attribute
     * @return array
     */
    private function generateMatchQuery(string $queryName, $attribute)
    {
        return [
            'name' => $queryName,
            'type' => 'matchQuery',
            'value' => '$' . $attribute->getAttributeCode() . '$',
            'match' => [
                [
                    'field' => $attribute->getAttributeCode(),
                    'boost' => $attribute->getSearchWeight() ?: 1,
                ],
            ],
        ];
    }

    /**
     * Check if attribute's filter should use exact match
     *
     * @param  DataObject $attribute
     * @return bool
     */
    private function isExactMatchAttribute($attribute)
    {
        if (in_array($attribute->getFrontendInput(), ['select', 'multiselect'])) {
            return true;
        }
        if (in_array($attribute->getAttributeCode(), $this->exactMatchAttributes)) {
            return true;
        }

        return false;
    }

    /**
     * Replace Attribute collection stub with direct SQL query
     *
     * @return array
     */
    private function getAttributes()
    {
        $connection = $this->resourceConnection->getConnection();
        $cond = [
            $connection->quoteInto('c.is_searchable IN (?)', [1]),
            $connection->quoteInto('c.is_visible_in_advanced_search IN (?)', [1]),
            $connection->quoteInto('c.is_filterable IN (?)', [1,2]),
            $connection->quoteInto('c.is_filterable_in_search IN (?)', [1])
        ];
        $attrSelect = $connection->select()
            ->from(
                ['a' => $this->resourceConnection->getTableName('eav_attribute')],
                [
                       'a.attribute_id',
                       'a.attribute_code',
                       'a.backend_type',
                       'a.frontend_input'
                   ]
            )
            ->joinLeft(
                ['c' => $this->resourceConnection->getTableName('catalog_eav_attribute')],
                'a.attribute_id = c.attribute_id',
                [
                    'c.is_searchable',
                    'c.is_visible_in_advanced_search',
                    'c.is_filterable',
                    'c.is_filterable_in_search'
                ]
            )
            ->where(implode(' OR ', $cond))
            ->where('a.entity_type_id=?', 4);
        $rows = $connection->fetchAssoc($attrSelect);
        $attributes = [];
        if ($rows) {
            foreach ($rows as $data) {
                $attribute = $this->objectManager->create(DataObject::class);
                foreach ($data as $index => $value) {
                    $attribute->setData($index, $value);
                }
                $attributes[] = $attribute;
            }
        }
        return $attributes;
    }
}
