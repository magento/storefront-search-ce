#!/bin/sh
if [[ ! -f auth.json ]] ; then
  cp auth.json.tmpl auth.json
fi
cp composer.json.standalone composer.json
cp composer.lock.standalone composer.lock
cp app/etc/storefront_search/di.xml.standalone app/etc/storefront_search/di.xml
cp app/storefront_search_autoload.php app/autoload.php
cp app/storefront_search_bootstrap.php app/bootstrap.php
