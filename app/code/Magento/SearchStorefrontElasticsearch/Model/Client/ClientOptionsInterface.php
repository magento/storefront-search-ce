<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\SearchStorefrontElasticsearch\Model\Client;

/**
 * Copied from Magento_AdvancedSearch
 */
interface ClientOptionsInterface
{
    /**
     * Return search client options
     *
     * @param  array $options
     * @return array
     */
    public function prepareClientOptions($options = []);
}
