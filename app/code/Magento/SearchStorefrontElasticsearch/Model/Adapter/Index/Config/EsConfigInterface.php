<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\SearchStorefrontElasticsearch\Model\Adapter\Index\Config;

/**
 * Copy of Elasticsearch\Model\Adapter\Index\Config\EsConfigInterface
 */
interface EsConfigInterface
{
    /**
     * Get stemmer info from config
     *
     * @return array
     */
    public function getStemmerInfo();

    /**
     * Get stopwords info from config
     *
     * @return array
     */
    public function getStopwordsInfo();
}
