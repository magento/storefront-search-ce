<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Magento\SearchStorefrontElasticsearch\SearchAdapter\Query\ValueTransformer;

use Magento\SearchStorefrontElasticsearch\Model\Adapter\FieldType\Date;
use Magento\SearchStorefrontElasticsearch\SearchAdapter\Query\ValueTransformerInterface;

/**
 * Value transformer for date type fields.
 * Copy of Elasticsearch\SearchAdapter\Query\ValueTransformer\DateTransformer
 */
class DateTransformer implements ValueTransformerInterface
{
    /**
     * @var Date
     */
    private $dateFieldType;

    /**
     * @param Date $dateFieldType
     */
    public function __construct(Date $dateFieldType)
    {
        $this->dateFieldType = $dateFieldType;
    }

    /**
     * @inheritdoc
     */
    public function transform(string $value): ?string
    {
        try {
            $formattedDate = $this->dateFieldType->formatDate($value);
        } catch (\Exception $e) {
            $formattedDate = null;
        }

        return $formattedDate;
    }
}
