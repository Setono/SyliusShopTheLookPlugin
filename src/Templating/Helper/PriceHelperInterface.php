<?php

declare(strict_types=1);

namespace Setono\SyliusShopTheLookPlugin\Templating\Helper;

use Setono\SyliusShopTheLookPlugin\Model\LookInterface;
use Sylius\Component\Core\Model\ProductVariantInterface;

interface PriceHelperInterface
{
    public function getPrice(LookInterface $look, array $context): int;

    public function getDiscount(LookInterface $look, array $context): int;

    public function getTotal(LookInterface $look, array $context): int;

    public function getVariantDiscount(ProductVariantInterface $productVariant, LookInterface $look, array $context): int;
}
