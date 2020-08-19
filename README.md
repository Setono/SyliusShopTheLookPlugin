# Setono Sylius Shop The Look Plugin

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE)
[![Build Status][ico-github-actions]][link-github-actions]

## Installation

### Require plugin with composer

```bash
composer require setono/sylius-shop-the-look-plugin
```

### Import configuration

```yaml
# config/packages/setono_sylius_shop_the_look.yaml
imports:
    - { resource: "@SetonoSyliusShopTheLookPlugin/Resources/config/app/config.yaml" }
```

### (Optional) Import fixtures

If you wish to have some looks to play with in your application during development.

```yaml
# config/packages/setono_sylius_shop_the_look.yaml
imports:
    # ...
    - { resource: "@SetonoSyliusShopTheLookPlugin/Resources/config/app/fixtures.yaml" }
```

### Import routing

```yaml
# config/routes/setono_sylius_shop_the_look.yaml
setono_sylius_shop_the_look:
    resource: "@SetonoSyliusShopTheLookPlugin/Resources/config/routes.yaml"
    # Or if your app doesn't use localized URLs:
    # resource: "@SetonoSyliusShopTheLookPlugin/Resources/config/routes_no_locale.yaml"
    # @see https://docs.sylius.com/en/latest/cookbook/shop/disabling-localised-urls.html
```

### Add plugin class to your `bundles.php`

Make sure you add it before `SyliusGridBundle`, otherwise you'll get exception.

```php
<?php
$bundles = [
    // ...
    Setono\SyliusShopTheLookPlugin\SetonoSyliusShopTheLookPlugin::class => ['all' => true],
    Sylius\Bundle\GridBundle\SyliusGridBundle::class => ['all' => true],
    // ...
];
```

### Prepare assets

#### If you're using Webpack encore in your app:

- Add swiper to your `package.json`:

```bash
yarn add swiper
```

- Specify plugin's scripts alias at `webpack.config.js`:

```js
// webpack.config.js
// Admin
const setonoShopTheLookPluginAdminResources = path.resolve(__dirname, 'vendor/setono/sylius-shop-the-look-plugin/src/Resources/private/admin/');
// ...
adminConfig.resolve.alias['setono/shop-the-look-plugin'] = setonoShopTheLookPluginAdminResources;
// ...

// Shop
const setonoShopTheLookPluginShopResources = path.resolve(__dirname, 'vendor/setono/sylius-shop-the-look-plugin/src/Resources/private/shop/');
// ...
shopConfig.resolve.alias['setono/shop-the-look-plugin'] = setonoShopTheLookPluginShopResources;
```

- Use plugin's apps/styles:

```js
// assets/admin/js/app.js
import 'setono/shop-the-look-plugin/js/app';
```

```js
// assets/shop/js/app.js
import 'setono/shop-the-look-plugin/js/app';
import 'setono/shop-the-look-plugin/scss/app';
```

- And run `yarn encore dev` to rebuild it

#### If you're using regular scripts inclusion

This is not supported out of the box (slug generation script),
but I guess you can do something like this at your app's javascript file:

```js
(function ($) {
  'use strict';

  $.fn.extend({
    // Put setono-shop-the-look-slug.js's content here
  });

  $(document).ready(() => {
    $(document).lookSlugGenerator();
  });
})(jQuery);
```

```yaml
# config/packages/setono_sylius_shop_the_look.yaml
imports:
    // ...
    - { resource: "@SetonoSyliusShopTheLookPlugin/Resources/config/app/ui/admin.yaml" }
```

Also, you should discover [src/Resources/private](src/Resources/private)
and adjust shop styles/scripts to your needs.

### Update your database:

```bash
$ bin/console doctrine:migrations:diff
$ bin/console doctrine:migrations:migrate
```

### Override templates

Inject look discount lines from [src/Resources/views/templates/bundles](src/Resources/views/templates/bundles) templates
to cart/checkout/order templates like it was done at [tests/Application/templates/bundles](tests/Application/templates/bundles).

[ico-version]: https://img.shields.io/packagist/v/setono/sylius-shop-the-look-plugin.svg
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg
[ico-github-actions]: https://github.com/Setono/SyliusShopTheLookPlugin/workflows/build/badge.svg

[link-packagist]: https://packagist.org/packages/setono/sylius-shop-the-look-plugin
[link-github-actions]: https://github.com/Setono/SyliusShopTheLookPlugin/actions
