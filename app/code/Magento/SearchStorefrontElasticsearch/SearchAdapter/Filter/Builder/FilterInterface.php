<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\SearchStorefrontElasticsearch\SearchAdapter\Filter\Builder;

use Magento\Framework\Search\Request\FilterInterface as RequestFilterInterface;

/**
 * Copy of Magento\Elasticsearch\SearchAdapter\Filter\Builder\FilterInterface
 */
interface FilterInterface
{
    /**
     * @param RequestFilterInterface $filter
     * @return array
     * @since 100.1.0
     */
    public function buildFilter(RequestFilterInterface $filter);
}
