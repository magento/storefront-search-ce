<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\SearchStorefrontSearch\Model;

/**
 * Data model to retrieve synonyms by passed in phrase
 *
 * @method \Magento\SearchStorefrontSearch\Model\SynonymReader setGroupId(int $group)
 * @method int getGroupId()
 * @method \Magento\SearchStorefrontSearch\Model\SynonymReader setStoreId(int $storeId)
 * @method int getStoreId()
 * @method \Magento\SearchStorefrontSearch\Model\SynonymReader setWebsiteId(int $websiteId)
 * @method int getWebsiteId()
 * @method \Magento\SearchStorefrontSearch\Model\SynonymReader setSynonyms(string $value)
 * @method string getSynonyms()
 */
class SynonymReader extends \Magento\Framework\DataObject
{
    /**
     * Load synonyms by user query phrase in context of current store view
     *
     * @param string $phrase
     * @return $this
     * @throws \Magento\Framework\Exception\LocalizedException
    */
    public function loadByPhrase($phrase)
    {
        // @TODO synonyms reader need to be implemented
        // (eg create indexer to index synonyms to elastic)
        return $this;
    }
}
