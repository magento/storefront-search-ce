<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\SearchStorefrontStore\Model\Scope\Resolver;

use Magento\Framework\Exception\NoSuchEntityException;

class Website implements \Magento\Framework\App\ScopeResolverInterface
{
    public const WEBSITE_TABLE = 'store_website';

    /**
     * @var \Magento\Framework\App\ResourceConnection
     */
    private $resourceConnection;

    /**
     * @var \Magento\SearchStorefrontStore\Model\StoreInterfaceFactory
     */
    private $scopeFactory;

    /**
     * @param \Magento\Framework\App\ResourceConnection                  $resourceConnection
     * @param \Magento\SearchStorefrontStore\Model\StoreInterfaceFactory $scopeFactory
     */
    public function __construct(
        \Magento\Framework\App\ResourceConnection                  $resourceConnection,
        \Magento\SearchStorefrontStore\Model\StoreInterfaceFactory $scopeFactory
    ) {
        $this->resourceConnection = $resourceConnection;
        $this->scopeFactory = $scopeFactory;
    }

    /**
     * @inheritdoc
     *
     * @throws NoSuchEntityException
     */
    public function getScope($scopeId = null)
    {
        $scopeData = $this->loadData($scopeId, false);
        return $this->populate($scopeData);
    }

    /**
     * Retrieve a list of available stores
     *
     * @return \Magento\SearchStorefrontStore\Model\StoreInterface[]
     * @throws NoSuchEntityException
     */
    public function getScopes()
    {
        $scopes = [];
        $scopeData = $this->loadData(null, true);

        foreach ($scopeData as $item) {
            $scopes[] = $this->populate($item);
        }

        return $scopes;
    }

    /**
     * Load data from DB
     *
     * @param  int|string|null $scopeId
     * @param  bool $loadAll
     * @return array|mixed
     */
    public function loadData($scopeId = null, $loadAll = false)
    {
        $connection = $this->resourceConnection->getConnection();
        $select = $connection->select()
            ->from($connection->getTableName(self::WEBSITE_TABLE));

        if ($loadAll) {
            return $connection->fetchAll($select);
        }

        if ($scopeId) {
            $select->where('website_id = ?', $scopeId);
        } else {
            $select->where('is_default = 1');
        }

        return $connection->fetchRow($select);
    }

    /**
     * Fill model with data
     *
     * @param array $data
     * @return \Magento\Framework\App\ScopeInterface
     * @throws NoSuchEntityException
     */
    private function populate(array $data = [])
    {
        if (empty($data)) {
            throw new NoSuchEntityException(__('Cannot find requested website'));
        }

        $object = $this->scopeFactory->create();
        $object->setData('id', $data['website_id']);
        $object->setData('code', $data['code']);
        $object->setData('name', $data['name']);
        $object->setData('scope_type', 'website');

        return $object;
    }
}
