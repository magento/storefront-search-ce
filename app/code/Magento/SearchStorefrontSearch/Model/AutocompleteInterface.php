<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\SearchStorefrontSearch\Model;

use Magento\SearchStorefrontSearch\Model\Autocomplete\ItemInterface;

interface AutocompleteInterface
{
    /**
     * @return ItemInterface[]
     */
    public function getItems();
}
