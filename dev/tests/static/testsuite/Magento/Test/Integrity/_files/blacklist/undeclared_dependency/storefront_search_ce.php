<?php
/**
 * Black list for the @see \Magento\Test\Integrity\DependencyTest::testUndeclared()
 *
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
return [
    'app/code/Magento/Store/etc/di.xml' => ['Magento\SearchStorefrontStore'],
    'app/code/Magento/SearchStorefrontStore/etc/di.xml' => ['Magento\Store'],
    'app/code/Magento/SearchStorefrontElasticsearch7/etc/di.xml' => ['Magento\Elasticsearch']
];
