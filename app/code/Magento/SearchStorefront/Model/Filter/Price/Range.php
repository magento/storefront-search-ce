<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Magento\SearchStorefront\Model\Filter\Price;

use Magento\SearchStorefrontStub\Model\Search\Options;

class Range
{
    const PATH_RANGE_STEP = 'range_step';

    /**
     * @var Options
     */
    private $rangeOptions;

    /**
     * @param Options $rangeOptions
     */
    public function __construct(
        Options $rangeOptions
    ) {
        $this->rangeOptions = $rangeOptions;
    }

    /**
     * Get price range for calculation algorithm
     *
     * @return float
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getPriceRange()
    {
        // atm we use default settings from app/code/Magento/LayeredNavigation/etc/config.xml
        return $this->rangeOptions->get()[self::PATH_RANGE_STEP];
    }
}
