<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\SearchStorefrontElasticsearch\Model\Adapter;

use Magento\SearchStorefrontElasticsearch\Model\Adapter\FieldMapper\Product\FieldProvider\FieldType\ResolverInterface;

/**
 * Class FieldType
 *
 * @deprecated 100.3.0 This class provide not full data about field type. Only basic rules apply in this class.
 * @see ResolverInterface
 * Copy of Magento\Elasticsearch\Model\Adapter\FieldType removed soft dependency on EAV module
 */
class FieldType
{
    /**#@+
     * @deprecated
     * @see \Magento\SearchStorefrontElasticsearch\Model\Adapter\FieldMapper\Product\FieldProvider\FieldType\ConverterInterface
     *
     * Text flags for Elasticsearch field types
     */
    const ES_DATA_TYPE_STRING = 'string';
    const ES_DATA_TYPE_FLOAT = 'float';
    const ES_DATA_TYPE_INT = 'integer';
    const ES_DATA_TYPE_DATE = 'date';

    /** @deprecated */
    const ES_DATA_TYPE_ARRAY = 'array';
    /**#@-*/

    /**
     * Get field type.
     *
     * @deprecated 100.3.0
     * @see ResolverInterface::getFieldType
     *
     * @return string
     */
    public function getFieldType($attribute)
    {
        trigger_error('Class is deprecated', E_USER_DEPRECATED);
        $backendType = $attribute->getBackendType();
        $frontendInput = $attribute->getFrontendInput();

        if ($backendType === 'timestamp') {
            $fieldType = self::ES_DATA_TYPE_DATE;
        } elseif ((in_array($backendType, ['int', 'smallint'], true)
            || (in_array($frontendInput, ['select', 'boolean'], true) && $backendType !== 'varchar'))
            && !$attribute->getIsUserDefined()
        ) {
            $fieldType = self::ES_DATA_TYPE_INT;
        } elseif ($backendType === 'decimal') {
            $fieldType = self::ES_DATA_TYPE_FLOAT;
        } else {
            $fieldType = self::ES_DATA_TYPE_STRING;
        }

        return $fieldType;
    }
}
