# Overview
Provides Read API through gRPC to serve Catalog Search related requests

NOTE: existing implementation is a "transition" phase which utilizes Magento Monolith fulltextsearch index among with DB connection (to retrieve necessary data to build search response, e.g. attributes names for build layered navigation)

### Install
Current repository temporary adapted for monolith installation. To be able to use search service as standalone application do the next steps:  

1. Run `./dev/tools/make_standalone_app.sh`
2. Fill auth.json with your credentials
3. Run composer install/update
4. Run `bin/command storefront:search:init` providing Magento db and es connection options

## GRPC up (local php)
1. Run `bin/command storefront:grpc:init \\Magento\\SearchStorefrontApi\\Api\\SearchProxyServer`
2. Run `./vendor/bin/grpc-server`

### Contributing
Contributions are welcomed! Read the [Contributing Guide](./CONTRIBUTING.md) for more information.

### Licensing
This project is licensed under the OSL-3.0 License. See [LICENSE](./LICENSE.md) for more information.
