<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Magento\SearchStorefrontElasticsearch\SearchAdapter\Query\ValueTransformer;

use Magento\Framework\Search\Adapter\Preprocessor\PreprocessorInterface;
use Magento\SearchStorefrontElasticsearch\SearchAdapter\Query\ValueTransformerInterface;

/**
 * Value transformer for fields with text types.
 * Copy of Elasticsearch\SearchAdapter\Query\ValueTransformer\TextTransformer
 */
class TextTransformer implements ValueTransformerInterface
{
    /**
     * @var PreprocessorInterface[]
     */
    private $preprocessors;

    /**
     * @param PreprocessorInterface[] $preprocessors
     */
    public function __construct(array $preprocessors = [])
    {
        foreach ($preprocessors as $preprocessor) {
            if (!$preprocessor instanceof PreprocessorInterface) {
                throw new \InvalidArgumentException(
                    \sprintf('"%s" is not a instance of ValueTransformerInterface.', get_class($preprocessor))
                );
            }
        }

        $this->preprocessors = $preprocessors;
    }

    /**
     * @inheritdoc
     */
    public function transform(string $value): string
    {
        $value = $this->escape($value);
        foreach ($this->preprocessors as $preprocessor) {
            $value = $preprocessor->process($value);
        }

        return $value;
    }

    /**
     * Escape a value for special query characters such as ':', '(', ')', '*', '?', etc.
     *
     * @param  string $value
     * @return string
     */
    private function escape(string $value): string
    {
        $pattern = '/(\+|-|&&|\|\||!|\(|\)|\{|}|\[|]|\^|"|~|\/|\*|\?|:|\\\)/';
        $replace = '\\\$1';

        return preg_replace($pattern, $replace, $value);
    }
}
