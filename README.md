# Setono Sylius Shop The Look Plugin

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE)
[![Build Status][ico-github-actions]][link-github-actions]
[![Quality Score][ico-code-quality]][link-code-quality]

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
[ico-code-quality]: https://img.shields.io/scrutinizer/g/Setono/SyliusShopTheLookPlugin.svg

[link-packagist]: https://packagist.org/packages/setono/sylius-shop-the-look-plugin
[link-github-actions]: https://github.com/Setono/SyliusShopTheLookPlugin/actions
[link-code-quality]: https://scrutinizer-ci.com/g/Setono/SyliusShopTheLookPlugin
