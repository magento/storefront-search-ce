<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Magento\SearchStorefront\Model\Filter\Price;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\ResourceConnection;
use Magento\SearchStorefrontStore\Model\ScopeInterface;
use Magento\SearchStorefrontStore\Model\StoreManagerInterface;

class Range
{
    const XML_PATH_RANGE_STEP = 'catalog/layered_navigation/price_range_step';

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @var ResourceConnection
     */
    private $resourceConnection;

    /**
     * @param StoreManagerInterface $storeManager
     * @param CategoryRepositoryInterface $categoryRepository
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        StoreManagerInterface $storeManager,
        ScopeConfigInterface $scopeConfig,
        ResourceConnection $resourceConnection
    ) {
        $this->storeManager = $storeManager;
        $this->scopeConfig = $scopeConfig;
        $this->resourceConnection = $resourceConnection;
    }

    /**
     * @return float
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getPriceRange()
    {
        return $this->getFilterPriceRange() ?? $this->getConfigRangeStep($this->storeManager->getStore()->getId());
    }

    /**
     * @param $storeId
     * @return float
     */
    public function getConfigRangeStep($storeId)
    {
        return (double) $this->scopeConfig->getValue(
            self::XML_PATH_RANGE_STEP,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     *
     * Direct sql for category's filter_price_range attribute value
     *
     * @return float|bool
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    private function getFilterPriceRange()
    {
        $store = $this->storeManager->getStore();
        $connection = $this->resourceConnection->getConnection();
        $attributeSelect = $connection->select()
            ->from(['a' => $this->resourceConnection->getTableName('eav_attribute')],
                   [
                       'id' => 'a.attribute_id',
                       'type' => 'a.backend_type'
                   ])
            ->where('a.attribute_code=?', 'filter_price_range');

        $attribute = $connection->fetchRow($attributeSelect);

        $table = $this->resourceConnection->getTableName('catalog_category_entity_'.$attribute['type']);
        $categorySelect = $connection->select()
            ->from(['c' => $table],
                    [
                        'value' => 'c.value'
                    ])
            ->where('c.entity_id=?', $store->getRootCategoryId()) //TODO: is it a staging entity and should be used "row_id"?
            ->where('c.attribute_id=?', $attribute['id'])
            ->where('c.store_id=?', $store->getId());

        $filter = $connection->fetchRow($categorySelect);

        return $filter['value'] ?? $filter;
    }
}
