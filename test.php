<?php

/** @var \Magento\Framework\App\Http $app *\/
 * $app = $bootstrap->createApplication(\Magento\Framework\App\Http::class);
 * $bootstrap->run($app);
 * --------------------------------------------
 *
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */


use Magento\SearchStorefrontApi\Api\Data\ProductSearchRequest;
use Magento\SearchStorefrontApi\Api\Data\ProductSearchRequestMapper;

try {
    require __DIR__ . '/app/bootstrap.php';
} catch (\Exception $e) {
    echo <<<HTML
<div style="font:12px/1.35em arial, helvetica, sans-serif;">
    <div style="margin:0 0 25px 0; border-bottom:1px solid #ccc;">
        <h3 style="margin:0;font-size:1.7em;font-weight:normal;text-transform:none;text-align:left;color:#2f2f2f;">
        Autoload error</h3>
    </div>,
    <p>{$e->getMessage()}</p>
</div>
HTML;
    exit(1);
}

$bootstrap = \Magento\Framework\App\Bootstrap::create(BP, $_SERVER);

$om = $bootstrap->getObjectManager();

//$cfact = $om->create(\Magento\SearchStorefront\Model\Eav\Attribute\Product\CollectionFactory::class);
//
//$collection = $cfact->create()->addFieldToFilter(
//    ['is_searchable', 'is_visible_in_advanced_search', 'is_filterable', 'is_filterable_in_search'],
//    [1, 1, [1, 2], 1]
//);
//$collection->load();

/** @var ProductSearchRequestMapper $requestMapper */
$requestMapper = $om->create(ProductSearchRequestMapper::class);

$requestArray = [
    'phrase' => 'top',
    'store' => '1',
    'include_aggregations' => true,
    'customer_group_id' => 1,
    'filters' => [
        [
            'attribute' => 'color',
            'eq' => '98'
        ],
        [
            'attribute' => 'material',
            'in' => ['199','195']
        ],
        [
            'attribute' => 'price',
            'range' => [
                'from' => 10,
                'to' => 30
            ]
        ]
    ]
];
$request = $requestMapper->setData($requestArray)->build();

/** @var Magento\SearchStorefront\Model\SearchService $searchService */
$searchService = $om->get(\Magento\SearchStorefront\Model\SearchService::class);

$res = $searchService->searchProducts($request);

die();
