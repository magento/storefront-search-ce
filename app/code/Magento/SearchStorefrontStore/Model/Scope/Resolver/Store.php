<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\SearchStorefrontStore\Model\Scope\Resolver;

use Magento\Framework\Exception\NoSuchEntityException;

class Store implements \Magento\Framework\App\ScopeResolverInterface
{
    public const STORE_TABLE = 'store';

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
     */
    public function getScope($scopeId = null)
    {
        $scopeData = $this->loadData($scopeId);
        return $this->populate($scopeData);
    }

    /**
     * Retrieve a list of available stores
     *
     * @return \Magento\SearchStorefrontStore\Model\StoreInterface[]
     */
    public function getScopes()
    {
        return [];
    }

    /**
     * Load store data from DB
     *
     * @param  int|string|null $scopeId
     * @param  bool $loadAll
     * @return array|mixed
     */
    public function loadData($scopeId = null, $loadAll = false)
    {
        $connection = $this->resourceConnection->getConnection();
        $select = $connection->select()
            ->from(['stores' => $connection->getTableName(self::STORE_TABLE)]);

        if ($loadAll) {
            return $connection->fetchAll($select);
        }

        if ($scopeId) {
            $select->where('store_id = ?', $scopeId);
        } else {
            $select->join(
                ['store_group' => $this->resourceConnection->getTableName('store_group')],
                'stores.store_id = store_group.default_store_id',
                []
            )->join(
                ['websites' => $this->resourceConnection->getTableName(Website::WEBSITE_TABLE)],
                'websites.default_group_id = store_group.group_id',
                ['websites_code' => 'code']
            )->where('websites.is_default = 1');
        }

        return $connection->fetchRow($select);
    }

    /**
     * Fills model with data
     *
     * @param array $data
     * @return \Magento\SearchStorefrontStore\Model\StoreInterface
     * @throws NoSuchEntityException
     */
    private function populate(array $data = [])
    {
        if (empty($data)) {
            throw new NoSuchEntityException(__('Cannot find requested store"'));
        }

        $object = $this->scopeFactory->create();
        $object->setData('id', $data['store_id']);
        $object->setData('code', $data['code']);
        $object->setData('name', $data['name']);
        $object->setData('scope_type', 'store');

        return $object;
    }
}
