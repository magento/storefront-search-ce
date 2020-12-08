<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\SearchStorefrontElasticsearch\Model\Adapter\Index\Config;

use Magento\Framework\Config\Data;

/**
 * Copy of Elasticsearch\Model\Adapter\Index\Config\Converter\EsConfig
 */
class EsConfig extends Data implements EsConfigInterface
{
    /**
     * @inheritdoc
     */
    public function getStemmerInfo()
    {
        return $this->get('stemmerInfo');
    }

    /**
     * @inheritdoc
     */
    public function getStopwordsInfo()
    {
        return $this->get('stopwordsInfo');
    }
}
