<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\SearchStorefrontStub\Model\Search;

use Magento\Framework\Search\Adapter\OptionsInterface;

/**
 * Copied from \Magento\CatalogSearch\Model\Adapter\Options
 * TODO: get config after resolving https://github.com/magento/catalog-storefront/issues/427
 */
class Options implements OptionsInterface
{
    const XML_PATH_INTERVAL_DIVISION_LIMIT = 'catalog/layered_navigation/interval_division_limit';
    const XML_PATH_RANGE_STEP = 'catalog/layered_navigation/price_range_step';
    const XML_PATH_RANGE_MAX_INTERVALS = 'catalog/layered_navigation/price_range_max_intervals';

    /**
     * @inheritdoc
     */
    public function get()
    {
        // get default from app/code/Magento/LayeredNavigation/etc/config.xml
        return [
            'interval_division_limit' => 9,
            'range_step' =>100,
            'min_range_power' => 10,
            'max_intervals_number' => 10
        ];
    }
}
