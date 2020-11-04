# Overview
This repository provides Read/Write API through gRPC for Search domain area

### Contributing
Contributions are welcomed! Read the [Contributing Guide](./CONTRIBUTING.md) for more information.

### Licensing
This project is licensed under the OSL-3.0 License. See [LICENSE](./LICENSE.md) for more information.

### Install
1. composer install
2. bin/magento storefront:search:init
3. edit env.php to specify database and elasticsearch connections

## GRPC up (local php)
1. Run bin/magento storefront:grpc:init \\\Magento\\\SearchStorefrontApi\\\Api\\\SearchProxyServer
2. ./vendor/grpc-server

## GRPC-UI
1. install grpcui
2. grpcui -plaintext -proto search.proto -port 8080 -bind 0.0.0.0 -import-path path_to_your_magento_project_root localhost:9001
