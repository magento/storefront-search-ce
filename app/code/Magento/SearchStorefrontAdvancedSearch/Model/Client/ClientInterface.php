<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\SearchStorefrontAdvancedSearch\Model\Client;

/**
 * Copied from Magento_AdvancedSearch
 */
interface ClientInterface
{
    /**
     * Validate connection params for search engine
     *
     * @return bool
     */
    public function testConnection();
}
