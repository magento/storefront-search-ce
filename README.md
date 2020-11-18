# Overview
This repository provides Read/Write API through gRPC for Search domain area

NOTE: existing implementation is a "transition" phase which utilize Magento Monolith Elasticsearch index ad a data source among with DB tables to retrieve necessary data to build search response. 

### Install
Current repository temporary adapted for monolith installation. To be able install search service as standalone application do the next steps:  

1. Set auth.json `cp auth.json.tmpl auth.json` and provide correct credentials 
1. Copy composer `cp composer.json.standalone composer.json`
1. `composer install`
1. Run `bin/command storefront:search:init` to configure connection

## GRPC up (local php)
1. Run bin/magento storefront:grpc:init \\\Magento\\\SearchStorefrontApi\\\Api\\\SearchProxyServer
2. ./vendor/grpc-server

## GRPC-UI
1. install grpcui
2. grpcui -plaintext -proto search.proto -port 8080 -bind 0.0.0.0 -import-path path_to_your_magento_project_root localhost:9001

### Contributing
Contributions are welcomed! Read the [Contributing Guide](./CONTRIBUTING.md) for more information.

### Licensing
This project is licensed under the OSL-3.0 License. See [LICENSE](./LICENSE.md) for more information.
