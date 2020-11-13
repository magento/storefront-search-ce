<?php

namespace Magento\SearchStorefrontStub\Model\Eav\Attribute;

use Magento\Framework\DB\Select;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    /**
     * @var string
     */
    private $entityCode;

    protected $_idFieldName = 'attribute_id';

    protected function _construct()
    {
        $this->_init(
            \Magento\SearchStorefrontStub\Model\Eav\Attribute::class,
            \Magento\SearchStorefrontStub\Model\Eav\Attribute\ResourceModel\Attribute::class
        );
    }

    /**
     * @param string|null $entityCode
     * @return $this
     */
    public function setEntityTypeFilter(?string $entityCode = null)
    {
        if ($entityCode) {
            $connection = $this->getConnection();
            $select = $connection->select()
                ->from($connection->getTableName('eav_entity_type'))
                ->where('entity_type_code = ?', $entityCode);

            $result = $connection->fetchRow($select);
            $this->entityCode = $result['entity_type_id'] ?? null;
        }

        return $this;
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

    /**
     * @return Collection
     */
    public function _beforeLoad()
    {
        if ($this->entityCode) {
            $this->addFieldToFilter('entity_type_id', $this->entityCode);
        }

        return parent::_beforeLoad();
    }
}
