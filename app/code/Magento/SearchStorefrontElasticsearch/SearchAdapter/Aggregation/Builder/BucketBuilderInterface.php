<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\SearchStorefrontElasticsearch\SearchAdapter\Aggregation\Builder;

use Magento\Framework\Search\Dynamic\DataProviderInterface;
use Magento\Framework\Search\Request\BucketInterface as RequestBucketInterface;
use Magento\Framework\Search\Request\Dimension;

/**
 * Copy of Magento\Elasticsearch\SearchAdapter\Aggregation\Builder\BucketBuilderInterface
 */
interface BucketBuilderInterface
{
    /**
     * Provides bucket build functionality
     *
     * @param  RequestBucketInterface $bucket
     * @param  Dimension[]            $dimensions
     * @param  array                  $queryResult
     * @param  DataProviderInterface  $dataProvider
     * @return array
     */
    public function build(
        RequestBucketInterface $bucket,
        array $dimensions,
        array $queryResult,
        DataProviderInterface $dataProvider
    );
}
