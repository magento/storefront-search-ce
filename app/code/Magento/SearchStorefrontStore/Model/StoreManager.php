<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Magento\SearchStorefrontStore\Model;

/**
 * Copied and adapted from Magento/Store
 */
class StoreManager implements \Magento\SearchStorefrontStore\Model\StoreManagerInterface
{
    /**
     * @var Store
     */
    private $currentStore;

    /**
     * @param StoreFactory $storeFactory
     */
    public function __construct(
        StoreFactory $storeFactory
    ) {
        $this->setCurrentStore($storeFactory->create());
    }

    /**
     * @inheritDoc
     */
    public function getStore($storeId = null)
    {
        return $this->currentStore;
    }

    /**
     * @inheritDoc
     */
    public function setCurrentStore($store)
    {
        $this->currentStore = $store;
    }
}
