<?php

namespace Magento\SearchStorefront\Model\Eav\Attribute;

use Magento\Framework\DB\Select;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected $_idFieldName = 'attribute_id';

    protected function _construct()
    {
        $this->_init(
            \Magento\SearchStorefront\Model\Eav\Attribute::class,
            \Magento\SearchStorefront\Model\Eav\Attribute\ResourceModel\Attribute::class
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
