<?php

/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Magento\SearchStorefront\Model\Catalog\ResourceModel\Category;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * {@inheritDoc}
     */
    protected function _construct()
    {
        $this->_init(
            \Magento\SearchStorefront\Model\Catalog\Category::class,
            \Magento\SearchStorefront\Model\Catalog\ResourceModel\Category::class
        );
    }

    /**
     * Return array of fields to load attribute values
     *
     * @return string[]
     * @codeCoverageIgnore
     */
    protected function _getLoadDataFields()
    {
        return [
            'attribute_id',
            'entity_type_id',
            'attribute_code',
            'attribute_model',
            'backend_model',
            'backend_type',
            'backend_table',
            'frontend_input',
            'source_model'
        ];
    }

    /**
     * Return array of fields to load attribute values
     *
     * @return string[]
     * @codeCoverageIgnore
     */
    protected function _getLoadDataAdditionalFields()
    {
        return [
            'is_searchable',
            'is_visible_in_advanced_search',
            'is_filterable',
            'is_filterable_in_search'
        ];
    }

    /**
     * Initialize select object
     *
     * @return $this
     */
    protected function _initSelect()
    {
        $this->getSelect()->from(
            ['main_table' => $this->getResource()->getMainTable()],
            $this->_getLoadDataFields()
        )->join(
            ['additional_table' => $this->getTable('catalog_eav_attribute')],
            'additional_table.attribute_id = main_table.attribute_id',
            $this->_getLoadDataAdditionalFields()
        );
        return $this;
    }

}
