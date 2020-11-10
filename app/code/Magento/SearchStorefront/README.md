# Overview

Module Magento_SearchStorefront provides Product Search service implementation and has the following responsibilities:

- Provide product search functionality by search term and specified filters and returns ids of product that matches search criteria

Here is the example of request and response to Search Service
In case of data is not found for specified id Service doesn't return any data
```
    // Request
    {
      "filters": [
        {
          "in": [],
          "attribute": "price",
          "range": {
            "from": 10,
            "to": 150
          }
        }
      ],
      "sort": [],
      "phrase": "top",
      "store": "1",
      "page_size": 20,
      "current_page": 0,
      "include_aggregations": true,
      "customer_group_id": 0
    }{
      "filters": [
        {
          "in": [],
          "attribute": "price",
          "range": {
            "from": 10,
            "to": 150
          }
        }
      ],
      "sort": [],
      "phrase": "top",
      "store": "1",
      "page_size": 20,
      "current_page": 0,
      "include_aggregations": true,
      "customer_group_id": 0
    }{
      "filters": [
        {
          "in": [],
          "attribute": "price",
          "range": {
            "from": 10,
            "to": 150
          }
        }
      ],
      "sort": [],
      "phrase": "top",
      "store": "1",
      "page_size": 20,
      "current_page": 0,
      "include_aggregations": true,
      "customer_group_id": 0
    }

// Response
{
    "total_count": 18,
    "items": [1099, 1105, 1097, 1100, 7, 1154],
    "facets": [
        {
            "attribute": "fashion_material",
            "label": "Material",
            "count": 12,
            "options": [
                {
                    "value": "5434",
                    "label": "14K Gold",
                    "count": 1
                }
            ]
        }
    ],
    "page_info": {
        "current_page": 1,
        "page_size": 20,
        "total_pages": 1
    }
}
```


## Storage

Search service requires connection to elasticsearch as main product data storage and DB as storage of attributes data
that is required to build aggregations.

Default Storage configuration:
(You can override any options through app/etc/env.php file.)
```
    'db' => [
        'connection' => [
            'default' => [
                'host' => 'db',
                'dbname' => 'magento',
                'username' => 'root',
                'password' => 'root',
                'model' => 'mysql4',
                'engine' => 'innodb',
                'initStatements' => 'SET NAMES utf8;',
                'active' => '1',
                'driver_options' => [
                    1014 => false
                ]
            ]
        ],
        'table_prefix' => ''
    ],
    'search-store-front' => [
        'connections' => [
            'default' => [
                'protocol' => 'http',
                'hostname' => 'elastic',
                'port' => '9200',
                'enable_auth' => '',
                'username' => '',
                'password' => '',
                'timeout' => 30
            ]
        ],
        'engine' => 'storefrontElasticsearch6',
        'minimum_should_match' => 1,
        'index_prefix' => 'magento2_',
        'source_current_version' => 1
    ],
```

Service implementation of ScopeConfigInterface is reading configuration from deployment configuration files and db, 
it has configuration fallback between scopes.

Add following configuration to your deployment config files:

         'system' => [
            'stores' => [
                'catalog' => [
                    'layered_navigation' => [
                        'price_range_calculation' => 'auto',
                        'interval_division_limit' => 1,
                        'price_range_step' => 100,
                        'price_range_max_intervals' => 10,
                        'one_price_interval' => 1
                    ]
                ]
            ]
        ],
