<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\SearchStorefrontStore\Model;

/**
 * Copied and adapted from Magento/Store
 */
interface ScopeInterface
{
    /**#@+
     * Scope types
     */
    const SCOPE_STORES = 'stores';
    const SCOPE_WEBSITES = 'websites';

    const SCOPE_STORE   = 'store';
    /**#@-*/
}
