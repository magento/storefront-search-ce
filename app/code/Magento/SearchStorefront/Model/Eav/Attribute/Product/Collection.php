<?php

namespace Magento\SearchStorefront\Model\Eav\Attribute\Product;

class Collection extends \Magento\SearchStorefront\Model\Eav\Attribute\Collection
{
    protected $entityTypeId = 4;

    protected function _initSelect()
    {
        parent::_initSelect();
        $this->getSelect()->where(
            'main_table.entity_type_id = ?',
            $this->entityTypeId
        );
    }
}
