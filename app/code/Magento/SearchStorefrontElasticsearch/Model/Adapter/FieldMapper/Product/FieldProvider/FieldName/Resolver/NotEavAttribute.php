<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Magento\SearchStorefrontElasticsearch\Model\Adapter\FieldMapper\Product\FieldProvider\FieldName\Resolver;

use Magento\SearchStorefrontElasticsearch\Model\Adapter\FieldMapper\Product\AttributeAdapter;
use Magento\SearchStorefrontElasticsearch\Model\Adapter\FieldMapper\Product\FieldProvider\FieldName\ResolverInterface;

/**
 * Resolver field name for not EAV attribute.
 * Copy of MagentoElasticsearch\Model\Adapter\FieldMapper\Product\FieldProvider\FieldName\Resolver\NotEavAttribute
 */
class NotEavAttribute implements ResolverInterface
{
    /**
     * Get field name for not EAV attributes.
     *
     * @param  AttributeAdapter $attribute
     * @param  array            $context
     * @return string
     */
    public function getFieldName(AttributeAdapter $attribute, $context = []): ?string
    {
        if (!$attribute->isEavAttribute()) {
            return $attribute->getAttributeCode();
        }

        return null;
    }
}
