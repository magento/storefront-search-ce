<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\SearchStorefrontElasticsearch\SearchAdapter;

use Magento\Framework\ObjectManagerInterface;

/**
 * Aggregation Factory
 * Copy of Elasticsearch\SearchAdapter\AggregationFactory
 */
class AggregationFactory
{
    /**
     * Object Manager instance
     *
     * @var ObjectManagerInterface
     */
    protected $objectManager;

    /**
     * @param ObjectManagerInterface $objectManager
     */
    public function __construct(ObjectManagerInterface $objectManager)
    {
        $this->objectManager = $objectManager;
    }

    /**
     * Create Aggregation instance
     *
     * @param  array $rawAggregation
     * @return \Magento\Framework\Search\Response\Aggregation
     */
    public function create(array $rawAggregation)
    {
        $buckets = [];
        foreach ($rawAggregation as $rawBucketName => $rawBucket) {
            $buckets[$rawBucketName] = $this->objectManager->create(
                \Magento\Framework\Search\Response\Bucket::class,
                [
                    'name' => $rawBucketName,
                    'values' => $this->prepareValues($rawBucket)
                ]
            );
        }
        return $this->objectManager->create(
            \Magento\Framework\Search\Response\Aggregation::class,
            ['buckets' => $buckets]
        );
    }

    /**
     * Prepare values list
     *
     * @param  array $values
     * @return \Magento\Framework\Search\Response\Aggregation\Value[]
     */
    private function prepareValues(array $values)
    {
        $valuesObjects = [];
        foreach ($values as $name => $value) {
            $valuesObjects[] = $this->objectManager->create(
                \Magento\Framework\Search\Response\Aggregation\Value::class,
                [
                    'value' => $name,
                    'metrics' => $value,
                ]
            );
        }
        return $valuesObjects;
    }
}
