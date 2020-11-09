<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Magento\SearchStorefrontElasticsearch\Model\Adapter;

/**
 * Modifies fields mapping before save
 * Copy of Magento\Elasticsearch\Model\Adapter\FieldsMappingPreprocessorInterface
 */
interface FieldsMappingPreprocessorInterface
{
    /**
     * Modifies fields mapping before save
     *
     * @param array $mapping
     * @return array
     */
    public function process(array $mapping): array;
}
