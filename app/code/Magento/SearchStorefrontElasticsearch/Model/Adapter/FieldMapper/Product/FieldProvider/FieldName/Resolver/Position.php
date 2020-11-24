<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Magento\SearchStorefrontElasticsearch\Model\Adapter\FieldMapper\Product\FieldProvider\FieldName\Resolver;

use Magento\SearchStorefrontElasticsearch\Model\Adapter\FieldMapper\Product\AttributeAdapter;
use Magento\SearchStorefrontElasticsearch\Model\Adapter\FieldMapper\Product\FieldProvider\FieldName\ResolverInterface;
use Magento\Framework\Registry;
use Magento\SearchStorefrontStore\Model\StoreManagerInterface as StoreManager;

/**
 * Resolver field name for position attribute.
 */
class Position implements ResolverInterface
{
    /**
     * @var StoreManager
     */
    private $storeManager;

    /**
     * @param StoreManager $storeManager
     */
    public function __construct(
        StoreManager $storeManager
    ) {
        $this->storeManager = $storeManager;
    }

    /**
     * @inheritdoc
     */
    public function getFieldName(AttributeAdapter $attribute, $context = []): ?string
    {
        if ($attribute->getAttributeCode() === 'position') {
            return 'position_category_' . $this->storeManager->getStore()->getRootCategoryId();
        }

        return null;
    }
}
