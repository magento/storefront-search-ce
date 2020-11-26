<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\SearchStorefrontStub\Framework\App\Request;

use Magento\Framework\App\ObjectManager;

/**
 * Stub class required to build \Magento\Framework\App\Response\Http and \Magento\Framework\App\Request\Http
 * Do nothing for standalone installation and proxy request to Magento\Store\App\Request\PathInfoProcessor
 * with monolithic installation
 */
class DefaultPathInfoProcessor implements \Magento\Framework\App\Request\PathInfoProcessorInterface
{
    /**
     * Do not process pathinfo
     *
     * @param  \Magento\Framework\App\RequestInterface $request
     * @param  string                                  $pathInfo
     * @return string
     */
    public function process(\Magento\Framework\App\RequestInterface $request, $pathInfo)
    {
        $monolithClass = \Magento\Store\App\Request\PathInfoProcessor::class;
        if (\class_exists($monolithClass)) {
            return ObjectManager::getInstance()->get($monolithClass)->process($request, $pathInfo);
        }
        return $pathInfo;
    }
}
