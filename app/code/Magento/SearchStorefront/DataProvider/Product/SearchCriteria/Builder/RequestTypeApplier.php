<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Magento\SearchStorefront\DataProvider\Product\SearchCriteria\Builder;

use Magento\Framework\Api\Search\SearchCriteriaInterface;
use Magento\SearchStorefrontApi\Api\Data\ProductSearchRequestInterface;

/**
 * Applies search request name and price bucket calculation algorithm
 */
class RequestTypeApplier extends FilterApplier
{
    const REQUEST_WITH_AGGREGATION = 'search_service_request';
    const REQUEST_WITHOUT_AGGREGATION = 'search_service_request_without_aggregation';
    const PRICE_AGGREGATION_ALGORITHM_FIELD = 'price_dynamic_algorithm';

    /**
     * Apply search request name to search criteria.
     *
     * @param  ProductSearchRequestInterface $request
     * @param  SearchCriteriaInterface       $searchCriteria
     * @return SearchCriteriaInterface
     */
    public function apply(
        ProductSearchRequestInterface $request,
        SearchCriteriaInterface $searchCriteria
    ) : SearchCriteriaInterface {
        if ($request->getIncludeAggregations()) {
            $this->preparePriceAggregation($searchCriteria);
            $requestName = self::REQUEST_WITH_AGGREGATION;
        } else {
            $requestName = self::REQUEST_WITHOUT_AGGREGATION;
        }

        $searchCriteria->setRequestName($requestName);

        return $searchCriteria;
    }

    /**
     * Prepare price aggregation algorithm
     *
     * @param  SearchCriteriaInterface $searchCriteria
     * @return SearchCriteriaInterface
     */
    private function preparePriceAggregation(SearchCriteriaInterface $searchCriteria): SearchCriteriaInterface
    {
        // TODO: get config after resolving https://github.com/magento/catalog-storefront/issues/427
        $priceRangeCalculation = 'auto';

        if ($priceRangeCalculation) {
            $searchCriteria = $this->addFilter(
                $searchCriteria,
                self::PRICE_AGGREGATION_ALGORITHM_FIELD,
                $priceRangeCalculation
            );
        }

        return $searchCriteria;
    }
}
