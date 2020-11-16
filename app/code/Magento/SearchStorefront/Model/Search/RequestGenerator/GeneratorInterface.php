<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\SearchStorefront\Model\Search\RequestGenerator;

use Magento\Framework\DataObject;

/**
 * Catalog search reguest generator interface.
 */
interface GeneratorInterface
{
    /**
     * Get filter data for specific attribute.
     *
     * @param DataObject $attribute
     * @param string $filterName
     * @return array
     */
    public function getFilterData($attribute, $filterName);

    /**
     * Get aggregation data for specific attribute.
     *
     * @param DataObject $attribute
     * @param string $bucketName
     * @return array
     */
    public function getAggregationData($attribute, $bucketName);
}
