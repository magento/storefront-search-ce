<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\SearchStorefrontElasticsearch\SearchAdapter\Aggregation\Builder;

use Magento\Framework\Search\Dynamic\DataProviderInterface;
use Magento\Framework\Search\Request\BucketInterface as RequestBucketInterface;

/**
 * Builder for term buckets.
 * Copy of Elasticsearch\SearchAdapter\Aggregation\Builder\Term
 */
class Term implements BucketBuilderInterface
{
    /**
     * @inheritdoc
     */
    public function build(
        RequestBucketInterface $bucket,
        array $dimensions,
        array $queryResult,
        DataProviderInterface $dataProvider
    ) {
        $buckets = $queryResult['aggregations'][$bucket->getName()]['buckets'] ?? [];
        $values = [];
        foreach ($buckets as $resultBucket) {
            $values[$resultBucket['key']] = [
                'value' => $resultBucket['key'],
                'count' => $resultBucket['doc_count'],
            ];
        }

        return $values;
    }
}
