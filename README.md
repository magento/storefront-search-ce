# Overview
Provides Read API through gRPC to serve Catalog Search related requests

NOTE: existing implementation is a "transition" phase which utilizes Magento Monolith fulltextsearch index among with DB connection (to retrieve necessary data to build search response, e.g. attributes names for build layered navigation)

### Install
Current repository temporary adapted for monolith installation. To be able install search service as standalone application do the next steps:  

1. Set auth.json `cp auth.json.tmpl auth.json` and provide correct credentials 
1. Copy composer `cp composer.json.standalone composer.json`
1. `composer install`
1. Run `bin/command storefront:search:init` to configure connection

## GRPC up (local php)
1. Run bin/command storefront:grpc:init \\\Magento\\\SearchStorefrontApi\\\Api\\\SearchProxyServer
2. ./vendor/grpc-server

### Contributing
Contributions are welcomed! Read the [Contributing Guide](./CONTRIBUTING.md) for more information.

### Licensing
This project is licensed under the OSL-3.0 License. See [LICENSE](./LICENSE.md) for more information.
