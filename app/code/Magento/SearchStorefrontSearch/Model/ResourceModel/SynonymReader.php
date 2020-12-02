<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\SearchStorefrontSearch\Model\ResourceModel;

use Magento\Framework\App\ResourceConnection;
use Magento\Framework\DB\Helper\Mysql\Fulltext;
use Magento\SearchStorefrontStore\Model\StoreManager;

/**
 * Synonym Reader resource model
 *
 * Copied and adapted Magento\Search\Model\ResourceModel\SynonymReader
 */
class SynonymReader
{
    /**
     * @var Fulltext $fullTextSelect
     */
    private $fullTextSelect;

    /**
     * Store manager
     *
     * @var StoreManager
     */
    private $storeManager;

    /**
     * @var ResourceConnection
     */
    private $resourceConnection;

    /**
     * @param StoreManager       $storeManager
     * @param Fulltext           $fulltext
     * @param ResourceConnection $resourceConnection
     */
    public function __construct(
        StoreManager $storeManager,
        Fulltext $fulltext,
        ResourceConnection $resourceConnection
    ) {
        $this->fullTextSelect = $fulltext;
        $this->storeManager = $storeManager;
        $this->resourceConnection = $resourceConnection;
    }

    /**
     * Custom load model: Get data by user query phrase
     *
     * @param \Magento\SearchStorefrontSearch\Model\SynonymReader $object
     * @param string $phrase
     * @return $this
     */
    public function loadByPhrase(\Magento\SearchStorefrontSearch\Model\SynonymReader $object, $phrase)
    {
        $rows = $this->queryByPhrase(strtolower($phrase));
        $synsPerScope = $this->getSynRowsPerScope($rows);

        if (!empty($synsPerScope[\Magento\SearchStorefrontStore\Model\ScopeInterface::SCOPE_STORES])) {
            $object->setData($synsPerScope[\Magento\SearchStorefrontStore\Model\ScopeInterface::SCOPE_STORES]);
        } elseif (!empty($synsPerScope[\Magento\SearchStorefrontStore\Model\ScopeInterface::SCOPE_WEBSITES])) {
            $object->setData($synsPerScope[\Magento\SearchStorefrontStore\Model\ScopeInterface::SCOPE_WEBSITES]);
        } else {
            $object->setData($synsPerScope[\Magento\Framework\App\Config\ScopeConfigInterface::SCOPE_TYPE_DEFAULT]);
        }
        return $this;
    }

    /**
     * A helper function to query by phrase and get results
     *
     * @param string $phrase
     * @return array
     */
    private function queryByPhrase($phrase)
    {
        $phrase = $this->fullTextSelect->removeSpecialCharacters($phrase);
        $matchQuery = $this->fullTextSelect->getMatchQuery(
            ['synonyms' => 'synonyms'],
            $this->escapePhrase($phrase),
            Fulltext::FULLTEXT_MODE_BOOLEAN
        );
        $query = $this->resourceConnection->getConnection()->select()->from(
            $this->resourceConnection->getTableName('search_synonyms'),
        )->where($matchQuery);

        return $this->resourceConnection->getConnection()->fetchAll($query);
    }

    /**
     * Cut trailing plus or minus sign, and @ symbol, using of which causes InnoDB to report a syntax error.
     *
     * @see https://dev.mysql.com/doc/refman/5.7/en/fulltext-boolean.html
     * @param string $phrase
     * @return string
     */
    private function escapePhrase(string $phrase): string
    {
        return preg_replace('/@+|[@+-]+$|[<>]/', '', $phrase);
    }

    /**
     * A private helper function to retrieve matching synonym groups per scope
     *
     * @param array $rows
     * @return array
     */
    private function getSynRowsPerScope($rows)
    {
        $synRowsForStoreView = [];
        $synRowsForWebsite = [];
        $synRowsForDefault = [];

        // The synonyms configured for current store view gets highest priority. Second highest is current website
        // scope. If there were no store view and website specific synonyms then at last 'default' (All store views)
        // will be considered.
        foreach ($rows as $row) {
            if ($this->isSynRowForStoreView($row)) {
                // Check for current store view
                $synRowsForStoreView[] = $row;
            } elseif (empty($synRowsForStoreView) && $this->isSynRowForWebsite($row)) {
                // Check for current website
                $synRowsForWebsite[] = $row;
            } elseif (empty($synRowsForStoreView)
                && empty($synRowsForWebsite)
                && $this->isSynRowForDefaultScope($row)) {
                // Check for all store views (i.e. global/default config)
                $synRowsForDefault[] = $row;
            }
        }
        $synsPerScope[\Magento\SearchStorefrontStore\Model\ScopeInterface::SCOPE_STORES] = $synRowsForStoreView;
        $synsPerScope[\Magento\SearchStorefrontStore\Model\ScopeInterface::SCOPE_WEBSITES] = $synRowsForWebsite;
        $synsPerScope[\Magento\Framework\App\Config\ScopeConfigInterface::SCOPE_TYPE_DEFAULT] = $synRowsForDefault;
        return $synsPerScope;
    }

    /**
     * A helper method to check if the synonym group row is for the current store view
     *
     * @param array $row
     * @return bool
     */
    private function isSynRowForStoreView($row)
    {
        $storeViewId = $this->storeManager->getStore()->getId();
        return ((int) $row['store_id'] === $storeViewId);
    }

    /**
     * A helper method to check if the synonym group row is for the current website
     *
     * @param array $row
     * @return bool
     */
    private function isSynRowForWebsite($row)
    {
        $websiteId = $this->storeManager->getStore()->getWebsiteId();
        return (((int) $row['website_id'] === $websiteId) && ((int) $row['store_id'] == 0));
    }

    /**
     * A helper method to check if the synonym group row is for all store views (default or global scope)
     *
     * @param array $row
     * @return bool
     */
    private function isSynRowForDefaultScope($row)
    {
        return (((int) $row['website_id'] == 0) && ((int) $row['store_id'] == 0));
    }
}
