<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\SearchStorefrontStore\Model;

use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Copied and adapted from Magento/Store
 */
interface StoreManagerInterface
{
    /**
     * Store cache context
     */
    const CONTEXT_STORE = 'store';

    /**
     * The store GET Param name
     */
    const PARAM_NAME = '___store';

    /**
     * Retrieve application store object
     *
     * @param  null|string|bool|int|\Magento\SearchStorefrontStore\Model\StoreInterface $storeId
     * @return \Magento\SearchStorefrontStore\Model\StoreInterface
     * @throws NoSuchEntityException If given store doesn't exist.
     */
    public function getStore($storeId = null);

    /**
     * Set current default store
     *
     * @param  string|int|\Magento\SearchStorefrontStore\Model\StoreInterface $store
     * @return void
     */
    public function setCurrentStore($store);
}
