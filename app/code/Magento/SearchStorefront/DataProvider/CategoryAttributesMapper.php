<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Magento\SearchStorefront\DataProvider;

/**
 * Copied from Magento\CatalogGraphQl
 *
 * Map for category attributes.
 */
class CategoryAttributesMapper
{
    /**
     * Returns attribute values for given attribute codes.
     *
     * @param  array $fetchResult
     * @return array
     */
    public function getAttributesValues(array $fetchResult): array
    {
        $attributes = [];

        foreach ($fetchResult as $row) {
            if (!isset($attributes[$row['entity_id']])) {
                $attributes[$row['entity_id']] = $row;
                //TODO: do we need to introduce field mapping?
                $attributes[$row['entity_id']]['id'] = $row['entity_id'];
            }
            if (isset($row['attribute_code'])) {
                $attributes[$row['entity_id']][$row['attribute_code']] = $row['value'];
            }
        }

        return $attributes;
    }
}
