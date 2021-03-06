<?php
// Generated by the Magento PHP proto generator.  DO NOT EDIT!

/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Magento\SearchStorefrontApi\Api;

use \Magento\SearchStorefrontApi\Api\Data\ProductSearchRequestInterface;
use \Magento\SearchStorefrontApi\Api\Data\ProductsSearchResultInterface;

/**
 * Autogenerated description for SearchInterface interface
 */
interface SearchInterface
{
    /**
     * Autogenerated description for searchProducts interface method
     *
     * @param  ProductSearchRequestInterface $request
     * @return ProductsSearchResultInterface
     * @throws \Throwable
     */
    public function searchProducts(ProductSearchRequestInterface $request): ProductsSearchResultInterface;
}
