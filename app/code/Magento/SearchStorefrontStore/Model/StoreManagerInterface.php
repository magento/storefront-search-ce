<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\SearchStorefrontStore\Model;

use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Store manager interface
 *
 * @api
 * @since 100.0.2
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
     * Allow or disallow single store mode
     *
     * @param bool $value
     * @return void
     */
    public function setIsSingleStoreModeAllowed($value);

    /**
     * Check if store has only one store view
     *
     * @return bool
     */
    public function hasSingleStore();

    /**
     * Check if system is run in the single store mode
     *
     * @return bool
     */
    public function isSingleStoreMode();

    /**
     * Retrieve application store object
     *
     * @param null|string|bool|int|\Magento\SearchStorefrontStore\Api\Data\StoreInterface $storeId
     * @return \Magento\SearchStorefrontStore\Api\Data\StoreInterface
     * @throws NoSuchEntityException If given store doesn't exist.
     */
    public function getStore($storeId = null);

    /**
     * Retrieve stores array
     *
     * @param bool $withDefault
     * @param bool $codeKey
     * @return \Magento\SearchStorefrontStore\Api\Data\StoreInterface[]
     */
    public function getStores($withDefault = false, $codeKey = false);

    /**
     * Retrieve application website object
     *
     * @param null|bool|int|string|\Magento\SearchStorefrontStore\Api\Data\WebsiteInterface $websiteId
     * @return \Magento\SearchStorefrontStore\Api\Data\WebsiteInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getWebsite($websiteId = null);

    /**
     * Get loaded websites
     *
     * @param bool $withDefault
     * @param bool $codeKey
     * @return \Magento\SearchStorefrontStore\Api\Data\WebsiteInterface[]
     */
    public function getWebsites($withDefault = false, $codeKey = false);

    /**
     * Reinitialize store list
     *
     * @return void
     */
    public function reinitStores();

    /**
     * Retrieve default store for default group and website
     *
     * @return \Magento\SearchStorefrontStore\Api\Data\StoreInterface|null
     */
    public function getDefaultStoreView();

    /**
     * Retrieve application store group object
     *
     * @param null|\Magento\SearchStorefrontStore\Api\Data\GroupInterface|string $groupId
     * @return \Magento\SearchStorefrontStore\Api\Data\GroupInterface
     */
    public function getGroup($groupId = null);

    /**
     * Prepare array of store groups
     *
     * @param bool $withDefault
     * @return \Magento\SearchStorefrontStore\Api\Data\GroupInterface[]
     */
    public function getGroups($withDefault = false);

    /**
     * Set current default store
     *
     * @param string|int|\Magento\SearchStorefrontStore\Api\Data\StoreInterface $store
     * @return void
     */
    public function setCurrentStore($store);
}
