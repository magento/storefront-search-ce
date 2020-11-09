<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\SearchStorefrontSearch\Model\Autocomplete;

interface DataProviderInterface
{
    /**
     * @return ItemInterface[]
     */
    public function getItems();
}
