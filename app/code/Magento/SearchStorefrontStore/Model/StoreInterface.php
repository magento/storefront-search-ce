<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\SearchStorefrontStore\Model;

/**
 * Copied and adapted from Magento/Store
 */
interface StoreInterface
{
    /**
     * Get store id
     *
     * @return int
     */
    public function getId();

    /**
     * Get store code
     *
     * @return string
     */
    public function getCode();

    /**
     * Get website id
     *
     * @return int
     */
    public function getWebsiteId();
}
