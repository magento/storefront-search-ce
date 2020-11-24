<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\SearchStorefrontStore\Model\Scope;

class ScopeResolver implements \Magento\Framework\App\ScopeResolverInterface
{
    /**
     * @var \Magento\SearchStorefrontStore\Model\StoreManagerInterface
     */
    private $_storeManager;

    /**
     * @param \Magento\SearchStorefrontStore\Model\StoreManagerInterface $storeManager
     */
    public function __construct(\Magento\SearchStorefrontStore\Model\StoreManagerInterface $storeManager)
    {
        $this->_storeManager = $storeManager;
    }

    /**
     * @param \Magento\SearchStorefrontStore\Model\StoreManagerInterface $storeManager
     */
    public function getScope($scopeId = null)
    {
        $scope = $this->_storeManager->getStore($scopeId);

        if (!$scope instanceof \Magento\Framework\App\ScopeInterface) {
            throw new \Magento\Framework\Exception\State\InitException(
                __('The scope object is invalid. Verify the scope object and try again.')
            );
        }

        return $scope;
    }

    /**
     * {@inheritdoc}
     * @throws \Magento\Framework\Exception\State\InitException
     */
    public function getScopes()
    {
        return $this->_storeManager->getStores();
    }
}
