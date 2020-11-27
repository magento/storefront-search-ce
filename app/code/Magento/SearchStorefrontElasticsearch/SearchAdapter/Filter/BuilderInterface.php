<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\SearchStorefrontElasticsearch\SearchAdapter\Filter;

use Magento\Framework\Search\Request\FilterInterface as RequestFilterInterface;

/**
 * Copy of Magento\Elasticsearch\SearchAdapter\Filter\BuilderInterface
 */
interface BuilderInterface
{
    const FILTER_QUERY_CONDITION_MUST = 'must';

    const FILTER_QUERY_CONDITION_SHOULD = 'should';

    const FILTER_QUERY_CONDITION_MUST_NOT = 'must_not';

    /**
     * Build filter based on request
     *
     * @param  RequestFilterInterface $filter
     * @param  string                 $conditionType
     * @return string
     */
    public function build(RequestFilterInterface $filter, $conditionType);
}
