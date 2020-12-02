<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\SearchStorefrontSearch\Model;

use Magento\SearchStorefrontSearch\Model\ResourceModel\SynonymReader as Reader;

/**
 * Data model to retrieve synonyms by passed in phrase
 * Adapted copy from Magento/Search
 */
class SynonymReader extends \Magento\Framework\DataObject
{
    /**
     * @var Reader
     */
    private $reader;

    /**
     * @param Reader $reader
     * @param array  $data
     */
    public function __construct(
        Reader $reader,
        array $data = [])
    {
        parent::__construct($data);
        $this->reader = $reader;
    }

    /**
     * Load synonyms by user query phrase in context of current store view
     *
     * @param string $phrase
     * @return $this
     */
    public function loadByPhrase(string $phrase)
    {
        // DB_query
        $this->reader->loadByPhrase($this, $phrase);
        return $this;
    }
}
