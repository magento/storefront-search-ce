<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\SearchStorefrontElasticsearch\Model\Adapter\Index\Config;

/**
 * Copy of Magento\Elasticsearch\Model\Adapter\Index\Config\EsConfigInterface
 */
interface EsConfigInterface
{
    /**
     * @return array
     */
    public function getStemmerInfo();

    /**
     * @return array
     */
    public function getStopwordsInfo();
}
