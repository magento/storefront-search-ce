<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\SearchStorefrontElasticsearch\Elasticsearch5\SearchAdapter\Query;

use Magento\Framework\App\ObjectManager;
use Magento\Framework\Search\RequestInterface;
use Magento\SearchStorefrontElasticsearch\Model\Config;
use Magento\SearchStorefrontElasticsearch\SearchAdapter\Query\Builder\Aggregation as AggregationBuilder;
use Magento\SearchStorefrontElasticsearch\SearchAdapter\Query\Builder\Sort;
use Magento\SearchStorefrontElasticsearch\SearchAdapter\SearchIndexNameResolver;
use Magento\SearchStorefrontStore\Model\Scope\ScopeResolver;

/**
 * Query builder for search adapter.
 *
 * Copy of Magento\Elasticsearch\Elasticsearch5\SearchAdapter\Query\Builder
 */
class Builder
{
    /**
     * @var Config
     */
    protected $clientConfig;

    /**
     * @var SearchIndexNameResolver
     */
    protected $searchIndexNameResolver;

    /**
     * @var AggregationBuilder
     */
    protected $aggregationBuilder;

    /**
     * @var ScopeResolver
     */
    protected $scopeResolver;

    /**
     * @var Sort
     */
    private $sortBuilder;

    /**
     * @param Config $clientConfig
     * @param SearchIndexNameResolver $searchIndexNameResolver
     * @param AggregationBuilder $aggregationBuilder
     * @param ScopeResolver $scopeResolver
     * @param Sort|null $sortBuilder
     */
    public function __construct(
        Config $clientConfig,
        SearchIndexNameResolver $searchIndexNameResolver,
        AggregationBuilder $aggregationBuilder,
        ScopeResolver $scopeResolver,
        ?Sort $sortBuilder = null
    ) {
        $this->clientConfig = $clientConfig;
        $this->searchIndexNameResolver = $searchIndexNameResolver;
        $this->aggregationBuilder = $aggregationBuilder;
        $this->scopeResolver = $scopeResolver;
        $this->sortBuilder = $sortBuilder ?: ObjectManager::getInstance()->get(Sort::class);
    }

    /**
     * Set initial settings for query
     *
     * @param RequestInterface $request
     * @return array
     */
    public function initQuery(RequestInterface $request)
    {
        $dimension = current($request->getDimensions());
        $storeId = $this->scopeResolver->getScope($dimension->getValue())->getId();

        $searchQuery = [
            'index' => $this->searchIndexNameResolver->getIndexName($storeId, $request->getIndex()),
            'type' => $this->clientConfig->getEntityType(),
            'body' => [
                'from' => $request->getFrom(),
                'size' => $request->getSize(),
                'stored_fields' => ['_id', '_score'],
                'sort' => $this->sortBuilder->getSort($request),
                'query' => [],
            ],
        ];
        return $searchQuery;
    }

    /**
     * Add aggregations settings to query
     *
     * @param RequestInterface $request
     * @param array $searchQuery
     * @return array
     */
    public function initAggregations(
        RequestInterface $request,
        array $searchQuery
    ) {
        return $this->aggregationBuilder->build($request, $searchQuery);
    }
}
