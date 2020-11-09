<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\SearchStorefrontStore\Model;

use Magento\Framework\App\ObjectManager;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\App\Config;

/**
 * Information Expert in store groups handling
 *
 * @package Magento\SearchStorefrontStore\Model
 */
class GroupRepository implements \Magento\SearchStorefrontStore\Api\GroupRepositoryInterface
{
    /**
     * @var GroupFactory
     */
    protected $groupFactory;

    /**
     * @var \Magento\SearchStorefrontStore\Api\Data\GroupInterface[]
     */
    protected $entities = [];

    /**
     * @var bool
     */
    protected $allLoaded = false;

    /**
     * @var \Magento\SearchStorefrontStore\Model\ResourceModel\Group\CollectionFactory
     */
    protected $groupCollectionFactory;

    /**
     * @var Config
     */
    private $appConfig;

    /**
     * @param GroupFactory $groupFactory
     * @param \Magento\SearchStorefrontStore\Model\ResourceModel\Group\CollectionFactory $groupCollectionFactory
     */
    public function __construct(
        GroupFactory $groupFactory,
        \Magento\SearchStorefrontStore\Model\ResourceModel\Group\CollectionFactory $groupCollectionFactory
    ) {
        $this->groupFactory = $groupFactory;
        $this->groupCollectionFactory = $groupCollectionFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function get($id)
    {
        if (isset($this->entities[$id])) {
            return $this->entities[$id];
        }

        $group = $this->groupFactory->create([
            'data' => $this->getAppConfig()->get('scopes', "groups/$id", [])
        ]);

        if (null === $group->getId()) {
            throw new NoSuchEntityException();
        }
        $this->entities[$id] = $group;
        return $group;
    }

    /**
     * {@inheritdoc}
     */
    public function getList()
    {
        if (!$this->allLoaded) {
            $groups = $this->getAppConfig()->get('scopes', 'groups', []);
            foreach ($groups as $data) {
                $group = $this->groupFactory->create([
                    'data' => $data
                ]);
                $this->entities[$group->getId()] = $group;
            }
            $this->allLoaded = true;
        }

        return $this->entities;
    }

    /**
     * {@inheritdoc}
     */
    public function clean()
    {
        $this->entities = [];
        $this->allLoaded = false;
    }

    /**
     * Retrieve application config.
     *
     * @deprecated 100.1.3
     * @return Config
     */
    private function getAppConfig()
    {
        if (!$this->appConfig) {
            $this->appConfig = ObjectManager::getInstance()->get(Config::class);
        }
        return $this->appConfig;
    }
}
