<?php

/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Magento\SearchStorefront\Model\Catalog\ResourceModel\Category;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * {@inheritDoc}
     */
    protected function _construct()
    {
        $this->_init(
            \Magento\SearchStorefront\Model\Catalog\Category::class,
            \Magento\SearchStorefront\Model\Catalog\ResourceModel\Category::class
        );
    }
}
